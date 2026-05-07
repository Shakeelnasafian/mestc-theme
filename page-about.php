<?php
/**
 * Custom layout for the About page (slug: about).
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$contact = mestc_contact_info();
?>

<section class="mestc-aboutpage-hero">
	<div class="mestc-aboutpage-hero__inner">
		<div class="mestc-aboutpage-hero__text">
			<span class="eyebrow eyebrow--light"><?php esc_html_e( 'About MESTC · Since 2009', 'mestc-theme' ); ?></span>
			<h1><?php esc_html_e( 'Industrial &amp; Electrical Supply', 'mestc-theme' ); ?> <em><?php esc_html_e( 'Trusted Across the GCC', 'mestc-theme' ); ?></em></h1>
			<p><?php esc_html_e( 'A decade of supplying ATEX-certified equipment, professional tools and bulk industrial inventory to oil &amp; gas, construction, marine and energy projects in the UAE, Saudi Arabia, Qatar, Kuwait and Oman.', 'mestc-theme' ); ?></p>
			<div class="mestc-aboutpage-hero__cta">
				<a class="btn-orange" href="<?php echo esc_url( mestc_shop_url() ); ?>"><?php esc_html_e( 'Browse Catalogue', 'mestc-theme' ); ?> →</a>
				<a class="btn-white" href="<?php echo esc_url( mestc_contact_url() ); ?>"><?php esc_html_e( 'Get a Quote', 'mestc-theme' ); ?></a>
			</div>
		</div>
		<div class="mestc-aboutpage-hero__art" aria-hidden="true">
			<div class="hero-stat"><strong>10+</strong><span><?php esc_html_e( 'Years in UAE', 'mestc-theme' ); ?></span></div>
			<div class="hero-stat"><strong>500+</strong><span><?php esc_html_e( 'Products in stock', 'mestc-theme' ); ?></span></div>
			<div class="hero-stat"><strong>100+</strong><span><?php esc_html_e( 'B2B Clients', 'mestc-theme' ); ?></span></div>
			<div class="hero-stat"><strong>24/7</strong><span><?php esc_html_e( 'Engineer support', 'mestc-theme' ); ?></span></div>
		</div>
	</div>
</section>

<main id="primary" class="site-main mestc-aboutpage">

	<!-- ============= Story 2-col ============= -->
	<section class="section mestc-about-story">
		<div class="section-inner">
			<div class="section-header">
				<div class="section-header-left">
					<div class="eyebrow"><?php esc_html_e( 'Our Story', 'mestc-theme' ); ?></div>
					<h2><?php esc_html_e( 'Built around the projects that keep the GCC running.', 'mestc-theme' ); ?></h2>
				</div>
			</div>
			<div class="mestc-about-story__grid">
				<div class="mestc-about-story__art">
					<div class="story-photo" style="background-image:url('https://images.unsplash.com/photo-1581094288338-2314dddb7ece?w=900&q=80')"></div>
					<div class="story-photo story-photo--small" style="background-image:url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&q=80')"></div>
					<div class="story-badge">
						<strong>2009</strong>
						<span><?php esc_html_e( 'Founded in Dubai', 'mestc-theme' ); ?></span>
					</div>
				</div>
				<div class="mestc-about-story__text">
					<p><?php esc_html_e( 'MESTC began as a single supply yard in Dubai with a simple goal: get specialist industrial &amp; electrical components into the hands of UAE site teams without the lead-time pain of importing every unit. Fifteen years later we still answer the phone in 60 seconds — only now we ship across six countries and stock six categories of certified hardware.', 'mestc-theme' ); ?></p>
					<p><?php esc_html_e( 'Customers stay because we stay close to their drawings. We read the spec, we know which Zone 1 luminaire fits their cable diameter, and we hold stock against repeat projects so site never waits on a reorder. That&rsquo;s the bit catalogues never tell you about.', 'mestc-theme' ); ?></p>

					<ul class="mestc-about-pillars">
						<li>
							<div class="pillar-ico" aria-hidden="true">🛡</div>
							<div>
								<strong><?php esc_html_e( 'Certified hardware only', 'mestc-theme' ); ?></strong>
								<p><?php esc_html_e( 'ATEX, IECEx, CE, ISO 9001, UL — verified at the import door.', 'mestc-theme' ); ?></p>
							</div>
						</li>
						<li>
							<div class="pillar-ico" aria-hidden="true">⚡</div>
							<div>
								<strong><?php esc_html_e( 'Same-day dispatch', 'mestc-theme' ); ?></strong>
								<p><?php esc_html_e( 'Held stock in Dubai with next-day delivery across the UAE and 3–5 days GCC.', 'mestc-theme' ); ?></p>
							</div>
						</li>
						<li>
							<div class="pillar-ico" aria-hidden="true">🤝</div>
							<div>
								<strong><?php esc_html_e( 'Direct distributor lines', 'mestc-theme' ); ?></strong>
								<p><?php esc_html_e( 'Authorized for 50+ industrial brands — no grey-market intermediates.', 'mestc-theme' ); ?></p>
							</div>
						</li>
						<li>
							<div class="pillar-ico" aria-hidden="true">🧑‍🔧</div>
							<div>
								<strong><?php esc_html_e( 'Engineers on call', 'mestc-theme' ); ?></strong>
								<p><?php esc_html_e( 'Specifications, drawings, ratings — speak to a technical buyer, not a sales clerk.', 'mestc-theme' ); ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- ============= Mission / Vision / Values ============= -->
	<section class="section section-alt mestc-mvv">
		<div class="section-inner">
			<div class="section-header">
				<div class="section-header-left">
					<div class="eyebrow"><?php esc_html_e( 'Mission · Vision · Values', 'mestc-theme' ); ?></div>
					<h2><?php esc_html_e( 'Why our buyers keep coming back.', 'mestc-theme' ); ?></h2>
				</div>
			</div>
			<div class="mestc-mvv__grid">
				<article class="mvv-card">
					<div class="mvv-card__ico">🎯</div>
					<h3><?php esc_html_e( 'Our Mission', 'mestc-theme' ); ?></h3>
					<p><?php esc_html_e( 'Keep critical UAE &amp; GCC projects moving by holding the right industrial inventory locally — certified, priced for bulk, ready to ship.', 'mestc-theme' ); ?></p>
				</article>
				<article class="mvv-card mvv-card--accent">
					<div class="mvv-card__ico">🌍</div>
					<h3><?php esc_html_e( 'Our Vision', 'mestc-theme' ); ?></h3>
					<p><?php esc_html_e( 'Be the first call for any contractor, fabricator or facility manager in the Middle East who needs explosion-proof, marine-grade or industrial supply.', 'mestc-theme' ); ?></p>
				</article>
				<article class="mvv-card">
					<div class="mvv-card__ico">🏗</div>
					<h3><?php esc_html_e( 'Our Values', 'mestc-theme' ); ?></h3>
					<p><?php esc_html_e( 'Honest specs, transparent quotes, fast responses. Engineering integrity and after-sale follow-through, not just product placement.', 'mestc-theme' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<!-- ============= Capabilities timeline strip ============= -->
	<section class="section mestc-capabilities">
		<div class="section-inner">
			<div class="section-header">
				<div class="section-header-left">
					<div class="eyebrow"><?php esc_html_e( 'What We Do Best', 'mestc-theme' ); ?></div>
					<h2><?php esc_html_e( 'Six categories. One trusted source.', 'mestc-theme' ); ?></h2>
					<p><?php esc_html_e( 'Each category sits behind direct distributor agreements with global manufacturers.', 'mestc-theme' ); ?></p>
				</div>
				<a class="view-all-link" href="<?php echo esc_url( mestc_shop_url() ); ?>"><?php esc_html_e( 'View Catalogue', 'mestc-theme' ); ?> →</a>
			</div>
			<div class="mestc-capabilities__grid">
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">💥</span>
					<strong><?php esc_html_e( 'Explosion Proof', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'ATEX / IECEx Zone 1 &amp; 2', 'mestc-theme' ); ?></small>
				</a>
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">⚡</span>
					<strong><?php esc_html_e( 'Electrical Equipment', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Switchgear, panels, motors', 'mestc-theme' ); ?></small>
				</a>
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">🔧</span>
					<strong><?php esc_html_e( 'Hand &amp; Power Tools', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Insulated, calibrated, site-ready', 'mestc-theme' ); ?></small>
				</a>
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">🦺</span>
					<strong><?php esc_html_e( 'Safety &amp; PPE', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Industrial-grade head-to-toe', 'mestc-theme' ); ?></small>
				</a>
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">🔌</span>
					<strong><?php esc_html_e( 'Wires &amp; Cables', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'LV/MV, marine, fire-survival', 'mestc-theme' ); ?></small>
				</a>
				<a class="cap-card" href="<?php echo esc_url( mestc_shop_url() ); ?>">
					<span class="cap-ico" aria-hidden="true">🛢</span>
					<strong><?php esc_html_e( 'Oil &amp; Gas Supply', 'mestc-theme' ); ?></strong>
					<small><?php esc_html_e( 'Hazardous-area certified', 'mestc-theme' ); ?></small>
				</a>
			</div>
		</div>
	</section>

	<!-- ============= Industries we serve ============= -->
	<?php get_template_part( 'template-parts/sections/industries' ); ?>

	<!-- ============= Certifications ============= -->
	<?php get_template_part( 'template-parts/sections/certifications' ); ?>

	<!-- ============= Final CTA ============= -->
	<section class="mestc-finalcta">
		<div class="section-inner mestc-finalcta__inner">
			<div>
				<h2><?php esc_html_e( 'Make MESTC your single industrial supply source.', 'mestc-theme' ); ?></h2>
				<p><?php esc_html_e( 'Bulk pricing, certifications and engineer-grade response. Tell us what you need.', 'mestc-theme' ); ?></p>
			</div>
			<div class="mestc-finalcta__buttons">
				<a class="btn-orange" href="<?php echo esc_url( mestc_contact_url() ); ?>"><?php esc_html_e( 'Request a Quote', 'mestc-theme' ); ?> →</a>
				<a class="btn-white" href="tel:<?php echo esc_attr( mestc_tel( $contact['phone'] ) ); ?>"><?php esc_html_e( 'Call', 'mestc-theme' ); ?> <?php echo esc_html( $contact['phone'] ); ?></a>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
