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

$rail_id = get_query_var( 'mestc_rail_id' );
if ( $rail_id && ! mestc_section_visible( $rail_id ) ) { return; }

$eyebrow     = get_query_var( 'mestc_eyebrow' );
$heading     = get_query_var( 'mestc_heading' );
$sub         = get_query_var( 'mestc_sub' );
$alt         = (bool) get_query_var( 'mestc_alt' );
$category    = get_query_var( 'mestc_category' );
$block_title = get_query_var( 'mestc_block_title' );
$block_sub   = get_query_var( 'mestc_block_sub' );
$count       = (int) ( get_query_var( 'mestc_count' ) ?: 4 );
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
		$pid     = get_the_ID();
		$cats    = wc_get_product_terms( $pid, 'product_cat', array( 'fields' => 'names' ) );
		$cat     = is_array( $cats ) && ! is_wp_error( $cats ) && ! empty( $cats ) ? $cats[0] : '';
		$has_atex = $cat && stripos( $cat, 'explos' ) !== false;
		$products[] = array(
			'id'      => $pid,
			'title'   => get_the_title(),
			'url'     => get_permalink(),
			'thumb'   => get_the_post_thumbnail_url( $pid, 'mestc-product-thumb' ),
			'price'   => function_exists( 'wc_get_product' ) ? wc_get_product( $pid )->get_price_html() : '',
			'cat'     => $cat,
			'has_atex'=> $has_atex,
		);
	}
	wp_reset_postdata();
}
?>
<?php
$total_count = 0;
if ( $category ) {
	$term_obj = get_term_by( 'slug', $category, 'product_cat' );
	if ( $term_obj && ! is_wp_error( $term_obj ) ) {
		$total_count = (int) $term_obj->count;
	}
}
?>
<section class="section <?php echo $alt ? 'section-alt' : ''; ?> mestc-products-section">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<?php if ( $eyebrow ) : ?><div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div><?php endif; ?>
				<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
				<?php if ( $sub ) : ?><p><?php echo esc_html( $sub ); ?></p><?php endif; ?>
				<?php if ( $total_count > 0 ) : ?>
					<div class="mestc-rail-meta">
						<span class="mestc-rail-meta__pill"><?php echo (int) $total_count; ?> <?php esc_html_e( 'products available', 'mestc-theme' ); ?></span>
						<?php if ( $block_sub ) : ?>
							<span class="mestc-rail-meta__sub"><?php echo esc_html( $block_sub ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<a class="view-all-link" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'View Full Range', 'mestc-theme' ); ?> →</a>
		</div>

		<div class="product-block">
			<div class="prod-grid">
				<?php if ( ! empty( $products ) ) : ?>
					<?php foreach ( $products as $p ) : ?>
						<article class="prod-card">
							<a class="prod-card__link" href="<?php echo esc_url( $p['url'] ); ?>" aria-label="<?php echo esc_attr( $p['title'] ); ?>">
								<div class="prod-card__media">
									<?php if ( $p['has_atex'] ) : ?>
										<span class="prod-card__badge">ATEX · IECEx</span>
									<?php endif; ?>
									<?php if ( $p['thumb'] ) : ?>
										<img src="<?php echo esc_url( $p['thumb'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" />
									<?php else : ?>
										<span aria-hidden="true" class="prod-card__placeholder">📦</span>
									<?php endif; ?>
								</div>
								<div class="prod-card__body">
									<?php if ( $p['cat'] ) : ?>
										<div class="prod-card__cat"><?php echo esc_html( $p['cat'] ); ?></div>
									<?php endif; ?>
									<h4 class="prod-card__title"><?php echo esc_html( $p['title'] ); ?></h4>
									<?php if ( $p['price'] ) : ?>
										<div class="prod-card__price"><?php echo wp_kses_post( $p['price'] ); ?></div>
									<?php endif; ?>
								</div>
							</a>
							<div class="prod-card__actions">
								<button type="button" class="prod-card__btn-inquire mestc-add-rfq"
									data-product-id="<?php echo (int) $p['id']; ?>"
									data-product-title="<?php echo esc_attr( $p['title'] ); ?>"
									data-product-url="<?php echo esc_attr( $p['url'] ); ?>"
									data-product-thumb="<?php echo esc_attr( $p['thumb'] ); ?>">
									<span aria-hidden="true">+</span> <?php esc_html_e( 'Add to RFQ', 'mestc-theme' ); ?>
								</button>
								<button type="button" class="prod-card__btn-email mestc-inquire-btn"
									data-product-id="<?php echo (int) $p['id']; ?>"
									data-product-title="<?php echo esc_attr( $p['title'] ); ?>"
									data-product-url="<?php echo esc_attr( $p['url'] ); ?>"
									aria-label="<?php esc_attr_e( 'Email inquiry directly', 'mestc-theme' ); ?>"
									title="<?php esc_attr_e( 'Email inquiry directly', 'mestc-theme' ); ?>">
									<span aria-hidden="true">✉</span>
								</button>
							</div>
						</article>
					<?php endforeach; ?>
				<?php elseif ( ! empty( $fallback ) ) : ?>
					<?php foreach ( $fallback as $f ) : ?>
						<a class="prod-card prod-card--fallback" href="<?php echo esc_url( $archive_url ); ?>">
							<div class="prod-card__media">
								<span aria-hidden="true" class="prod-card__placeholder"><?php echo esc_html( $f['icon'] ?? '📦' ); ?></span>
							</div>
							<div class="prod-card__body">
								<h4 class="prod-card__title"><?php echo esc_html( $f['name'] ); ?></h4>
							</div>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="prod-empty">
						<p><?php esc_html_e( 'Catalogue update in progress — meanwhile speak to our specialists for stock availability.', 'mestc-theme' ); ?></p>
						<a class="btn-orange" href="<?php echo esc_url( mestc_contact_url() ); ?>"><?php esc_html_e( 'Send Inquiry', 'mestc-theme' ); ?> →</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
