<?php
/**
 * Primary navigation.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$cta_label = get_theme_mod( 'mestc_quote_button_label', 'REQUEST A QUOTE' );
$cta_url   = mestc_contact_url();
?>
<header class="mestc-nav-wrap" role="banner">
	<nav class="mestc-nav" aria-label="<?php esc_attr_e( 'Primary', 'mestc-theme' ); ?>">
		<div class="mestc-logo">
			<?php mestc_logo(); ?>
		</div>

		<button class="menu-toggle" id="menuToggle" aria-controls="primaryMenu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle menu', 'mestc-theme' ); ?>">
			<span></span><span></span><span></span>
		</button>

		<div class="mestc-menu" id="primaryMenu">
			<?php
			mestc_nav_menu( 'primary', array(
				array( 'label' => __( 'Home', 'mestc-theme' ),       'url' => home_url( '/' ) ),
				array( 'label' => __( 'About Us', 'mestc-theme' ),   'url' => home_url( '/about/' ) ),
				array( 'label' => __( 'Products', 'mestc-theme' ),   'url' => mestc_shop_url() ),
				array( 'label' => __( 'Categories', 'mestc-theme' ), 'url' => get_post_type_archive_link( 'product' ) ?: home_url( '/shop/' ) ),
				array( 'label' => __( 'Industries', 'mestc-theme' ), 'url' => get_post_type_archive_link( 'mestc_industry' ) ?: home_url( '/industries/' ) ),
				array( 'label' => __( 'Projects', 'mestc-theme' ),   'url' => get_post_type_archive_link( 'mestc_project' )  ?: home_url( '/projects/' ) ),
				array( 'label' => __( 'Blog', 'mestc-theme' ),       'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
				array( 'label' => __( 'Contact Us', 'mestc-theme' ), 'url' => mestc_contact_url() ),
			) );
			?>
		</div>

		<div class="nav-right">
			<form role="search" method="get" class="nav-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="mestc-search"><?php esc_html_e( 'Search', 'mestc-theme' ); ?></label>
				<input id="mestc-search" class="nav-search" type="search" name="s" autocomplete="off" placeholder="<?php esc_attr_e( 'Search products...', 'mestc-theme' ); ?>" />
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<input type="hidden" name="post_type" value="product" />
				<?php endif; ?>
				<div class="mestc-search-results" id="mestcSearchResults" role="listbox" aria-live="polite"></div>
			</form>

			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<?php mestc_render_mini_cart_button(); ?>
			<?php endif; ?>

			<a class="btn-quote" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
		</div>
	</nav>
</header>
