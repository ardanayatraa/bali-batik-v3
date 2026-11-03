<?php
/**
 * Shared helpers: product data, prices, images, links and contact.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** Product meta stored with the V2-compatible "_bb_" prefix. */
function bb3_meta( int $post_id, string $key ) {
	return get_post_meta( $post_id, '_bb_' . $key, true );
}

/**
 * Business name. One source of truth: the WordPress Site Title
 * (Customize → Bali Batik → Brand & Contact, or Settings → General).
 * Everything — titles, Open Graph, schema, logo text, footer, WhatsApp
 * messages, the generated share image and favicon — follows it.
 */
function bb3_brand(): string {
	$legacy = trim( (string) get_theme_mod( 'bb3_brand_name', '' ) );
	$name   = '' !== $legacy ? $legacy : trim( wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES ) );
	return '' !== $name ? $name : 'Batik';
}

/** Short tagline: the WordPress Tagline. */
function bb3_tagline(): string {
	$legacy = trim( (string) get_theme_mod( 'bb3_brand_tagline', '' ) );
	return '' !== $legacy ? $legacy : trim( wp_specialchars_decode( (string) get_bloginfo( 'description' ), ENT_QUOTES ) );
}

/** Fill {brand}, {tagline} and {year} in any editable text. */
function bb3_fill( string $text ): string {
	if ( ! str_contains( $text, '{' ) ) {
		return $text;
	}
	return strtr(
		$text,
		array(
			'{brand}'   => bb3_brand(),
			'{tagline}' => bb3_tagline(),
			'{year}'    => wp_date( 'Y' ),
		)
	);
}

// [bb3_brand] and [bb3_tagline] keep page content in step with the current name.
add_shortcode( 'bb3_brand', fn() => esc_html( bb3_brand() ) );
add_shortcode( 'bb3_tagline', fn() => esc_html( bb3_tagline() ) );

/**
 * Older installs stored the name in the theme; move it to the Site Title once
 * so there is only one place to change it.
 */
add_action(
	'admin_init',
	function () {
		foreach ( array(
			'bb3_brand_name'    => 'blogname',
			'bb3_brand_tagline' => 'blogdescription',
		) as $mod => $option ) {
			$value = trim( (string) get_theme_mod( $mod, '' ) );
			if ( '' !== $value && current_user_can( 'manage_options' ) ) {
				update_option( $option, $value );
				remove_theme_mod( $mod );
			}
		}
	}
);

/** Format an integer as Indonesian Rupiah, e.g. "Rp 385.000". */
function bb3_format_price( $amount ): string {
	if ( '' === $amount || null === $amount || ! is_numeric( $amount ) ) {
		return '';
	}
	return 'Rp ' . number_format( (float) $amount, 0, ',', '.' );
}

/** Price + compare price, compare only when it is really higher. */
function bb3_prices( int $post_id ): array {
	$price   = bb3_meta( $post_id, 'price' );
	$compare = bb3_meta( $post_id, 'compare_price' );
	$price   = ( '' === $price || null === $price ) ? null : (int) $price;
	$compare = ( '' === $compare || null === $compare ) ? null : (int) $compare;
	if ( null === $price || null === $compare || $compare <= $price ) {
		$compare = null;
	}
	return array(
		'price'   => $price,
		'compare' => $compare,
	);
}

function bb3_price_html( int $post_id ): string {
	$p = bb3_prices( $post_id );
	if ( null === $p['price'] ) {
		return '<span class="bb3-price bb3-price--ask">' . esc_html__( 'Price on request', 'bali-batik-v3' ) . '</span>';
	}
	$html = '<span class="bb3-price">';
	if ( $p['compare'] ) {
		$html .= '<del><span class="screen-reader-text">' . esc_html__( 'Original price:', 'bali-batik-v3' ) . ' </span>' . esc_html( bb3_format_price( $p['compare'] ) ) . '</del> ';
		$html .= '<ins><span class="screen-reader-text">' . esc_html__( 'Current price:', 'bali-batik-v3' ) . ' </span>' . esc_html( bb3_format_price( $p['price'] ) ) . '</ins>';
	} else {
		$html .= '<span>' . esc_html( bb3_format_price( $p['price'] ) ) . '</span>';
	}
	return $html . '</span>';
}

