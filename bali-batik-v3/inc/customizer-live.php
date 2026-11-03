<?php
/**
 * Customizer registration with live preview:
 * - every homepage section, the announcement bar and the footer refresh in
 *   place and show a pencil shortcut in the preview ("edit where you see it");
 * - colours, fonts and shapes update instantly;
 * - homepage sections can be reordered and hidden.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** Sanitise the "sections" order string: known keys only, missing ones appended. */
function bb3_sanitize_sections( $value ): string {
	$known = array_keys( bb3_home_section_labels() );
	$seen  = array();
	$out   = array();
	foreach ( explode( ',', (string) $value ) as $item ) {
		$item = trim( $item );
		$key  = ltrim( $item, '-' );
		if ( in_array( $key, $known, true ) && ! in_array( $key, $seen, true ) ) {
			$seen[] = $key;
			$out[]  = ( str_starts_with( $item, '-' ) ? '-' : '' ) . $key;
		}
	}
	foreach ( array_diff( $known, $seen ) as $key ) {
		$out[] = $key;
	}
	return implode( ',', $out );
}

/** Which homepage section a setting belongs to (for live refresh), or ''. */
function bb3_setting_section( string $key ): string {
	if ( in_array( $key, array( 'hero_font', 'hero_height', 'hero_shade' ), true ) ) {
		return '';
	}
	$prefixes = array(
		'hero_'     => 'hero',
		'cats_'     => 'categories',
		'arrivals_' => 'arrivals',
		'picks_'    => 'picks',
		'story_'    => 'story',
		'banner_'   => 'banners',
		'explore_'  => 'explore',
		'spot_'     => 'spotlight',
		'occ_'      => 'occasions',
		'statement' => 'statement',
		'testi_'    => 'testimonials',
		'svc_'      => 'services',
		'gal_'      => 'gallery',
		'about_'    => 'about',
	);
	foreach ( $prefixes as $prefix => $section ) {
		if ( str_starts_with( $key, $prefix ) ) {
			return $section;
		}
	}
	return '';
}

function bb3_topbar_settings(): array {
	return array( 'announce_1', 'announce_2', 'announce_3', 'facebook', 'instagram', 'youtube', 'tiktok', 'x' );
}

function bb3_footer_settings(): array {
	return array( 'footer_intro', 'copyright', 'email', 'phone', 'address', 'hours', 'facebook', 'instagram', 'youtube', 'tiktok', 'x' );
}

