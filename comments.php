<?php
/**
 * Comments template.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( post_password_required() ) { return; }
?>
<section id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			if ( '1' === (string) $count ) {
				printf( esc_html__( 'One thought on “%s”', 'mestc-theme' ), esc_html( get_the_title() ) );
			} else {
				printf(
					esc_html( _nx( '%1$s thought on “%2$s”', '%1$s thoughts on “%2$s”', $count, 'comments title', 'mestc-theme' ) ),
					number_format_i18n( $count ),
					esc_html( get_the_title() )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>

		<?php
		the_comments_navigation( array(
			'prev_text' => __( '← Older comments', 'mestc-theme' ),
			'next_text' => __( 'Newer comments →', 'mestc-theme' ),
		) );

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'mestc-theme' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_form'         => 'comment-form mestc-form',
		'class_submit'       => 'btn-orange',
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h3>',
	) );
	?>
</section>
