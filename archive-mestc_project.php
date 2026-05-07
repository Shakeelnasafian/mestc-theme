<?php
/**
 * Projects archive.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero( __( 'Our Projects', 'mestc-theme' ), __( 'Selected work delivered across the UAE & GCC.', 'mestc-theme' ) );
?>
<main id="primary" class="site-main mestc-archive-main">
	<section class="section">
		<div class="section-inner">
			<?php if ( have_posts() ) : ?>
				<div class="blog-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article class="blog-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="blog-image" href="<?php the_permalink(); ?>" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'mestc-card' ) ); ?>')" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
							<?php endif; ?>
							<div class="blog-body">
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<div class="entry-summary"><?php the_excerpt(); ?></div>
								<a class="blog-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Project', 'mestc-theme' ); ?> →</a>
							</div>
						</article>
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
	</section>
</main>
<?php get_footer(); ?>
