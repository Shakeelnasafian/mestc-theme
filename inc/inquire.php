<?php
/**
 * Product inquiry flow (B2B catalog mode).
 * Replaces add-to-cart with an "Inquire" button that emails the owner with
 * the customer's contact details and the product info.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------------- Catalog mode toggle ---------------- */

/**
 * Whether the site is in catalog/inquiry mode (no checkout).
 * Defaults to true; filterable so a future admin toggle can switch it off.
 */
function mestc_is_catalog_mode() {
	return (bool) apply_filters( 'mestc_catalog_mode', true );
}

/* ---------------- CPT for logging inquiries ---------------- */

function mestc_register_inquiry_cpt() {
	register_post_type( 'mestc_inquiry', array(
		'labels' => array(
			'name'          => __( 'Inquiries', 'mestc-theme' ),
			'singular_name' => __( 'Inquiry', 'mestc-theme' ),
			'menu_name'     => __( 'Inquiries', 'mestc-theme' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_position' => 24,
		'menu_icon'     => 'dashicons-email-alt',
		'supports'      => array( 'title', 'editor' ),
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
}
add_action( 'init', 'mestc_register_inquiry_cpt' );

/* ---------------- Process + persist + email ---------------- */

/**
 * Validate, persist, and email an inquiry.
 *
 * @param array $data Raw request payload.
 * @return array { ok: bool, message: string }
 */
function mestc_process_inquiry( $data ) {
	$name     = sanitize_text_field( $data['name']     ?? '' );
	$email    = sanitize_email(       $data['email']    ?? '' );
	$phone    = sanitize_text_field( $data['phone']    ?? '' );
	$company  = sanitize_text_field( $data['company']  ?? '' );
	$qty      = sanitize_text_field( $data['quantity'] ?? '' );
	$message  = sanitize_textarea_field( $data['message'] ?? '' );
	$honey    = sanitize_text_field( $data['mestc_hp'] ?? '' );
	$prod_id  = absint( $data['product_id'] ?? 0 );

	if ( $honey !== '' ) {
		return array( 'ok' => false, 'message' => __( 'Spam blocked.', 'mestc-theme' ) );
	}
	if ( $name === '' || $email === '' || $phone === '' ) {
		return array( 'ok' => false, 'message' => __( 'Please fill in all required fields (name, email, phone).', 'mestc-theme' ) );
	}
	if ( ! is_email( $email ) ) {
		return array( 'ok' => false, 'message' => __( 'Please provide a valid email address.', 'mestc-theme' ) );
	}

	// Resolve product details (best-effort; inquiry can also come from non-product pages).
	$product_title = '';
	$product_url   = '';
	$product_sku   = '';
	$product_cats  = '';
	if ( $prod_id ) {
		$product_title = get_the_title( $prod_id );
		$product_url   = get_permalink( $prod_id );
		if ( function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $prod_id );
			if ( $product ) {
				$product_sku  = $product->get_sku();
				$product_cats = wp_strip_all_tags( wc_get_product_category_list( $prod_id, ', ' ) );
			}
		}
	}

	// Persist as a private CPT entry for back-office tracking.
	$inquiry_title = $product_title
		? sprintf( '%s — %s', $name, $product_title )
		: sprintf( __( 'General inquiry from %s', 'mestc-theme' ), $name );

	$inquiry_id = wp_insert_post( array(
		'post_type'    => 'mestc_inquiry',
		'post_status'  => 'private',
		'post_title'   => $inquiry_title,
		'post_content' => $message,
	) );

	if ( $inquiry_id && ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_inquiry_name',    $name );
		update_post_meta( $inquiry_id, '_inquiry_email',   $email );
		update_post_meta( $inquiry_id, '_inquiry_phone',   $phone );
		update_post_meta( $inquiry_id, '_inquiry_company', $company );
		update_post_meta( $inquiry_id, '_inquiry_qty',     $qty );
		update_post_meta( $inquiry_id, '_inquiry_product', $prod_id );
		update_post_meta( $inquiry_id, '_inquiry_url',     $product_url );
		update_post_meta( $inquiry_id, '_inquiry_sku',     $product_sku );
	}

	// Build email body.
	$site_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$lines = array();
	$lines[] = '== ' . __( 'New Product Inquiry', 'mestc-theme' ) . ' ==';
	$lines[] = '';
	if ( $product_title ) {
		$lines[] = __( 'Product:', 'mestc-theme' )   . ' ' . $product_title;
		if ( $product_url )   { $lines[] = __( 'URL:',      'mestc-theme' ) . ' ' . $product_url; }
		if ( $product_sku )   { $lines[] = __( 'SKU:',      'mestc-theme' ) . ' ' . $product_sku; }
		if ( $product_cats )  { $lines[] = __( 'Category:', 'mestc-theme' ) . ' ' . $product_cats; }
		if ( $qty )           { $lines[] = __( 'Quantity:', 'mestc-theme' ) . ' ' . $qty; }
		$lines[] = '';
	}
	$lines[] = __( 'Customer details:', 'mestc-theme' );
	$lines[] = __( 'Name:',    'mestc-theme' ) . ' ' . $name;
	$lines[] = __( 'Email:',   'mestc-theme' ) . ' ' . $email;
	$lines[] = __( 'Phone:',   'mestc-theme' ) . ' ' . $phone;
	if ( $company ) { $lines[] = __( 'Company:', 'mestc-theme' ) . ' ' . $company; }
	if ( $message ) {
		$lines[] = '';
		$lines[] = __( 'Message:', 'mestc-theme' );
		$lines[] = $message;
	}

	$to      = get_theme_mod( 'mestc_email', get_option( 'admin_email' ) );
	if ( ! is_email( $to ) ) { $to = get_option( 'admin_email' ); }
	$subject = sprintf( '[%s] %s — %s',
		$site_name,
		__( 'Inquiry', 'mestc-theme' ),
		$product_title ?: $name
	);
	$body    = implode( "\n", $lines );
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . sprintf( '%s <%s>', $name, $email ),
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $inquiry_id && ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_inquiry_email_sent', $sent ? 1 : 0 );
	}

	do_action( 'mestc_inquiry_submitted', array(
		'inquiry_id' => $inquiry_id,
		'name'       => $name,
		'email'      => $email,
		'phone'      => $phone,
		'company'    => $company,
		'qty'        => $qty,
		'message'    => $message,
		'product_id' => $prod_id,
		'sent'       => $sent,
	) );

	if ( ! $sent ) {
		// Mail failure shouldn't lose the lead — entry is already saved.
		return array(
			'ok'      => true,
			'message' => __( 'Thanks — your inquiry was received. Our team will contact you within 24 hours.', 'mestc-theme' ),
		);
	}

	return array(
		'ok'      => true,
		'message' => __( 'Thank you. Your inquiry has been sent — we will respond within 24 hours.', 'mestc-theme' ),
	);
}

/* ---------------- AJAX endpoint ---------------- */

function mestc_ajax_inquire() {
	check_ajax_referer( 'mestc_inquire', 'nonce' );
	$result = mestc_process_inquiry( $_POST );
	if ( $result['ok'] ) {
		wp_send_json_success( $result );
	}
	wp_send_json_error( $result );
}
add_action( 'wp_ajax_mestc_inquire',        'mestc_ajax_inquire' );
add_action( 'wp_ajax_nopriv_mestc_inquire', 'mestc_ajax_inquire' );

/* ---------------- Form-post fallback ---------------- */

function mestc_handle_inquire_post() {
	if ( ! isset( $_POST['mestc_inquire_nonce'] ) || ! wp_verify_nonce( $_POST['mestc_inquire_nonce'], 'mestc_inquire' ) ) {
		wp_safe_redirect( wp_get_referer() ?: home_url( '/' ) );
		exit;
	}
	$result   = mestc_process_inquiry( $_POST );
	$redirect = wp_get_referer() ?: home_url( '/' );
	$redirect = add_query_arg( array( 'mestc_inquired' => $result['ok'] ? '1' : '0' ), $redirect );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_mestc_inquire',        'mestc_handle_inquire_post' );
add_action( 'admin_post_nopriv_mestc_inquire', 'mestc_handle_inquire_post' );

/* ---------------- Inquiries admin columns ---------------- */

add_filter( 'manage_mestc_inquiry_posts_columns', function ( $cols ) {
	$cols = array(
		'cb'       => $cols['cb'],
		'title'    => __( 'Inquiry', 'mestc-theme' ),
		'product'  => __( 'Product', 'mestc-theme' ),
		'contact'  => __( 'Contact', 'mestc-theme' ),
		'sent'     => __( 'Email', 'mestc-theme' ),
		'date'     => $cols['date'],
	);
	return $cols;
} );
add_action( 'manage_mestc_inquiry_posts_custom_column', function ( $col, $id ) {
	switch ( $col ) {
		case 'product':
			$pid = (int) get_post_meta( $id, '_inquiry_product', true );
			if ( $pid && get_post_status( $pid ) ) {
				echo '<a href="' . esc_url( get_edit_post_link( $pid ) ) . '">' . esc_html( get_the_title( $pid ) ) . '</a>';
				$sku = get_post_meta( $id, '_inquiry_sku', true );
				if ( $sku ) { echo '<br><small>SKU: ' . esc_html( $sku ) . '</small>'; }
			} else {
				echo '—';
			}
			break;
		case 'contact':
			$email = get_post_meta( $id, '_inquiry_email', true );
			$phone = get_post_meta( $id, '_inquiry_phone', true );
			if ( $email ) { echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'; }
			if ( $phone ) { echo '<br><small>' . esc_html( $phone ) . '</small>'; }
			break;
		case 'sent':
			$sent = (int) get_post_meta( $id, '_inquiry_email_sent', true );
			echo $sent ? '✅' : '⚠️';
			break;
	}
}, 10, 2 );
