<?php
/**
 * Custom layout for the Contact page (slug: contact).
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$contact = mestc_contact_info();
$flag    = isset( $_GET['mestc_sent'] ) ? sanitize_text_field( wp_unslash( $_GET['mestc_sent'] ) ) : '';
?>

<section class="mestc-contactpage-hero">
	<div class="mestc-contactpage-hero__inner">
		<span class="eyebrow eyebrow--light"><?php esc_html_e( 'Contact MESTC', 'mestc-theme' ); ?></span>
		<h1><?php esc_html_e( 'Talk to a real engineer in', 'mestc-theme' ); ?> <em><?php esc_html_e( '60 seconds.', 'mestc-theme' ); ?></em></h1>
		<p><?php esc_html_e( 'Spec sheets, bulk RFQs, technical questions, delivery to remote sites — pick the channel that suits you.', 'mestc-theme' ); ?></p>
	</div>
</section>

<main id="primary" class="site-main mestc-contactpage">

	<!-- ============= Quick action tiles ============= -->
	<section class="section">
		<div class="section-inner">
			<div class="mestc-contact-tiles">
				<a class="contact-tile" href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>">
					<div class="contact-tile__ico">📞</div>
					<strong><?php esc_html_e( 'Call us', 'mestc-theme' ); ?></strong>
					<span><?php echo esc_html( $contact['phone'] ); ?></span>
					<small><?php esc_html_e( 'Sun–Thu · 08:00 – 17:30', 'mestc-theme' ); ?></small>
				</a>
				<a class="contact-tile" href="mailto:<?php echo esc_attr( $contact['email'] ); ?>">
					<div class="contact-tile__ico">✉</div>
					<strong><?php esc_html_e( 'Email', 'mestc-theme' ); ?></strong>
					<span><?php echo esc_html( $contact['email'] ); ?></span>
					<small><?php esc_html_e( 'Replies within 24 hours', 'mestc-theme' ); ?></small>
				</a>
				<a class="contact-tile" href="https://wa.me/<?php echo esc_attr( ltrim( mestc_tel( $contact['phone'] ), '+' ) ); ?>" target="_blank" rel="noopener">
					<div class="contact-tile__ico">💬</div>
					<strong><?php esc_html_e( 'WhatsApp', 'mestc-theme' ); ?></strong>
					<span><?php esc_html_e( 'Chat with sales', 'mestc-theme' ); ?></span>
					<small><?php esc_html_e( 'Send specs &amp; drawings', 'mestc-theme' ); ?></small>
				</a>
				<a class="contact-tile" href="#mestcContactBlock">
					<div class="contact-tile__ico">📋</div>
					<strong><?php esc_html_e( 'Request RFQ', 'mestc-theme' ); ?></strong>
					<span><?php esc_html_e( 'Bulk pricing form', 'mestc-theme' ); ?></span>
					<small><?php esc_html_e( '24-hour quote turnaround', 'mestc-theme' ); ?></small>
				</a>
			</div>
		</div>
	</section>

	<!-- ============= Form + Info ============= -->
	<section class="section section-alt" id="mestcContactBlock">
		<div class="section-inner">
			<div class="mestc-contactpage__grid">
				<div class="mestc-contactpage__info">
					<span class="eyebrow"><?php esc_html_e( 'Get In Touch', 'mestc-theme' ); ?></span>
					<h2><?php esc_html_e( 'Send us your project specs.', 'mestc-theme' ); ?></h2>
					<p class="mestc-contactpage__lede"><?php esc_html_e( 'Drop us a line — quantities, certifications, delivery location. A technical buyer will respond with stock availability and bulk pricing within 24 hours.', 'mestc-theme' ); ?></p>

					<ul class="mestc-contactpage__list">
						<li>
							<span class="ico" aria-hidden="true">📍</span>
							<div><strong><?php esc_html_e( 'Address', 'mestc-theme' ); ?></strong><span><?php echo esc_html( $contact['address'] ); ?></span></div>
						</li>
						<li>
							<span class="ico" aria-hidden="true">🕐</span>
							<div><strong><?php esc_html_e( 'Working Hours', 'mestc-theme' ); ?></strong><span><?php echo esc_html( $contact['hours'] ); ?></span></div>
						</li>
						<li>
							<span class="ico" aria-hidden="true">📞</span>
							<div><strong><?php esc_html_e( 'Phone', 'mestc-theme' ); ?></strong>
								<a href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>"><?php echo esc_html( $contact['phone'] ); ?></a>
							</div>
						</li>
						<li>
							<span class="ico" aria-hidden="true">✉</span>
							<div><strong><?php esc_html_e( 'Email', 'mestc-theme' ); ?></strong>
								<a href="mailto:<?php echo esc_attr( $contact['email'] ); ?>"><?php echo esc_html( $contact['email'] ); ?></a>
							</div>
						</li>
					</ul>

					<div class="mestc-contactpage__map">
						<iframe
							src="https://www.openstreetmap.org/export/embed.html?bbox=55.0,25.0,55.5,25.4&amp;layer=mapnik"
							width="100%"
							height="240"
							loading="lazy"
							style="border:0;border-radius:8px"
							title="<?php esc_attr_e( 'MESTC location map', 'mestc-theme' ); ?>"></iframe>
					</div>
				</div>

				<form class="contact-form mestc-contactpage__form" id="mestcContactForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<h3><?php esc_html_e( 'Request a Quote or Inquiry', 'mestc-theme' ); ?></h3>
					<p class="mestc-contactpage__form-lede"><?php esc_html_e( '* Required fields. We never share your details.', 'mestc-theme' ); ?></p>

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
					<textarea class="form-textarea" name="message" rows="5" placeholder="<?php esc_attr_e( 'Tell us what you need — product name, quantity, certifications, delivery location...', 'mestc-theme' ); ?>"></textarea>
					<button class="form-submit" type="submit"><?php esc_html_e( 'Send Inquiry', 'mestc-theme' ); ?> →</button>
					<div class="form-message form-message--inline" aria-live="polite"></div>

					<div class="mestc-contactpage__assure">
						<span aria-hidden="true">🔒</span>
						<?php esc_html_e( 'Your details are kept confidential and used only to reply to your inquiry.', 'mestc-theme' ); ?>
					</div>
				</form>
			</div>
		</div>
	</section>

	<!-- ============= FAQ block (compact) ============= -->
	<?php get_template_part( 'template-parts/sections/faq' ); ?>

</main>

<?php get_footer(); ?>
