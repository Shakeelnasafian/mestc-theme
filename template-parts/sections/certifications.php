<?php
/**
 * Certification strip — quick visual trust marker.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$items = array(
	array( 'name' => 'ATEX',    'sub' => 'Hazardous Areas' ),
	array( 'name' => 'IECEx',   'sub' => 'International' ),
	array( 'name' => 'ISO 9001','sub' => 'Quality System' ),
	array( 'name' => 'CE',      'sub' => 'Marked' ),
	array( 'name' => 'UL',      'sub' => 'Listed' ),
	array( 'name' => 'NEMA',    'sub' => 'Compliant' ),
);
?>
<aside class="mestc-certs-strip" aria-label="<?php esc_attr_e( 'Certifications', 'mestc-theme' ); ?>">
	<div class="mestc-certs-strip__inner">
		<div class="mestc-certs-strip__lead">
			<span class="mestc-certs-strip__eyebrow"><?php esc_html_e( 'Internationally certified', 'mestc-theme' ); ?></span>
			<span class="mestc-certs-strip__title"><?php esc_html_e( 'Every product, every standard', 'mestc-theme' ); ?></span>
		</div>
		<div class="mestc-certs-strip__list">
			<?php foreach ( $items as $it ) : ?>
				<div class="mestc-cert">
					<strong><?php echo esc_html( $it['name'] ); ?></strong>
					<span><?php echo esc_html( $it['sub'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</aside>
