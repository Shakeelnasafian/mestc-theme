<?php
/**
 * Single post.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero( single_post_title( '', false ) ?: get_the_title() );
?>
<main id="primary" class="site-main mestc-single-main">
	<div class="content-grid">
		<div class="content-main">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'single' );
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
		</div>
		<aside class="content-sidebar">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</main>
<?php get_footer(); ?>
