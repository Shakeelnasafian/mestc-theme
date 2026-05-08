<?php
/**
 * Footer.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$contact = mestc_contact_info();
$about   = get_theme_mod( 'mestc_footer_about', 'Your trusted industrial and electrical supply company in Dubai, UAE. Serving Oil & Gas, Construction, Marine and Industrial sectors across the GCC with internationally certified products since 2009.' );
$certs   = array_filter( array_map( 'trim', explode( ',', get_theme_mod( 'mestc_footer_certs', 'ATEX,IECEx,ISO 9001,CE Marked' ) ) ) );
?>
<footer class="mestc-footer" role="contentinfo">
	<div class="mestc-footer-inner">
		<div class="footer-grid">
			<div class="footer-col footer-col--brand">
				<div class="footer-logo"><?php mestc_logo( true ); ?></div>
				<?php if ( $about ) : ?>
					<p class="footer-desc"><?php echo wp_kses_post( $about ); ?></p>
				<?php endif; ?>
				<div class="footer-contact">
					<a href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>">
						<span aria-hidden="true">📞</span> <?php echo esc_html( $contact['phone'] ); ?>
					</a>
					<a href="mailto:<?php echo esc_attr( $contact['email'] ); ?>">
						<span aria-hidden="true">✉️</span> <?php echo esc_html( $contact['email'] ); ?>
					</a>
					<a href="<?php echo esc_url( mestc_contact_url() ); ?>">
						<span aria-hidden="true">📍</span> <?php echo esc_html( $contact['address'] ); ?>
					</a>
				</div>

				<div class="mestc-newsletter">
					<h5><?php esc_html_e( 'Stay in the loop', 'mestc-theme' ); ?></h5>
					<p><?php esc_html_e( 'Catalogue updates, certification news and bulk-order specials — straight to your inbox.', 'mestc-theme' ); ?></p>
					<form id="mestcQuickQuoteFooter" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
						<input type="hidden" name="action" value="mestc_inquire" />
						<?php wp_nonce_field( 'mestc_inquire', 'mestc_inquire_nonce' ); ?>
						<input type="hidden" name="mestc_hp" value="" />
						<input type="hidden" name="name" value="<?php esc_attr_e( 'Newsletter Signup', 'mestc-theme' ); ?>" />
						<input type="hidden" name="phone" value="-" />
						<input type="hidden" name="message" value="<?php esc_attr_e( 'Newsletter / catalogue subscription request.', 'mestc-theme' ); ?>" />
						<label class="screen-reader-text" for="mestc-newsletter-email"><?php esc_html_e( 'Email address', 'mestc-theme' ); ?></label>
						<input id="mestc-newsletter-email" type="email" name="email" required placeholder="<?php esc_attr_e( 'your@email.com', 'mestc-theme' ); ?>" />
						<button type="submit"><?php esc_html_e( 'Subscribe', 'mestc-theme' ); ?></button>
					</form>
				</div>

				<div class="mestc-social" aria-label="<?php esc_attr_e( 'Social profiles', 'mestc-theme' ); ?>">
					<a href="#" aria-label="LinkedIn"><span aria-hidden="true">in</span></a>
					<a href="#" aria-label="Facebook"><span aria-hidden="true">f</span></a>
					<a href="#" aria-label="Instagram"><span aria-hidden="true">◎</span></a>
					<a href="#" aria-label="WhatsApp"><span aria-hidden="true">✆</span></a>
				</div>
			</div>

			<?php
			$columns = array(
				array(
					'title'    => __( 'Products', 'mestc-theme' ),
					'menu'     => 'footer-products',
					'sidebar'  => 'footer-2',
					'fallback' => array(
						array( 'label' => 'Explosion Proof',     'url' => mestc_shop_url() ),
						array( 'label' => 'Electrical Equipment','url' => mestc_shop_url() ),
						array( 'label' => 'Hand & Power Tools',  'url' => mestc_shop_url() ),
						array( 'label' => 'Safety / PPE',        'url' => mestc_shop_url() ),
						array( 'label' => 'Wires & Cables',      'url' => mestc_shop_url() ),
						array( 'label' => 'Oil & Gas Products',  'url' => mestc_shop_url() ),
					),
				),
				array(
					'title'    => __( 'Company', 'mestc-theme' ),
					'menu'     => 'footer-company',
					'sidebar'  => 'footer-3',
					'fallback' => array(
						array( 'label' => 'About Us',       'url' => home_url( '/about/' ) ),
						array( 'label' => 'Industries',     'url' => get_post_type_archive_link( 'mestc_industry' ) ?: home_url( '/industries/' ) ),
						array( 'label' => 'Projects',       'url' => get_post_type_archive_link( 'mestc_project' )  ?: home_url( '/projects/' ) ),
						array( 'label' => 'Certifications', 'url' => home_url( '/certifications/' ) ),
						array( 'label' => 'Blog',           'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
						array( 'label' => 'Contact Us',     'url' => mestc_contact_url() ),
					),
				),
				array(
					'title'    => __( 'Support', 'mestc-theme' ),
					'menu'     => 'footer-support',
					'sidebar'  => 'footer-4',
					'fallback' => array(
						array( 'label' => 'Request a Quote',     'url' => mestc_contact_url() ),
						array( 'label' => 'Technical Help',      'url' => mestc_contact_url() ),
						array( 'label' => 'Download Catalogue',  'url' => '#' ),
						array( 'label' => 'Track Order',         'url' => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/track-order/' ) ),
						array( 'label' => 'FAQs',                'url' => home_url( '/faqs/' ) ),
					),
				),
			);

			foreach ( $columns as $col ) :
				?>
				<div class="footer-col">
					<h4><?php echo esc_html( $col['title'] ); ?></h4>
					<?php if ( is_active_sidebar( $col['sidebar'] ) ) : ?>
						<?php dynamic_sidebar( $col['sidebar'] ); ?>
					<?php else : ?>
						<nav aria-label="<?php echo esc_attr( $col['title'] ); ?>">
							<?php mestc_nav_menu( $col['menu'], $col['fallback'] ); ?>
						</nav>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="footer-bottom">
			<p><?php mestc_footer_copyright(); ?></p>
			<?php if ( ! empty( $certs ) ) : ?>
				<div class="footer-certs" aria-label="<?php esc_attr_e( 'Certifications', 'mestc-theme' ); ?>">
					<?php foreach ( $certs as $cert ) : ?>
						<span class="cert-badge"><?php echo esc_html( $cert ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/inquire-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
