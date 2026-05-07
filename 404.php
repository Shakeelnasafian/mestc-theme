<?php
/**
 * 404 — page not found.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero( __( 'Page not found', 'mestc-theme' ) );
?>
<main id="primary" class="site-main mestc-404-main">
	<section class="not-found-section">
		<div class="not-found-inner">
			<h2><?php esc_html_e( 'Sorry, we could not find that page.', 'mestc-theme' ); ?></h2>
			<p><?php esc_html_e( 'It may have been moved or removed. Try a search or head back home.', 'mestc-theme' ); ?></p>
			<?php get_search_form(); ?>
			<div class="not-found-links">
				<a class="btn-orange" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'mestc-theme' ); ?></a>
				<a class="btn-white" href="<?php echo esc_url( mestc_shop_url() ); ?>"><?php esc_html_e( 'Browse Products', 'mestc-theme' ); ?></a>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
