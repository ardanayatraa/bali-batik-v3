<?php
/**
 * Theme supports, assets, and front-end performance clean-up.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'bali-batik-v3', BB3_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 360,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		add_theme_support( 'editor-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		// The block editor shows pages with the real theme styles (inline editing looks like the site).
		add_editor_style( array( 'assets/css/theme.css', 'assets/css/editor.css' ) );

		register_nav_menus(
			array(
				'primary'     => __( 'Main menu (header)', 'bali-batik-v3' ),
				'footer_help' => __( 'Footer: Help', 'bali-batik-v3' ),
				'footer_info' => __( 'Footer: Information', 'bali-batik-v3' ),
			)
		);

		// Product card 600:789 portrait (+ half size for phones), square for round/gallery images.
		add_image_size( 'bb3-card', 600, 789, true );
		add_image_size( 'bb3-card-sm', 360, 473, true );
		add_image_size( 'bb3-square', 600, 600, true );
	}
);

/** Resized JPEG copies are saved as WebP (smaller files), when the server supports it. */
add_filter(
	'image_editor_output_format',
	function ( $formats ) {
		if ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
			$formats['image/jpeg'] = 'image/webp';
		}
		return $formats;
	}
);

/**
 * One-time rebuild of demo image sizes (adds WebP and the small card size).
 * Only touches images the demo importer created.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'bb3_media_ver' ) >= 3 || 'done' !== get_option( 'bb3_demo_state' ) || get_transient( 'bb3_media_lock' ) ) {
			return;
		}
		set_transient( 'bb3_media_lock', 1, 5 * MINUTE_IN_SECONDS );
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		}
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$ids = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image/jpeg',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_bb3_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		foreach ( $ids as $id ) {
			$file = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
			if ( ! $file || ! file_exists( $file ) ) {
				continue;
			}
			$old = wp_get_attachment_metadata( $id );
			foreach ( (array) ( $old['sizes'] ?? array() ) as $size ) {
				if ( ! empty( $size['file'] ) ) {
					wp_delete_file( trailingslashit( dirname( $file ) ) . $size['file'] );
				}
			}
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}
		update_option( 'bb3_media_ver', 3, false );
		delete_transient( 'bb3_media_lock' );
	},
	31
);

$GLOBALS['content_width'] = 760;

/** Asset version: file time in development, theme version otherwise. */
function bb3_asset_ver( string $relative ): string {
	$file = BB3_DIR . '/' . $relative;
	return is_readable( $file ) ? BB3_VERSION . '.' . filemtime( $file ) : BB3_VERSION;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		// theme.css is printed inline in <head> (see bb3_inline_css) so it never blocks rendering.
		wp_enqueue_script(
			'bb3-theme',
			BB3_URI . '/assets/js/theme.js',
			array(),
			bb3_asset_ver( 'assets/js/theme.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_localize_script(
			'bb3-theme',
			'bb3',
			array(
				'rest'       => esc_url_raw( rest_url( 'bb3/v1/products' ) ),
				'favKey'     => 'bb3_favorites',
				'speed'      => max( 0, min( 120, (int) bb3_mod( 'cats_speed' ) ) ),
				'collection' => bb3_collection_url(),
				'i18n'       => array(
					'save'        => __( 'Save to favorites', 'bali-batik-v3' ),
					'saved'       => __( 'Saved to favorites', 'bali-batik-v3' ),
					'remove'      => __( 'Remove', 'bali-batik-v3' ),
					'empty'       => __( 'Nothing saved yet. Tap the heart on any piece to keep it here.', 'bali-batik-v3' ),
					'unavailable' => __( 'No longer available', 'bali-batik-v3' ),
					'storage'     => __( 'Favorites cannot be saved in this browser.', 'bali-batik-v3' ),
					'pause'       => __( 'Pause category carousel', 'bali-batik-v3' ),
					'play'        => __( 'Play category carousel', 'bali-batik-v3' ),
					'days'        => __( 'days', 'bali-batik-v3' ),
					'image'       => __( 'Image %1$s of %2$s', 'bali-batik-v3' ),
				),
			)
		);

		// Core block CSS is only needed where block content is printed.
		$bb3_post = is_singular() ? get_queried_object() : null;
		if ( ! ( $bb3_post instanceof WP_Post && has_blocks( $bb3_post ) ) ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'classic-theme-styles' );
			wp_dequeue_style( 'global-styles' );
		}
	},
	20
);

