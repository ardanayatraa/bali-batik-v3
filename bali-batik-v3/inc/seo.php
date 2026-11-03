<?php
/**
 * SEO: titles, descriptions, canonical, robots, Open Graph, Twitter Card,
 * JSON-LD (Organization, WebSite, BreadcrumbList, Product, CollectionPage).
 *
 * One owner only: when an SEO plugin is active the theme hands titles,
 * descriptions, canonical and social tags to the plugin and keeps only the
 * Product schema, which those plugins do not generate for this post type.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

function bb3_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || class_exists( 'SQ_Classes_ObjController' );
}

/* ── Titles ─────────────────────────────────────────────────────────── */

add_filter( 'document_title_separator', fn() => '|' );

add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( bb3_seo_plugin_active() ) {
			return $title;
		}
		$custom = bb3_seo_title();
		return '' !== $custom ? esc_html( $custom ) : $title;
	},
	20
);

/** Drop the site name when the page title already contains it ("About Bali Batik"). */
add_filter(
	'document_title_parts',
	function ( array $parts ) {
		if ( isset( $parts['site'] ) ) {
			$parts['site'] = bb3_brand();
		}
		if ( isset( $parts['title'], $parts['site'] ) && false !== stripos( $parts['title'], (string) $parts['site'] ) ) {
			unset( $parts['site'] );
		}
		unset( $parts['tagline'] );
		return $parts;
	}
);

/** Page number suffix for paginated archives. */
function bb3_page_suffix(): string {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	/* translators: %d: page number. */
	return $paged > 1 ? ' – ' . sprintf( __( 'Page %d', 'bali-batik-v3' ), $paged ) : '';
}

/** Title for the current view, empty to let WordPress decide. */
function bb3_seo_title(): string {
	$brand = bb3_brand();
	if ( is_front_page() ) {
		$custom = (string) bb3_mod( 'seo_home_title' );
		return '' !== $custom ? $custom : $brand . ( '' !== bb3_tagline() ? ' | ' . bb3_tagline() : '' );
	}
	if ( is_singular() ) {
		$custom = bb3_fill( trim( (string) get_post_meta( get_queried_object_id(), '_bb3_seo_title', true ) ) );
		if ( '' !== $custom ) {
			return $custom;
		}
		if ( is_singular( 'bb_product' ) ) {
			$title = html_entity_decode( get_the_title( get_queried_object_id() ), ENT_QUOTES, 'UTF-8' );
			$term  = bb3_primary_term( get_queried_object_id() );
			if ( false === stripos( $title, 'batik' ) && $term ) {
				$title .= ' – Batik ' . $term->name;
			}
			return $title . ' | ' . $brand;
		}
		return '';
	}
	if ( is_tax( 'bb_product_cat' ) ) {
		$name = single_term_title( '', false );
		$name = false === stripos( $name, 'batik' ) ? 'Batik ' . $name : $name;
		return $name . bb3_page_suffix() . ' | ' . $brand;
	}
	if ( is_post_type_archive( 'bb_product' ) ) {
		$q = bb3_catalog_params()['q'];
		if ( '' !== $q ) {
			/* translators: %s: search words. */
			return sprintf( __( 'Search results for “%s”', 'bali-batik-v3' ), $q ) . ' | ' . $brand;
		}
		return __( 'Batik Collection: Fabrics, Shirts, Dresses & Scarves', 'bali-batik-v3' ) . bb3_page_suffix() . ' | ' . $brand;
	}
	return '';
}

/* ── Descriptions ───────────────────────────────────────────────────── */

