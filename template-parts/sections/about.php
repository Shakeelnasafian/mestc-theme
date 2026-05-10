<?php
/**
 * About section.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'about' ) ) { return; }

$default_body   = "MESTC is a leading wholesaler and trusted partner in the industrial and electrical supply industry. We specialize in the bulk supply of high-quality, durable components and systems for oil & gas, construction, marine, and industrial applications across the UAE and GCC.\n\nWe are more than just a supplier — we are your dedicated business partner. Our experienced team is committed to providing expert support and seamless service to keep your projects running on time.";
$default_checks = "ATEX, IECEx, CE and ISO 9001 certified products\nAuthorized distributor for 50+ leading global brands\nFast delivery across UAE, Saudi Arabia, Qatar & Oman\nDedicated technical support team with field expertise\nCompetitive pricing with custom RFQ for bulk orders";

$image     = get_theme_mod( 'mestc_about_image', 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1000&q=80' );
$badge_num = get_theme_mod( 'mestc_about_badge_num', '10+' );
$badge_lbl = get_theme_mod( 'mestc_about_badge_lbl', 'Years in UAE Market' );
$heading   = get_theme_mod( 'mestc_about_heading', 'Welcome to MESTC — Industrial & Electrical Supply Company in UAE' );
$body      = get_theme_mod( 'mestc_about_text', $default_body );
$checks    = mestc_lines_to_array( get_theme_mod( 'mestc_about_checks', $default_checks ) );
$btn_label = get_theme_mod( 'mestc_about_btn_label', 'EXPLORE MORE' );
$btn_url   = mestc_url_or_home( get_theme_mod( 'mestc_about_btn_url', '' ) ?: home_url( '/about/' ) );
$paragraphs = preg_split( '/\n\s*\n/', trim( (string) $body ) );
?>
<section class="section mestc-about">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'About MESTC', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Your Trusted Industrial & Electrical Supply Partner', 'mestc-theme' ); ?></h2>
			</div>
		</div>

		<div class="about-grid">
			<div class="about-image-wrap">
				<div class="about-image" style="background-image:url('<?php echo esc_url( $image ); ?>')"></div>
				<?php if ( $badge_num || $badge_lbl ) : ?>
					<div class="about-badge">
						<strong><?php echo esc_html( $badge_num ); ?></strong>
						<span><?php echo esc_html( $badge_lbl ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			<div class="about-content">
				<h3><?php echo esc_html( $heading ); ?></h3>
				<?php foreach ( $paragraphs as $p ) : if ( trim( $p ) === '' ) { continue; } ?>
					<p><?php echo wp_kses_post( $p ); ?></p>
				<?php endforeach; ?>

				<?php if ( ! empty( $checks ) ) : ?>
					<ul class="about-checklist">
						<?php foreach ( $checks as $line ) : ?>
							<li><div class="check-icon" aria-hidden="true">✓</div><?php echo esc_html( $line ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $btn_label ) : ?>
					<a class="btn-orange" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_label ); ?> →</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
