<?php
/**
 * WooCommerce integration — branded layout + catalog/inquiry mode.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------------- Wrappers ---------------- */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );

/** Page hero already prints the title — suppress WC's inner H1. */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/* ---------------- Loop layout knobs ---------------- */

add_filter( 'loop_shop_columns',  function () { return 4; }, 99 );
add_filter( 'loop_shop_per_page', function () { return 12; }, 99 );

/* ---------------- Catalog mode: hide cart UI everywhere ---------------- */

if ( mestc_is_catalog_mode() ) {

	// Replace the loop add-to-cart button with the Inquire button.
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	add_action(    'woocommerce_after_shop_loop_item', 'mestc_loop_inquire_button', 10 );

	// Replace the single-product add-to-cart form with our Inquire button.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	add_action(    'woocommerce_single_product_summary', 'mestc_single_inquire_button', 30 );

	// Hide the price line on single + loop in pure catalog mode.
	add_filter( 'woocommerce_get_price_html', function ( $price ) {
		if ( apply_filters( 'mestc_show_prices', false ) ) { return $price; }
		return '';
	}, 99 );

	// Stop WC redirecting to cart after add-to-cart.
	add_filter( 'woocommerce_add_to_cart_redirect', function () { return wp_get_referer(); } );
}

/* ---------------- Inquire buttons ---------------- */

function mestc_loop_inquire_button() {
	global $product;
	if ( ! $product ) { return; }
	$thumb = get_the_post_thumbnail_url( $product->get_id(), 'mestc-product-thumb' );
	printf(
		'<button type="button" class="mestc-inquire-btn mestc-add-rfq" data-product-id="%1$d" data-product-title="%2$s" data-product-url="%3$s" data-product-thumb="%5$s">+ %4$s</button>',
		(int) $product->get_id(),
		esc_attr( $product->get_name() ),
		esc_attr( get_permalink( $product->get_id() ) ),
		esc_html__( 'Add to RFQ', 'mestc-theme' ),
		esc_attr( $thumb )
	);
}

