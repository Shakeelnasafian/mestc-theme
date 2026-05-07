<?php
/**
 * Empty results placeholder.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="no-results not-found">
	<header class="page-header">
		<h2 class="page-title"><?php esc_html_e( 'Nothing found', 'mestc-theme' ); ?></h2>
	</header>
	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, no results matched your search. Try different keywords.', 'mestc-theme' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we cannot find what you are looking for. Try a search.', 'mestc-theme' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