function bb3_design_settings(): array {
	return array( 'color_accent', 'color_dark', 'color_bar', 'color_ticker', 'color_soft', 'hero_font', 'heading_font', 'button_shape', 'radius', 'card_ratio', 'catalog_cols', 'container', 'hero_height', 'hero_shade' );
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		require_once BB3_DIR . '/inc/class-bb3-sections-control.php';
		$defaults = bb3_defaults();

		$wp_customize->add_panel(
			'bb3_panel',
			array(
				'title'       => __( 'Bali Batik', 'bali-batik-v3' ),
				'description' => __( 'Changes show instantly in the preview. Click a pencil icon in the preview to jump straight to that section. In any text you can write {brand}, {tagline} or {year}; they always show the current business name, tagline and year.', 'bali-batik-v3' ),
				'priority'    => 25,
			)
		);

		$sanitizers = array(
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea_field',
			'email'    => 'sanitize_email',
			'url'      => 'esc_url_raw',
			'link'     => 'bb3_sanitize_link',
			'image'    => 'absint',
			'number'   => 'absint',
			'range'    => 'absint',
			'checkbox' => 'bb3_sanitize_checkbox',
			'icon'     => 'bb3_sanitize_icon',
			'focus'    => 'bb3_sanitize_focus',
			'color'    => 'sanitize_hex_color',
			'select'   => 'sanitize_key',
			'sections' => 'bb3_sanitize_sections',
		);
		$live       = array_merge( bb3_topbar_settings(), bb3_footer_settings(), bb3_design_settings(), array( 'sections' ) );
		$priority   = 10;

		foreach ( bb3_customizer_map() as $section => [ $title, $fields ] ) {
			$priority += 5;
			$wp_customize->add_section(
				$section,
				array(
					'title'    => $title,
					'panel'    => 'bb3_panel',
					'priority' => $priority,
				)
			);
			foreach ( $fields as $key => $field ) {
				$type = $field[0];
				// WordPress' own Site Title / Tagline, shown here so the name lives in one place.
				if ( 'core' === $type ) {
					$wp_customize->add_control(
						'bb3_core_' . $key,
						array(
							'label'       => $field[1],
							'description' => $field[2] ?? '',
							'section'     => $section,
							'settings'    => $key,
							'type'        => 'text',
						)
					);
					continue;
				}
				$setting = 'bb3_' . $key;
				$wp_customize->add_setting(
					$setting,
					array(
						'default'           => $defaults[ $key ] ?? '',
						'sanitize_callback' => 'spot_deadline' === $key ? 'bb3_sanitize_datetime' : $sanitizers[ $type ],
						'capability'        => 'edit_theme_options',
						'transport'         => ( in_array( $key, $live, true ) || '' !== bb3_setting_section( $key ) ) ? 'postMessage' : 'refresh',
					)
				);
				$args = array(
					'label'       => $field[1],
					'description' => $field[2] ?? '',
					'section'     => $section,
					'settings'    => $setting,
				);
				switch ( $type ) {
					case 'image':
						$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $setting, $args + array( 'mime_type' => 'image' ) ) );
						continue 2;
					case 'color':
						$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $args ) );
						continue 2;
					case 'sections':
						$wp_customize->add_control( new BB3_Sections_Control( $wp_customize, $setting, $args ) );
						continue 2;
					case 'icon':
						$args += array(
							'type'    => 'select',
							'choices' => bb3_icon_choices(),
						);
						break;
					case 'focus':
						$args += array(
							'type'    => 'select',
							'choices' => array(
								'50% 50%' => __( 'Center', 'bali-batik-v3' ),
								'30% 50%' => __( 'Left', 'bali-batik-v3' ),
								'70% 50%' => __( 'Right', 'bali-batik-v3' ),
								'50% 30%' => __( 'Top', 'bali-batik-v3' ),
								'50% 70%' => __( 'Bottom', 'bali-batik-v3' ),
							),
						);
						break;
					case 'select':
						$args += array(
							'type'    => 'select',
							'choices' => $field[3],
						);
						break;
					case 'range':
						$args += array(
							'type'        => 'range',
							'input_attrs' => array(
								'min'  => $field[3][0],
								'max'  => $field[3][1],
								'step' => $field[3][2],
							),
						);
						break;
					case 'link':
						$args['type'] = 'text';
						break;
					default:
						$args['type'] = $type;
				}
				$wp_customize->add_control( $setting, $args );
			}
		}

		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		// One partial per homepage section: refreshes in place + pencil shortcut.
		$groups = array();
		foreach ( bb3_customizer_map() as [ , $fields ] ) {
			foreach ( array_keys( $fields ) as $key ) {
				$sec = bb3_setting_section( $key );
				if ( '' !== $sec ) {
					$groups[ $sec ][] = 'bb3_' . $key;
				}
			}
		}
		foreach ( $groups as $sec => $settings ) {
			$wp_customize->selective_refresh->add_partial(
				'bb3_sec_' . $sec,
				array(
					'selector'            => '#bb3-sec-' . $sec,
					'settings'            => $settings,
					'container_inclusive' => true,
					'render_callback'     => function () use ( $sec ) {
						bb3_render_home_section( $sec );
					},
				)
			);
		}
		$wp_customize->selective_refresh->add_partial(
			'bb3_home_layout',
			array(
				'selector'            => '[data-bb3-home]',
				'settings'            => array( 'bb3_sections' ),
				'container_inclusive' => false,
				'render_callback'     => function () {
					foreach ( bb3_home_sections() as $sec ) {
						bb3_render_home_section( $sec );
					}
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'bb3_topbar',
			array(
				'selector'            => '.bb3-topbar',
				'settings'            => array_map( fn( $k ) => 'bb3_' . $k, bb3_topbar_settings() ),
				'container_inclusive' => true,
				'render_callback'     => function () {
					get_template_part( 'template-parts/site/topbar' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'bb3_footer',
			array(
				'selector'            => '#site-footer',
				'settings'            => array_map( fn( $k ) => 'bb3_' . $k, bb3_footer_settings() ),
				'container_inclusive' => true,
				'render_callback'     => function () {
					get_template_part( 'template-parts/site/footer-main' );
				},
			)
		);
	},
	20 // after WordPress registers Site Title / Tagline
);

/** Drag-and-drop for the sections list. */
add_action(
	'customize_controls_enqueue_scripts',
	function () {
		wp_enqueue_script( 'bb3-customizer-controls', BB3_URI . '/assets/js/customizer-controls.js', array( 'customize-controls', 'jquery-ui-sortable' ), bb3_asset_ver( 'assets/js/customizer-controls.js' ), true );
		wp_add_inline_style( 'customize-controls', '.bb3-sections-list{margin:0}.bb3-sections-list li{display:flex;align-items:center;gap:6px;margin:0 0 6px;padding:8px 6px;background:#fff;border:1px solid #dcdcde;border-radius:4px}.bb3-sections-list label{flex:1}.bb3-sec-handle{cursor:move;color:#8c8f94}.bb3-sections-list .ui-sortable-placeholder{visibility:visible!important;height:38px;border:1px dashed #2271b1;background:#f0f6fc}' );
	}
);

/** Instant preview of colours, fonts and shapes; re-run scripts on refreshed sections. */
add_action(
	'customize_preview_init',
	function () {
		wp_enqueue_script( 'bb3-customizer-preview', BB3_URI . '/assets/js/customizer-preview.js', array( 'customize-preview', 'customize-selective-refresh' ), bb3_asset_ver( 'assets/js/customizer-preview.js' ), true );
		wp_localize_script( 'bb3-customizer-preview', 'bb3Design', bb3_design_maps() );
	}
);
