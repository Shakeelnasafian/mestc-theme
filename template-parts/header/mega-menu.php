<?php
/**
 * Mega menu — product categories with thumbnails.
 *
 * Rendered as a sibling of the primary nav. Opened on hover/focus of the
 * Products menu trigger via main.js.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! taxonomy_exists( 'product_cat' ) ) { return; }

$terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
	'orderby'    => 'count',
	'order'      => 'DESC',
	'number'     => 9,
	'exclude'    => array( get_option( 'default_product_cat' ) ),
) );

if ( empty( $terms ) || is_wp_error( $terms ) ) { return; }

// Build a thumbnail per term — prefer the term's thumbnail, fall back to the first product's image.
$cat_thumbs = array();
foreach ( $terms as $term ) {
	$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	$thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'mestc-product-thumb' ) : '';

	if ( ! $thumb ) {
		$q = new WP_Query( array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
			'meta_query'     => array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		) );
		if ( $q->have_posts() ) {
			$q->the_post();
			$thumb = get_the_post_thumbnail_url( get_the_ID(), 'mestc-product-thumb' );
			wp_reset_postdata();
		}
	}

	$cat_thumbs[ $term->term_id ] = $thumb;
}
?>
<div class="mestc-mega" id="mestcMega" aria-hidden="true">
	<div class="mestc-mega__inner">
		<div class="mestc-mega__grid">
			<?php foreach ( $terms as $term ) :
				$thumb = $cat_thumbs[ $term->term_id ] ?? '';
				$icon  = function_exists( 'mestc_default_category_icon' )
					? mestc_default_category_icon( $term->slug, $term->name )
					: '📦';
				?>
				<a class="mestc-mega__card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<div class="mestc-mega__card-thumb">
						<?php if ( $thumb ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" />
						<?php else : ?>
							<span aria-hidden="true" class="mestc-mega__card-icon"><?php echo esc_html( $icon ); ?></span>
						<?php endif; ?>
					</div>
					<div class="mestc-mega__card-text">
						<strong><?php echo esc_html( $term->name ); ?></strong>
						<span><?php
							echo esc_html( sprintf(
								_n( '%d product', '%d products', (int) $term->count, 'mestc-theme' ),
								(int) $term->count
							) );
						?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
