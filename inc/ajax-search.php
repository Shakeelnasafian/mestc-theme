<?php
/**
 * AJAX live search for products + posts.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_ajax_search() {
	check_ajax_referer( 'mestc_search', 'nonce' );

	$term = sanitize_text_field( wp_unslash( $_GET['q'] ?? '' ) );
	if ( strlen( $term ) < 2 ) {
		wp_send_json_success( array( 'items' => array() ) );
	}

	$post_types = array( 'post' );
	if ( class_exists( 'WooCommerce' ) ) {
		array_unshift( $post_types, 'product' );
	}

	$query = new WP_Query( array(
		'post_type'      => $post_types,
		'post_status'    => 'publish',
		's'              => $term,
		'posts_per_page' => 8,
		'no_found_rows'  => true,
	) );

	$items = array();
	while ( $query->have_posts() ) {
		$query->the_post();
		$id    = get_the_ID();
		$type  = get_post_type();
		$thumb = get_the_post_thumbnail_url( $id, 'thumbnail' );
		$price = '';
		if ( 'product' === $type && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $id );
			if ( $product ) {
				$price = wp_strip_all_tags( $product->get_price_html() );
			}
		}
		$items[] = array(
			'id'    => $id,
			'title' => html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ),
			'url'   => get_permalink(),
			'type'  => $type,
			'thumb' => $thumb ?: '',
			'price' => $price,
		);
	}
	wp_reset_postdata();

	wp_send_json_success( array(
		'items' => $items,
		'all_url' => add_query_arg( array( 's' => $term, 'post_type' => 'product' ), home_url( '/' ) ),
	) );
}
add_action( 'wp_ajax_mestc_search', 'mestc_ajax_search' );
add_action( 'wp_ajax_nopriv_mestc_search', 'mestc_ajax_search' );
