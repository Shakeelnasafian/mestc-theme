<?php
/**
 * Bulk RFQ basket — accumulates multiple products on the client and submits a
 * single structured inquiry to the owner.
 *
 * Storage on the client: localStorage (key `mestc_rfq_v1`).
 * Persistence on the server: existing `mestc_inquiry` CPT with an extra
 * `_inquiry_lines` meta containing the JSON line-item list.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Sanitize and resolve a list of RFQ lines posted from the drawer.
 * Drops anything that isn't a real, published product.
 *
 * @param array $raw Lines from $_POST. Each item: { id, qty, note }.
 * @return array Resolved lines: { id, title, url, sku, qty, note }.
 */
function mestc_rfq_resolve_lines( $raw ) {
	if ( ! is_array( $raw ) ) { return array(); }

	$out  = array();
	$seen = array();
	foreach ( $raw as $line ) {
		$pid = isset( $line['id'] ) ? absint( $line['id'] ) : 0;
		if ( ! $pid || isset( $seen[ $pid ] ) ) { continue; }

		$post = get_post( $pid );
		if ( ! $post || $post->post_status !== 'publish' || $post->post_type !== 'product' ) {
			continue;
		}

		$qty  = isset( $line['qty'] ) ? max( 1, absint( $line['qty'] ) ) : 1;
		$note = isset( $line['note'] ) ? sanitize_text_field( wp_unslash( $line['note'] ) ) : '';
		$sku  = '';
		if ( function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $pid );
			if ( $product ) { $sku = $product->get_sku(); }
		}

		$out[] = array(
			'id'    => $pid,
			'title' => get_the_title( $pid ),
			'url'   => get_permalink( $pid ),
			'sku'   => $sku,
			'qty'   => $qty,
			'note'  => $note,
		);
		$seen[ $pid ] = true;
	}
	return $out;
}

/**
 * Format the email body for an RFQ submission.
 */
function mestc_rfq_format_body( $customer, $lines ) {
	$out  = array();
	$out[] = '== ' . __( 'NEW BULK RFQ', 'mestc-theme' ) . ' ==';
	$out[] = '';
	$out[] = __( 'Customer:', 'mestc-theme' );
	$out[] = '  ' . __( 'Name:',    'mestc-theme' ) . ' ' . $customer['name'];
	$out[] = '  ' . __( 'Email:',   'mestc-theme' ) . ' ' . $customer['email'];
	$out[] = '  ' . __( 'Phone:',   'mestc-theme' ) . ' ' . $customer['phone'];
	if ( ! empty( $customer['company'] ) ) {
		$out[] = '  ' . __( 'Company:', 'mestc-theme' ) . ' ' . $customer['company'];
	}
	$out[] = '';
	$out[] = sprintf( _n( '%d product:', '%d products:', count( $lines ), 'mestc-theme' ), count( $lines ) );
	$out[] = str_repeat( '-', 60 );

	$total_qty = 0;
	foreach ( $lines as $i => $line ) {
		$num = $i + 1;
		$out[] = sprintf( '%d. %s', $num, $line['title'] );
		if ( $line['sku'] ) { $out[] = '   ' . __( 'SKU:', 'mestc-theme' ) . ' ' . $line['sku']; }
		$out[] = '   ' . __( 'Quantity:', 'mestc-theme' ) . ' ' . $line['qty'];
		if ( $line['note'] ) { $out[] = '   ' . __( 'Note:', 'mestc-theme' ) . ' ' . $line['note']; }
		$out[] = '   ' . __( 'URL:', 'mestc-theme' ) . '  ' . $line['url'];
		$out[] = '';
		$total_qty += $line['qty'];
	}
	$out[] = str_repeat( '-', 60 );
	$out[] = sprintf( __( 'Total quantity across all lines: %d', 'mestc-theme' ), $total_qty );

	if ( ! empty( $customer['message'] ) ) {
		$out[] = '';
		$out[] = __( 'Customer message:', 'mestc-theme' );
		$out[] = $customer['message'];
	}

	return implode( "\n", $out );
}

/**
 * AJAX endpoint — submit a bulk RFQ.
 */
