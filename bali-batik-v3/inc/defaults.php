<?php
/**
 * Default content for every editable theme setting.
 *
 * All copy is factual: no founding year, awards, ratings, discounts or
 * shipping promises. Owners replace it in Appearance → Customize.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** Homepage sections in their default (V2) order. */
function bb3_home_section_labels(): array {
	return array(
		'hero'         => __( 'Hero slides', 'bali-batik-v3' ),
		'categories'   => __( 'Moving categories', 'bali-batik-v3' ),
		'arrivals'     => __( 'New Arrivals', 'bali-batik-v3' ),
		'picks'        => __( 'Our Picks', 'bali-batik-v3' ),
		'story'        => __( 'Story banner', 'bali-batik-v3' ),
		'banners'      => __( 'Three promo banners', 'bali-batik-v3' ),
		'explore'      => __( 'Explore the Collection', 'bali-batik-v3' ),
		'spotlight'    => __( 'Motif spotlight', 'bali-batik-v3' ),
		'occasions'    => __( 'Batik for Every Occasion', 'bali-batik-v3' ),
		'statement'    => __( 'Brand statement', 'bali-batik-v3' ),
		'testimonials' => __( 'Testimonials', 'bali-batik-v3' ),
		'services'     => __( 'How We Help', 'bali-batik-v3' ),
		'gallery'      => __( 'Inspiration gallery', 'bali-batik-v3' ),
		'about'        => __( 'About', 'bali-batik-v3' ),
	);
}

/** Visible homepage sections in the chosen order. */
function bb3_home_sections(): array {
	$out = array();
	foreach ( explode( ',', (string) bb3_mod( 'sections' ) ) as $key ) {
		$key = trim( $key );
		if ( '' !== $key && '-' !== $key[0] && isset( bb3_home_section_labels()[ $key ] ) ) {
			$out[] = $key;
		}
	}
	return $out;
}

/**
 * Default value for each theme mod.
 */
