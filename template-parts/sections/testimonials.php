<?php
/**
 * Testimonials section — three quotes with name, role, company.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'testimonials' ) ) { return; }

$items = array(
	array(
		'quote' => __( '"MESTC ships exactly what we spec. Three years of project deliveries and not one return for non-conformity. The team understands hazardous-area certification."', 'mestc-theme' ),
		'name'  => 'Hassan Al-Mansoori',
		'role'  => __( 'Senior Procurement Manager', 'mestc-theme' ),
		'org'   => 'EPC Contractor, Abu Dhabi',
		'star'  => 5,
	),
	array(
		'quote' => __( '"They held inventory against our rolling site schedule and dispatched within 24 hours every single time. Pricing was straight bulk, no markup tricks."', 'mestc-theme' ),
		'name'  => 'Priya Ramanathan',
		'role'  => __( 'Site Engineer', 'mestc-theme' ),
		'org'   => 'Construction Group, Dubai',
		'star'  => 5,
	),
	array(
		'quote' => __( '"We compared four suppliers in the GCC. MESTC was the only one that read our wiring schedule and pushed back on a wrong cable gland we were about to order. Saved us a re-spec cycle."', 'mestc-theme' ),
		'name'  => 'Mohammed Al-Suwaidi',
		'role'  => __( 'Operations Lead', 'mestc-theme' ),
		'org'   => 'Oil & Gas Operator',
		'star'  => 5,
	),
);
?>
<section class="section section-alt mestc-testimonials">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'What our buyers say', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Trusted by procurement teams across the GCC.', 'mestc-theme' ); ?></h2>
			</div>
		</div>
		<div class="mestc-testimonials__grid">
			<?php foreach ( $items as $t ) : ?>
				<article class="testi-card">
					<div class="testi-stars" aria-label="<?php echo esc_attr( $t['star'] . ' / 5' ); ?>">
						<?php for ( $i = 0; $i < $t['star']; $i++ ) { echo '★'; } ?>
					</div>
					<blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
					<footer>
						<div class="testi-avatar"><?php echo esc_html( mb_substr( $t['name'], 0, 1 ) ); ?></div>
						<div>
							<strong><?php echo esc_html( $t['name'] ); ?></strong>
							<span><?php echo esc_html( $t['role'] ); ?> · <?php echo esc_html( $t['org'] ); ?></span>
						</div>
					</footer>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
