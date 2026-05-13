<?php
/**
 * MESTC Settings — top-level admin page that lets non-technical admins edit
 * the same theme_mod fields the Customizer exposes (phone, email, address,
 * hours, top-bar message, footer copy, copyright, etc.) without entering the
 * Customizer.
 *
 * @package MESTC
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Field schema — single source of truth for the admin form. Each entry maps a
 * `theme_mod` key to a label + sanitization callback. Keep keys in sync with
 * inc/customizer.php so both UIs edit the same option.
 */
function mestc_admin_settings_fields() {
	return array(
		'contact'  => array(
			'title'  => __( 'Contact Information', 'mestc-theme' ),
			'desc'   => __( 'Used in the top bar, footer, contact tiles, inquiry mailto links and the contact page.', 'mestc-theme' ),
			'fields' => array(
				'mestc_phone'   => array( 'label' => __( 'Phone Number',     'mestc-theme' ), 'type' => 'text',  'default' => '+971 XX XXX XXXX', 'sanitize' => 'sanitize_text_field' ),
				'mestc_email'   => array( 'label' => __( 'Email Address',    'mestc-theme' ), 'type' => 'email', 'default' => 'info@mestc.com',   'sanitize' => 'sanitize_email' ),
				'mestc_address' => array( 'label' => __( 'Address',          'mestc-theme' ), 'type' => 'text',  'default' => 'Dubai, United Arab Emirates', 'sanitize' => 'sanitize_text_field' ),
				'mestc_hours'   => array( 'label' => __( 'Working Hours',    'mestc-theme' ), 'type' => 'text',  'default' => 'Sun–Thu: 08:00 – 17:30 GST', 'sanitize' => 'sanitize_text_field' ),
				'mestc_topbar_message' => array( 'label' => __( 'Top Bar Message', 'mestc-theme' ), 'type' => 'text', 'default' => 'Supplying GCC & Middle East', 'sanitize' => 'sanitize_text_field' ),
			),
		),
		'header'   => array(
			'title'  => __( 'Header CTA', 'mestc-theme' ),
			'desc'   => __( 'The orange button on the top right of the header.', 'mestc-theme' ),
			'fields' => array(
				'mestc_quote_button_label' => array( 'label' => __( 'CTA Label', 'mestc-theme' ), 'type' => 'text', 'default' => 'REQUEST A QUOTE', 'sanitize' => 'sanitize_text_field' ),
				'mestc_quote_button_url'   => array( 'label' => __( 'CTA URL',   'mestc-theme' ), 'type' => 'url',  'default' => '', 'sanitize' => 'esc_url_raw', 'desc' => __( 'Leave empty to default to the contact page.', 'mestc-theme' ) ),
			),
		),
		'footer'   => array(
			'title'  => __( 'Footer', 'mestc-theme' ),
			'desc'   => __( 'Footer brand text and copyright notice.', 'mestc-theme' ),
			'fields' => array(
				'mestc_footer_about' => array( 'label' => __( 'About paragraph', 'mestc-theme' ), 'type' => 'textarea', 'default' => 'Your trusted industrial and electrical supply company in Dubai, UAE.', 'sanitize' => 'wp_kses_post' ),
				'mestc_footer_copy'  => array( 'label' => __( 'Copyright Line', 'mestc-theme' ), 'type' => 'text', 'default' => '© [year] MESTC. All rights reserved. Dubai, UAE.', 'sanitize' => 'wp_kses_post', 'desc' => __( 'Use [year] for the current year.', 'mestc-theme' ) ),
				'mestc_footer_certs' => array( 'label' => __( 'Certification Badges', 'mestc-theme' ), 'type' => 'text', 'default' => 'ATEX,IECEx,ISO 9001,CE Marked', 'sanitize' => 'sanitize_text_field', 'desc' => __( 'Comma-separated list shown bottom-right of the footer.', 'mestc-theme' ) ),
			),
		),
		'forms'    => array(
			'title'  => __( 'Forms — FluentForm Integration', 'mestc-theme' ),
			'desc'   => __( 'Drop in a FluentForm shortcode to replace the built-in contact form on the homepage Contact section and the dedicated Contact Us page. Leave empty to use the default theme form.', 'mestc-theme' ),
			'fields' => array(
				'mestc_fluentform_contact'  => array(
					'label'    => __( 'Contact form shortcode', 'mestc-theme' ),
					'type'     => 'text',
					'default'  => '',
					'sanitize' => 'mestc_sanitize_shortcode',
					'desc'     => __( 'Example: [fluentform id="3"] — find the shortcode under WP-admin → Fluent Forms → All Forms → Shortcode column.', 'mestc-theme' ),
				),
			),
		),
	);
}

