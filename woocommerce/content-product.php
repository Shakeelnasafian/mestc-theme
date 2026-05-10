<?php
/**
 * Branded WooCommerce loop card.
 *
 * Bypasses third-party "Email Us" hooks by rendering a clean MESTC card.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $product;
if ( empty( $product ) || ! $product->is_visible() ) { return; }

$id      = $product->get_id();
$url     = get_permalink( $id );
$title   = $product->get_name();
$short   = wp_strip_all_tags( $product->get_short_description() );
$short   = $short ? wp_trim_words( $short, 18, '…' ) : '';
$thumb   = get_the_post_thumbnail( $id, 'mestc-product-thumb', array( 'class' => 'mestc-card-img', 'loading' => 'lazy', 'alt' => esc_attr( $title ) ) );
if ( ! $thumb && function_exists( 'wc_placeholder_img' ) ) {
	$thumb = wc_placeholder_img( 'mestc-product-thumb', array( 'class' => 'mestc-card-img', 'alt' => '' ) );
}
$cats    = wc_get_product_category_list( $id, ', ' );
$onsale  = $product->is_on_sale();
?>
<li <?php wc_product_class( 'mestc-product-card', $product ); ?>>
	<a class="mestc-card-link" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
		<div class="mestc-card-thumb">
			<?php if ( $onsale ) : ?>
				<span class="mestc-card-badge mestc-card-badge--sale"><?php esc_html_e( 'Sale', 'mestc-theme' ); ?></span>
			<?php endif; ?>
			<?php
			// Detect a generic "ATEX/IECEx" tag from the category list.
			if ( $cats && stripos( $cats, 'explos' ) !== false ) {
				echo '<span class="mestc-card-badge mestc-card-badge--cert">ATEX</span>';
			}
			?>
			<?php echo $thumb ? wp_kses_post( $thumb ) : ''; ?>
		</div>
		<div class="mestc-card-body">
			<?php if ( $cats ) : ?>
				<div class="mestc-card-cat"><?php echo wp_kses_post( $cats ); ?></div>
			<?php endif; ?>
			<h3 class="mestc-card-title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $short ) : ?>
				<p class="mestc-card-excerpt"><?php echo esc_html( $short ); ?></p>
			<?php endif; ?>
		</div>
	</a>
	<div class="mestc-card-actions">
		<?php if ( apply_filters( 'mestc_show_prices', false ) ) :
			$price = $product->get_price_html();
			if ( $price ) : ?>
				<span class="mestc-card-price"><?php echo wp_kses_post( $price ); ?></span>
			<?php endif;
		endif; ?>
		<?php
		$thumb_url = '';
		if ( has_post_thumbnail( $id ) ) {
			$thumb_url = get_the_post_thumbnail_url( $id, 'mestc-product-thumb' );
		}
		?>
		<div class="mestc-card-actions__group">
			<button type="button" class="mestc-card-actions__primary mestc-add-rfq"
				data-product-id="<?php echo (int) $id; ?>"
				data-product-title="<?php echo esc_attr( $title ); ?>"
				data-product-url="<?php echo esc_attr( $url ); ?>"
				data-product-thumb="<?php echo esc_attr( $thumb_url ); ?>">
				<span aria-hidden="true">+</span> <?php esc_html_e( 'Add to RFQ', 'mestc-theme' ); ?>
			</button>
			<button type="button" class="mestc-card-actions__email mestc-inquire-btn"
				data-product-id="<?php echo (int) $id; ?>"
				data-product-title="<?php echo esc_attr( $title ); ?>"
				data-product-url="<?php echo esc_attr( $url ); ?>"
				aria-label="<?php esc_attr_e( 'Email inquiry directly', 'mestc-theme' ); ?>"
				title="<?php esc_attr_e( 'Email inquiry directly', 'mestc-theme' ); ?>">
				<span aria-hidden="true">✉</span>
			</button>
		</div>
		<a class="mestc-card-view" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'View Details', 'mestc-theme' ); ?> →</a>
	</div>
</li>
