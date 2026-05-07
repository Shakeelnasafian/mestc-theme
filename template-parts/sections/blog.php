<?php
/**
 * Latest blog posts (3).
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'no_found_rows'  => true,
) );

if ( ! $query->have_posts() ) { return; }

$blog_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' );
?>
<section class="section mestc-blog-latest">
	<div class="section-inner">
		<div class="section-header">
			<div class="section-header-left">
				<div class="eyebrow"><?php esc_html_e( 'Latest News', 'mestc-theme' ); ?></div>
				<h2><?php esc_html_e( 'From Our Blog', 'mestc-theme' ); ?></h2>
			</div>
			<a class="view-all-link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View All Posts', 'mestc-theme' ); ?> →</a>
		</div>

		<div class="blog-grid">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<article class="blog-card">
					<a href="<?php the_permalink(); ?>" class="blog-image" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'mestc-card' ) ?: 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=800&q=70' ); ?>')" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
					<div class="blog-body">
						<div class="blog-date"><?php echo esc_html( get_the_date() ); ?></div>
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<a class="blog-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'mestc-theme' ); ?> →</a>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
