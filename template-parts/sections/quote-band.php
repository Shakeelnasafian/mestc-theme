<?php
/**
 * Quote band — quick email capture.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! mestc_section_visible( 'quote_band' ) ) { return; }

$title = get_theme_mod( 'mestc_quote_band_title', 'Make MESTC Your Single Source for Industrial & Electrical Supply.' );
$text  = get_theme_mod( 'mestc_quote_band_text',  'Get a Quote for Your Project Needs — Our team responds within 24 hours.' );
?>
<aside class="quote-band">
	<div class="quote-band-inner">
		<div class="quote-band-text">
			<h2><?php echo esc_html( $title ); ?></h2>
			<?php if ( $text ) : ?><p><?php echo esc_html( $text ); ?></p><?php endif; ?>
		</div>
		<form class="quote-band-form" id="mestcQuickQuote" action="<?php echo esc_url( mestc_contact_url() ); ?>" method="post">
			<label class="screen-reader-text" for="mestcQuickQuoteEmail"><?php esc_html_e( 'Your email address', 'mestc-theme' ); ?></label>
			<input id="mestcQuickQuoteEmail" class="quote-input" type="email" name="email" required placeholder="<?php esc_attr_e( 'Your email address', 'mestc-theme' ); ?>" />
			<button type="submit" class="btn-orange"><?php esc_html_e( 'GET A QUOTE TODAY', 'mestc-theme' ); ?> →</button>
			<span class="quote-band-status" aria-live="polite"></span>
		</form>
	</div>
</aside>
