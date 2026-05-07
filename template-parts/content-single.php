<?php
/**
 * Single post content.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'mestc-entry mestc-entry-single' ); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<span class="meta-date"><?php echo esc_html( get_the_date() ); ?></span>
			<span class="meta-sep">·</span>
			<span class="meta-author"><?php the_author(); ?></span>
			<?php
			$categories = get_the_category_list( ', ' );
			if ( $categories ) :
				?>
				<span class="meta-sep">·</span>
				<span class="meta-cats"><?php echo wp_kses_post( $categories ); ?></span>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'mestc-theme' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<footer class="entry-footer">
		<?php
		$tags = get_the_tag_list( '<span class="tags-label">' . esc_html__( 'Tags:', 'mestc-theme' ) . '</span> ', ', ' );
		if ( $tags ) :
			?>
			<div class="entry-tags"><?php echo wp_kses_post( $tags ); ?></div>
		<?php endif; ?>
	</footer>
</article>
