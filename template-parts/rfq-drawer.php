<?php
/**
 * RFQ drawer — sliding panel that shows the user's accumulated line items
 * and the customer form. Rendered once in the footer; controlled by main.js.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$contact = function_exists( 'mestc_contact_info' ) ? mestc_contact_info() : array();
?>
<aside class="mestc-rfq" id="mestcRfq" aria-hidden="true" hidden>
	<div class="mestc-rfq__backdrop" data-mestc-rfq-close></div>

	<div class="mestc-rfq__panel" role="dialog" aria-modal="true" aria-labelledby="mestcRfqTitle">
		<header class="mestc-rfq__head">
			<div>
				<span class="mestc-rfq__eyebrow"><?php esc_html_e( 'Your Inquiry List', 'mestc-theme' ); ?></span>
				<h2 id="mestcRfqTitle"><?php esc_html_e( 'Bulk RFQ', 'mestc-theme' ); ?> · <span data-mestc-rfq-count>0</span></h2>
			</div>
			<button type="button" class="mestc-rfq__close" data-mestc-rfq-close aria-label="<?php esc_attr_e( 'Close', 'mestc-theme' ); ?>">×</button>
		</header>

		<div class="mestc-rfq__body">

			<!-- Empty state -->
			<div class="mestc-rfq__empty" data-mestc-rfq-empty>
				<div class="mestc-rfq__empty-ico" aria-hidden="true">📋</div>
				<h3><?php esc_html_e( 'Your inquiry list is empty', 'mestc-theme' ); ?></h3>
				<p><?php esc_html_e( 'Click "Add to RFQ" on any product to start building a bulk inquiry. We respond within 24 hours with pricing, lead times and availability.', 'mestc-theme' ); ?></p>
				<a class="btn-orange" href="<?php echo esc_url( mestc_shop_url() ); ?>" data-mestc-rfq-close><?php esc_html_e( 'Browse Products', 'mestc-theme' ); ?> →</a>
			</div>

			<!-- Line items list -->
			<ul class="mestc-rfq__lines" data-mestc-rfq-lines hidden></ul>

			<!-- Customer form -->
			<form class="mestc-rfq__form" id="mestcRfqForm" data-mestc-rfq-form hidden>
				<h4><?php esc_html_e( 'Send your inquiry', 'mestc-theme' ); ?></h4>
				<?php wp_nonce_field( 'mestc_inquire', 'mestc_inquire_nonce' ); ?>
				<div class="mestc-hp" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden">
					<label>Leave this field empty <input type="text" name="mestc_hp" tabindex="-1" autocomplete="off" /></label>
				</div>

				<div class="mestc-rfq__form-row">
					<label>
						<span><?php esc_html_e( 'Full name *', 'mestc-theme' ); ?></span>
						<input type="text" name="name" required autocomplete="name" />
					</label>
					<label>
						<span><?php esc_html_e( 'Email *', 'mestc-theme' ); ?></span>
						<input type="email" name="email" required autocomplete="email" />
					</label>
				</div>
				<div class="mestc-rfq__form-row">
					<label>
						<span><?php esc_html_e( 'Phone *', 'mestc-theme' ); ?></span>
						<input type="tel" name="phone" required autocomplete="tel" />
					</label>
					<label>
						<span><?php esc_html_e( 'Company', 'mestc-theme' ); ?></span>
						<input type="text" name="company" autocomplete="organization" />
					</label>
				</div>
				<label class="mestc-rfq__form-message">
					<span><?php esc_html_e( 'Additional details', 'mestc-theme' ); ?></span>
					<textarea name="message" rows="3" placeholder="<?php esc_attr_e( 'Delivery location, certifications required, deadline...', 'mestc-theme' ); ?>"></textarea>
				</label>

				<div class="mestc-rfq__status" aria-live="polite"></div>

				<button type="submit" class="mestc-rfq__submit"><?php esc_html_e( 'Send Bulk RFQ', 'mestc-theme' ); ?> →</button>
				<a class="mestc-rfq__mailto" href="mailto:<?php echo esc_attr( $contact['email'] ?? '' ); ?>" data-mestc-rfq-mailto>
					<?php esc_html_e( 'Or open in your email app', 'mestc-theme' ); ?> →
				</a>
			</form>
		</div>
	</div>
</aside>

<!-- Floating quick-access trigger (always visible once an item is added) -->
<button type="button" class="mestc-rfq-fab" id="mestcRfqFab" data-mestc-rfq-open hidden>
	<span class="mestc-rfq-fab__ico" aria-hidden="true">📋</span>
	<span class="mestc-rfq-fab__text">
		<strong><?php esc_html_e( 'Inquiry List', 'mestc-theme' ); ?></strong>
		<small><span data-mestc-rfq-count>0</span> <?php esc_html_e( 'items', 'mestc-theme' ); ?></small>
	</span>
</button>