function bb3_defaults(): array {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$collection = '/koleksi/';

	$defaults = array(
		// Brand and contact.
		'footer_intro'    => __( 'Batik worth keeping, chosen with care. Browse the collection and ask us about any piece.', 'bali-batik-v3' ),
		'whatsapp'        => '',
		'wa_template'     => __( 'Hello {business}, I am interested in {name} ({price}). Is it still available? {link}', 'bali-batik-v3' ),
		'email'           => '',
		'phone'           => '',
		'address'         => '',
		'hours'           => '',
		'facebook'        => '',
		'instagram'       => '',
		'youtube'         => '',
		'tiktok'          => '',
		'x'               => '',
		'copyright'       => __( '© {year} {brand}. All rights reserved.', 'bali-batik-v3' ),

		// Announcement bar.
		'announce_1'      => __( 'Batik fabrics, shirts, dresses and scarves, chosen with care', 'bali-batik-v3' ),
		'announce_2'      => __( 'Found a piece you like? Ask about it on WhatsApp', 'bali-batik-v3' ),
		'announce_3'      => __( 'Save favorites on this device. No account needed', 'bali-batik-v3' ),

		// Hero slides.
		'hero_1_on'       => true,
		'hero_1_image'    => 0,
		'hero_1_asset'    => 'batik/hero-batik-rack.jpg',
		'hero_1_focus'    => '50% 50%',
		'hero_1_eyebrow'  => __( 'Batik, chosen with care', 'bali-batik-v3' ),
		'hero_1_title'    => __( 'Find the Batik That Feels Like You', 'bali-batik-v3' ),
		'hero_1_cta'      => __( 'Explore the Collection', 'bali-batik-v3' ),
		'hero_1_url'      => $collection,
		'hero_2_on'       => true,
		'hero_2_image'    => 0,
		'hero_2_asset'    => 'batik/hero-batik-fabric.jpg',
		'hero_2_focus'    => '50% 50%',
		'hero_2_eyebrow'  => __( 'Fabrics with character', 'bali-batik-v3' ),
		'hero_2_title'    => __( 'Motifs Worth Making Something From', 'bali-batik-v3' ),
		'hero_2_cta'      => __( 'Browse Fabrics', 'bali-batik-v3' ),
		'hero_2_url'      => $collection,
		'hero_3_on'       => true,
		'hero_3_image'    => 0,
		'hero_3_asset'    => 'batik/hero-batik-craft.jpg',
		'hero_3_focus'    => '50% 50%',
		'hero_3_eyebrow'  => __( 'The craft behind the cloth', 'bali-batik-v3' ),
		'hero_3_title'    => __( 'Every Motif Begins with Wax and Patience', 'bali-batik-v3' ),
		'hero_3_cta'      => __( 'Read Our Story', 'bali-batik-v3' ),
		'hero_3_url'      => '/about/',

		// Category row.
		'cats_title'      => __( 'Shop by Category', 'bali-batik-v3' ),
		'cats_speed'      => 27,

		// New arrivals.
		'arrivals_title'      => __( 'New Arrivals', 'bali-batik-v3' ),
		'arrivals_text'       => __( 'The latest pieces added to the collection.', 'bali-batik-v3' ),
		'arrivals_count'      => 12,
		'arrivals_promo_eye'  => __( 'Just added', 'bali-batik-v3' ),
		'arrivals_promo_title' => __( 'Fresh Motifs for the Season', 'bali-batik-v3' ),
		'arrivals_promo_cta'  => __( 'See What’s New', 'bali-batik-v3' ),
		'arrivals_promo_url'  => $collection . '?sort=latest',
		'arrivals_promo_image' => 0,

		// Our picks.
		'picks_title'     => __( 'Our Picks', 'bali-batik-v3' ),
		'picks_text'      => __( 'Pieces we keep coming back to, and think you will too.', 'bali-batik-v3' ),
		'picks_side'      => __( 'Featured Categories', 'bali-batik-v3' ),

		// Story banner.
		'story_eyebrow'   => __( 'The batik story', 'bali-batik-v3' ),
		'story_title'     => __( 'Every Line Carries a Meaning', 'bali-batik-v3' ),
		'story_cta'       => __( 'Read Our Story', 'bali-batik-v3' ),
		'story_url'       => '/about/',
		'story_image'     => 0,
		'story_ticker'    => __( "Wax-resist dyeing\nMotifs with meaning\nFabrics, shirts, dresses & scarves\nAsk us anything on WhatsApp", 'bali-batik-v3' ),

		// Three promo banners.
		'banner_1_eyebrow' => __( 'For everyday wear', 'bali-batik-v3' ),
		'banner_1_title'   => __( 'Batik Shirts', 'bali-batik-v3' ),
		'banner_1_cta'     => __( 'See Shirts', 'bali-batik-v3' ),
		'banner_1_url'     => $collection,
		'banner_1_image'   => 0,
		'banner_2_eyebrow' => __( 'Made to move', 'bali-batik-v3' ),
		'banner_2_title'   => __( 'Batik Dresses', 'bali-batik-v3' ),
		'banner_2_cta'     => __( 'See Dresses', 'bali-batik-v3' ),
		'banner_2_url'     => $collection,
		'banner_2_image'   => 0,
		'banner_3_eyebrow' => __( 'Finish the look', 'bali-batik-v3' ),
		'banner_3_title'   => __( 'Scarves & Accessories', 'bali-batik-v3' ),
		'banner_3_cta'     => __( 'See Scarves', 'bali-batik-v3' ),
		'banner_3_url'     => $collection,
		'banner_3_image'   => 0,

		// Explore grid.
		'explore_title'   => __( 'Explore the Collection', 'bali-batik-v3' ),
		'explore_text'    => __( 'Ten pieces from across the range. Open any one to see its motif, material and price.', 'bali-batik-v3' ),

		// Motif spotlight.
		'spot_eyebrow'    => __( 'Motif spotlight', 'bali-batik-v3' ),
		'spot_title'      => __( 'Kawung: Balance in Four Ovals', 'bali-batik-v3' ),
		'spot_text'       => __( 'Kawung is one of the oldest recognised batik motifs: four ovals set around a single point, often read as a sign of balance and clear intention. It sits as comfortably on a work shirt as on a length of cloth.', 'bali-batik-v3' ),
		'spot_cta'        => __( 'See Kawung Pieces', 'bali-batik-v3' ),
		'spot_url'        => $collection . '?q=kawung',
		'spot_image'      => 0,
		'spot_deadline'   => '',

		// Occasions.
		'occ_title'       => __( 'Batik for Every Occasion', 'bali-batik-v3' ),
		'occ_1_label'     => __( 'Office', 'bali-batik-v3' ),
		'occ_1_url'       => $collection,
		'occ_1_image'     => 0,
		'occ_2_label'     => __( 'Weddings', 'bali-batik-v3' ),
		'occ_2_url'       => $collection,
		'occ_2_image'     => 0,
		'occ_3_label'     => __( 'Celebrations', 'bali-batik-v3' ),
		'occ_3_url'       => $collection,
		'occ_3_image'     => 0,
		'occ_4_label'     => __( 'Everyday', 'bali-batik-v3' ),
		'occ_4_url'       => $collection,
		'occ_4_image'     => 0,
		'occ_5_label'     => __( 'Gifts', 'bali-batik-v3' ),
		'occ_5_url'       => $collection,
		'occ_5_image'     => 0,

		// Brand statement. {img1} and {img2} become small motif images.
		'statement'       => __( 'Batik is {img1} more than a pattern. It is a {img2} story you can wear.', 'bali-batik-v3' ),
		'statement_img_1' => 0,
		'statement_img_2' => 0,

		// Testimonials: empty by default, section stays hidden until real quotes exist.
		'testi_title'     => __( 'What Our Customers Say', 'bali-batik-v3' ),
		'testi_1_quote'   => '',
		'testi_1_name'    => '',
		'testi_2_quote'   => '',
		'testi_2_name'    => '',
		'testi_3_quote'   => '',
		'testi_3_name'    => '',

		// Services.
		'svc_title'       => __( 'How We Help', 'bali-batik-v3' ),
		'svc_text'        => __( 'A simple way to find batik you will love wearing.', 'bali-batik-v3' ),
		'svc_1_icon'      => 'whatsapp',
		'svc_1_title'     => __( 'Ask on WhatsApp', 'bali-batik-v3' ),
		'svc_1_text'      => __( 'Message us about any piece to check size, color and availability before you decide.', 'bali-batik-v3' ),
		'svc_2_icon'      => 'info',
		'svc_2_title'     => __( 'Clear Product Details', 'bali-batik-v3' ),
		'svc_2_text'      => __( 'Motif, material, size and care notes sit on every product page.', 'bali-batik-v3' ),
		'svc_3_icon'      => 'heart',
		'svc_3_title'     => __( 'Save Your Favorites', 'bali-batik-v3' ),
		'svc_3_text'      => __( 'Keep a shortlist on this device. No account, no sign-up.', 'bali-batik-v3' ),
		'svc_4_icon'      => 'leaf',
		'svc_4_title'     => __( 'Care Guidance', 'bali-batik-v3' ),
		'svc_4_text'      => __( 'Simple care tips help keep colors rich and fabric soft for longer.', 'bali-batik-v3' ),

		// Inspiration gallery.
		'gal_title'       => __( 'Batik Inspiration', 'bali-batik-v3' ),
		'gal_text'        => __( 'Color, texture and motif, up close.', 'bali-batik-v3' ),
		'gal_1_image'     => 0,
		'gal_2_image'     => 0,
		'gal_3_image'     => 0,
		'gal_4_image'     => 0,
		'gal_5_image'     => 0,

		// About.
		'about_title'     => __( 'About {brand}', 'bali-batik-v3' ),
		'about_text'      => __( '{brand} is a curated catalogue of batik fabrics and clothing. Every piece is listed with its motif, material and price, so you can compare at your own pace. When something catches your eye, send us a message and we will answer your questions. No account and no checkout, just a real conversation about batik you will love wearing.', 'bali-batik-v3' ),

		// Homepage layout: order of sections; a leading "-" hides one.
		'home_mode'       => 'sections',
		'sections'        => implode( ',', array_keys( bb3_home_section_labels() ) ),

		// Design & layout.
		'color_accent'    => '#2f8548',
		'color_dark'      => '#111111',
		'color_bar'       => '#2c2c2c',
		'color_ticker'    => '#315b53',
		'color_soft'      => '#f4f4f4',
		'hero_font'       => 'serif',
		'heading_font'    => 'sans',
		'radius'          => 5,
		'button_shape'    => 'square',
		'card_ratio'      => 'portrait',
		'catalog_cols'    => 3,
		'container'       => 1640,
		'hero_height'     => 'tall',
		'hero_shade'      => 64,
		'header_overlay'  => true,

		// SEO.
		'seo_home_title'  => '',
		'seo_home_desc'   => __( 'Browse batik fabrics, shirts, dresses and scarves at {brand}. See the motif, material and price of every piece, then ask about it on WhatsApp.', 'bali-batik-v3' ),
		'og_image'        => 0,
		'x_handle'        => '',
	);

	return $defaults;
}

/**
 * Read one theme mod with its default.
 *
 * @param string $key Setting key without the bb3_ prefix.
 * @return mixed
 */
function bb3_mod( string $key ) {
	$defaults = bb3_defaults();
	$value    = get_theme_mod( 'bb3_' . $key, $defaults[ $key ] ?? '' );
	// Texts may contain {brand}, {tagline} and {year}; they always show current values.
	return is_string( $value ) ? bb3_fill( trim( $value ) ) : $value;
}
