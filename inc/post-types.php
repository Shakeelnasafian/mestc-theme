<?php
/**
 * Custom post types: Industries, Projects, Brands, FAQ.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function mestc_register_cpts() {

	register_post_type( 'mestc_industry', array(
		'labels' => array(
			'name'               => __( 'Industries', 'mestc-theme' ),
			'singular_name'      => __( 'Industry', 'mestc-theme' ),
			'add_new'            => __( 'Add New', 'mestc-theme' ),
			'add_new_item'       => __( 'Add New Industry', 'mestc-theme' ),
			'edit_item'          => __( 'Edit Industry', 'mestc-theme' ),
			'new_item'           => __( 'New Industry', 'mestc-theme' ),
			'view_item'          => __( 'View Industry', 'mestc-theme' ),
			'search_items'       => __( 'Search Industries', 'mestc-theme' ),
			'not_found'          => __( 'No industries found', 'mestc-theme' ),
			'menu_name'          => __( 'Industries', 'mestc-theme' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'industries' ),
		'menu_position' => 20,
		'menu_icon'     => 'dashicons-building',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'mestc_project', array(
		'labels' => array(
			'name'               => __( 'Projects', 'mestc-theme' ),
			'singular_name'      => __( 'Project', 'mestc-theme' ),
			'add_new_item'       => __( 'Add New Project', 'mestc-theme' ),
			'edit_item'          => __( 'Edit Project', 'mestc-theme' ),
			'menu_name'          => __( 'Projects', 'mestc-theme' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'projects' ),
		'menu_position' => 21,
		'menu_icon'     => 'dashicons-portfolio',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'mestc_brand', array(
		'labels' => array(
			'name'               => __( 'Brands', 'mestc-theme' ),
			'singular_name'      => __( 'Brand', 'mestc-theme' ),
			'menu_name'          => __( 'Brands', 'mestc-theme' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_position' => 22,
		'menu_icon'     => 'dashicons-awards',
		'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'mestc_faq', array(
		'labels' => array(
			'name'               => __( 'FAQs', 'mestc-theme' ),
			'singular_name'      => __( 'FAQ', 'mestc-theme' ),
			'menu_name'          => __( 'FAQs', 'mestc-theme' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_position' => 23,
		'menu_icon'     => 'dashicons-format-chat',
		'supports'      => array( 'title', 'editor', 'page-attributes' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'mestc_register_cpts' );

/**
 * Industry meta box: tag (e.g., "ATEX Certified") and target URL.
 */
function mestc_industry_meta_boxes() {
	add_meta_box(
		'mestc_industry_meta',
		__( 'Industry Card Settings', 'mestc-theme' ),
		'mestc_industry_meta_box_html',
		'mestc_industry',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mestc_industry_meta_boxes' );

function mestc_industry_meta_box_html( $post ) {
	wp_nonce_field( 'mestc_industry_meta', 'mestc_industry_meta_nonce' );
	$tag = get_post_meta( $post->ID, '_mestc_industry_tag', true );
	$url = get_post_meta( $post->ID, '_mestc_industry_url', true );
	?>
	<p>
		<label for="mestc_industry_tag"><strong><?php esc_html_e( 'Card Tag', 'mestc-theme' ); ?></strong></label>
		<input type="text" id="mestc_industry_tag" name="mestc_industry_tag" value="<?php echo esc_attr( $tag ); ?>" style="width:100%" placeholder="ATEX Certified" />
	</p>
	<p>
		<label for="mestc_industry_url"><strong><?php esc_html_e( 'Card Link URL', 'mestc-theme' ); ?></strong></label>
		<input type="url" id="mestc_industry_url" name="mestc_industry_url" value="<?php echo esc_attr( $url ); ?>" style="width:100%" placeholder="https://...products/..." />
		<small><?php esc_html_e( 'Leave empty to link to the industry detail page.', 'mestc-theme' ); ?></small>
	</p>
	<?php
}

function mestc_industry_save_meta( $post_id ) {
	if ( ! isset( $_POST['mestc_industry_meta_nonce'] ) || ! wp_verify_nonce( $_POST['mestc_industry_meta_nonce'], 'mestc_industry_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	update_post_meta( $post_id, '_mestc_industry_tag', sanitize_text_field( wp_unslash( $_POST['mestc_industry_tag'] ?? '' ) ) );
	update_post_meta( $post_id, '_mestc_industry_url', esc_url_raw( wp_unslash( $_POST['mestc_industry_url'] ?? '' ) ) );
}
add_action( 'save_post_mestc_industry', 'mestc_industry_save_meta' );

/**
 * Brand meta box: external URL.
 */
function mestc_brand_meta_boxes() {
	add_meta_box(
		'mestc_brand_meta',
		__( 'Brand Settings', 'mestc-theme' ),
		'mestc_brand_meta_box_html',
		'mestc_brand',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mestc_brand_meta_boxes' );

function mestc_brand_meta_box_html( $post ) {
	wp_nonce_field( 'mestc_brand_meta', 'mestc_brand_meta_nonce' );
	$url = get_post_meta( $post->ID, '_mestc_brand_url', true );
	?>
	<p>
		<label for="mestc_brand_url"><strong><?php esc_html_e( 'Brand URL', 'mestc-theme' ); ?></strong></label>
		<input type="url" id="mestc_brand_url" name="mestc_brand_url" value="<?php echo esc_attr( $url ); ?>" style="width:100%" />
	</p>
	<?php
}

function mestc_brand_save_meta( $post_id ) {
	if ( ! isset( $_POST['mestc_brand_meta_nonce'] ) || ! wp_verify_nonce( $_POST['mestc_brand_meta_nonce'], 'mestc_brand_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	update_post_meta( $post_id, '_mestc_brand_url', esc_url_raw( wp_unslash( $_POST['mestc_brand_url'] ?? '' ) ) );
}
add_action( 'save_post_mestc_brand', 'mestc_brand_save_meta' );

/**
 * Flush rewrite rules on theme switch.
 */
function mestc_rewrite_flush() {
	mestc_register_cpts();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'mestc_rewrite_flush' );
