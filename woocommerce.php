<?php
/**
 * WooCommerce default template wrapper.
 *
 * Used when no specific WC template (archive-product, single-product, taxonomy-product_cat, etc.) is provided.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

if ( is_shop() || is_product_category() || is_product_tag() ) {
	$title = is_shop()
		? get_the_title( wc_get_page_id( 'shop' ) )
		: single_term_title( '', false );
	$description = is_shop() ? '' : term_description();
	mestc_page_hero( $title, $description );
} elseif ( is_product() ) {
	mestc_page_hero( get_the_title() );
} else {
	mestc_page_hero( woocommerce_page_title( false ) );
}
?>
<main id="primary" class="site-main mestc-shop">
	<div class="mestc-shop-inner">
		<?php woocommerce_content(); ?>
	</div>
</main>
<?php get_footer(); ?>
