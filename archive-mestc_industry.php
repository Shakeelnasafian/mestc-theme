<?php
/**
 * Industries archive.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
mestc_page_hero( __( 'Industries We Serve', 'mestc-theme' ), __( 'Specialized supply solutions for every major industrial sector in the UAE & GCC.', 'mestc-theme' ) );
?>
<main id="primary" class="site-main mestc-archive-main">
	<section class="section">
		<div class="section-inner">
			<?php if ( have_posts() ) : ?>
				<div class="ind-grid">
					<?php while ( have_posts() ) : the_post();
						$tag   = get_post_meta( get_the_ID(), '_mestc_industry_tag', true );
						$image = get_the_post_thumbnail_url( get_the_ID(), 'mestc-card' ) ?: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=900&q=70';
						?>
						<article class="ind-card">
							<a href="<?php the_permalink(); ?>" class="ind-image" style="background-image:linear-gradient(rgba(5,15,40,.25),rgba(5,15,40,.65)),url('<?php echo esc_url( $image ); ?>')" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( $tag ) : ?><span class="ind-tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
							</a>
							<div class="ind-body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<div class="ind-excerpt"><?php the_excerpt(); ?></div>
								<a class="ind-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Products', 'mestc-theme' ); ?> →</a>
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
