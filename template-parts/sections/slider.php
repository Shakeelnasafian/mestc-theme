<?php
/**
 * Hero slider — driven by Customizer slide settings.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'hero' ) ) { return; }

$default_images = array(
	1 => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=1600&q=80',
	2 => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1600&q=80',
	3 => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&q=80',
);

$slides = array();
for ( $i = 1; $i <= 3; $i++ ) {
	$d        = mestc_default_slide( $i );
	$image    = get_theme_mod( "mestc_slide_{$i}_image", '' );
	$slides[] = array(
		'tag'    => get_theme_mod( "mestc_slide_{$i}_tag",   $d['tag'] ),
		'title'  => get_theme_mod( "mestc_slide_{$i}_title", $d['title'] ),
		'text'   => get_theme_mod( "mestc_slide_{$i}_text",  $d['text'] ),
		'image'  => $image ?: $default_images[ $i ],
		'btn1_l' => get_theme_mod( "mestc_slide_{$i}_btn1_label", $d['btn1_label'] ),
		'btn1_u' => mestc_url_or_home( get_theme_mod( "mestc_slide_{$i}_btn1_url", $d['btn1_url'] ) ?: mestc_shop_url() ),
		'btn2_l' => get_theme_mod( "mestc_slide_{$i}_btn2_label", $d['btn2_label'] ),
		'btn2_u' => mestc_url_or_home( get_theme_mod( "mestc_slide_{$i}_btn2_url", $d['btn2_url'] ) ?: mestc_contact_url() ),
	);
}
$total = count( $slides );
$track_width = $total * 100;
$slide_width = 100 / $total;
?>
<section class="mestc-slider" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Hero slider', 'mestc-theme' ); ?>">
	<div class="slider-track" id="sliderTrack" style="width:<?php echo esc_attr( $track_width ); ?>%">
		<?php foreach ( $slides as $i => $s ) : ?>
			<div class="slide" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' / ' . $total ); ?>" style="flex-basis:<?php echo esc_attr( $slide_width ); ?>%;background-image:linear-gradient(to right,rgba(10,20,55,0.88) 45%,rgba(10,20,55,0.4) 100%),url('<?php echo esc_url( $s['image'] ); ?>');">
				<div class="slide-content">
					<?php if ( $s['tag'] ) : ?>
						<div class="slide-tag"><?php echo esc_html( $s['tag'] ); ?></div>
					<?php endif; ?>
					<h2 class="slide-title"><?php echo wp_kses( $s['title'], array( 'em' => array(), 'br' => array(), 'strong' => array() ) ); ?></h2>
					<p class="slide-text"><?php echo esc_html( $s['text'] ); ?></p>
					<div class="slide-btns">
						<?php if ( $s['btn1_l'] ) : ?>
							<a class="btn-white" href="<?php echo esc_url( $s['btn1_u'] ); ?>"><?php echo esc_html( $s['btn1_l'] ); ?></a>
						<?php endif; ?>
						<?php if ( $s['btn2_l'] ) : ?>
							<a class="btn-orange" href="<?php echo esc_url( $s['btn2_u'] ); ?>"><?php echo esc_html( $s['btn2_l'] ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<button class="slider-prev" id="sliderPrev" type="button" aria-label="<?php esc_attr_e( 'Previous slide', 'mestc-theme' ); ?>">&#8249;</button>
	<button class="slider-next" id="sliderNext" type="button" aria-label="<?php esc_attr_e( 'Next slide', 'mestc-theme' ); ?>">&#8250;</button>

	<aside class="mestc-hero-card" aria-label="<?php esc_attr_e( 'Trust signals', 'mestc-theme' ); ?>">
		<div class="mestc-hero-card__head">
			<div class="mestc-hero-card__rating">
				<span class="stars" aria-hidden="true">★★★★★</span>
				<strong>4.9 / 5</strong>
				<small><?php esc_html_e( 'from 100+ B2B clients', 'mestc-theme' ); ?></small>
			</div>
		</div>
		<ul class="mestc-hero-card__list">
			<li><span class="ico" aria-hidden="true">⏱</span><div><strong><?php esc_html_e( '24-hour quotes', 'mestc-theme' ); ?></strong><span><?php esc_html_e( 'Bulk RFQ turnaround', 'mestc-theme' ); ?></span></div></li>
			<li><span class="ico" aria-hidden="true">✓</span><div><strong><?php esc_html_e( 'ATEX / IECEx', 'mestc-theme' ); ?></strong><span><?php esc_html_e( 'Internationally certified', 'mestc-theme' ); ?></span></div></li>
			<li><span class="ico" aria-hidden="true">🚚</span><div><strong><?php esc_html_e( 'GCC delivery', 'mestc-theme' ); ?></strong><span><?php esc_html_e( 'Same / next day UAE', 'mestc-theme' ); ?></span></div></li>
		</ul>
		<div class="mestc-hero-card__cta">
			<a class="btn-orange" href="<?php echo esc_url( mestc_contact_url() ); ?>"><?php esc_html_e( 'Talk to a buyer', 'mestc-theme' ); ?> →</a>
		</div>
	</aside>

	<div class="slider-dots" role="tablist">
		<?php for ( $i = 0; $i < $total; $i++ ) : ?>
			<button type="button" class="slider-dot<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo (int) $i; ?>" role="tab" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'mestc-theme' ), $i + 1 ) ); ?>"<?php echo $i === 0 ? ' aria-selected="true"' : ' aria-selected="false"'; ?>></button>
		<?php endfor; ?>
	</div>
</section>