function mestc_ajax_rfq_submit() {
	check_ajax_referer( 'mestc_inquire', 'nonce' );

	$honey = sanitize_text_field( wp_unslash( $_POST['mestc_hp'] ?? '' ) );
	if ( $honey !== '' ) {
		wp_send_json_error( array( 'message' => __( 'Spam blocked.', 'mestc-theme' ) ) );
	}

	$customer = array(
		'name'    => sanitize_text_field( wp_unslash( $_POST['name']    ?? '' ) ),
		'email'   => sanitize_email(       wp_unslash( $_POST['email']    ?? '' ) ),
		'phone'   => sanitize_text_field( wp_unslash( $_POST['phone']   ?? '' ) ),
		'company' => sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) ),
		'message' => sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) ),
	);
	if ( $customer['name'] === '' || $customer['email'] === '' || $customer['phone'] === '' ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in name, email and phone.', 'mestc-theme' ) ) );
	}
	if ( ! is_email( $customer['email'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Please provide a valid email.', 'mestc-theme' ) ) );
	}

	$lines_raw = json_decode( wp_unslash( $_POST['lines'] ?? '[]' ), true );
	$lines     = mestc_rfq_resolve_lines( $lines_raw );
	if ( empty( $lines ) ) {
		wp_send_json_error( array( 'message' => __( 'Your RFQ list is empty.', 'mestc-theme' ) ) );
	}

	$inquiry_id = wp_insert_post( array(
		'post_type'    => 'mestc_inquiry',
		'post_status'  => 'private',
		'post_title'   => sprintf( '%s — RFQ (%d items)', $customer['name'], count( $lines ) ),
		'post_content' => $customer['message'],
	) );

	if ( $inquiry_id && ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_inquiry_name',    $customer['name'] );
		update_post_meta( $inquiry_id, '_inquiry_email',   $customer['email'] );
		update_post_meta( $inquiry_id, '_inquiry_phone',   $customer['phone'] );
		update_post_meta( $inquiry_id, '_inquiry_company', $customer['company'] );
		update_post_meta( $inquiry_id, '_inquiry_lines',   wp_json_encode( $lines ) );
		update_post_meta( $inquiry_id, '_inquiry_kind',    'rfq' );
		update_post_meta( $inquiry_id, '_inquiry_count',   count( $lines ) );
	}

	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$to   = get_theme_mod( 'mestc_email', get_option( 'admin_email' ) );
	if ( ! is_email( $to ) ) { $to = get_option( 'admin_email' ); }
	$subject = sprintf( '[%s] %s — %d %s',
		$site,
		__( 'Bulk RFQ', 'mestc-theme' ),
		count( $lines ),
		_n( 'item', 'items', count( $lines ), 'mestc-theme' )
	);
	$body    = mestc_rfq_format_body( $customer, $lines );
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . sprintf( '%s <%s>', $customer['name'], $customer['email'] ),
	);
	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $inquiry_id && ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_inquiry_email_sent', $sent ? 1 : 0 );
	}

	do_action( 'mestc_rfq_submitted', array(
		'inquiry_id' => $inquiry_id,
		'customer'   => $customer,
		'lines'      => $lines,
		'sent'       => $sent,
	) );

	wp_send_json_success( array(
		'message'    => __( 'Thank you. Your RFQ has been received — we will respond within 24 hours.', 'mestc-theme' ),
		'inquiry_id' => $inquiry_id,
	) );
}
add_action( 'wp_ajax_mestc_rfq_submit',        'mestc_ajax_rfq_submit' );
add_action( 'wp_ajax_nopriv_mestc_rfq_submit', 'mestc_ajax_rfq_submit' );

/**
 * Render line items in the inquiry CPT post-edit screen for back-office review.
 */
function mestc_rfq_inquiry_meta_box() {
	add_meta_box(
		'mestc_inquiry_lines',
		__( 'RFQ Line Items', 'mestc-theme' ),
		'mestc_rfq_render_lines_meta_box',
		'mestc_inquiry',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'mestc_rfq_inquiry_meta_box' );

function mestc_rfq_render_lines_meta_box( $post ) {
	$json  = (string) get_post_meta( $post->ID, '_inquiry_lines', true );
	$lines = $json ? json_decode( $json, true ) : array();
	if ( empty( $lines ) ) {
		echo '<p>' . esc_html__( 'This inquiry was a single-product or general inquiry, not a bulk RFQ.', 'mestc-theme' ) . '</p>';
		return;
	}
	echo '<table class="widefat striped" style="margin-top:6px"><thead><tr>';
	echo '<th style="width:36%">' . esc_html__( 'Product', 'mestc-theme' ) . '</th>';
	echo '<th>' . esc_html__( 'SKU', 'mestc-theme' ) . '</th>';
	echo '<th style="width:80px">' . esc_html__( 'Qty', 'mestc-theme' ) . '</th>';
	echo '<th>' . esc_html__( 'Note', 'mestc-theme' ) . '</th>';
	echo '<th>' . esc_html__( 'Link', 'mestc-theme' ) . '</th>';
	echo '</tr></thead><tbody>';
	foreach ( $lines as $line ) {
		echo '<tr>';
		echo '<td><strong>' . esc_html( $line['title'] ?? '' ) . '</strong></td>';
		echo '<td>' . esc_html( $line['sku'] ?? '' ) . '</td>';
		echo '<td>' . (int) ( $line['qty'] ?? 0 ) . '</td>';
		echo '<td>' . esc_html( $line['note'] ?? '' ) . '</td>';
		echo '<td>';
		if ( ! empty( $line['id'] ) ) {
			printf( '<a href="%s" target="_blank" rel="noopener">%s</a>',
				esc_url( get_edit_post_link( (int) $line['id'] ) ),
				esc_html__( 'Edit product', 'mestc-theme' )
			);
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/* Add an RFQ-or-single column to the inquiries admin list. */
add_filter( 'manage_mestc_inquiry_posts_columns', function ( $cols ) {
	$cols['kind'] = __( 'Kind', 'mestc-theme' );
	return $cols;
} );
add_action( 'manage_mestc_inquiry_posts_custom_column', function ( $col, $id ) {
	if ( $col === 'kind' ) {
		$kind  = (string) get_post_meta( $id, '_inquiry_kind', true );
		$count = (int)    get_post_meta( $id, '_inquiry_count', true );
		if ( $kind === 'rfq' ) {
			printf( '<span style="color:#b08842;font-weight:700">RFQ</span> · %d %s',
				$count,
				$count === 1 ? esc_html__( 'item', 'mestc-theme' ) : esc_html__( 'items', 'mestc-theme' )
			);
		} else {
			echo esc_html__( 'Single', 'mestc-theme' );
		}
	}
}, 10, 2 );
