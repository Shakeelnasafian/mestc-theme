<?php
/**
 * Native contact form handler — POST + AJAX.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Process a contact form submission. Returns array { ok => bool, message => string }.
 */
function mestc_process_contact( $data ) {
	$name    = sanitize_text_field( $data['name']    ?? '' );
	$email   = sanitize_email(       $data['email']   ?? '' );
	$phone   = sanitize_text_field( $data['phone']   ?? '' );
	$company = sanitize_text_field( $data['company'] ?? '' );
	$message = sanitize_textarea_field( $data['message'] ?? '' );
	$honey   = sanitize_text_field( $data['mestc_hp'] ?? '' );

	if ( $honey !== '' ) {
		return array( 'ok' => false, 'message' => __( 'Spam detected.', 'mestc-theme' ) );
	}
	if ( $name === '' || $email === '' || $phone === '' ) {
		return array( 'ok' => false, 'message' => __( 'Please fill in all required fields.', 'mestc-theme' ) );
	}
	if ( ! is_email( $email ) ) {
		return array( 'ok' => false, 'message' => __( 'Please provide a valid email address.', 'mestc-theme' ) );
	}

	$to = get_theme_mod( 'mestc_email', get_option( 'admin_email' ) );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$subject = sprintf( '[%s] %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), __( 'New Inquiry', 'mestc-theme' ) );
	$body  = "Name: {$name}\n";
	$body .= "Email: {$email}\n";
	$body .= "Phone: {$phone}\n";
	if ( $company ) { $body .= "Company: {$company}\n"; }
	$body .= "\nMessage:\n{$message}\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		return array( 'ok' => false, 'message' => __( 'Mail server unavailable. Please call us instead.', 'mestc-theme' ) );
	}

	do_action( 'mestc_contact_submitted', compact( 'name', 'email', 'phone', 'company', 'message' ) );

	return array( 'ok' => true, 'message' => __( 'Thank you. We will respond within 24 hours.', 'mestc-theme' ) );
}

/**
 * Handle non-AJAX submissions posted to admin-post.php (action=mestc_contact).
 */
function mestc_handle_contact_post() {
	if ( ! isset( $_POST['mestc_contact_nonce'] ) || ! wp_verify_nonce( $_POST['mestc_contact_nonce'], 'mestc_contact' ) ) {
		wp_safe_redirect( wp_get_referer() ?: home_url( '/' ) );
		exit;
	}

	$result   = mestc_process_contact( $_POST );
	$redirect = wp_get_referer() ?: home_url( '/' );
	$redirect = add_query_arg( array( 'mestc_sent' => $result['ok'] ? '1' : '0' ), $redirect );

	if ( ! $result['ok'] ) {
		$redirect .= '#mestcContactForm';
	}

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_mestc_contact',        'mestc_handle_contact_post' );
add_action( 'admin_post_nopriv_mestc_contact', 'mestc_handle_contact_post' );

/**
 * AJAX endpoints (logged in + guest).
 */
function mestc_ajax_contact() {
	check_ajax_referer( 'mestc_contact', 'nonce' );
	$result = mestc_process_contact( $_POST );
	if ( $result['ok'] ) {
		wp_send_json_success( $result );
	}
	wp_send_json_error( $result );
}
add_action( 'wp_ajax_mestc_contact', 'mestc_ajax_contact' );
add_action( 'wp_ajax_nopriv_mestc_contact', 'mestc_ajax_contact' );

/**
 * Quote band quick-email submission.
 */
function mestc_ajax_quick_quote() {
	check_ajax_referer( 'mestc_contact', 'nonce' );
	$email = sanitize_email( $_POST['email'] ?? '' );
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please provide a valid email address.', 'mestc-theme' ) ) );
	}

	$to = get_theme_mod( 'mestc_email', get_option( 'admin_email' ) );
	if ( ! is_email( $to ) ) { $to = get_option( 'admin_email' ); }
	$subject = sprintf( '[%s] %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), __( 'Quick Quote Request', 'mestc-theme' ) );
	$body    = sprintf( __( 'Quick quote request from %s', 'mestc-theme' ), $email );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email );

	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		wp_send_json_success( array( 'message' => __( 'Thanks — our team will be in touch shortly.', 'mestc-theme' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Could not send. Please try again.', 'mestc-theme' ) ) );
}
add_action( 'wp_ajax_mestc_quick_quote', 'mestc_ajax_quick_quote' );
add_action( 'wp_ajax_nopriv_mestc_quick_quote', 'mestc_ajax_quick_quote' );
