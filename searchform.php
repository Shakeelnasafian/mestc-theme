<?php
/**
 * Search form template.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<form role="search" method="get" class="mestc-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="mestc-s-<?php echo esc_attr( uniqid() ); ?>"><?php esc_html_e( 'Search for:', 'mestc-theme' ); ?></label>
	<input type="search" id="mestc-s-<?php echo esc_attr( uniqid() ); ?>" name="s" placeholder="<?php esc_attr_e( 'Search...', 'mestc-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
	<button type="submit" class="btn-orange"><?php esc_html_e( 'Search', 'mestc-theme' ); ?></button>
</form>
