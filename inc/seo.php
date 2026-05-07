<?php
/**
 * SEO meta + JSON-LD schemas.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------------- Open Graph + Twitter ---------------- */

function mestc_seo_meta() {
	$site    = wp_specialchars_decode( get_bloginfo( 'name' ),        ENT_QUOTES );
	$tagline = wp_specialchars_decode( get_bloginfo( 'description' ), ENT_QUOTES );

	$title = wp_get_document_title();
	$desc  = $tagline;
	$url   = home_url( add_query_arg( null, null ) );
	$image = '';

	if ( is_singular() ) {
		$desc = wp_strip_all_tags( get_the_excerpt() );
		if ( ! $desc ) {
			$desc = wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '…' );
		}
		$url = get_permalink();
		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		}
	} elseif ( is_post_type_archive( 'product' ) && function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			$desc = wp_strip_all_tags( get_the_excerpt( $shop_id ) );
		}
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$desc = term_description();
		$desc = $desc ? wp_strip_all_tags( $desc ) : $tagline;
	}

	if ( ! $image && has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$image   = wp_get_attachment_image_url( $logo_id, 'full' );
	}
	if ( ! $image && function_exists( 'get_theme_mod' ) ) {
		$slide1 = get_theme_mod( 'mestc_slide_1_image' );
		if ( $slide1 ) { $image = $slide1; }
	}

	$desc = trim( wp_strip_all_tags( (string) $desc ) );
	if ( $desc === '' ) {
		$desc = $tagline;
	}
	$desc = mb_substr( $desc, 0, 200 );

	echo "\n<!-- MESTC SEO -->\n";
	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc ) { printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) ); }
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );
	if ( $image ) { printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) ); }

	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc )  { printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) ); }
	if ( $image ) { printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) ); }

	echo '<meta name="theme-color" content="#1c2e5e">' . "\n";
	echo "<!-- /MESTC SEO -->\n";
}
add_action( 'wp_head', 'mestc_seo_meta', 5 );

/* ---------------- JSON-LD schemas ---------------- */

function mestc_jsonld() {
	$schemas = array();

	// Organization (always)
	$contact = function_exists( 'mestc_contact_info' ) ? mestc_contact_info() : array();
	$logo    = '';
	if ( has_custom_logo() ) {
		$logo = wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' );
	}
	$schemas[] = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Organization',
		'name'        => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'url'         => home_url( '/' ),
		'logo'        => $logo ?: '',
		'email'       => $contact['email'] ?? '',
		'telephone'   => $contact['phone'] ?? '',
		'description' => wp_specialchars_decode( get_bloginfo( 'description' ), ENT_QUOTES ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $contact['address'] ?? '',
			'addressCountry'  => 'AE',
			'addressLocality' => 'Dubai',
		),
	);

	// WebSite + SearchAction (front page)
	if ( is_front_page() ) {
		$schemas[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'name'            => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
			'url'             => home_url( '/' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'      => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		);

		// Aggregate FAQ schema (using built-in defaults; replace with CPT data when populated)
		$faq_items = mestc_get_faq_items_for_schema();
		if ( $faq_items ) {
			$schemas[] = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_items,
			);
		}
	}

	// Product (single product)
	if ( function_exists( 'is_product' ) && is_product() ) {
		$pid     = get_queried_object_id();
		$product = $pid && function_exists( 'wc_get_product' ) ? wc_get_product( $pid ) : null;
		if ( $product instanceof WC_Product ) {
			$image = get_the_post_thumbnail_url( $product->get_id(), 'large' );
			$prod  = array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Product',
				'name'        => $product->get_name(),
				'description' => wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ),
				'sku'         => $product->get_sku(),
				'url'         => get_permalink( $product->get_id() ),
				'brand'       => array( '@type' => 'Brand', 'name' => 'MESTC' ),
			);
			if ( $image ) { $prod['image'] = $image; }
			$schemas[] = $prod;
		}
	}

	// BreadcrumbList for non-front pages
	if ( ! is_front_page() ) {
		$crumbs = mestc_breadcrumb_list();
		if ( $crumbs ) {
			$schemas[] = array(
				'@context'         => 'https://schema.org',
				'@type'            => 'BreadcrumbList',
				'itemListElement'  => $crumbs,
			);
		}
	}

	if ( empty( $schemas ) ) { return; }
	echo "\n<script type=\"application/ld+json\">\n";
	echo wp_json_encode( count( $schemas ) === 1 ? $schemas[0] : array( '@graph' => $schemas ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	echo "\n</script>\n";
}
add_action( 'wp_head', 'mestc_jsonld', 20 );

/* ---------------- Helpers ---------------- */

function mestc_breadcrumb_list() {
	$items = array();
	$position = 1;
	$items[] = array(
		'@type'    => 'ListItem',
		'position' => $position++,
		'name'     => __( 'Home', 'mestc-theme' ),
		'item'     => home_url( '/' ),
	);

	if ( is_singular( 'post' ) ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		if ( $blog_id ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => get_the_title( $blog_id ),
				'item'     => get_permalink( $blog_id ),
			);
		}
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$pid     = get_queried_object_id();
		$product = $pid && function_exists( 'wc_get_product' ) ? wc_get_product( $pid ) : null;
		if ( $product instanceof WC_Product ) {
			$cats = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'orderby' => 'parent' ) );
			if ( $cats && ! is_wp_error( $cats ) ) {
				$cat = $cats[0];
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $position++,
					'name'     => $cat->name,
					'item'     => get_term_link( $cat ),
				);
			}
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $product->get_name(),
				'item'     => get_permalink( $product->get_id() ),
			);
		}
	} elseif ( is_singular() ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term && isset( $term->name ) ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $term->name,
				'item'     => get_term_link( $term ),
			);
		}
	}
	return $items;
}

function mestc_get_faq_items_for_schema() {
	$query = new WP_Query( array(
		'post_type'      => 'mestc_faq',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );

	$items = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = array(
				'@type'          => 'Question',
				'name'           => get_the_title(),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) ),
				),
			);
		}
		wp_reset_postdata();
		return $items;
	}

	// Fallback to built-in defaults so the schema isn't empty.
	$defaults = array(
		array( 'q' => 'What does MESTC specialize in?',                'a' => 'MESTC specializes in the wholesale supply of industrial and electrical products including explosion-proof equipment, hand tools, safety PPE, wires & cables, and oilfield supplies for UAE and GCC markets.' ),
		array( 'q' => 'Are your products ATEX and IECEx certified?',   'a' => 'Yes. All hazardous area products we supply are fully certified under ATEX and IECEx international standards.' ),
		array( 'q' => 'Do you offer bulk pricing and custom RFQ?',     'a' => 'Yes. We offer competitive bulk pricing and custom RFQ for large orders. Submit your requirements and our team will respond within 24 hours.' ),
		array( 'q' => 'Which industries do you serve?',                'a' => 'Oil & Gas, Construction, Marine & Offshore, Manufacturing, Energy & Power, and general industrial sectors across the UAE and GCC.' ),
		array( 'q' => 'How fast can you deliver within the UAE?',      'a' => 'Same-day and next-day delivery across the UAE; GCC deliveries within 3–5 business days depending on location.' ),
	);
	foreach ( $defaults as $f ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $f['q'],
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ),
		);
	}
	return $items;
}
