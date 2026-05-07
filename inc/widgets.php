<?php
/**
 * Widget areas.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_widgets_init() {
	$common = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	);

	register_sidebar( array_merge( $common, array(
		'name'        => __( 'Blog Sidebar', 'mestc-theme' ),
		'id'          => 'sidebar-blog',
		'description' => __( 'Sidebar shown on blog and post pages.', 'mestc-theme' ),
	) ) );

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar( array_merge( $common, array(
			'name'        => __( 'Shop Sidebar', 'mestc-theme' ),
			'id'          => 'sidebar-shop',
			'description' => __( 'Sidebar for shop and product archive pages.', 'mestc-theme' ),
		) ) );
	}

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array_merge( $common, array(
			'name'        => sprintf( __( 'Footer Column %d', 'mestc-theme' ), $i ),
			'id'          => 'footer-' . $i,
			'description' => __( 'Optional footer column. Leave empty to use the assigned menu instead.', 'mestc-theme' ),
		) ) );
	}
}
add_action( 'widgets_init', 'mestc_widgets_init' );
