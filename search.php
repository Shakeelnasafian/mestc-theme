<?php
/**
 * Search results.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero(
	sprintf( __( 'Search results: %s', 'mestc-theme' ), '<em>' . esc_html( get_search_query() ) . '</em>' ),
	sprintf( _n( '%d result', '%d results', (int) $GLOBALS['wp_query']->found_posts, 'mestc-theme' ), (int) $GLOBALS['wp_query']->found_posts )
);
?>
<main id="primary" class="site-main mestc-search-main">
	<div class="content-grid">
		<div class="content-main">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'search' ); ?>
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
