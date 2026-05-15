<?php
/**
 * Contact section — info + inquiry form.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'contact_form' ) ) { return; }

$contact = mestc_contact_info();
$flag    = isset( $_GET['mestc_sent'] ) ? sanitize_text_field( wp_unslash( $_GET['mestc_sent'] ) ) : '';
?>
<section class="section section-alt mestc-contact">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Get In Touch', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Send Us Your Inquiry', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Let us help you find the right products for your project', 'mestc-theme' ); ?></p>
			</div>
		</div>

		<div class="contact-grid">
			<div class="contact-info">
				<h2><?php esc_html_e( 'Contact MESTC', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Our experts are ready to assist with product selection, bulk pricing, technical specifications and fast delivery across the UAE and GCC.', 'mestc-theme' ); ?></p>

				<div class="contact-item">
					<div class="contact-icon" aria-hidden="true">📞</div>
					<div><strong><?php esc_html_e( 'Phone', 'mestc-theme' ); ?></strong>
						<a href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>"><?php echo esc_html( $contact['phone'] ); ?></a>
					</div>
				</div>
				<div class="contact-item">
					<div class="contact-icon" aria-hidden="true">✉️</div>
					<div><strong><?php esc_html_e( 'Email', 'mestc-theme' ); ?></strong>
						<a href="mailto:<?php echo esc_attr( $contact['email'] ); ?>"><?php echo esc_html( $contact['email'] ); ?></a>
					</div>
				</div>
				<div class="contact-item">
					<div class="contact-icon" aria-hidden="true">📍</div>
					<div><strong><?php esc_html_e( 'Address', 'mestc-theme' ); ?></strong>
						<span><?php echo esc_html( $contact['address'] ); ?></span>
					</div>
				</div>
				<div class="contact-item">
					<div class="contact-icon" aria-hidden="true">🕐</div>
					<div><strong><?php esc_html_e( 'Working Hours', 'mestc-theme' ); ?></strong>
						<span><?php echo esc_html( $contact['hours'] ); ?></span>
					</div>
				</div>
			</div>

			<div class="contact-form">
				<h3><?php esc_html_e( 'Request a Quote or Inquiry', 'mestc-theme' ); ?></h3>

				<?php if ( ! mestc_render_fluentform_contact() ) : ?>
					<form id="mestcContactForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<?php if ( $flag === '1' ) : ?>
							<div class="form-message form-message--ok"><?php esc_html_e( 'Thank you. We will respond within 24 hours.', 'mestc-theme' ); ?></div>
						<?php elseif ( $flag === '0' ) : ?>
							<div class="form-message form-message--err"><?php esc_html_e( 'Sorry, something went wrong. Please try again or call us directly.', 'mestc-theme' ); ?></div>
						<?php endif; ?>

						<input type="hidden" name="action" value="mestc_contact" />
						<?php wp_nonce_field( 'mestc_contact', 'mestc_contact_nonce' ); ?>
						<div class="mestc-hp" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden">
							<label>Leave this field empty <input type="text" name="mestc_hp" tabindex="-1" autocomplete="off" /></label>
						</div>

						<div class="form-row">
							<input class="form-input" type="text"  name="name"    required placeholder="<?php esc_attr_e( 'Full Name *',    'mestc-theme' ); ?>" />
							<input class="form-input" type="email" name="email"   required placeholder="<?php esc_attr_e( 'Email Address *','mestc-theme' ); ?>" />
						</div>
						<div class="form-row">
							<input class="form-input" type="tel"  name="phone"   required placeholder="<?php esc_attr_e( 'Phone Number *', 'mestc-theme' ); ?>" />
							<input class="form-input" type="text" name="company"          placeholder="<?php esc_attr_e( 'Company Name',  'mestc-theme' ); ?>" />
						</div>
						<textarea class="form-textarea" name="message" rows="4" placeholder="<?php esc_attr_e( 'Tell us what you need — product name, quantity, specifications...', 'mestc-theme' ); ?>"></textarea>
						<button class="form-submit" type="submit"><?php esc_html_e( 'SUBMIT INQUIRY', 'mestc-theme' ); ?> →</button>
						<div class="form-message form-message--inline" aria-live="polite"></div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
