<?php
/**
 * Generic archive (categories, tags, custom taxonomies, CPT archives that don't have their own).
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero( get_the_archive_title(), get_the_archive_description() );
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
