<?php
/**
 * Customizer settings: contact, hero slides, stats, trust strip, quote band, footer.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_customize_register( $wp_customize ) {

	/* ---------------- Panel: MESTC Theme ---------------- */
	$wp_customize->add_panel( 'mestc_panel', array(
		'title'    => __( 'MESTC Theme', 'mestc-theme' ),
		'priority' => 10,
	) );

	/* ---------------- Section: Contact ---------------- */
	$wp_customize->add_section( 'mestc_contact', array(
		'title' => __( 'Contact Information', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$contact_fields = array(
		'mestc_phone'           => array( 'label' => 'Phone Number',     'default' => '+971 XX XXX XXXX' ),
		'mestc_email'           => array( 'label' => 'Email Address',    'default' => 'info@mestc.com' ),
		'mestc_address'         => array( 'label' => 'Address',          'default' => 'Dubai, United Arab Emirates' ),
		'mestc_hours'           => array( 'label' => 'Working Hours',    'default' => 'Sun–Thu: 08:00 – 17:30 GST' ),
		'mestc_topbar_message'  => array( 'label' => 'Top Bar Message',  'default' => 'Supplying GCC & Middle East' ),
	);
	foreach ( $contact_fields as $id => $args ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $args['label'],
			'section' => 'mestc_contact',
			'type'    => 'text',
		) );
	}

	/* ---------------- Section: Header ---------------- */
	$wp_customize->add_section( 'mestc_header', array(
		'title' => __( 'Header', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$wp_customize->add_setting( 'mestc_show_topbar', array(
		'default'           => 1,
		'sanitize_callback' => 'mestc_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'mestc_show_topbar', array(
		'label'   => 'Show top utility bar',
		'section' => 'mestc_header',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'mestc_quote_button_label', array(
		'default'           => 'REQUEST A QUOTE',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_quote_button_label', array(
		'label'   => 'Header CTA button label',
		'section' => 'mestc_header',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mestc_quote_button_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'mestc_quote_button_url', array(
		'label'       => 'Header CTA button URL',
		'description' => 'Defaults to the contact page if left empty.',
		'section'     => 'mestc_header',
		'type'        => 'url',
	) );

	/* ---------------- Section: Hero Slider ---------------- */
	$wp_customize->add_section( 'mestc_hero', array(
		'title' => __( 'Hero Slider (Front Page)', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$wp_customize->add_setting( 'mestc_show_hero', array(
		'default' => 1,
		'sanitize_callback' => 'mestc_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'mestc_show_hero', array(
		'label'   => 'Show hero slider',
		'section' => 'mestc_hero',
		'type'    => 'checkbox',
	) );

	for ( $i = 1; $i <= 3; $i++ ) {
		$defaults = mestc_default_slide( $i );

		$wp_customize->add_setting( "mestc_slide_{$i}_tag", array(
			'default' => $defaults['tag'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_tag", array(
			'label'   => "Slide {$i} — Tag",
			'section' => 'mestc_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_title", array(
			'default' => $defaults['title'],
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_title", array(
			'label'       => "Slide {$i} — Title (HTML allowed)",
			'description' => 'Wrap accent text in &lt;em&gt; tags. Use &lt;br&gt; for line breaks.',
			'section'     => 'mestc_hero',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_text", array(
			'default' => $defaults['text'],
			'sanitize_callback' => 'sanitize_textarea_field',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_text", array(
			'label'   => "Slide {$i} — Description",
			'section' => 'mestc_hero',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_image", array(
			'default' => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "mestc_slide_{$i}_image", array(
			'label'   => "Slide {$i} — Background Image",
			'section' => 'mestc_hero',
		) ) );

		$wp_customize->add_setting( "mestc_slide_{$i}_btn1_label", array(
			'default' => $defaults['btn1_label'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_btn1_label", array(
			'label'   => "Slide {$i} — Button 1 Label",
			'section' => 'mestc_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_btn1_url", array(
			'default' => $defaults['btn1_url'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_btn1_url", array(
			'label'   => "Slide {$i} — Button 1 URL",
			'section' => 'mestc_hero',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_btn2_label", array(
			'default' => $defaults['btn2_label'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_btn2_label", array(
			'label'   => "Slide {$i} — Button 2 Label",
			'section' => 'mestc_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "mestc_slide_{$i}_btn2_url", array(
			'default' => $defaults['btn2_url'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( "mestc_slide_{$i}_btn2_url", array(
			'label'   => "Slide {$i} — Button 2 URL",
			'section' => 'mestc_hero',
			'type'        => 'url',
		) );
	}

	/* ---------------- Section: Stats ---------------- */
	$wp_customize->add_section( 'mestc_stats', array(
		'title' => __( 'Stats Bar', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$default_stats = mestc_default_stats();
	for ( $i = 1; $i <= 5; $i++ ) {
		$d = $default_stats[ $i - 1 ];
		$wp_customize->add_setting( "mestc_stat_{$i}_num", array(
			'default' => $d['num'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "mestc_stat_{$i}_num", array(
			'label'   => "Stat {$i} — Number",
			'section' => 'mestc_stats',
			'type'    => 'text',
		) );
		$wp_customize->add_setting( "mestc_stat_{$i}_lbl", array(
			'default' => $d['lbl'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "mestc_stat_{$i}_lbl", array(
			'label'   => "Stat {$i} — Label",
			'section' => 'mestc_stats',
			'type'    => 'text',
		) );
	}

	/* ---------------- Section: About ---------------- */
	$wp_customize->add_section( 'mestc_about', array(
		'title' => __( 'About Section', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$wp_customize->add_setting( 'mestc_about_image', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mestc_about_image', array(
		'label'   => 'About — Image',
		'section' => 'mestc_about',
	) ) );

	$wp_customize->add_setting( 'mestc_about_badge_num', array(
		'default' => '10+', 'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_about_badge_num', array(
		'label' => 'Badge Number', 'section' => 'mestc_about', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'mestc_about_badge_lbl', array(
		'default' => 'Years in UAE Market', 'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_about_badge_lbl', array(
		'label' => 'Badge Label', 'section' => 'mestc_about', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'mestc_about_heading', array(
		'default' => 'Welcome to MESTC — Industrial & Electrical Supply Company in UAE',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_about_heading', array(
		'label' => 'Heading', 'section' => 'mestc_about', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'mestc_about_text', array(
		'default' => "MESTC is a leading wholesaler and trusted partner in the industrial and electrical supply industry. We specialize in the bulk supply of high-quality, durable components and systems for oil & gas, construction, marine, and industrial applications across the UAE and GCC.\n\nWe are more than just a supplier — we are your dedicated business partner. Our experienced team is committed to providing expert support and seamless service to keep your projects running on time.",
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'mestc_about_text', array(
		'label'       => 'Body text',
		'description' => 'Separate paragraphs with a blank line.',
		'section'     => 'mestc_about',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'mestc_about_checks', array(
		'default' => "ATEX, IECEx, CE and ISO 9001 certified products\nAuthorized distributor for 50+ leading global brands\nFast delivery across UAE, Saudi Arabia, Qatar & Oman\nDedicated technical support team with field expertise\nCompetitive pricing with custom RFQ for bulk orders",
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'mestc_about_checks', array(
		'label'       => 'Bulleted list',
		'description' => 'One item per line.',
		'section'     => 'mestc_about',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'mestc_about_btn_label', array(
		'default' => 'EXPLORE MORE', 'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_about_btn_label', array(
		'label' => 'Button Label', 'section' => 'mestc_about', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'mestc_about_btn_url', array(
		'default' => '', 'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'mestc_about_btn_url', array(
		'label' => 'Button URL', 'section' => 'mestc_about', 'type' => 'url',
	) );

	/* ---------------- Section: Quote Band ---------------- */
	$wp_customize->add_section( 'mestc_quote_band', array(
		'title' => __( 'Quote Band', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$wp_customize->add_setting( 'mestc_quote_band_title', array(
		'default' => 'Make MESTC Your Single Source for Industrial & Electrical Supply.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_quote_band_title', array(
		'label' => 'Title', 'section' => 'mestc_quote_band', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'mestc_quote_band_text', array(
		'default' => 'Get a Quote for Your Project Needs — Our team responds within 24 hours.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_quote_band_text', array(
		'label' => 'Subtitle', 'section' => 'mestc_quote_band', 'type' => 'text',
	) );

	/* ---------------- Section: Footer ---------------- */
	$wp_customize->add_section( 'mestc_footer', array(
		'title' => __( 'Footer', 'mestc-theme' ),
		'panel' => 'mestc_panel',
	) );

	$wp_customize->add_setting( 'mestc_footer_about', array(
		'default' => 'Your trusted industrial and electrical supply company in Dubai, UAE. Serving Oil & Gas, Construction, Marine and Industrial sectors across the GCC with internationally certified products since 2009.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'mestc_footer_about', array(
		'label' => 'Footer About Text', 'section' => 'mestc_footer', 'type' => 'textarea',
	) );

	$wp_customize->add_setting( 'mestc_footer_copy', array(
		'default' => '© [year] MESTC. All rights reserved. Dubai, UAE.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'mestc_footer_copy', array(
		'label'       => 'Copyright Line',
		'description' => 'Use [year] for the current year.',
		'section'     => 'mestc_footer',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'mestc_footer_certs', array(
		'default' => 'ATEX,IECEx,ISO 9001,CE Marked',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mestc_footer_certs', array(
		'label'       => 'Certification Badges',
		'description' => 'Comma-separated list.',
		'section'     => 'mestc_footer',
		'type'        => 'text',
	) );
}
add_action( 'customize_register', 'mestc_customize_register' );

/* ---------------- Helpers ---------------- */

function mestc_sanitize_checkbox( $value ) {
	return ( '1' === (string) $value || true === $value || 'on' === $value ) ? 1 : 0;
}

function mestc_default_slide( $i ) {
	$defaults = array(
		1 => array(
			'tag'   => 'Industrial Supply — UAE',
			'title' => 'Reliable Industrial &<br><em>Electrical Solutions</em><br>Across UAE',
			'text'  => 'High-quality electrical, oilfield and industrial products with international standards. Trusted by 100+ clients across the GCC and Middle East.',
			'btn1_label' => 'EXPLORE PRODUCTS',
			'btn1_url'   => '',
			'btn2_label' => 'GET A QUOTE',
			'btn2_url'   => '',
		),
		2 => array(
			'tag'   => 'Oil & Gas Certified',
			'title' => 'ATEX & IECEx<br><em>Certified Equipment</em><br>for Oil & Gas',
			'text'  => 'Explosion-proof and hazardous area products for upstream, midstream and downstream oil & gas operations across the GCC.',
			'btn1_label' => 'VIEW PRODUCTS',
			'btn1_url'   => '',
			'btn2_label' => 'CONTACT US',
			'btn2_url'   => '',
		),
		3 => array(
			'tag'   => 'Construction & Marine',
			'title' => 'Complete Supply<br><em>Solutions for</em><br>Construction & Marine',
			'text'  => 'Tools, safety equipment, electrical supplies and construction materials — all from one trusted source in Dubai.',
			'btn1_label' => 'VIEW PRODUCTS',
			'btn1_url'   => '',
			'btn2_label' => 'DOWNLOAD CATALOGUE',
			'btn2_url'   => '',
		),
	);
	return isset( $defaults[ $i ] ) ? $defaults[ $i ] : $defaults[1];
}

function mestc_default_stats() {
	return array(
		array( 'num' => '10+',  'lbl' => 'Years in UAE' ),
		array( 'num' => '500+', 'lbl' => 'Products in Stock' ),
		array( 'num' => '100+', 'lbl' => 'Happy Clients' ),
		array( 'num' => '50+',  'lbl' => 'Global Brands' ),
		array( 'num' => '24/7', 'lbl' => 'Technical Support' ),
	);
}
