<?php
/**
 * Inquiry modal — rendered once in the footer; opened via JS by .mestc-inquire-btn buttons.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$contact = mestc_contact_info();
?>
<div class="mestc-inquire-modal" id="mestcInquireModal" role="dialog" aria-modal="true" aria-labelledby="mestcInquireTitle" hidden>
	<div class="mestc-inquire-modal__backdrop" data-mestc-close></div>
	<div class="mestc-inquire-modal__panel" role="document">
		<button type="button" class="mestc-inquire-modal__close" data-mestc-close aria-label="<?php esc_attr_e( 'Close', 'mestc-theme' ); ?>">×</button>

		<div class="mestc-inquire-modal__head">
			<div class="mestc-inquire-modal__eyebrow"><?php esc_html_e( 'Quote &amp; Inquiry', 'mestc-theme' ); ?></div>
			<h2 id="mestcInquireTitle"><?php esc_html_e( 'Request Information', 'mestc-theme' ); ?></h2>
			<p class="mestc-inquire-modal__sub"><?php esc_html_e( 'Tell us about your requirement and we will respond within 24 hours.', 'mestc-theme' ); ?></p>
		</div>

		<div class="mestc-inquire-product" data-mestc-product hidden>
			<div class="mestc-inquire-product__label"><?php esc_html_e( 'Inquiring about', 'mestc-theme' ); ?>:</div>
			<div class="mestc-inquire-product__title" data-mestc-product-title></div>
			<a class="mestc-inquire-product__link" href="#" target="_blank" rel="noopener" data-mestc-product-link><?php esc_html_e( 'View product page', 'mestc-theme' ); ?> →</a>
		</div>

		<form class="mestc-inquire-form" id="mestcInquireForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mestc_inquire" />
			<input type="hidden" name="product_id" value="" data-mestc-product-id />
			<?php wp_nonce_field( 'mestc_inquire', 'mestc_inquire_nonce' ); ?>

			<div class="mestc-hp" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden">
				<label>Leave this field empty <input type="text" name="mestc_hp" tabindex="-1" autocomplete="off" /></label>
			</div>

			<div class="mestc-inquire-form__row">
				<label class="mestc-inquire-field">
					<span class="mestc-inquire-field__label"><?php esc_html_e( 'Full name *', 'mestc-theme' ); ?></span>
					<input type="text" name="name" required autocomplete="name" />
				</label>
				<label class="mestc-inquire-field">
					<span class="mestc-inquire-field__label"><?php esc_html_e( 'Email *', 'mestc-theme' ); ?></span>
					<input type="email" name="email" required autocomplete="email" />
				</label>
			</div>
			<div class="mestc-inquire-form__row">
				<label class="mestc-inquire-field">
					<span class="mestc-inquire-field__label"><?php esc_html_e( 'Phone *', 'mestc-theme' ); ?></span>
					<input type="tel" name="phone" required autocomplete="tel" />
				</label>
				<label class="mestc-inquire-field">
					<span class="mestc-inquire-field__label"><?php esc_html_e( 'Company', 'mestc-theme' ); ?></span>
					<input type="text" name="company" autocomplete="organization" />
				</label>
			</div>
			<label class="mestc-inquire-field">
				<span class="mestc-inquire-field__label"><?php esc_html_e( 'Quantity / Specifications', 'mestc-theme' ); ?></span>
				<input type="text" name="quantity" placeholder="<?php esc_attr_e( 'e.g., 50 units, 24V', 'mestc-theme' ); ?>" />
			</label>
			<label class="mestc-inquire-field">
				<span class="mestc-inquire-field__label"><?php esc_html_e( 'Additional details', 'mestc-theme' ); ?></span>
				<textarea name="message" rows="4" placeholder="<?php esc_attr_e( 'Application, certification requirements, delivery location...', 'mestc-theme' ); ?>"></textarea>
			</label>

			<div class="mestc-inquire-form__footer">
				<button type="submit" class="mestc-inquire-form__submit btn-orange"><?php esc_html_e( 'Send Inquiry', 'mestc-theme' ); ?> →</button>
				<a class="mestc-inquire-form__call" href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>">
					<?php esc_html_e( 'Or call', 'mestc-theme' ); ?> <strong><?php echo esc_html( $contact['phone'] ); ?></strong>
				</a>
			</div>
			<div class="mestc-inquire-form__status" aria-live="polite"></div>
		</form>
	</div>
</div>
