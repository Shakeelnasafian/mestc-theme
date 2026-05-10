<?php
/**
 * MESTC Industrial Theme — bootstrap.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MESTC_THEME_VERSION', '2.0.0' );
define( 'MESTC_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'MESTC_THEME_URI', trailingslashit( get_template_directory_uri() ) );

require MESTC_THEME_DIR . 'inc/setup.php';
require MESTC_THEME_DIR . 'inc/enqueue.php';
require MESTC_THEME_DIR . 'inc/menus.php';
require MESTC_THEME_DIR . 'inc/widgets.php';
require MESTC_THEME_DIR . 'inc/customizer.php';
require MESTC_THEME_DIR . 'inc/visibility.php';
require MESTC_THEME_DIR . 'inc/post-types.php';
require MESTC_THEME_DIR . 'inc/template-functions.php';
require MESTC_THEME_DIR . 'inc/contact-form.php';
require MESTC_THEME_DIR . 'inc/ajax-search.php';
require MESTC_THEME_DIR . 'inc/inquire.php';
require MESTC_THEME_DIR . 'inc/rfq.php';
require MESTC_THEME_DIR . 'inc/seo.php';

if ( is_admin() ) {
	require MESTC_THEME_DIR . 'inc/admin-settings.php';
}

if ( class_exists( 'WooCommerce' ) ) {
	require MESTC_THEME_DIR . 'inc/woocommerce.php';
}
