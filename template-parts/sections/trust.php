<?php
/**
 * Trust strip.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$items = array(
	array( 'icon' => '✓', 'title' => __( 'ATEX / IECEx Certified',  'mestc-theme' ), 'sub' => __( 'All products internationally certified', 'mestc-theme' ) ),
	array( 'icon' => '🚚','title' => __( 'Fast UAE Delivery',       'mestc-theme' ), 'sub' => __( 'Same & next-day dispatch available',     'mestc-theme' ) ),
	array( 'icon' => '💬','title' => __( '24/7 Technical Support',  'mestc-theme' ), 'sub' => __( 'Expert engineers always ready',           'mestc-theme' ) ),
	array( 'icon' => '📋','title' => __( 'Custom RFQ',              'mestc-theme' ), 'sub' => __( 'Bulk orders & special specs',            'mestc-theme' ) ),
);
?>
<div class="trust-strip">
	<?php foreach ( $items as $it ) : ?>
		<div class="trust-item">
			<div class="trust-icon" aria-hidden="true"><?php echo esc_html( $it['icon'] ); ?></div>
			<div class="trust-text">
				<strong><?php echo esc_html( $it['title'] ); ?></strong>
				<span><?php echo esc_html( $it['sub'] ); ?></span>
			</div>
		</div>
	<?php endforeach; ?>
</div>
