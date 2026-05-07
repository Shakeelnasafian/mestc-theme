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
		<div class="mestc-brands-marquee" aria-label="<?php esc_attr_e( 'Authorized distributor brands', 'mestc-theme' ); ?>">
			<div class="mestc-brands-marquee__track">
				<?php
				$render_brand = function ( $title, $thumb = '', $url = '' ) {
					$tag    = $url ? 'a' : 'div';
					$attrs  = 'class="brand-item"';
					if ( $url ) { $attrs .= ' href="' . esc_url( $url ) . '" target="_blank" rel="noopener"'; }
					echo "<{$tag} {$attrs}>";
					if ( $thumb ) {
						printf( '<img src="%s" alt="%s" loading="lazy" />', esc_url( $thumb ), esc_attr( $title ) );
					} else {
						printf( '<span>%s</span>', esc_html( $title ) );
					}
					echo "</{$tag}>";
				};

				$render_set = function () use ( $query, $default, $render_brand ) {
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
							$url   = get_post_meta( get_the_ID(), '_mestc_brand_url', true );
							$render_brand( get_the_title(), $thumb, $url );
						}
						wp_reset_postdata();
					} else {
						foreach ( $default as $brand ) {
							$render_brand( $brand );
						}
					}
				};
				$render_set();
				$render_set(); // duplicate for seamless marquee
				?>
			</div>
		</div>
	</div>
</section>