/**
 * Turn a stored link into an absolute URL. Relative paths ("/about/",
 * "?q=x") resolve against the site address.
 */
function bb3_url( $value ): string {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( str_starts_with( $value, '/' ) && ! str_starts_with( $value, '//' ) ) {
		return home_url( $value );
	}
	if ( str_starts_with( $value, '?' ) || str_starts_with( $value, '#' ) ) {
		return home_url( '/' . $value );
	}
	return esc_url_raw( $value );
}

function bb3_collection_url(): string {
	$link = get_post_type_archive_link( 'bb_product' );
	return $link ? $link : home_url( '/koleksi/' );
}

/** Pixel size of a bundled asset, cached per request. */
function bb3_asset_size( string $relative ): array {
	static $cache = array();
	if ( ! isset( $cache[ $relative ] ) ) {
		$file               = BB3_DIR . '/assets/' . $relative;
		$size               = is_readable( $file ) ? wp_getimagesize( $file ) : false;
		$cache[ $relative ] = $size ? array( (int) $size[0], (int) $size[1] ) : array( 1200, 800 );
	}
	return $cache[ $relative ];
}

/**
 * Responsive image from the media library, or a bundled asset as fallback.
 *
 * @param int    $id       Attachment ID (0 for fallback).
 * @param string $size     Registered image size.
 * @param array  $attr     Extra attributes (alt, class, loading, sizes, fetchpriority).
 * @param string $fallback Asset path relative to assets/, e.g. "demo/x.jpg".
 */
