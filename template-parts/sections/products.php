<?php
/**
 * Product showcase block — pulls products from a WC category, falls back to static cards.
 *
 * Args (passed via set_query_var):
 *   - eyebrow:    section eyebrow text
 *   - heading:    section heading
 *   - sub:        section subtitle
 *   - alt:        true to apply the alternate background
 *   - category:   product_cat slug (optional)
 *   - block_title: header inside the bordered block
 *   - block_sub:   sub-text inside the bordered block
 *   - count:      number of products to fetch (default 6)
 *   - fallback:   array of items { name, icon } to render when no products
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow     = get_query_var( 'mestc_eyebrow' );
$heading     = get_query_var( 'mestc_heading' );
$sub         = get_query_var( 'mestc_sub' );
$alt         = (bool) get_query_var( 'mestc_alt' );
$category    = get_query_var( 'mestc_category' );
$block_title = get_query_var( 'mestc_block_title' );
$block_sub   = get_query_var( 'mestc_block_sub' );
$count       = (int) ( get_query_var( 'mestc_count' ) ?: 6 );
$fallback    = (array) get_query_var( 'mestc_fallback' );
$archive_url = mestc_shop_url();

$products = array();
if ( class_exists( 'WooCommerce' ) ) {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'orderby'        => 'menu_order',
		'no_found_rows'  => true,
	);
	if ( $category ) {
		$args['tax_query'] = array(
			array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $category ),
		);
		$term = get_term_by( 'slug', $category, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$archive_url = get_term_link( $term );
		}
	}
	$q = new WP_Query( $args );
	while ( $q->have_posts() ) {
		$q->the_post();
		$products[] = array(
			'id'    => get_the_ID(),
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'thumb' => get_the_post_thumbnail_url( get_the_ID(), 'mestc-product-thumb' ),
			'price' => function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '',
		);
	}
	wp_reset_postdata();
}
?>
<section class="section <?php echo $alt ? 'section-alt' : ''; ?> mestc-products-section">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<?php if ( $eyebrow ) : ?><div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div><?php endif; ?>
				<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
				<?php if ( $sub ) : ?><p><?php echo esc_html( $sub ); ?></p><?php endif; ?>
			</div>
			<a class="view-all-link" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'View All', 'mestc-theme' ); ?> →</a>
		</div>

		<div class="product-block">
			<div class="product-block-header">
				<div>
					<h3><?php echo esc_html( $block_title ?: $heading ); ?></h3>
					<?php if ( $block_sub ) : ?><p><?php echo esc_html( $block_sub ); ?></p><?php endif; ?>
				</div>
				<a href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'View Full Range', 'mestc-theme' ); ?> →</a>
			</div>

			<div class="prod-grid">
				<?php if ( ! empty( $products ) ) : ?>
					<?php foreach ( $products as $p ) : ?>
						<a class="prod-card" href="<?php echo esc_url( $p['url'] ); ?>">
							<div class="prod-image">
								<?php if ( $p['thumb'] ) : ?>
									<img src="<?php echo esc_url( $p['thumb'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" />
								<?php else : ?>
									<span aria-hidden="true">📦</span>
								<?php endif; ?>
							</div>
							<div class="prod-name"><?php echo esc_html( $p['title'] ); ?></div>
							<?php if ( $p['price'] ) : ?>
								<div class="prod-price"><?php echo wp_kses_post( $p['price'] ); ?></div>
							<?php endif; ?>
							<span class="prod-view"><?php esc_html_e( 'View Detail', 'mestc-theme' ); ?></span>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<?php foreach ( $fallback as $f ) : ?>
						<a class="prod-card" href="<?php echo esc_url( $archive_url ); ?>">
							<div class="prod-image"><span aria-hidden="true"><?php echo esc_html( $f['icon'] ?? '📦' ); ?></span></div>
							<div class="prod-name"><?php echo esc_html( $f['name'] ); ?></div>
							<span class="prod-view"><?php esc_html_e( 'View Detail', 'mestc-theme' ); ?></span>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
