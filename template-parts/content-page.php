<?php
/**
 * Single page content.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'mestc-entry mestc-entry-page' ); ?>>
	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'mestc-theme' ),
			'after'  => '</div>',
		) );
		?>
	</div>
</article>
