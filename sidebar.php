<?php
/**
 * Default sidebar.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$id = ( is_singular( 'product' ) || is_post_type_archive( 'product' ) || is_tax( array( 'product_cat', 'product_tag' ) ) )
	? 'sidebar-shop'
	: 'sidebar-blog';

if ( ! is_active_sidebar( $id ) ) { return; }
?>
<div class="sidebar widget-area" role="complementary">
	<?php dynamic_sidebar( $id ); ?>
</div>