function bb3_image( int $id, string $size = 'large', array $attr = array(), string $fallback = '' ): string {
	// Explicit lazy loading: WordPress skips it for images outside the loop,
	// which made every homepage image download up front.
	$attr = wp_parse_args(
		$attr,
		array(
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image( $id, $size, false, $attr );
	}
	if ( '' === $fallback ) {
		return '';
	}
	[ $w, $h ] = bb3_asset_size( $fallback );
	$attr      = wp_parse_args(
		$attr,
		array(
			'alt'      => '',
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
	$html      = '<img src="' . esc_url( BB3_URI . '/assets/' . $fallback ) . '" width="' . (int) $w . '" height="' . (int) $h . '"';
	foreach ( $attr as $name => $value ) {
		if ( 'sizes' === $name || false === $value || null === $value ) {
			continue;
		}
		$html .= ' ' . esc_attr( $name ) . '="' . esc_attr( (string) $value ) . '"';
	}
	return $html . '>';
}

/** Absolute URL + size for an image setting, used by SEO/OG. */
function bb3_image_data( int $id, string $size = 'full' ): ?array {
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return null;
	}
	$src = wp_get_attachment_image_src( $id, $size );
	if ( ! $src ) {
		return null;
	}
	return array(
		'url'    => $src[0],
		'width'  => (int) $src[1],
		'height' => (int) $src[2],
		'type'   => (string) get_post_mime_type( $id ),
		'alt'    => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}

/**
 * Share image data from the originally uploaded file (JPEG/PNG), because
 * resized copies may be WebP, which some link previews do not show.
 */
function bb3_og_image_data( int $id ): ?array {
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return null;
	}
	$path = wp_get_original_image_path( $id );
	$url  = wp_get_original_image_url( $id );
	// A WebP main file usually sits next to the uploaded JPEG/PNG: prefer that one.
	if ( $path && $url && preg_match( '/\.webp$/i', $path ) ) {
		foreach ( array( 'jpg', 'jpeg', 'png' ) as $ext ) {
			$sibling = preg_replace( '/\.webp$/i', '.' . $ext, $path );
			if ( file_exists( $sibling ) ) {
				$url  = str_replace( wp_basename( $path ), wp_basename( $sibling ), $url );
				$path = $sibling;
				break;
			}
		}
	}
	$type = $path ? (string) wp_check_filetype( $path )['type'] : '';
	$size = ( $path && file_exists( $path ) ) ? wp_getimagesize( $path ) : false;
	if ( ! $url || ! $size || ! in_array( $type, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
		return bb3_image_data( $id, 'large' );
	}
	return array(
		'url'    => $url,
		'width'  => (int) $size[0],
		'height' => (int) $size[1],
		'type'   => $type,
		'alt'    => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}

/** Attachment IDs in the product gallery (max 10). */
function bb3_gallery_ids( int $post_id ): array {
	$ids = array_filter( array_map( 'absint', explode( ',', (string) bb3_meta( $post_id, 'gallery' ) ) ) );
	return array_slice( array_values( array_filter( $ids, 'wp_attachment_is_image' ) ), 0, 10 );
}

/** Featured image first, then gallery, without duplicates. */
function bb3_product_images( int $post_id ): array {
	$ids   = array();
	$thumb = (int) get_post_thumbnail_id( $post_id );
	if ( $thumb ) {
		$ids[] = $thumb;
	}
	foreach ( bb3_gallery_ids( $post_id ) as $id ) {
		if ( ! in_array( $id, $ids, true ) ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/** Most specific category of a product (deepest term). */
function bb3_primary_term( int $post_id ): ?WP_Term {
	$terms = get_the_terms( $post_id, 'bb_product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	usort(
		$terms,
		fn( $a, $b ) => count( get_ancestors( $b->term_id, 'bb_product_cat' ) ) <=> count( get_ancestors( $a->term_id, 'bb_product_cat' ) )
	);
	return $terms[0];
}

/** Term slugs of a product plus all ancestors, for client-side tab filters. */
function bb3_term_slugs( int $post_id ): array {
	$terms = get_the_terms( $post_id, 'bb_product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}
	$slugs = array();
	foreach ( $terms as $term ) {
		$slugs[] = $term->slug;
		foreach ( get_ancestors( $term->term_id, 'bb_product_cat' ) as $ancestor ) {
			$parent = get_term( $ancestor, 'bb_product_cat' );
			if ( $parent instanceof WP_Term ) {
				$slugs[] = $parent->slug;
			}
		}
	}
	return array_values( array_unique( $slugs ) );
}

/** Category cover image: own cover, else first product photo. */
function bb3_term_cover( WP_Term $term ): int {
	$cover = (int) get_term_meta( $term->term_id, 'bb_cover', true );
	if ( $cover && wp_attachment_is_image( $cover ) ) {
		return $cover;
	}
	$ids = get_posts(
		array(
			'post_type'      => 'bb_product',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery
			'tax_query'      => array( array( 'taxonomy' => 'bb_product_cat', 'terms' => $term->term_id ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return $ids ? (int) get_post_thumbnail_id( $ids[0] ) : 0;
}

/**
 * Product categories ordered by "bb_order" then name, cached.
 *
 * @return WP_Term[]
 */
function bb3_categories( int $parent = -1, bool $hide_empty = true ): array {
	// "last_changed" moves whenever a term or term meta changes.
	$key   = 'bb3_cats_' . $parent . '_' . (int) $hide_empty . '_' . wp_cache_get_last_changed( 'terms' );
	$terms = wp_cache_get( $key, 'bb3' );
	if ( false === $terms ) {
		$args = array(
			'taxonomy'   => 'bb_product_cat',
			'hide_empty' => $hide_empty,
			'pad_counts' => true, // parent counts include products in sub-categories
		);
		if ( $parent >= 0 ) {
			$args['parent'] = $parent;
		}
		$terms = get_terms( $args );
		$terms = is_wp_error( $terms ) ? array() : $terms;
		usort(
			$terms,
			function ( $a, $b ) {
				$order = (int) get_term_meta( $a->term_id, 'bb_order', true ) <=> (int) get_term_meta( $b->term_id, 'bb_order', true );
				return $order ?: strcasecmp( $a->name, $b->name );
			}
		);
		wp_cache_set( $key, $terms, 'bb3' );
	}
	return $terms;
}

/** Published products query with sensible defaults. */
function bb3_products( array $args = array() ): WP_Query {
	return new WP_Query(
		wp_parse_args(
			$args,
			array(
				'post_type'           => 'bb_product',
				'post_status'         => 'publish',
				'posts_per_page'      => 12,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'orderby'             => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
			)
		)
	);
}

/** Configured social profiles only. */
function bb3_socials(): array {
	$labels = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
		'x'         => 'X',
	);
	$out    = array();
	foreach ( $labels as $key => $label ) {
		$url = esc_url( (string) bb3_mod( $key ) );
		if ( $url ) {
			$out[ $key ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $out;
}

/* ── Homepage mode and sections ───────────────────────────────────── */

/** True when the homepage is a block-editor page instead of theme sections. */
function bb3_home_uses_page(): bool {
	return 'page' === bb3_mod( 'home_mode' ) && 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0;
}

/** Transparent header only when the page starts with a hero photo. */
function bb3_has_overlay_header(): bool {
	if ( ! is_front_page() || ! bb3_mod( 'header_overlay' ) ) {
		return false;
	}
	if ( bb3_home_uses_page() ) {
		$page = get_post( (int) get_option( 'page_on_front' ) );
		foreach ( $page ? parse_blocks( $page->post_content ) : array() as $block ) {
			if ( empty( $block['blockName'] ) ) {
				continue;
			}
			return 'bali-batik/hero' === $block['blockName'] || ( 'bali-batik/section' === $block['blockName'] && 'hero' === ( $block['attrs']['section'] ?? '' ) );
		}
		return false;
	}
	$sections = bb3_home_sections();
	return $sections && 'hero' === $sections[0];
}

/** One homepage section inside a wrapper the Customizer can refresh. */
function bb3_render_home_section( string $key ): void {
	if ( ! isset( bb3_home_section_labels()[ $key ] ) ) {
		return;
	}
	echo '<div id="bb3-sec-' . esc_attr( $key ) . '" class="bb3-sec">';
	get_template_part( 'template-parts/home/' . $key );
	echo '</div>';
}

/* ── WhatsApp and contact ──────────────────────────────────────────── */

/** Normalise a phone number to international digits (62…). */
function bb3_normalize_phone( string $raw ): string {
	$digits = preg_replace( '/\D+/', '', $raw );
	if ( '' === $digits ) {
		return '';
	}
	if ( str_starts_with( $digits, '00' ) ) {
		$digits = substr( $digits, 2 );
	} elseif ( str_starts_with( $digits, '0' ) ) {
		$digits = '62' . substr( $digits, 1 );
	} elseif ( str_starts_with( $digits, '8' ) ) {
		$digits = '62' . $digits;
	}
	return strlen( $digits ) >= 8 ? $digits : '';
}

function bb3_whatsapp_number(): string {
	return bb3_normalize_phone( (string) bb3_mod( 'whatsapp' ) );
}

function bb3_whatsapp_url( string $message = '' ): string {
	$number = bb3_whatsapp_number();
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	return '' !== $message ? $url . '?text=' . rawurlencode( $message ) : $url;
}

/** URL of the contact page (template or slug "contact"). */
function bb3_contact_page_url(): string {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => 'page-templates/contact.php', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	if ( $pages ) {
		return (string) get_permalink( $pages[0] );
	}
	$page = get_page_by_path( 'contact' );
	return ( $page && 'publish' === $page->post_status ) ? (string) get_permalink( $page ) : '';
}

/**
 * Best available contact action: WhatsApp, else contact page, else e-mail.
 *
 * @return array{url:string,label:string,whatsapp:bool,external:bool}
 */
function bb3_contact_action( string $message = '' ): array {
	$wa = bb3_whatsapp_url( $message );
	if ( $wa ) {
		return array(
			'url'      => $wa,
			'label'    => __( 'Chat on WhatsApp', 'bali-batik-v3' ),
			'whatsapp' => true,
			'external' => true,
		);
	}
	$page = bb3_contact_page_url();
	if ( $page ) {
		return array(
			'url'      => $page,
			'label'    => __( 'Contact Us', 'bali-batik-v3' ),
			'whatsapp' => false,
			'external' => false,
		);
	}
	$email = sanitize_email( (string) bb3_mod( 'email' ) );
	return array(
		'url'      => $email ? 'mailto:' . $email : home_url( '/#site-footer' ),
		'label'    => __( 'Contact Us', 'bali-batik-v3' ),
		'whatsapp' => false,
		'external' => false,
	);
}

/** WhatsApp message for one product, built from the editable template. */
function bb3_product_message( int $post_id ): string {
	$p     = bb3_prices( $post_id );
	$price = null === $p['price'] ? __( 'price on request', 'bali-batik-v3' ) : bb3_format_price( $p['price'] );
	$pairs = array(
		'{bisnis}'   => bb3_brand(),
		'{business}' => bb3_brand(),
		'{nama}'     => get_the_title( $post_id ),
		'{name}'     => get_the_title( $post_id ),
		'{harga}'    => $price,
		'{price}'    => $price,
		'{link}'     => (string) get_permalink( $post_id ),
	);
	$text  = strtr( (string) bb3_mod( 'wa_template' ), $pairs );
	if ( ! str_contains( $text, (string) get_permalink( $post_id ) ) ) {
		$text .= ' ' . get_permalink( $post_id );
	}
	return html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES, 'UTF-8' );
}

/** Plain-text summary trimmed on a word boundary. */
function bb3_trim( string $text, int $max = 155 ): string {
	$text = trim( preg_replace( '/\s+/', ' ', html_entity_decode( wp_strip_all_tags( strip_shortcodes( $text ) ), ENT_QUOTES, 'UTF-8' ) ) );
	if ( mb_strlen( $text ) <= $max ) {
		return $text;
	}
	$cut   = mb_substr( $text, 0, $max - 1 );
	$space = mb_strrpos( $cut, ' ' );
	return rtrim( false !== $space ? mb_substr( $cut, 0, $space ) : $cut, " ,.;:-–" ) . '…';
}

/**
 * Breadcrumb trail for the current view.
 *
 * @return array<int,array{0:string,1:string}> [label, url]
 */
function bb3_breadcrumbs(): array {
	$trail = array( array( __( 'Home', 'bali-batik-v3' ), home_url( '/' ) ) );
	$coll  = array( __( 'Collection', 'bali-batik-v3' ), bb3_collection_url() );

	if ( is_singular( 'bb_product' ) ) {
		$trail[] = $coll;
		$term    = bb3_primary_term( get_the_ID() );
		if ( $term ) {
			foreach ( array_reverse( get_ancestors( $term->term_id, 'bb_product_cat' ) ) as $ancestor ) {
				$a = get_term( $ancestor, 'bb_product_cat' );
				if ( $a instanceof WP_Term ) {
					$trail[] = array( $a->name, (string) get_term_link( $a ) );
				}
			}
			$trail[] = array( $term->name, (string) get_term_link( $term ) );
		}
		$trail[] = array( get_the_title(), (string) get_permalink() );
	} elseif ( is_tax( 'bb_product_cat' ) ) {
		$trail[] = $coll;
		$term    = get_queried_object();
		foreach ( array_reverse( get_ancestors( $term->term_id, 'bb_product_cat' ) ) as $ancestor ) {
			$a = get_term( $ancestor, 'bb_product_cat' );
			if ( $a instanceof WP_Term ) {
				$trail[] = array( $a->name, (string) get_term_link( $a ) );
			}
		}
		$trail[] = array( $term->name, (string) get_term_link( $term ) );
	} elseif ( is_post_type_archive( 'bb_product' ) ) {
		$trail[] = $coll;
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$trail[] = array( get_the_title( $ancestor ), (string) get_permalink( $ancestor ) );
		}
		$trail[] = array( get_the_title(), (string) get_permalink() );
	} elseif ( is_singular() ) {
		$trail[] = array( get_the_title(), (string) get_permalink() );
	} elseif ( is_search() ) {
		$trail[] = array( __( 'Search', 'bali-batik-v3' ), '' );
	}
	return $trail;
}

function bb3_breadcrumbs_html(): void {
	$trail = bb3_breadcrumbs();
	if ( count( $trail ) < 2 ) {
		return;
	}
	echo '<nav class="bb3-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'bali-batik-v3' ) . '"><ol>';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => [ $label, $url ] ) {
		if ( $i === $last || '' === $url ) {
			echo '<li><span aria-current="page">' . esc_html( $label ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/** Section heading used across the homepage. */
function bb3_section_heading( string $title, string $text = '', string $link = '', string $link_label = '', string $id = '' ): void {
	if ( '' === $title ) {
		return;
	}
	echo '<div class="bb3-heading"><div>';
	echo '<h2' . ( $id ? ' id="' . esc_attr( $id ) . '"' : '' ) . '>' . esc_html( $title ) . '</h2>';
	if ( '' !== $text ) {
		echo '<p>' . esc_html( $text ) . '</p>';
	}
	echo '</div>';
	if ( $link && $link_label ) {
		echo '<a class="bb3-text-link" href="' . esc_url( $link ) . '">' . esc_html( $link_label ) . bb3_icon( 'arrow-right', 16 ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</div>';
}
