<?php
/**
 * Front page.
 *
 * Renders the homepage entirely from modular section parts.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<main id="primary" class="site-main mestc-front-main">

	<?php get_template_part( 'template-parts/sections/slider' ); ?>
	<?php get_template_part( 'template-parts/sections/stats' ); ?>
	<?php get_template_part( 'template-parts/sections/trust' ); ?>
	<?php get_template_part( 'template-parts/sections/certifications' ); ?>
	<?php get_template_part( 'template-parts/sections/categories' ); ?>

	<?php
	// Featured Products — Block 1: Explosion Proof
	set_query_var( 'mestc_eyebrow',     __( 'Featured Products', 'mestc-theme' ) );
	set_query_var( 'mestc_heading',     __( 'Explosion Proof Products', 'mestc-theme' ) );
	set_query_var( 'mestc_sub',         __( 'Certified for hazardous area use — ATEX & IECEx approved', 'mestc-theme' ) );
	set_query_var( 'mestc_alt',         false );
	set_query_var( 'mestc_category',    'explosion-proof' );
	set_query_var( 'mestc_block_title', __( 'Explosion Proof Range', 'mestc-theme' ) );
	set_query_var( 'mestc_block_sub',   __( 'ATEX / IECEx Certified — In Stock Dubai', 'mestc-theme' ) );
	set_query_var( 'mestc_count',       6 );
	set_query_var( 'mestc_fallback',    array(
		array( 'name' => 'Ex Proof Lighting',  'icon' => '💡' ),
		array( 'name' => 'Ex Plug & Socket',   'icon' => '🔌' ),
		array( 'name' => 'Control Panels',     'icon' => '📦' ),
		array( 'name' => 'Junction Boxes',     'icon' => '⚡' ),
		array( 'name' => 'Ex Proof Motors',    'icon' => '🔋' ),
		array( 'name' => 'Ex Proof Alarms',    'icon' => '🔔' ),
	) );
	get_template_part( 'template-parts/sections/products' );

	// Featured Products — Block 2: Tools
	set_query_var( 'mestc_eyebrow',     __( 'Featured Products', 'mestc-theme' ) );
	set_query_var( 'mestc_heading',     __( 'Hand & Power Tools', 'mestc-theme' ) );
	set_query_var( 'mestc_sub',         __( 'Professional grade tools from trusted global brands', 'mestc-theme' ) );
	set_query_var( 'mestc_alt',         true );
	set_query_var( 'mestc_category',    'hand-power-tools' );
	set_query_var( 'mestc_block_title', __( 'Tools Range', 'mestc-theme' ) );
	set_query_var( 'mestc_block_sub',   __( 'In Stock — Fast Delivery Across UAE', 'mestc-theme' ) );
	set_query_var( 'mestc_count',       6 );
	set_query_var( 'mestc_fallback',    array(
		array( 'name' => 'Insulated Hand Tools', 'icon' => '🔧' ),
		array( 'name' => 'Power Drills',         'icon' => '🔩' ),
		array( 'name' => 'Ratchet Sets',         'icon' => '⚙️' ),
		array( 'name' => 'Cutting Tools',        'icon' => '✂️' ),
		array( 'name' => 'Torque Wrenches',      'icon' => '🔨' ),
		array( 'name' => 'Cable Jointing Kit',   'icon' => '🛠️' ),
	) );
	get_template_part( 'template-parts/sections/products' );
	?>

	<?php get_template_part( 'template-parts/sections/about' ); ?>
	<?php get_template_part( 'template-parts/sections/industries' ); ?>
	<?php get_template_part( 'template-parts/sections/quote-band' ); ?>
	<?php get_template_part( 'template-parts/sections/brands' ); ?>
	<?php get_template_part( 'template-parts/sections/blog' ); ?>
	<?php get_template_part( 'template-parts/sections/contact' ); ?>
	<?php get_template_part( 'template-parts/sections/faq' ); ?>

</main>

<?php get_footer(); ?>