function mestc_single_inquire_button() {
	global $product;
	if ( ! $product ) { return; }
	$thumb = get_the_post_thumbnail_url( $product->get_id(), 'mestc-product-thumb' );
	?>
	<div class="mestc-product-trust-row" aria-label="<?php esc_attr_e( 'Why buy from MESTC', 'mestc-theme' ); ?>">
		<div>
			<span class="ico" aria-hidden="true">⏱</span>
			<strong><?php esc_html_e( '24h Response', 'mestc-theme' ); ?></strong>
			<span><?php esc_html_e( 'Bulk pricing fast', 'mestc-theme' ); ?></span>
		</div>
		<div>
			<span class="ico" aria-hidden="true">✓</span>
			<strong><?php esc_html_e( 'ATEX / IECEx', 'mestc-theme' ); ?></strong>
			<span><?php esc_html_e( 'Internationally certified', 'mestc-theme' ); ?></span>
		</div>
		<div>
			<span class="ico" aria-hidden="true">🚚</span>
			<strong><?php esc_html_e( 'GCC Delivery', 'mestc-theme' ); ?></strong>
			<span><?php esc_html_e( 'Same / next day UAE', 'mestc-theme' ); ?></span>
		</div>
	</div>
	<div class="mestc-inquire-cta">
		<div class="mestc-inquire-cta__head">
			<span class="mestc-inquire-cta__eyebrow"><?php esc_html_e( 'Two ways to inquire', 'mestc-theme' ); ?></span>
			<h3><?php esc_html_e( 'Get pricing in 24 hours', 'mestc-theme' ); ?></h3>
		</div>

		<div class="mestc-inquire-cta__buttons">
			<button type="button" class="mestc-inquire-cta__primary mestc-add-rfq"
				data-product-id="<?php echo (int) $product->get_id(); ?>"
				data-product-title="<?php echo esc_attr( $product->get_name() ); ?>"
				data-product-url="<?php echo esc_attr( get_permalink( $product->get_id() ) ); ?>"
				data-product-thumb="<?php echo esc_attr( $thumb ); ?>">
				<span class="ico" aria-hidden="true">+</span>
				<span class="label">
					<strong><?php esc_html_e( 'Add to RFQ', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Build a multi-item inquiry', 'mestc-theme' ); ?></small>
				</span>
			</button>

			<button type="button" class="mestc-inquire-cta__secondary mestc-inquire-btn"
				data-product-id="<?php echo (int) $product->get_id(); ?>"
				data-product-title="<?php echo esc_attr( $product->get_name() ); ?>"
				data-product-url="<?php echo esc_attr( get_permalink( $product->get_id() ) ); ?>">
				<span class="ico" aria-hidden="true">✉</span>
				<span class="label">
					<strong><?php esc_html_e( 'Email Inquiry', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Open in your email app', 'mestc-theme' ); ?></small>
				</span>
			</button>
		</div>

		<a class="mestc-inquire-tel" href="tel:<?php echo esc_attr( mestc_tel( get_theme_mod( 'mestc_phone' ) ) ); ?>">
			<span aria-hidden="true">📞</span>
			<?php esc_html_e( 'Or call', 'mestc-theme' ); ?>
			<strong><?php echo esc_html( get_theme_mod( 'mestc_phone', '+971 XX XXX XXXX' ) ); ?></strong>
		</a>

		<ul class="mestc-inquire-perks">
			<li><span aria-hidden="true">✓</span> <?php esc_html_e( '24-hour response guaranteed', 'mestc-theme' ); ?></li>
			<li><span aria-hidden="true">✓</span> <?php esc_html_e( 'Bulk pricing & custom RFQ', 'mestc-theme' ); ?></li>
			<li><span aria-hidden="true">✓</span> <?php esc_html_e( 'Authorized GCC distributor', 'mestc-theme' ); ?></li>
		</ul>
	</div>
	<?php
}

/* ---------------- Mini-cart (kept in code, only rendered when not catalog) ---------------- */

function mestc_cart_fragment( $fragments ) {
	ob_start();
	mestc_render_mini_cart_button();
	$fragments['a.mestc-cart-toggle'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'mestc_cart_fragment' );

function mestc_render_mini_cart_button() {
	if ( mestc_is_catalog_mode() )            { return; }
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) { return; }
	$count = WC()->cart->get_cart_contents_count();
	$total = WC()->cart->get_cart_subtotal();
	?>
	<a class="mestc-cart-toggle" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'mestc-theme' ); ?>">
		<span class="mestc-cart-ico" aria-hidden="true">🛒</span>
		<span class="mestc-cart-meta">
			<span class="mestc-cart-count"><?php echo (int) $count; ?> <?php esc_html_e( 'items', 'mestc-theme' ); ?></span>
			<span class="mestc-cart-total"><?php echo wp_kses_post( $total ); ?></span>
		</span>
	</a>
	<?php
}

/* ---------------- Shop loop toolbar ---------------- */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action(    'woocommerce_before_shop_loop', 'mestc_shop_toolbar_open',    19 );
add_action(    'woocommerce_before_shop_loop', 'woocommerce_result_count',   20 );
add_action(    'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action(    'woocommerce_before_shop_loop', 'mestc_shop_toolbar_close',   31 );

function mestc_shop_toolbar_open()  { echo '<div class="mestc-shop-toolbar">'; }
function mestc_shop_toolbar_close() { echo '</div>'; }

/* ---------------- Category intro + sub-categories ---------------- */

remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
add_action(    'woocommerce_archive_description', 'mestc_category_intro_panel', 10 );

function mestc_category_intro_panel() {
	if ( ! is_product_category() ) { return; }

	$term     = get_queried_object();
	if ( ! $term || is_wp_error( $term ) ) { return; }
	$desc     = term_description( $term );
	$children = get_terms( array(
		'taxonomy'   => 'product_cat',
		'parent'     => $term->term_id,
		'hide_empty' => false,
	) );
	?>
	<div class="mestc-cat-intro">
		<div>
			<h2 class="mestc-cat-intro__title"><?php echo esc_html( $term->name ); ?></h2>
			<?php if ( $desc ) : ?>
				<div class="mestc-cat-intro__text"><?php echo wp_kses_post( $desc ); ?></div>
			<?php else : ?>
				<p class="mestc-cat-intro__text">
					<?php
					printf(
						/* translators: %s: category name */
						esc_html__( 'Browse our complete %s range. All products are sourced from internationally certified manufacturers and supplied in bulk across the UAE & GCC.', 'mestc-theme' ),
						esc_html( $term->name )
					);
					?>
				</p>
			<?php endif; ?>
		</div>
		<div class="mestc-cat-intro__cta">
			<h3><?php esc_html_e( 'Need a custom quote?', 'mestc-theme' ); ?></h3>
			<p><?php esc_html_e( 'Send us your specs — bulk pricing, certifications and 24-hour response.', 'mestc-theme' ); ?></p>
			<button type="button" class="btn-orange mestc-inquire-btn" data-product-title="<?php echo esc_attr( $term->name ); ?>"><?php esc_html_e( 'Send Inquiry', 'mestc-theme' ); ?> →</button>
		</div>
	</div>

	<?php if ( ! empty( $children ) && ! is_wp_error( $children ) ) : ?>
		<div class="mestc-subcats">
			<?php foreach ( $children as $child ) : ?>
				<a class="mestc-subcats__item" href="<?php echo esc_url( get_term_link( $child ) ); ?>">
					<h3><?php echo esc_html( $child->name ); ?></h3>
					<span><?php
						echo esc_html( sprintf(
							_n( '%d product', '%d products', (int) $child->count, 'mestc-theme' ),
							(int) $child->count
						) );
					?></span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif;
}

/* ---------------- Loop excerpt under the title ---------------- */

add_action( 'woocommerce_after_shop_loop_item_title', 'mestc_loop_short_excerpt', 4 );
function mestc_loop_short_excerpt() {
	global $product;
	if ( ! $product ) { return; }
	$short = wp_strip_all_tags( $product->get_short_description() );
	if ( $short ) {
		echo '<p class="mestc-loop-excerpt">' . esc_html( wp_trim_words( $short, 16, '…' ) ) . '</p>';
	}
}

/* ---------------- Pagination wrapper ---------------- */

add_action( 'woocommerce_after_shop_loop', 'mestc_after_loop_open', 9 );
add_action( 'woocommerce_after_shop_loop', 'mestc_after_loop_close', 11 );
function mestc_after_loop_open()  { echo '<div class="mestc-shop-pagination">'; }
function mestc_after_loop_close() { echo '</div>'; }

/* ---------------- Misc ---------------- */

add_filter( 'woocommerce_placeholder_img_src', function ( $src ) {
	$path = MESTC_THEME_DIR . 'assets/images/product-placeholder.png';
	if ( file_exists( $path ) ) {
		return MESTC_THEME_URI . 'assets/images/product-placeholder.png';
	}
	return $src;
} );

add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
} );

/**
 * Reorder single-product summary so our inquire CTA is right under the title.
 */
add_action( 'after_setup_theme', function () {
	if ( ! mestc_is_catalog_mode() ) { return; }
	// Move title -> price -> excerpt -> inquire -> meta
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	add_action(    'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 50 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
} );
