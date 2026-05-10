<?php
/**
 * Centralized section visibility registry.
 *
 * Every toggleable section across the front page, about page, contact page,
 * header and footer is registered here once. The same registry drives:
 *   - Customizer toggles (live preview)
 *   - MESTC Settings admin page checkboxes
 *   - Template guards via `mestc_section_visible( $id )`
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Registry — id => array( label, group, default ).
 * Group is one of: front, about, contact, header, footer.
 *
 * The id maps to a theme_mod key `mestc_show_<id>`.
 */
function mestc_visibility_registry() {
	return array(
		// ----- Header -----
		'topbar'              => array( 'label' => __( 'Top utility bar (location · phone · email)', 'mestc-theme' ), 'group' => 'header', 'default' => 1 ),
		'mega_menu'           => array( 'label' => __( 'Products mega-menu (categories with images)', 'mestc-theme' ), 'group' => 'header', 'default' => 1 ),
		'nav_search'          => array( 'label' => __( 'Search bar in header',                       'mestc-theme' ), 'group' => 'header', 'default' => 1 ),
		'nav_rfq_pill'        => array( 'label' => __( 'RFQ pill (with item counter)',              'mestc-theme' ), 'group' => 'header', 'default' => 1 ),
		'nav_quote_button'    => array( 'label' => __( '"Request a Quote" button in header',         'mestc-theme' ), 'group' => 'header', 'default' => 1 ),

		// ----- Front page sections -----
		'hero'                => array( 'label' => __( 'Hero slider',                'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'stats'               => array( 'label' => __( 'Stats bar (years, products, clients...)', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'trust'               => array( 'label' => __( 'Trust strip (4 perks)',      'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'certifications'      => array( 'label' => __( 'Certifications strip (ATEX / IECEx / ISO …)', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'categories'          => array( 'label' => __( 'Product categories grid',    'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'products_rail_1'     => array( 'label' => __( 'Product rail 1 — Explosion Proof', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'products_rail_2'     => array( 'label' => __( 'Product rail 2 — Hand & Power Tools', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'products_rail_3'     => array( 'label' => __( 'Product rail 3 — Safety & PPE', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'why_mestc'           => array( 'label' => __( '"Why MESTC" 4-card value-prop strip', 'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'about'               => array( 'label' => __( 'About section',              'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'industries'          => array( 'label' => __( 'Industries we serve',        'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'testimonials'        => array( 'label' => __( 'Customer testimonials',      'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'quote_band'          => array( 'label' => __( 'Quote band (orange CTA)',    'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'brands'              => array( 'label' => __( 'Brands marquee',             'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'blog'                => array( 'label' => __( 'Latest blog posts',          'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'contact_form'        => array( 'label' => __( 'Contact form section',       'mestc-theme' ), 'group' => 'front', 'default' => 1 ),
		'faq'                 => array( 'label' => __( 'FAQ accordion',              'mestc-theme' ), 'group' => 'front', 'default' => 1 ),

		// ----- About page sections -----
		'about_hero'          => array( 'label' => __( 'About page hero',            'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_story'         => array( 'label' => __( '"Our Story" 2-column block', 'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_mvv'           => array( 'label' => __( 'Mission · Vision · Values',  'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_capabilities'  => array( 'label' => __( 'Six capabilities grid',      'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_industries'    => array( 'label' => __( 'Industries we serve',        'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_certifications'=> array( 'label' => __( 'Certifications strip',       'mestc-theme' ), 'group' => 'about', 'default' => 1 ),
		'about_final_cta'     => array( 'label' => __( 'Final CTA banner',           'mestc-theme' ), 'group' => 'about', 'default' => 1 ),

		// ----- Contact page sections -----
		'contact_hero'        => array( 'label' => __( 'Contact page hero',          'mestc-theme' ), 'group' => 'contact', 'default' => 1 ),
		'contact_tiles'       => array( 'label' => __( 'Quick action tiles (call · email · WhatsApp · RFQ)', 'mestc-theme' ), 'group' => 'contact', 'default' => 1 ),
		'contact_form_block'  => array( 'label' => __( 'Form + info 2-column block', 'mestc-theme' ), 'group' => 'contact', 'default' => 1 ),
		'contact_map'         => array( 'label' => __( 'Embedded map',               'mestc-theme' ), 'group' => 'contact', 'default' => 1 ),
		'contact_faq'         => array( 'label' => __( 'FAQ accordion on contact page', 'mestc-theme' ), 'group' => 'contact', 'default' => 1 ),

		// ----- Footer -----
		'footer_about'        => array( 'label' => __( 'Footer brand block (logo + about + contact)', 'mestc-theme' ), 'group' => 'footer', 'default' => 1 ),
		'footer_newsletter'   => array( 'label' => __( 'Footer newsletter signup',    'mestc-theme' ), 'group' => 'footer', 'default' => 1 ),
		'footer_social'       => array( 'label' => __( 'Footer social icons',         'mestc-theme' ), 'group' => 'footer', 'default' => 1 ),
		'footer_certs'        => array( 'label' => __( 'Footer certification badges', 'mestc-theme' ), 'group' => 'footer', 'default' => 1 ),
	);
}

/**
 * Public helper used by section templates.
 * Returns true when a section should render.
 */
function mestc_section_visible( $id ) {
	$registry = mestc_visibility_registry();
	$default  = isset( $registry[ $id ]['default'] ) ? (int) $registry[ $id ]['default'] : 1;
	$mod      = get_theme_mod( 'mestc_show_' . $id, $default );
	return (bool) apply_filters( 'mestc_section_visible', (bool) $mod, $id );
}

/**
 * Group labels used in the admin + Customizer.
 */
function mestc_visibility_groups() {
	return array(
		'header'  => __( 'Header', 'mestc-theme' ),
		'front'   => __( 'Front Page Sections', 'mestc-theme' ),
		'about'   => __( 'About Page Sections', 'mestc-theme' ),
		'contact' => __( 'Contact Page Sections', 'mestc-theme' ),
		'footer'  => __( 'Footer', 'mestc-theme' ),
	);
}

/* ---------------- Customizer integration ---------------- */

add_action( 'customize_register', 'mestc_register_visibility_customizer', 20 );
function mestc_register_visibility_customizer( $wp_customize ) {
	$panel_id = 'mestc_panel';
	$registry = mestc_visibility_registry();
	$groups   = mestc_visibility_groups();

	foreach ( $groups as $group_id => $group_label ) {
		$section_id = 'mestc_visibility_' . $group_id;
		$wp_customize->add_section( $section_id, array(
			'title' => sprintf( __( 'Show / Hide — %s', 'mestc-theme' ), $group_label ),
			'panel' => $panel_id,
		) );

		foreach ( $registry as $id => $args ) {
			if ( $args['group'] !== $group_id ) { continue; }
			$key = 'mestc_show_' . $id;
			$wp_customize->add_setting( $key, array(
				'default'           => $args['default'],
				'sanitize_callback' => 'mestc_sanitize_checkbox',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( $key, array(
				'label'   => $args['label'],
				'section' => $section_id,
				'type'    => 'checkbox',
			) );
		}
	}
}