/** Preload the body font so text renders without a late swap. */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('js')</script>\n";
		echo '<link rel="preload" href="' . esc_url( BB3_URI . '/assets/fonts/poppins-400.woff' ) . '" as="font" type="font/woff" crossorigin>' . "\n";
		echo '<meta name="theme-color" content="' . esc_attr( sanitize_hex_color( (string) bb3_mod( 'color_dark' ) ) ?: '#111111' ) . '">' . "\n";
	},
	1
);

// Load only the CSS of core blocks that a page actually uses.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/** Value tables for the Design & Layout options. */
function bb3_design_maps(): array {
	$sans    = 'Poppins,"Segoe UI",Arial,sans-serif';
	$serif   = 'Georgia,"Times New Roman",serif';
	$elegant = '"Palatino Linotype",Palatino,"Book Antiqua","URW Palladio L",Georgia,serif';
	return array(
		'hero_font'    => array(
			'serif'   => $serif,
			'elegant' => $elegant,
			'sans'    => $sans,
		),
		'heading_font' => array(
			'sans'    => $sans,
			'serif'   => $serif,
			'elegant' => $elegant,
		),
		'button_shape' => array(
			'square'  => '0px',
			'rounded' => '8px',
			'pill'    => '999px',
		),
		'card_ratio'   => array(
			'portrait' => '600/789',
			'4-5'      => '4/5',
			'square'   => '1/1',
			'tall'     => '2/3',
		),
		'hero_height'  => array(
			'tall'   => array( 'clamp(560px,47vw,760px)', '580px' ),
			'medium' => array( 'clamp(460px,36vw,600px)', '500px' ),
			'full'   => array( 'calc(100vh - var(--bb3-topbar))', 'calc(100svh - var(--bb3-topbar))' ),
		),
	);
}

/** Lighten (positive) or darken (negative) a hex colour. */
function bb3_hex_shift( string $hex, float $amount ): string {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$out = '#';
	foreach ( str_split( $hex, 2 ) as $part ) {
		$v    = hexdec( $part );
		$v    = $amount < 0 ? $v * ( 1 + $amount ) : $v + ( 255 - $v ) * $amount;
		$out .= str_pad( dechex( (int) round( max( 0, min( 255, $v ) ) ) ), 2, '0', STR_PAD_LEFT );
	}
	return $out;
}

/** CSS custom properties for the Design & Layout options. */
function bb3_design_css(): string {
	$maps   = bb3_design_maps();
	$color  = fn( string $key, string $fallback ) => sanitize_hex_color( (string) bb3_mod( $key ) ) ?: $fallback;
	$pick   = fn( string $map, string $key, string $fallback ) => $maps[ $map ][ (string) bb3_mod( $key ) ] ?? $maps[ $map ][ $fallback ];
	$accent = $color( 'color_accent', '#2f8548' );
	$hero   = $pick( 'hero_height', 'hero_height', 'tall' );
	$cols   = (int) bb3_mod( 'catalog_cols' );
	$width  = (int) bb3_mod( 'container' );
	$vars   = array(
		'--bb3-green'           => $accent,
		'--bb3-green-dark'      => bb3_hex_shift( $accent, -0.2 ),
		'--bb3-dark'            => $color( 'color_dark', '#111111' ),
		'--bb3-bar'             => $color( 'color_bar', '#2c2c2c' ),
		'--bb3-teal'            => $color( 'color_ticker', '#315b53' ),
		'--bb3-soft'            => $color( 'color_soft', '#f4f4f4' ),
		'--bb3-serif'           => $pick( 'hero_font', 'hero_font', 'serif' ),
		'--bb3-heading-font'    => $pick( 'heading_font', 'heading_font', 'sans' ),
		'--bb3-radius'          => min( 32, absint( bb3_mod( 'radius' ) ) ) . 'px',
		'--bb3-hero-btn-radius' => $pick( 'button_shape', 'button_shape', 'square' ),
		'--bb3-card-ratio'      => $pick( 'card_ratio', 'card_ratio', 'portrait' ),
		'--bb3-catalog-cols'    => in_array( $cols, array( 3, 4 ), true ) ? (string) $cols : '3',
		'--bb3-container'       => ( in_array( $width, array( 1200, 1280, 1440, 1640, 1920 ), true ) ? $width : 1640 ) . 'px',
		'--bb3-hero-h'          => $hero[0],
		'--bb3-hero-h-m'        => $hero[1],
		'--bb3-shade'           => (string) round( max( 0, min( 90, (int) bb3_mod( 'hero_shade' ) ) ) / 100, 2 ),
	);
	$css    = '';
	foreach ( $vars as $name => $value ) {
		$css .= $name . ':' . $value . ';';
	}
	return ':root{' . $css . '}';
}

