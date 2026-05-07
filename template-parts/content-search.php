<?php
/**
 * Search result row.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card search-result' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="blog-image" href="<?php the_permalink(); ?>" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'mestc-card' ) ); ?>')" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
	<?php endif; ?>
	<div class="blog-body">
		<div class="blog-date"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ?? get_post_type() ); ?></div>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
		<a class="blog-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View', 'mestc-theme' ); ?> →</a>
	</div>
</article>
