<?php
/**
 * Product categories grid — pulled from WooCommerce product_cat terms.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'categories' ) ) { return; }

$terms = array();
if ( taxonomy_exists( 'product_cat' ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => 8,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'exclude'    => array( get_option( 'default_product_cat' ) ),
	) );
}

if ( is_wp_error( $terms ) ) {
	$terms = array();
}

// Skip the catch-all "Uncategorized" term defensively.
$terms = array_values( array_filter( $terms, function ( $t ) {
	return $t->slug !== 'uncategorized' && (int) $t->count > 0;
} ) );

// Fallback to a static list if WooCommerce hasn't been populated yet.
$fallback = array(
	array( 'name' => 'Electrical Equipment',  'count' => 22, 'icon' => '⚡',  'url' => mestc_shop_url() ),
	array( 'name' => 'Explosion Proof',       'count' => 24, 'icon' => '💥', 'url' => mestc_shop_url() ),
	array( 'name' => 'Hand & Power Tools',    'count' => 27, 'icon' => '🔧', 'url' => mestc_shop_url() ),
	array( 'name' => 'Safety & PPE',          'count' => 12, 'icon' => '🦺', 'url' => mestc_shop_url() ),
	array( 'name' => 'Wires & Cables',        'count' => 16, 'icon' => '🔌', 'url' => mestc_shop_url() ),
	array( 'name' => 'Oil & Gas Products',    'count' => 45, 'icon' => '🛢️', 'url' => mestc_shop_url() ),
	array( 'name' => 'Construction Material', 'count' => 15, 'icon' => '🏗️', 'url' => mestc_shop_url() ),
	array( 'name' => 'Industrial Automation', 'count' => 18, 'icon' => '⚙️', 'url' => mestc_shop_url() ),
);
?>
<section class="section section-alt mestc-categories">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Browse by Category', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Product Categories', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Complete industrial & electrical supply for every requirement', 'mestc-theme' ); ?></p>
			</div>
			<a class="view-all-link" href="<?php echo esc_url( mestc_shop_url() ); ?>">
				<?php esc_html_e( 'View All Categories', 'mestc-theme' ); ?> →
			</a>
		</div>
		<div class="cat-grid">
			<?php if ( ! empty( $terms ) ) : ?>
				<?php foreach ( $terms as $term ) :
					$icon = mestc_default_category_icon( $term->slug, $term->name );
					$thumb_id = function_exists( 'get_term_meta' ) ? (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0;
					$image = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) : '';
					?>
					<a class="cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
						<div class="cat-icon">
							<?php if ( $image ) : ?>
								<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
							<?php else : ?>
								<span aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
							<?php endif; ?>
						</div>
						<h3><?php echo esc_html( $term->name ); ?></h3>
						<p><?php
							echo esc_html( sprintf(
								/* translators: %d: number of products */
								_n( '%d Product', '%d Products', (int) $term->count, 'mestc-theme' ),
								(int) $term->count
							) );
						?></p>
					</a>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach ( $fallback as $f ) : ?>
					<a class="cat-card" href="<?php echo esc_url( $f['url'] ); ?>">
						<div class="cat-icon"><span aria-hidden="true"><?php echo esc_html( $f['icon'] ); ?></span></div>
						<h3><?php echo esc_html( $f['name'] ); ?></h3>
						<p><?php echo esc_html( $f['count'] ); ?> <?php esc_html_e( 'Products', 'mestc-theme' ); ?></p>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