/**
 * The stylesheet is small (≈10 KB gzipped), so it is inlined: one request
 * fewer and no render-blocking CSS on first paint.
 */
function bb3_inline_css(): string {
	$file = BB3_DIR . '/assets/css/theme.css';
	$key  = 'bb3_css_' . md5( BB3_VERSION . '|' . ( is_readable( $file ) ? filemtime( $file ) : 0 ) . '|' . BB3_URI );
	$css  = get_transient( $key );
	if ( false === $css ) {
		$css = is_readable( $file ) ? (string) file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
		$css = str_replace( 'url(../fonts/', 'url(' . BB3_URI . '/assets/fonts/', $css );
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = preg_replace( '/\s*\n\s*/', '', $css );
		$css = str_replace( '</', '<\/', $css );
		set_transient( $key, $css, WEEK_IN_SECONDS );
	}
	return $css;
}

add_action(
	'wp_head',
	function () {
		echo '<style id="bb3-theme-css">' . bb3_inline_css() . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- theme's own stylesheet.
		echo '<style id="bb3-design-vars">' . bb3_design_css() . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- values sanitised in bb3_design_css().
	},
	6
);

/** Start downloading the first hero photo as early as possible. */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		for ( $i = 1; $i <= 3; $i++ ) {
			if ( ! bb3_mod( "hero_{$i}_on" ) || '' === bb3_mod( "hero_{$i}_title" ) ) {
				continue;
			}
			$id = (int) bb3_mod( "hero_{$i}_image" );
			if ( $id && wp_attachment_is_image( $id ) ) {
				$srcset = (string) wp_get_attachment_image_srcset( $id, 'full' );
				echo '<link rel="preload" as="image" href="' . esc_url( (string) wp_get_attachment_image_url( $id, 'full' ) ) . '"' . ( $srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '" imagesizes="100vw"' : '' ) . ' fetchpriority="high">' . "\n";
			} else {
				echo '<link rel="preload" as="image" href="' . esc_url( BB3_URI . '/assets/' . bb3_mod( "hero_{$i}_asset" ) ) . '" fetchpriority="high">' . "\n";
			}
			return;
		}
	},
	3
);

/**
 * Pages must be revalidated by the browser, otherwise a host-wide
 * "ExpiresDefault 1 week" rule keeps old HTML (and removed images) on screen.
 * Sending Expires from PHP stops mod_expires from adding its own.
 */
add_action(
	'send_headers',
	function () {
		if ( is_admin() || headers_sent() ) {
			return;
		}
		header( 'Cache-Control: public, max-age=0, must-revalidate' );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	}
);

/* ── Head clean-up ─────────────────────────────────────────────────── */

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

add_filter(
	'body_class',
	function ( array $classes ) {
		$classes[] = 'bb3';
		if ( bb3_has_overlay_header() ) {
			$classes[] = 'bb3-has-overlay';
		}
		return $classes;
	}
);

add_filter( 'excerpt_length', fn() => 26 );
add_filter( 'excerpt_more', fn() => '…' );

/** Search form posts products to the collection page. */
function bb3_search_form( string $id = 'bb3-search' ): void {
	?>
	<form class="bb3-search-form" role="search" method="get" action="<?php echo esc_url( bb3_collection_url() ); ?>">
		<label class="screen-reader-text" for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Search the collection', 'bali-batik-v3' ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>" type="search" name="q" maxlength="80" placeholder="<?php esc_attr_e( 'Search motifs, fabrics, shirts…', 'bali-batik-v3' ); ?>" value="<?php echo esc_attr( bb3_catalog_params()['q'] ); ?>" autocomplete="off">
		<button type="submit"><?php echo bb3_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'bali-batik-v3' ); ?></span></button>
	</form>
	<?php
}

/** Remind administrators to fill in the WhatsApp number. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'customize' ) || bb3_whatsapp_number() ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'edit-bb_product', 'appearance_page_bb3-setup' ), true ) ) {
			return;
		}
		$url = admin_url( 'customize.php?autofocus[section]=bb3_contact' );
		echo '<div class="notice notice-info"><p>' . wp_kses_post(
			sprintf(
				/* translators: %s: Customizer link. */
				__( 'Bali Batik: product buttons show “Contact Us” until a WhatsApp number is set. <a href="%s">Add your WhatsApp number</a>.', 'bali-batik-v3' ),
				esc_url( $url )
			)
		) . '</p></div>';
	}
);
