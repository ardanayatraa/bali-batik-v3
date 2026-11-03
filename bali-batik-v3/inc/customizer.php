<?php
/**
 * Customizer: brand, contact, homepage content and SEO defaults.
 * Every text, image and link on the homepage is editable here.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** Links may be absolute (https://…) or relative to the site (/about/, ?q=…). */
function bb3_sanitize_link( $value ): string {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( preg_match( '#^(/(?!/)|\?|\#)#', $value ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
}

function bb3_sanitize_checkbox( $value ): bool {
	return (bool) $value;
}

function bb3_sanitize_icon( $value ): string {
	return array_key_exists( $value, bb3_icon_choices() ) ? $value : 'info';
}

function bb3_sanitize_focus( $value ): string {
	return preg_match( '/^\d{1,3}% \d{1,3}%$/', (string) $value ) ? (string) $value : '50% 50%';
}

function bb3_sanitize_datetime( $value ): string {
	$value = trim( (string) $value );
	return ( '' === $value || false === strtotime( $value ) ) ? '' : $value;
}

/**
 * Customizer structure: section => [title, priority, fields].
 * Field: key => [type, label, description?].
 */
function bb3_customizer_map(): array {
	$map = array(
		'bb3_contact'  => array(
			__( 'Brand & Contact', 'bali-batik-v3' ),
			array(
				'blogname'        => array( 'core', __( 'Business name', 'bali-batik-v3' ), __( 'Used everywhere: logo text, page titles, Google results, share previews, footer, WhatsApp messages, share image and favicon.', 'bali-batik-v3' ) ),
				'blogdescription' => array( 'core', __( 'Short tagline', 'bali-batik-v3' ), __( 'Shown in the homepage search title and the share image.', 'bali-batik-v3' ) ),
				'whatsapp'      => array( 'text', __( 'WhatsApp number', 'bali-batik-v3' ), __( 'e.g. 0812 3456 7890 or +62 812 3456 7890. Leave empty to show “Contact Us” instead.', 'bali-batik-v3' ) ),
				'wa_template'   => array( 'textarea', __( 'WhatsApp message template', 'bali-batik-v3' ), __( 'Placeholders: {business}, {name}, {price}, {link}. Indonesian {bisnis}, {nama}, {harga} also work.', 'bali-batik-v3' ) ),
				'email'         => array( 'email', __( 'E-mail', 'bali-batik-v3' ) ),
				'phone'         => array( 'text', __( 'Phone', 'bali-batik-v3' ) ),
				'address'       => array( 'textarea', __( 'Address', 'bali-batik-v3' ) ),
				'hours'         => array( 'text', __( 'Opening hours', 'bali-batik-v3' ) ),
				'facebook'      => array( 'url', __( 'Facebook URL', 'bali-batik-v3' ) ),
				'instagram'     => array( 'url', __( 'Instagram URL', 'bali-batik-v3' ) ),
				'youtube'       => array( 'url', __( 'YouTube URL', 'bali-batik-v3' ) ),
				'tiktok'        => array( 'url', __( 'TikTok URL', 'bali-batik-v3' ) ),
				'x'             => array( 'url', __( 'X (Twitter) URL', 'bali-batik-v3' ) ),
				'footer_intro'  => array( 'textarea', __( 'Footer introduction', 'bali-batik-v3' ) ),
				'copyright'     => array( 'text', __( 'Copyright line', 'bali-batik-v3' ), __( '{year} and {brand} are filled in automatically.', 'bali-batik-v3' ) ),
			),
		),
		'bb3_announce' => array(
			__( 'Announcement Bar', 'bali-batik-v3' ),
			array(
				'announce_1' => array( 'text', __( 'Message 1', 'bali-batik-v3' ) ),
				'announce_2' => array( 'text', __( 'Message 2', 'bali-batik-v3' ) ),
				'announce_3' => array( 'text', __( 'Message 3', 'bali-batik-v3' ) ),
			),
		),
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		$map[ 'bb3_hero_' . $i ] = array(
			/* translators: %d: slide number. */
			sprintf( __( 'Hero Slide %d', 'bali-batik-v3' ), $i ),
			array(
				"hero_{$i}_on"      => array( 'checkbox', __( 'Show this slide', 'bali-batik-v3' ) ),
				"hero_{$i}_image"   => array( 'image', __( 'Background photo', 'bali-batik-v3' ), __( 'Landscape, at least 1600 px wide.', 'bali-batik-v3' ) ),
				"hero_{$i}_focus"   => array( 'focus', __( 'Photo focus point', 'bali-batik-v3' ), __( 'Which part of the photo stays visible on phones.', 'bali-batik-v3' ) ),
				"hero_{$i}_eyebrow" => array( 'text', __( 'Small line above the title', 'bali-batik-v3' ) ),
				"hero_{$i}_title"   => array( 'text', __( 'Title', 'bali-batik-v3' ) ),
				"hero_{$i}_cta"     => array( 'text', __( 'Button text', 'bali-batik-v3' ) ),
				"hero_{$i}_url"     => array( 'link', __( 'Button link', 'bali-batik-v3' ) ),
			),
		);
	}

	$home = array(
		'cats_title'           => array( 'text', __( 'Category row: heading (screen readers)', 'bali-batik-v3' ) ),
		'cats_speed'           => array( 'number', __( 'Category row: speed (px per second)', 'bali-batik-v3' ) ),
		'arrivals_title'       => array( 'text', __( 'New Arrivals: title', 'bali-batik-v3' ) ),
		'arrivals_text'        => array( 'text', __( 'New Arrivals: subtitle', 'bali-batik-v3' ) ),
		'arrivals_promo_eye'   => array( 'text', __( 'New Arrivals banner: small line', 'bali-batik-v3' ) ),
		'arrivals_promo_title' => array( 'text', __( 'New Arrivals banner: title', 'bali-batik-v3' ) ),
		'arrivals_promo_cta'   => array( 'text', __( 'New Arrivals banner: button', 'bali-batik-v3' ) ),
		'arrivals_promo_url'   => array( 'link', __( 'New Arrivals banner: link', 'bali-batik-v3' ) ),
		'arrivals_promo_image' => array( 'image', __( 'New Arrivals banner: image', 'bali-batik-v3' ) ),
		'picks_title'          => array( 'text', __( 'Our Picks: title', 'bali-batik-v3' ) ),
		'picks_text'           => array( 'text', __( 'Our Picks: subtitle', 'bali-batik-v3' ) ),
		'picks_side'           => array( 'text', __( 'Our Picks: category list title', 'bali-batik-v3' ) ),
		'story_eyebrow'        => array( 'text', __( 'Story banner: small line', 'bali-batik-v3' ) ),
		'story_title'          => array( 'text', __( 'Story banner: title', 'bali-batik-v3' ) ),
		'story_cta'            => array( 'text', __( 'Story banner: button', 'bali-batik-v3' ) ),
		'story_url'            => array( 'link', __( 'Story banner: link', 'bali-batik-v3' ) ),
		'story_image'          => array( 'image', __( 'Story banner: image', 'bali-batik-v3' ) ),
		'story_ticker'         => array( 'textarea', __( 'Story banner: moving text (one item per line)', 'bali-batik-v3' ) ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		/* translators: %d: banner number. */
		$label                       = sprintf( __( 'Banner %d', 'bali-batik-v3' ), $i );
		$home[ "banner_{$i}_eyebrow" ] = array( 'text', $label . ': ' . __( 'small line', 'bali-batik-v3' ) );
		$home[ "banner_{$i}_title" ]   = array( 'text', $label . ': ' . __( 'title', 'bali-batik-v3' ) );
		$home[ "banner_{$i}_cta" ]     = array( 'text', $label . ': ' . __( 'button', 'bali-batik-v3' ) );
		$home[ "banner_{$i}_url" ]     = array( 'link', $label . ': ' . __( 'link', 'bali-batik-v3' ) );
		$home[ "banner_{$i}_image" ]   = array( 'image', $label . ': ' . __( 'image', 'bali-batik-v3' ) );
	}
	$home += array(
		'explore_title' => array( 'text', __( 'Explore: title', 'bali-batik-v3' ) ),
		'explore_text'  => array( 'text', __( 'Explore: subtitle', 'bali-batik-v3' ) ),
		'spot_eyebrow'  => array( 'text', __( 'Motif spotlight: small line', 'bali-batik-v3' ) ),
		'spot_title'    => array( 'text', __( 'Motif spotlight: title', 'bali-batik-v3' ) ),
		'spot_text'     => array( 'textarea', __( 'Motif spotlight: text', 'bali-batik-v3' ) ),
		'spot_cta'      => array( 'text', __( 'Motif spotlight: button', 'bali-batik-v3' ) ),
		'spot_url'      => array( 'link', __( 'Motif spotlight: link', 'bali-batik-v3' ) ),
		'spot_image'    => array( 'image', __( 'Motif spotlight: image', 'bali-batik-v3' ) ),
		'spot_deadline' => array( 'text', __( 'Motif spotlight: promotion end (optional)', 'bali-batik-v3' ), __( 'Only for a real promotion, e.g. 2026-12-31 23:59. A countdown appears until then. Leave empty for no countdown.', 'bali-batik-v3' ) ),
		'occ_title'     => array( 'text', __( 'Occasions: title', 'bali-batik-v3' ) ),
	);
	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: occasion number. */
		$label                    = sprintf( __( 'Occasion %d', 'bali-batik-v3' ), $i );
		$home[ "occ_{$i}_label" ] = array( 'text', $label . ': ' . __( 'label', 'bali-batik-v3' ) );
		$home[ "occ_{$i}_url" ]   = array( 'link', $label . ': ' . __( 'link', 'bali-batik-v3' ) );
		$home[ "occ_{$i}_image" ] = array( 'image', $label . ': ' . __( 'image', 'bali-batik-v3' ) );
	}
	$home += array(
		'statement'       => array( 'textarea', __( 'Brand statement', 'bali-batik-v3' ), __( '{img1} and {img2} show the two small images below.', 'bali-batik-v3' ) ),
		'statement_img_1' => array( 'image', __( 'Brand statement: image 1', 'bali-batik-v3' ) ),
		'statement_img_2' => array( 'image', __( 'Brand statement: image 2', 'bali-batik-v3' ) ),
		'testi_title'     => array( 'text', __( 'Testimonials: title', 'bali-batik-v3' ), __( 'The section only appears when at least one real, approved quote is filled in.', 'bali-batik-v3' ) ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		/* translators: %d: testimonial number. */
		$label                      = sprintf( __( 'Testimonial %d', 'bali-batik-v3' ), $i );
		$home[ "testi_{$i}_quote" ] = array( 'textarea', $label . ': ' . __( 'quote', 'bali-batik-v3' ) );
		$home[ "testi_{$i}_name" ]  = array( 'text', $label . ': ' . __( 'name', 'bali-batik-v3' ) );
	}
	$home += array(
		'svc_title' => array( 'text', __( 'Services: title', 'bali-batik-v3' ) ),
		'svc_text'  => array( 'text', __( 'Services: subtitle', 'bali-batik-v3' ) ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: service number. */
		$label                   = sprintf( __( 'Service %d', 'bali-batik-v3' ), $i );
		$home[ "svc_{$i}_icon" ]  = array( 'icon', $label . ': ' . __( 'icon', 'bali-batik-v3' ) );
		$home[ "svc_{$i}_title" ] = array( 'text', $label . ': ' . __( 'title', 'bali-batik-v3' ) );
		$home[ "svc_{$i}_text" ]  = array( 'text', $label . ': ' . __( 'text', 'bali-batik-v3' ) );
	}
	$home += array(
		'gal_title' => array( 'text', __( 'Inspiration: title', 'bali-batik-v3' ) ),
		'gal_text'  => array( 'text', __( 'Inspiration: subtitle', 'bali-batik-v3' ) ),
	);
	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: image number. */
		$home[ "gal_{$i}_image" ] = array( 'image', sprintf( __( 'Inspiration image %d', 'bali-batik-v3' ), $i ) );
	}
	$home += array(
		'about_title' => array( 'text', __( 'About: title', 'bali-batik-v3' ) ),
		'about_text'  => array( 'textarea', __( 'About: text', 'bali-batik-v3' ) ),
	);
	$map['bb3_home'] = array( __( 'Homepage Sections', 'bali-batik-v3' ), $home );

	$map = array(
		'bb3_layout' => array(
			__( 'Homepage Layout', 'bali-batik-v3' ),
			array(
				'home_mode'      => array(
					'select',
					__( 'Homepage content', 'bali-batik-v3' ),
					__( '“Block editor page” uses the page set in Settings → Reading → Homepage, so you can edit text directly on the page with Bali Batik blocks.', 'bali-batik-v3' ),
					array(
						'sections' => __( 'Theme sections (below)', 'bali-batik-v3' ),
						'page'     => __( 'Block editor page', 'bali-batik-v3' ),
					),
				),
				'sections'       => array( 'sections', __( 'Sections: drag to reorder, untick to hide', 'bali-batik-v3' ) ),
				'header_overlay' => array( 'checkbox', __( 'Transparent header over the hero photo', 'bali-batik-v3' ) ),
			),
		),
		'bb3_design' => array(
			__( 'Design & Layout', 'bali-batik-v3' ),
			array(
				'color_accent' => array( 'color', __( 'Button / accent colour', 'bali-batik-v3' ), __( 'Keep it dark enough for white text (the default passes WCAG AA).', 'bali-batik-v3' ) ),
				'color_dark'   => array( 'color', __( 'Header & dark button colour', 'bali-batik-v3' ) ),
				'color_bar'    => array( 'color', __( 'Announcement bar colour', 'bali-batik-v3' ) ),
				'color_ticker' => array( 'color', __( 'Moving text strip colour', 'bali-batik-v3' ) ),
				'color_soft'   => array( 'color', __( 'Soft section background', 'bali-batik-v3' ) ),
				'hero_font'    => array(
					'select',
					__( 'Hero title font', 'bali-batik-v3' ),
					'',
					array(
						'serif'   => __( 'Classic serif (Georgia)', 'bali-batik-v3' ),
						'elegant' => __( 'Elegant serif (Palatino)', 'bali-batik-v3' ),
						'sans'    => __( 'Modern sans (Poppins)', 'bali-batik-v3' ),
					),
				),
				'heading_font' => array(
					'select',
					__( 'Section heading font', 'bali-batik-v3' ),
					'',
					array(
						'sans'    => __( 'Modern sans (Poppins)', 'bali-batik-v3' ),
						'serif'   => __( 'Classic serif (Georgia)', 'bali-batik-v3' ),
						'elegant' => __( 'Elegant serif (Palatino)', 'bali-batik-v3' ),
					),
				),
				'button_shape' => array(
					'select',
					__( 'Main button shape', 'bali-batik-v3' ),
					'',
					array(
						'square'  => __( 'Square (V2 style)', 'bali-batik-v3' ),
						'rounded' => __( 'Slightly rounded', 'bali-batik-v3' ),
						'pill'    => __( 'Pill', 'bali-batik-v3' ),
					),
				),
				'radius'       => array( 'range', __( 'Corner radius of cards and banners (px)', 'bali-batik-v3' ), '', array( 0, 24, 1 ) ),
				'card_ratio'   => array(
					'select',
					__( 'Product photo shape', 'bali-batik-v3' ),
					'',
					array(
						'portrait' => __( 'Portrait 600 × 789 (V2)', 'bali-batik-v3' ),
						'4-5'      => __( 'Portrait 4 : 5', 'bali-batik-v3' ),
						'tall'     => __( 'Tall 2 : 3', 'bali-batik-v3' ),
						'square'   => __( 'Square', 'bali-batik-v3' ),
					),
				),
				'catalog_cols' => array(
					'select',
					__( 'Products per row in the collection (desktop)', 'bali-batik-v3' ),
					'',
					array(
						'3' => '3',
						'4' => '4',
					),
				),
				'container'    => array(
					'select',
					__( 'Maximum page width', 'bali-batik-v3' ),
					'',
					array(
						'1200' => '1200 px',
						'1280' => '1280 px',
						'1440' => '1440 px',
						'1640' => __( '1640 px (V2)', 'bali-batik-v3' ),
						'1920' => '1920 px',
					),
				),
				'hero_height'  => array(
					'select',
					__( 'Hero height', 'bali-batik-v3' ),
					'',
					array(
						'tall'   => __( 'Tall (V2)', 'bali-batik-v3' ),
						'medium' => __( 'Medium', 'bali-batik-v3' ),
						'full'   => __( 'Full screen', 'bali-batik-v3' ),
					),
				),
				'hero_shade'   => array( 'range', __( 'Hero photo darkness (%)', 'bali-batik-v3' ), __( 'More shade keeps white text readable.', 'bali-batik-v3' ), array( 20, 85, 1 ) ),
			),
		),
	) + $map;

	$map['bb3_seo'] = array(
		__( 'Search & Sharing (SEO)', 'bali-batik-v3' ),
		array(
			'seo_home_title' => array( 'text', __( 'Homepage search title', 'bali-batik-v3' ), __( 'Optional. About 50–60 characters. Default: “Business name | Tagline”.', 'bali-batik-v3' ) ),
			'seo_home_desc'  => array( 'textarea', __( 'Homepage search description', 'bali-batik-v3' ), __( 'About 120–155 characters.', 'bali-batik-v3' ) ),
			'og_image'       => array( 'image', __( 'Default share image (1200 × 630)', 'bali-batik-v3' ), __( 'Used when a page has no photo of its own.', 'bali-batik-v3' ) ),
			'x_handle'       => array( 'text', __( 'X username (without @)', 'bali-batik-v3' ) ),
		),
	);
	return $map;
}
