<?php
/**
 * Asset enqueuing.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_enqueue_assets() {
	$ver_style = file_exists( MESTC_THEME_DIR . 'style.css' ) ? filemtime( MESTC_THEME_DIR . 'style.css' ) : MESTC_THEME_VERSION;
	$ver_main  = file_exists( MESTC_THEME_DIR . 'assets/js/main.js' ) ? filemtime( MESTC_THEME_DIR . 'assets/js/main.js' ) : MESTC_THEME_VERSION;

	wp_enqueue_style( 'mestc-style', get_stylesheet_uri(), array(), $ver_style );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'mestc-woocommerce', MESTC_THEME_URI . 'assets/css/woocommerce.css', array( 'mestc-style' ), $ver_style );
	}

	wp_enqueue_script( 'mestc-main', MESTC_THEME_URI . 'assets/js/main.js', array(), $ver_main, true );

	$contact_email = get_theme_mod( 'mestc_email', get_option( 'admin_email' ) );
	if ( ! is_email( $contact_email ) ) {
		$contact_email = get_option( 'admin_email' );
	}

	wp_localize_script( 'mestc-main', 'mestcData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'searchNonce'  => wp_create_nonce( 'mestc_search' ),
		'contactNonce' => wp_create_nonce( 'mestc_contact' ),
		'inquireNonce' => wp_create_nonce( 'mestc_inquire' ),
		'homeUrl'      => home_url( '/' ),
		'siteName'     => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'contactEmail' => $contact_email,
		'i18n'         => array(
			'searching'      => __( 'Searching...', 'mestc-theme' ),
			'noResults'      => __( 'No products found.', 'mestc-theme' ),
			'sending'        => __( 'Sending...', 'mestc-theme' ),
			'sent'           => __( 'Thank you. We will respond within 24 hours.', 'mestc-theme' ),
			'error'          => __( 'Something went wrong. Please try again.', 'mestc-theme' ),
			'mailtoSubject'  => __( 'Product Inquiry', 'mestc-theme' ),
			'mailtoIntro'    => __( 'Hello MESTC team,', 'mestc-theme' ),
			'mailtoBody'     => __( "I would like to inquire about the following product:", 'mestc-theme' ),
			'mailtoSignoff'  => __( "Please send bulk pricing, lead time and certifications.\n\nThanks,", 'mestc-theme' ),
		),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mestc_enqueue_assets' );

/**
 * Defer non-critical scripts.
 */
function mestc_defer_scripts( $tag, $handle ) {
	if ( 'mestc-main' === $handle ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'mestc_defer_scripts', 10, 2 );

/**
 * Preconnect to image CDN used by hero slides if Customizer points to one.
 */
function mestc_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'mestc_resource_hints', 10, 2 );
