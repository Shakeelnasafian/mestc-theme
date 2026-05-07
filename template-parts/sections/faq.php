<?php
/**
 * FAQ section — pulls from `mestc_faq` CPT, falls back to defaults.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$query = new WP_Query( array(
	'post_type'      => 'mestc_faq',
	'post_status'    => 'publish',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$items = array();
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		$items[] = array(
			'q' => get_the_title(),
			'a' => apply_filters( 'the_content', get_the_content() ),
		);
	}
	wp_reset_postdata();
} else {
	$items = array(
		array( 'q' => 'What does MESTC specialize in?',                'a' => 'MESTC specializes in the wholesale supply of industrial and electrical products including explosion-proof equipment, hand tools, safety PPE, wires & cables, and oilfield supplies for UAE and GCC markets.' ),
		array( 'q' => 'Are your products ATEX and IECEx certified?',   'a' => 'Yes. All hazardous area products we supply are fully certified under ATEX and IECEx international standards, suitable for use in oil & gas, petrochemical and marine environments.' ),
		array( 'q' => 'Do you offer bulk pricing and custom RFQ?',     'a' => 'Absolutely. We offer competitive bulk pricing and custom RFQ for large orders. Submit your requirements and our team will respond within 24 hours with a detailed quotation.' ),
		array( 'q' => 'Which industries do you serve?',                'a' => 'We serve Oil & Gas, Construction, Marine & Offshore, Manufacturing, Energy & Power, and general industrial sectors across the UAE, Saudi Arabia, Qatar, Kuwait and Oman.' ),
		array( 'q' => 'How fast can you deliver within the UAE?',      'a' => 'We maintain local stock in Dubai and offer same-day and next-day delivery across the UAE. GCC deliveries are handled within 3–5 business days depending on location.' ),
	);
}
?>
<section class="section mestc-faq">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'FAQs', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Frequently Asked Questions', 'mestc-theme' ); ?></h2>
			</div>
		</div>
		<div class="faq-list" role="list">
			<?php foreach ( $items as $i => $item ) :
				$qid = 'faq-q-' . $i;
				$aid = 'faq-a-' . $i;
				?>
				<div class="faq-item" role="listitem">
					<button class="faq-question" id="<?php echo esc_attr( $qid ); ?>" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $aid ); ?>">
						<span><?php echo esc_html( $item['q'] ); ?></span>
					</button>
					<div class="faq-answer" id="<?php echo esc_attr( $aid ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $qid ); ?>" hidden>
						<?php echo wp_kses_post( wpautop( $item['a'] ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
