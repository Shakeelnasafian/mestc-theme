<?php
/**
 * Stats bar — icon + number + label.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$defaults = mestc_default_stats();
$icons = array( '🏆', '📦', '🤝', '🌐', '⚡' );
?>
<div class="stats-bar">
	<?php for ( $i = 1; $i <= 5; $i++ ) :
		$num = get_theme_mod( "mestc_stat_{$i}_num", $defaults[ $i - 1 ]['num'] );
		$lbl = get_theme_mod( "mestc_stat_{$i}_lbl", $defaults[ $i - 1 ]['lbl'] );
		if ( $num === '' && $lbl === '' ) { continue; }
		?>
		<div class="stat-item">
			<div class="stat-icon" aria-hidden="true"><?php echo esc_html( $icons[ $i - 1 ] ?? '✦' ); ?></div>
			<div class="stat-text">
				<div class="stat-num"><?php echo esc_html( $num ); ?></div>
				<div class="stat-label"><?php echo esc_html( $lbl ); ?></div>
			</div>
		</div>
	<?php endfor; ?>
</div>
