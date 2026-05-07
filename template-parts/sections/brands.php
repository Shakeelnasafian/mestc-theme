<?php
/**
 * Brands grid — pulls from `mestc_brand` CPT, falls back to defaults.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$query = new WP_Query( array(
	'post_type'      => 'mestc_brand',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$default = array(
	'EATON', 'SCHNEIDER', 'SIEMENS', 'ABB', 'LEGRAND',
	'HONEYWELL', '3M', 'APPLETON', 'WEIDMULLER', 'KYOWA',
);
?>
<section class="section section-alt mestc-brands">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Trusted Brands', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Brands We Work With', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Authorized distributor for globally certified manufacturers', 'mestc-theme' ); ?></p>
			</div>
		</div>
		<div class="brands-grid">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) :
					$query->the_post();
					$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					$url   = get_post_meta( get_the_ID(), '_mestc_brand_url', true );
					?>
					<a class="brand-item" href="<?php echo esc_url( $url ?: '#' ); ?>"<?php echo $url ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php if ( $thumb ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
						<?php else : ?>
							<span><?php echo esc_html( get_the_title() ); ?></span>
						<?php endif; ?>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ( $default as $brand ) : ?>
					<div class="brand-item"><span><?php echo esc_html( $brand ); ?></span></div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