/**
 * Allow shortcodes (with square brackets) through but block actual HTML.
 */
function mestc_sanitize_shortcode( $value ) {
	$value = wp_strip_all_tags( (string) $value );
	$value = trim( $value );
	return $value;
}

/**
 * Register the admin menu page.
 */
function mestc_register_admin_settings() {
	add_menu_page(
		__( 'MESTC Settings', 'mestc-theme' ),
		__( 'MESTC Settings', 'mestc-theme' ),
		'manage_options',
		'mestc-settings',
		'mestc_render_admin_settings',
		'dashicons-admin-customizer',
		3
	);
}
add_action( 'admin_menu', 'mestc_register_admin_settings' );

/**
 * Save handler.
 */
function mestc_handle_admin_settings_save() {
	if ( empty( $_POST['mestc_settings_nonce'] ) || ! wp_verify_nonce( $_POST['mestc_settings_nonce'], 'mestc_settings_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$schema  = mestc_admin_settings_fields();
	$updated = 0;

	foreach ( $schema as $section ) {
		foreach ( $section['fields'] as $key => $args ) {
			if ( ! array_key_exists( $key, $_POST ) ) { continue; }
			$raw       = wp_unslash( $_POST[ $key ] );
			$sanitize  = $args['sanitize'] ?? 'sanitize_text_field';
			$clean     = is_callable( $sanitize ) ? call_user_func( $sanitize, $raw ) : sanitize_text_field( $raw );
			set_theme_mod( $key, $clean );
			$updated++;
		}
	}

	// Visibility checkboxes — submitted only when ticked, so iterate the registry.
	if ( function_exists( 'mestc_visibility_registry' ) ) {
		foreach ( mestc_visibility_registry() as $id => $args ) {
			$key = 'mestc_show_' . $id;
			$on  = isset( $_POST[ $key ] ) && $_POST[ $key ] === '1';
			set_theme_mod( $key, $on ? 1 : 0 );
			$updated++;
		}
	}

	add_settings_error( 'mestc_settings', 'mestc_saved', sprintf( __( '%d settings saved.', 'mestc-theme' ), $updated ), 'success' );
}
add_action( 'load-toplevel_page_mestc-settings', 'mestc_handle_admin_settings_save' );

/**
 * Render the page.
 */
function mestc_render_admin_settings() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$schema = mestc_admin_settings_fields();
	?>
	<div class="wrap mestc-admin">
		<h1 style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
			<span class="dashicons dashicons-admin-customizer" style="font-size:28px;color:#b08842"></span>
			<?php esc_html_e( 'MESTC Settings', 'mestc-theme' ); ?>
		</h1>
		<p style="color:#646970;margin-bottom:20px;max-width:640px">
			<?php esc_html_e( 'Update your phone, email, address and other public-facing details. Changes apply across the entire site (top bar, footer, contact page, inquiry mailto links and product pages).', 'mestc-theme' ); ?>
			<br>
			<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=mestc_panel' ) ); ?>"><?php esc_html_e( 'Open the live Customizer for richer options (hero slides, stats, about copy)', 'mestc-theme' ); ?> →</a>
		</p>

		<?php settings_errors( 'mestc_settings' ); ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'mestc_settings_save', 'mestc_settings_nonce' ); ?>

			<?php foreach ( $schema as $section ) : ?>
				<div class="mestc-admin-card" style="background:#fff;border:1px solid #c3c4c7;border-radius:6px;padding:20px 24px;margin-bottom:18px;box-shadow:0 1px 1px rgba(0,0,0,.04)">
					<h2 style="margin-top:0;font-size:16px;color:#1d2f5a"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php if ( ! empty( $section['desc'] ) ) : ?>
						<p style="color:#646970;margin-bottom:18px"><?php echo esc_html( $section['desc'] ); ?></p>
					<?php endif; ?>
					<table class="form-table" role="presentation">
						<tbody>
							<?php foreach ( $section['fields'] as $key => $args ) :
								$value = get_theme_mod( $key, $args['default'] ?? '' );
								?>
								<tr>
									<th scope="row">
										<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
									</th>
									<td>
										<?php if ( ( $args['type'] ?? 'text' ) === 'textarea' ) : ?>
											<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="3" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
										<?php else : ?>
											<input
												id="<?php echo esc_attr( $key ); ?>"
												name="<?php echo esc_attr( $key ); ?>"
												type="<?php echo esc_attr( $args['type'] ?? 'text' ); ?>"
												value="<?php echo esc_attr( $value ); ?>"
												class="regular-text"
											/>
										<?php endif; ?>
										<?php if ( ! empty( $args['desc'] ) ) : ?>
											<p class="description"><?php echo esc_html( $args['desc'] ); ?></p>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>

			<?php if ( function_exists( 'mestc_visibility_registry' ) ) : ?>
				<div class="mestc-admin-card" style="background:#fff;border:1px solid #c3c4c7;border-radius:6px;padding:20px 24px;margin-bottom:18px;box-shadow:0 1px 1px rgba(0,0,0,.04)">
					<h2 style="margin-top:0;font-size:16px;color:#1d2f5a;display:flex;align-items:center;gap:8px">
						<span class="dashicons dashicons-visibility" style="color:#b08842"></span>
						<?php esc_html_e( 'Section Visibility', 'mestc-theme' ); ?>
					</h2>
					<p style="color:#646970;margin-bottom:18px">
						<?php esc_html_e( 'Tick a box to show that section, untick to hide it. Changes apply across the entire site.', 'mestc-theme' ); ?>
					</p>

					<?php
					$registry = mestc_visibility_registry();
					$groups   = mestc_visibility_groups();
					foreach ( $groups as $group_id => $group_label ) :
						?>
						<details open style="margin-bottom:14px;border:1px solid #dcdcde;border-radius:5px;padding:10px 14px;background:#f6f7f7">
							<summary style="cursor:pointer;font-weight:700;color:#1d2f5a;padding:4px 0">
								<?php echo esc_html( $group_label ); ?>
							</summary>
							<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:6px 24px;margin-top:10px;padding:6px 4px">
								<?php foreach ( $registry as $id => $args ) :
									if ( $args['group'] !== $group_id ) { continue; }
									$key     = 'mestc_show_' . $id;
									$checked = (int) get_theme_mod( $key, $args['default'] );
									?>
									<label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;padding:4px 0">
										<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( $checked, 1 ); ?> style="margin-top:3px" />
										<span style="font-size:13px;color:#1d2327;line-height:1.4"><?php echo esc_html( $args['label'] ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</details>
					<?php endforeach; ?>
				</div>

				<div class="mestc-admin-card" style="background:#fbf6ec;border:1px solid #e7d6b3;border-radius:6px;padding:16px 22px;margin-bottom:18px">
					<h3 style="margin-top:0;font-size:14px;color:#1d2f5a">
						<?php esc_html_e( 'Other admin areas you can use', 'mestc-theme' ); ?>
					</h3>
					<ul style="list-style:disc;padding-left:18px;color:#646970;font-size:13px;line-height:1.7;margin:6px 0 0">
						<li>
							<strong><?php esc_html_e( 'Menus:', 'mestc-theme' ); ?></strong>
							<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Appearance → Menus', 'mestc-theme' ); ?></a>
							— <?php esc_html_e( 'add / remove links, drag to reorder, build sub-menus.', 'mestc-theme' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Hero slides + about images + footer copy:', 'mestc-theme' ); ?></strong>
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=mestc_panel' ) ); ?>"><?php esc_html_e( 'Customize → MESTC Theme', 'mestc-theme' ); ?></a>
							— <?php esc_html_e( 'edit slide titles, upload images, change about copy with live preview.', 'mestc-theme' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Industries / Brands / FAQs / Inquiries:', 'mestc-theme' ); ?></strong>
							<?php esc_html_e( 'use the matching menu item in the WP-admin sidebar — each is its own custom post type.', 'mestc-theme' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Site logo:', 'mestc-theme' ); ?></strong>
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=custom_logo' ) ); ?>"><?php esc_html_e( 'Customize → Site Identity', 'mestc-theme' ); ?></a>.
						</li>
					</ul>
				</div>
			<?php endif; ?>

			<?php submit_button( __( 'Save Changes', 'mestc-theme' ) ); ?>
		</form>
	</div>
	<?php
}