function bb3_seo_description(): string {
	$brand = bb3_brand();
	if ( is_front_page() ) {
		return bb3_trim( (string) bb3_mod( 'seo_home_desc' ) );
	}
	if ( is_singular() ) {
		$id     = get_queried_object_id();
		$custom = bb3_fill( trim( (string) get_post_meta( $id, '_bb3_seo_desc', true ) ) );
		if ( '' !== $custom ) {
			return bb3_trim( $custom );
		}
		$post = get_post( $id );
		if ( is_singular( 'bb_product' ) ) {
			if ( has_excerpt( $id ) ) {
				return bb3_trim( $post->post_excerpt );
			}
			$bits  = array_filter( array( bb3_meta( $id, 'motif' ) ? bb3_meta( $id, 'motif' ) . ' motif' : '', bb3_meta( $id, 'material' ) ) );
			$price = bb3_prices( $id )['price'];
			/* translators: 1: product name, 2: motif/material, 3: brand. */
			$text = sprintf( __( '%1$s%2$s from %3$s.', 'bali-batik-v3' ), get_the_title( $id ), $bits ? ' – ' . implode( ', ', $bits ) : '', $brand );
			if ( null !== $price ) {
				$text .= ' ' . bb3_format_price( $price ) . '.';
			}
			return bb3_trim( $text . ' ' . __( 'See photos and details, then ask about it on WhatsApp.', 'bali-batik-v3' ) );
		}
		if ( $post && has_excerpt( $id ) ) {
			return bb3_trim( $post->post_excerpt );
		}
		return $post ? bb3_trim( $post->post_content ) : '';
	}
	if ( is_tax( 'bb_product_cat' ) ) {
		$term = get_queried_object();
		if ( '' !== trim( $term->description ) ) {
			return bb3_trim( $term->description );
		}
		/* translators: 1: category name, 2: brand. */
		return bb3_trim( sprintf( __( 'Browse batik %1$s at %2$s. Compare motifs, materials and prices, then ask about any piece on WhatsApp.', 'bali-batik-v3' ), mb_strtolower( $term->name ), $brand ) );
	}
	if ( is_post_type_archive( 'bb_product' ) ) {
		/* translators: %s: brand. */
		return sprintf( __( 'Explore the full %s collection of batik fabrics, shirts, dresses and scarves. Filter by category, sort by price and ask about any piece.', 'bali-batik-v3' ), $brand );
	}
	return bb3_trim( (string) get_bloginfo( 'description' ) );
}

/* ── Canonical and robots ───────────────────────────────────────────── */

function bb3_canonical(): string {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$url   = '';
	if ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_singular() ) {
		$url = (string) wp_get_canonical_url( get_queried_object_id() );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$link = get_term_link( get_queried_object() );
		$url  = is_wp_error( $link ) ? '' : $link;
	} elseif ( is_post_type_archive() ) {
		$url = (string) get_post_type_archive_link( get_query_var( 'post_type' ) ?: 'bb_product' );
	} elseif ( is_home() ) {
		$url = (string) get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/' );
	}
	if ( $url && $paged > 1 && ! is_singular() ) {
		$url = trailingslashit( $url ) . user_trailingslashit( 'page/' . $paged, 'paged' );
	}
	return $url;
}

add_filter(
	'wp_robots',
	function ( array $robots ) {
		$noindex = is_search() || is_404() || is_attachment() || is_author()
			|| ( bb3_is_catalog() && '' !== bb3_catalog_params()['q'] );
		if ( $noindex ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['max-image-preview'] );
		} elseif ( '1' === (string) get_option( 'blog_public' ) ) {
			$robots['max-image-preview'] = 'large';
			$robots['max-snippet']       = '-1';
		}
		return $robots;
	}
);

/* ── Share image ────────────────────────────────────────────────────── */

/** Share image: override → product/category photo → brand default. */
function bb3_share_image(): array {
	$candidates = array();
	if ( is_singular() ) {
		$id           = get_queried_object_id();
		$candidates[] = (int) get_post_meta( $id, '_bb3_og_image', true );
		$candidates[] = (int) get_post_thumbnail_id( $id );
	} elseif ( is_tax( 'bb_product_cat' ) ) {
		$candidates[] = bb3_term_cover( get_queried_object() );
	}
	$default = (int) bb3_mod( 'og_image' );
	// The bundled demo share image has the old name baked in, so it is skipped.
	if ( $default && 'img:img/og-default.jpg' !== get_post_meta( $default, '_bb3_demo_key', true ) ) {
		$candidates[] = $default;
	}
	foreach ( $candidates as $candidate ) {
		$data = bb3_og_image_data( $candidate );
		if ( $data ) {
			if ( '' === $data['alt'] ) {
				$data['alt'] = is_singular() ? get_the_title( get_queried_object_id() ) : bb3_brand();
			}
			return $data;
		}
	}
	// Generated from the current business name and tagline.
	$generated = bb3_generated_og_image();
	if ( $generated ) {
		return $generated;
	}
	// No GD on the server: plain batik photo without text, so no outdated name appears.
	[ $w, $h ] = bb3_asset_size( 'batik/hero-batik-fabric.jpg' );
	return array(
		'url'    => BB3_URI . '/assets/batik/hero-batik-fabric.jpg',
		'width'  => $w,
		'height' => $h,
		'type'   => 'image/jpeg',
		/* translators: %s: brand. */
		'alt'    => sprintf( __( '%s – batik fabrics, shirts, dresses and scarves', 'bali-batik-v3' ), bb3_brand() ),
	);
}

