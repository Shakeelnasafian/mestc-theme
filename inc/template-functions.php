<?php
/**
 * Reusable template helper functions.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Format a phone number for tel: links.
 */
function mestc_tel( $value ) {
	return preg_replace( '/[^\+0-9]/', '', (string) $value );
}

/**
 * Get configured contact info.
 */
function mestc_contact_info() {
	return array(
		'phone'   => get_theme_mod( 'mestc_phone',   '+971 XX XXX XXXX' ),
		'email'   => get_theme_mod( 'mestc_email',   'info@mestc.com' ),
		'address' => get_theme_mod( 'mestc_address', 'Dubai, United Arab Emirates' ),
		'hours'   => get_theme_mod( 'mestc_hours',   'Sun–Thu: 08:00 – 17:30 GST' ),
	);
}

/**
 * Resolve a URL to a permalink, falling back to the home URL.
 */
function mestc_url_or_home( $url ) {
	$url = trim( (string) $url );
	return $url !== '' ? $url : home_url( '/' );
}

/**
 * Find a "Contact" page if one exists, otherwise fall back to home.
 */
function mestc_contact_url() {
	$override = get_theme_mod( 'mestc_quote_button_url' );
	if ( ! empty( $override ) ) {
		return $override;
	}
	$page = get_page_by_path( 'contact' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/contact/' );
}

/**
 * Resolve the URL for the "Products" link — Shop page when WC is installed.
 */
function mestc_shop_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id && $shop_id > 0 ) {
			return get_permalink( $shop_id );
		}
	}
	return home_url( '/shop/' );
}

/**
 * Render the brand logo. Uploaded custom logos take precedence; otherwise the
 * styled MESTC wordmark renders regardless of site-title casing.
 *
 * @param bool $force_wordmark Skip the uploaded image and always render the
 *                             wordmark. Used in dark contexts (footer) where
 *                             the navy logo would disappear.
 */
function mestc_logo( $force_wordmark = false ) {
	if ( ! $force_wordmark && has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	echo '<a class="mestc-wordmark" href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
	echo '<span class="logo-square" aria-hidden="true">M</span>';
	echo '<span class="logo-text">MES<em>TC</em></span>';
	echo '</a>';
}

/**
 * Render footer copyright (replacing [year] with the current year).
 */
function mestc_footer_copyright() {
	$copy = get_theme_mod( 'mestc_footer_copy', '© [year] MESTC. All rights reserved. Dubai, UAE.' );
	echo wp_kses_post( str_replace( '[year]', date_i18n( 'Y' ), $copy ) );
}

/**
 * Default product category icons (used until terms have a custom icon set).
 */
function mestc_default_category_icon( $slug, $name ) {
	$map = array(
		'electrical-equipment'  => '⚡',
		'explosion-proof'       => '💥',
		'hand-power-tools'      => '🔧',
		'hand-and-power-tools'  => '🔧',
		'tools'                 => '🔧',
		'safety-ppe'            => '🦺',
		'safety'                => '🦺',
		'wires-cables'          => '🔌',
		'wires-and-cables'      => '🔌',
		'oil-gas'               => '🛢️',
		'oil-and-gas-products'  => '🛢️',
		'oil-gas-products'      => '🛢️',
		'construction'          => '🏗️',
		'construction-material' => '🏗️',
		'industrial-automation' => '⚙️',
		'automation'            => '⚙️',
	);
	if ( isset( $map[ $slug ] ) ) {
		return $map[ $slug ];
	}
	$first = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1 ) : substr( $name, 0, 1 );
	return strtoupper( $first );
}

/**
 * Render an archive page hero header (used by archive/search/single page).
 */
function mestc_page_hero( $title, $subtitle = '' ) {
	?>
	<section class="page-hero">
		<div class="page-hero-inner">
			<h1 class="page-hero-title"><?php echo wp_kses_post( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="page-hero-sub"><?php echo wp_kses_post( $subtitle ); ?></p>
			<?php endif; ?>
			<?php if ( function_exists( 'woocommerce_breadcrumb' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
				woocommerce_breadcrumb( array( 'wrap_before' => '<nav class="page-hero-crumbs">', 'wrap_after' => '</nav>' ) );
			} elseif ( ! is_front_page() ) {
				mestc_breadcrumbs();
			} ?>
		</div>
	</section>
	<?php
}

/**
 * Lightweight breadcrumbs (non-WC pages).
 */
function mestc_breadcrumbs() {
	if ( is_front_page() ) { return; }
	echo '<nav class="page-hero-crumbs" aria-label="breadcrumbs">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'mestc-theme' ) . '</a>';
	if ( is_singular( 'post' ) ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		if ( $blog_id ) {
			echo ' / <a href="' . esc_url( get_permalink( $blog_id ) ) . '">' . esc_html( get_the_title( $blog_id ) ) . '</a>';
		}
		echo ' / ' . esc_html( get_the_title() );
	} elseif ( is_singular() ) {
		echo ' / ' . esc_html( get_the_title() );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		echo ' / ' . esc_html( single_term_title( '', false ) );
	} elseif ( is_search() ) {
		echo ' / ' . esc_html__( 'Search results', 'mestc-theme' );
	} elseif ( is_archive() ) {
		echo ' / ' . wp_kses_post( get_the_archive_title() );
	} elseif ( is_404() ) {
		echo ' / ' . esc_html__( 'Page not found', 'mestc-theme' );
	}
	echo '</nav>';
}

/**
 * Render the configured FluentForm contact shortcode, or return false if none.
 * Falls back to the built-in form when no shortcode is configured.
 *
 * @return bool true when a FluentForm shortcode was rendered, false otherwise.
 */
function mestc_render_fluentform_contact() {
	$shortcode = trim( (string) get_theme_mod( 'mestc_fluentform_contact', '' ) );
	if ( $shortcode === '' ) {
		return false;
	}
	// Defensive: only proceed when FluentForm is active.
	if ( ! shortcode_exists( 'fluentform' ) ) {
		echo '<div class="form-message form-message--err">' . esc_html__( 'FluentForm plugin is not active — falling back to the default form.', 'mestc-theme' ) . '</div>';
		return false;
	}
	echo '<div class="mestc-fluentform-wrap">';
	echo do_shortcode( $shortcode );
	echo '</div>';
	return true;
}

/**
 * Convert customizer multi-line text to an array.
 */
function mestc_lines_to_array( $value ) {
	$value = (string) $value;
	if ( $value === '' ) { return array(); }
	$lines = preg_split( '/\r\n|\r|\n/', $value );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}
