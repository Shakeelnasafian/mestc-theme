<?php
/**
 * Menu registration and walkers.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_register_menus() {
	register_nav_menus( array(
		'primary'         => __( 'Primary Menu (Header)', 'mestc-theme' ),
		'footer-products' => __( 'Footer — Products', 'mestc-theme' ),
		'footer-company'  => __( 'Footer — Company', 'mestc-theme' ),
		'footer-support'  => __( 'Footer — Support', 'mestc-theme' ),
		'mobile'          => __( 'Mobile Menu', 'mestc-theme' ),
	) );
}
add_action( 'after_setup_theme', 'mestc_register_menus' );

/**
 * Render a nav menu, falling back to a default list of links if none assigned.
 */
function mestc_nav_menu( $location, $fallback_items = array() ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array(
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => '',
			'items_wrap'     => '%3$s',
			'depth'          => 2,
			'fallback_cb'    => false,
		) );
		return;
	}

	if ( empty( $fallback_items ) ) {
		return;
	}

	foreach ( $fallback_items as $item ) {
		printf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
}
