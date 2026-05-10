<?php
/**
 * Top utility bar (location | hours | phone | email).
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'topbar' ) ) {
	return;
}

$contact = mestc_contact_info();
$message = get_theme_mod( 'mestc_topbar_message', 'Supplying GCC & Middle East' );
?>
<div class="mestc-topbar" role="complementary">
	<div class="mestc-topbar-inner">
		<div class="topbar-left">
			<span class="topbar-location"><span aria-hidden="true">📍</span> <?php echo esc_html( $contact['address'] ); ?></span>
			<span class="topbar-divider" aria-hidden="true">|</span>
			<span class="topbar-hours"><?php echo esc_html( $contact['hours'] ); ?></span>
			<?php if ( $message ) : ?>
				<span class="topbar-divider" aria-hidden="true">|</span>
				<span class="topbar-message"><?php echo esc_html( $message ); ?></span>
			<?php endif; ?>
		</div>
		<div class="topbar-right">
			<a class="topbar-phone" href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>">
				<span aria-hidden="true">📞</span> <?php echo esc_html( $contact['phone'] ); ?>
			</a>
			<a class="topbar-email" href="mailto:<?php echo esc_attr( $contact['email'] ); ?>">
				<span aria-hidden="true">✉️</span> <?php echo esc_html( $contact['email'] ); ?>
			</a>
		</div>
	</div>
</div>
