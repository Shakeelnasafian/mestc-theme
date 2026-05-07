<?php
/**
 * Theme setup: features, supports, image sizes, content width.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'mestc_setup' ) ) {
	function mestc_setup() {
		load_theme_textdomain( 'mestc-theme', MESTC_THEME_DIR . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );

		// WooCommerce.
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 320,
			'single_image_width'    => 720,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 6,
			),
		) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		add_image_size( 'mestc-product-thumb', 320, 320, true );
		add_image_size( 'mestc-hero-slide', 1920, 720, true );
		add_image_size( 'mestc-card', 600, 400, true );
	}
}
add_action( 'after_setup_theme', 'mestc_setup' );

/**
 * Content width.
 */
function mestc_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mestc_content_width', 1280 );
}
add_action( 'after_setup_theme', 'mestc_content_width', 0 );

/**
 * Add a body class flag when WooCommerce is active so CSS can adjust.
 */
function mestc_body_classes( $classes ) {
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'has-woocommerce';
	}
	if ( is_front_page() ) {
		$classes[] = 'mestc-front';
	}
	return $classes;
}
add_filter( 'body_class', 'mestc_body_classes' );

/**
 * Pingback header.
 */
function mestc_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mestc_pingback_header' );