/* ── Head output ────────────────────────────────────────────────────── */

remove_action( 'wp_head', 'rel_canonical' );

add_action(
	'wp_head',
	function () {
		if ( bb3_seo_plugin_active() ) {
			if ( is_singular() ) {
				rel_canonical();
			}
			return;
		}
		$title     = wp_get_document_title();
		$desc      = bb3_seo_description();
		$canonical = bb3_canonical();
		$image     = bb3_share_image();
		$type      = is_singular( 'bb_product' ) ? 'product' : ( is_singular( 'post' ) ? 'article' : 'website' );
		$url       = $canonical ? $canonical : home_url( add_query_arg( array() ) );

		echo "\n<!-- Bali Batik V3 SEO -->\n";
		if ( '' !== $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		if ( $canonical ) {
			echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
		}
		$og = array(
			'og:locale'           => str_replace( '-', '_', get_bloginfo( 'language' ) ),
			'og:type'             => $type,
			'og:site_name'        => bb3_brand(),
			'og:title'            => $title,
			'og:description'      => $desc,
			'og:url'              => $url,
			'og:image'            => $image['url'],
			'og:image:secure_url' => str_starts_with( $image['url'], 'https://' ) ? $image['url'] : '',
			'og:image:width'      => (string) $image['width'],
			'og:image:height'     => (string) $image['height'],
			'og:image:type'       => $image['type'],
			'og:image:alt'        => $image['alt'],
		);
		if ( is_singular( 'bb_product' ) ) {
			$price = bb3_prices( get_queried_object_id() )['price'];
			if ( null !== $price ) {
				$og['product:price:amount']   = (string) $price;
				$og['product:price:currency'] = 'IDR';
			}
		}
		if ( 'article' === $type ) {
			$og['article:published_time'] = get_the_date( 'c', get_queried_object_id() );
			$og['article:modified_time']  = get_the_modified_date( 'c', get_queried_object_id() );
		}
		foreach ( $og as $property => $content ) {
			if ( '' !== (string) $content ) {
				echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
			}
		}
		$tw = array(
			'twitter:card'        => 'summary_large_image',
			'twitter:title'       => $title,
			'twitter:description' => $desc,
			'twitter:image'       => $image['url'],
			'twitter:image:alt'   => $image['alt'],
		);
		$handle = ltrim( (string) bb3_mod( 'x_handle' ), '@' );
		if ( '' !== $handle ) {
			$tw['twitter:site'] = '@' . $handle;
		}
		foreach ( $tw as $name => $content ) {
			if ( '' !== (string) $content ) {
				echo '<meta name="' . esc_attr( $name ) . '" content="' . esc_attr( $content ) . '">' . "\n";
			}
		}
	},
	2
);

/* ── Structured data ────────────────────────────────────────────────── */

function bb3_schema_graph(): array {
	$home  = home_url( '/' );
	$brand = bb3_brand();
	$graph = array();

	if ( ! bb3_seo_plugin_active() ) {
		$org = array(
			'@type' => 'Organization',
			'@id'   => $home . '#organization',
			'name'  => $brand,
			'url'   => $home,
		);
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? bb3_image_data( $logo_id ) : null;
		if ( ! $logo && get_option( 'site_icon' ) ) {
			$logo = bb3_image_data( (int) get_option( 'site_icon' ) );
		}
		if ( ! $logo ) {
			$logo = bb3_generated_icon( 512 );
		}
		if ( $logo ) {
			$org['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $logo['url'],
				'width'  => $logo['width'],
				'height' => $logo['height'],
			);
		}
		$same = array_values( wp_list_pluck( bb3_socials(), 'url' ) );
		if ( $same ) {
			$org['sameAs'] = $same;
		}
		$email = sanitize_email( (string) bb3_mod( 'email' ) );
		$phone = (string) bb3_mod( 'phone' );
		if ( $email || $phone || bb3_whatsapp_number() ) {
			$point = array(
				'@type'             => 'ContactPoint',
				'contactType'       => 'customer service',
				'availableLanguage' => array( 'en', 'id' ),
			);
			if ( $email ) {
				$point['email'] = $email;
			}
			$tel = $phone ? bb3_normalize_phone( $phone ) : bb3_whatsapp_number();
			if ( $tel ) {
				$point['telephone'] = '+' . $tel;
			}
			$org['contactPoint'] = array( $point );
		}
		$graph[] = $org;

		$graph[] = array(
			'@type'           => 'WebSite',
			'@id'             => $home . '#website',
			'url'             => $home,
			'name'            => $brand,
			'description'     => (string) bb3_mod( 'seo_home_desc' ),
			'inLanguage'      => get_bloginfo( 'language' ),
			'publisher'       => array( '@id' => $home . '#organization' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => add_query_arg( 'q', '{search_term_string}', bb3_collection_url() ),
				),
				'query-input' => 'required name=search_term_string',
			),
		);

		$crumbs = bb3_breadcrumbs();
		if ( count( $crumbs ) > 1 ) {
			$items = array();
			foreach ( $crumbs as $i => [ $label, $link ] ) {
				$item = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => wp_strip_all_tags( $label ),
				);
				if ( $link ) {
					$item['item'] = $link;
				}
				$items[] = $item;
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'@id'             => ( bb3_canonical() ?: $home ) . '#breadcrumb',
				'itemListElement' => $items,
			);
		}

		if ( bb3_is_catalog() && '' === bb3_catalog_params()['q'] ) {
			global $wp_query;
			$list = array();
			foreach ( $wp_query->posts as $i => $p ) {
				$list[] = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'url'      => get_permalink( $p ),
					'name'     => get_the_title( $p ),
				);
			}
			$graph[] = array(
				'@type'      => 'CollectionPage',
				'@id'        => bb3_canonical() . '#webpage',
				'url'        => bb3_canonical(),
				'name'       => wp_get_document_title(),
				'isPartOf'   => array( '@id' => $home . '#website' ),
				'breadcrumb' => array( '@id' => bb3_canonical() . '#breadcrumb' ),
				'mainEntity' => array(
					'@type'           => 'ItemList',
					'numberOfItems'   => count( $list ),
					'itemListElement' => $list,
				),
			);
		}
	}

	if ( is_singular( 'bb_product' ) ) {
		$id      = get_queried_object_id();
		$images  = array();
		foreach ( bb3_product_images( $id ) as $img ) {
			$src = wp_get_attachment_image_url( $img, 'large' );
			if ( $src ) {
				$images[] = $src;
			}
		}
		$product = array(
			'@type'       => 'Product',
			'@id'         => get_permalink( $id ) . '#product',
			'name'        => get_the_title( $id ),
			'url'         => get_permalink( $id ),
			'description' => bb3_trim( has_excerpt( $id ) ? get_post_field( 'post_excerpt', $id ) : get_post_field( 'post_content', $id ), 500 ),
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => $brand,
			),
		);
		if ( $images ) {
			$product['image'] = $images;
		}
		foreach ( array(
			'sku'      => 'sku',
			'material' => 'material',
			'color'    => 'color',
			'pattern'  => 'motif',
		) as $prop => $key ) {
			$value = (string) bb3_meta( $id, $key );
			if ( '' !== $value ) {
				$product[ $prop ] = $value;
			}
		}
		$term = bb3_primary_term( $id );
		if ( $term ) {
			$product['category'] = $term->name;
		}
		$price = bb3_prices( $id )['price'];
		if ( null !== $price ) {
			$offer        = array(
				'@type'         => 'Offer',
				'url'           => get_permalink( $id ),
				'price'         => (string) $price,
				'priceCurrency' => 'IDR',
				'seller'        => array( '@id' => $home . '#organization' ),
			);
			$availability = array(
				'in_stock'      => 'https://schema.org/InStock',
				'made_to_order' => 'https://schema.org/MadeToOrder',
				'sold_out'      => 'https://schema.org/SoldOut',
			)[ (string) bb3_meta( $id, 'availability' ) ] ?? '';
			if ( $availability ) {
				$offer['availability'] = $availability;
			}
			$product['offers'] = $offer;
		}
		$graph[] = $product;
	}

	return $graph;
}

add_action(
	'wp_head',
	function () {
		if ( is_404() || is_search() ) {
			return;
		}
		$graph = bb3_schema_graph();
		if ( ! $graph ) {
			return;
		}
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
		) . "</script>\n";
	},
	3
);

/* ── Sitemaps ───────────────────────────────────────────────────────── */

// Author archives are not part of a catalogue site; keep usernames private.
add_filter(
	'wp_sitemaps_add_provider',
	fn( $provider, $name ) => 'users' === $name ? false : $provider,
	10,
	2
);
add_filter(
	'wp_sitemaps_taxonomies',
	function ( $taxonomies ) {
		unset( $taxonomies['post_format'] );
		return $taxonomies;
	}
);
add_action(
	'template_redirect',
	function () {
		if ( is_author() ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);
