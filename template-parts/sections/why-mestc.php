<?php
/**
 * Why MESTC — value-prop strip with comparison feel.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'why_mestc' ) ) { return; }

$cards = array(
	array(
		'ico'   => '📄',
		'title' => __( 'Quote in 24 hours', 'mestc-theme' ),
		'text'  => __( 'Send specs / quantities — a technical buyer comes back the same business day with stock and bulk pricing.', 'mestc-theme' ),
	),
	array(
		'ico'   => '🛡',
		'title' => __( 'Only certified hardware', 'mestc-theme' ),
		'text'  => __( 'ATEX, IECEx, ISO 9001, CE, UL — verification at the import door, not just on the marketing brochure.', 'mestc-theme' ),
	),
	array(
		'ico'   => '🚚',
		'title' => __( 'Stock held in Dubai', 'mestc-theme' ),
		'text'  => __( 'Same-day dispatch across the UAE, 3–5 days to the rest of the GCC. No air-freight surprise charges.', 'mestc-theme' ),
	),
	array(
		'ico'   => '🧑‍🔧',
		'title' => __( 'Engineers, not clerks', 'mestc-theme' ),
		'text'  => __( 'Speak to people who can read your single-line diagram and recommend the right Zone-1 luminaire.', 'mestc-theme' ),
	),
);
?>
<section class="section mestc-why">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Why MESTC', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Built for buyers who hate excuses.', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Four reasons site teams across the GCC keep our number on speed-dial.', 'mestc-theme' ); ?></p>
			</div>
			<a class="view-all-link" href="<?php echo esc_url( mestc_contact_url() ); ?>"><?php esc_html_e( 'Tell us your project', 'mestc-theme' ); ?> →</a>
		</div>
		<div class="mestc-why__grid">
			<?php foreach ( $cards as $i => $c ) : ?>
				<article class="why-card">
					<div class="why-card__num"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
					<div class="why-card__ico" aria-hidden="true"><?php echo esc_html( $c['ico'] ); ?></div>
					<h3><?php echo esc_html( $c['title'] ); ?></h3>
					<p><?php echo esc_html( $c['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
