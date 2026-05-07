<?php
/**
 * Default index — blog/posts listing fallback.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

if ( is_home() && ! is_front_page() ) {
	$blog_id = (int) get_option( 'page_for_posts' );
	$title   = $blog_id ? get_the_title( $blog_id ) : __( 'Latest News', 'mestc-theme' );
	mestc_page_hero( $title );
}
?>

<main id="primary" class="site-main mestc-archive-main">
	<div class="content-grid">
		<div class="content-main">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
					<?php endwhile; ?>
				</div>

				<?php
				the_posts_pagination( array(
					'prev_text' => __( '← Previous', 'mestc-theme' ),
					'next_text' => __( 'Next →', 'mestc-theme' ),
				) );
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>

		<aside class="content-sidebar">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</main>

<?php get_footer(); ?>
