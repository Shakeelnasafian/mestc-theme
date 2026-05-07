<?php
/**
 * Industries section — pulls from `mestc_industry` CPT, falls back to defaults.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$query = new WP_Query( array(
	'post_type'      => 'mestc_industry',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$default_images = array(
	'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=900&q=70',
	'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=900&q=70',
	'https://images.unsplash.com/photo-1565043589221-1a6fd9ae45c7?w=900&q=70',
);
$default_industries = array(
	array(
		'title' => 'Oil & Gas',
		'tag'   => 'ATEX Certified',
		'text'  => 'Explosion-proof and hazardous area certified equipment for upstream, midstream and downstream operations.',
		'image' => $default_images[0],
		'url'   => mestc_shop_url(),
	),
	array(
		'title' => 'Construction',
		'tag'   => 'Site Ready',
		'text'  => 'Tools, safety gear, and electrical supplies for large-scale construction projects across the GCC.',
		'image' => $default_images[1],
		'url'   => mestc_shop_url(),
	),
	array(
		'title' => 'Marine & Offshore',
		'tag'   => 'Marine Grade',
		'text'  => 'Marine-grade wiring, equipment and safety solutions for the harshest offshore environments.',
		'image' => $default_images[2],
		'url'   => mestc_shop_url(),
	),
);

$cards = array();
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		$id    = get_the_ID();
		$image = get_the_post_thumbnail_url( $id, 'mestc-card' );
		$tag   = get_post_meta( $id, '_mestc_industry_tag', true );
		$url   = get_post_meta( $id, '_mestc_industry_url', true );
		$cards[] = array(
			'title' => get_the_title(),
			'tag'   => $tag,
			'text'  => wp_strip_all_tags( get_the_excerpt() ),
			'image' => $image ?: $default_images[ count( $cards ) % 3 ],
			'url'   => $url ?: get_permalink(),
		);
	}
	wp_reset_postdata();
} else {
	$cards = $default_industries;
}
?>
<section class="section section-alt mestc-industries">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Industries We Serve', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'Built for the Most Demanding Sectors', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Specialized supply solutions for every major industrial sector in the UAE', 'mestc-theme' ); ?></p>
			</div>
		</div>

		<div class="ind-grid">
			<?php foreach ( $cards as $c ) : ?>
				<article class="ind-card">
					<a href="<?php echo esc_url( $c['url'] ); ?>" class="ind-image" style="background-image:linear-gradient(rgba(5,15,40,.25),rgba(5,15,40,.65)),url('<?php echo esc_url( $c['image'] ); ?>')" aria-label="<?php echo esc_attr( $c['title'] ); ?>">
						<?php if ( ! empty( $c['tag'] ) ) : ?>
							<span class="ind-tag"><?php echo esc_html( $c['tag'] ); ?></span>
						<?php endif; ?>
					</a>
					<div class="ind-body">
						<h3><a href="<?php echo esc_url( $c['url'] ); ?>"><?php echo esc_html( $c['title'] ); ?></a></h3>
						<?php if ( $c['text'] ) : ?>
							<p><?php echo esc_html( wp_trim_words( $c['text'], 24, '…' ) ); ?></p>
						<?php endif; ?>
						<a class="ind-link" href="<?php echo esc_url( $c['url'] ); ?>"><?php esc_html_e( 'View Products', 'mestc-theme' ); ?> →</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
