<?php
/**
 * Demo content: batik-only sample products, categories, pages and images.
 *
 * - Safe to run again: every item carries a "_bb3_demo_key"; existing items
 *   are reused, never duplicated.
 * - Never overwrites content or settings the owner has changed.
 * - Removal only deletes demo items that are still untouched.
 * - Runs automatically once after activation on a site without products,
 *   otherwise from Appearance → Bali Batik Setup.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

function bb3_demo_categories(): array {
	return array(
		'clothing'    => array( __( 'Clothing', 'bali-batik-v3' ), '', 10, 'demo/dress-ivory.jpg', __( 'Batik clothing for everyday wear and special days: shirts and dresses in classic motifs such as Kawung, Parang and Truntum.', 'bali-batik-v3' ) ),
		'shirts'      => array( __( 'Shirts', 'bali-batik-v3' ), 'clothing', 11, 'demo/shirt-indigo.jpg', __( 'Batik shirts in classic motifs, from Kawung to Parang. Compare colors, sizes and prices, then ask about any shirt on WhatsApp.', 'bali-batik-v3' ) ),
		'dresses'     => array( __( 'Dresses', 'bali-batik-v3' ), 'clothing', 12, 'demo/dress-terracotta.jpg', __( 'Batik dresses with motifs that move as you walk. See the size, material and price of every dress.', 'bali-batik-v3' ) ),
		'fabrics'     => array( __( 'Fabrics', 'bali-batik-v3' ), '', 20, 'demo/fabric-indigo.jpg', __( 'Batik fabric by the piece, ready for your tailor. Each listing shows the motif, material, size and price.', 'bali-batik-v3' ) ),
		'accessories' => array( __( 'Accessories', 'bali-batik-v3' ), '', 30, 'demo/scarf-ivory.jpg', __( 'Batik accessories that finish an outfit, starting with lightweight scarves.', 'bali-batik-v3' ) ),
		'scarves'     => array( __( 'Scarves', 'bali-batik-v3' ), 'accessories', 31, 'demo/scarf-hijau.jpg', __( 'Lightweight batik scarves in Ceplok and Lereng motifs. An easy way to add batik to any outfit.', 'bali-batik-v3' ) ),
	);
}

/** [title, category, image, gallery[], price, motif, material, color, size, featured, excerpt, story] */
function bb3_demo_products(): array {
	$cotton_care = __( 'Hand wash in cold water with a mild soap. Dry in the shade and iron on the reverse side.', 'bali-batik-v3' );
	$rayon_care  = __( 'Hand wash gently in cold water. Do not wring. Dry flat in the shade and iron on low heat.', 'bali-batik-v3' );
	return array(
		'maroon-kawung-shirt'     => array( __( 'Maroon Kawung Batik Shirt', 'bali-batik-v3' ), 'shirts', 'demo/shirt-marun.jpg', array( 'demo/detail-3.jpg' ), 385000, 'Kawung', __( 'Cotton', 'bali-batik-v3' ), __( 'Maroon and cream', 'bali-batik-v3' ), 'S, M, L, XL', true, __( 'A short-sleeve shirt in deep maroon with the classic Kawung motif: four cream ovals set around a single point.', 'bali-batik-v3' ), __( 'Kawung is among the oldest batik motifs, built from four ovals arranged around a centre point. Here the cream ovals sit on a deep maroon ground, so the pattern reads clearly from across the room and stays calm up close. Wear it to the office with dark trousers, or keep it relaxed with denim.', 'bali-batik-v3' ), $cotton_care ),
		'indigo-lereng-shirt'     => array( __( 'Indigo Lereng Batik Shirt', 'bali-batik-v3' ), 'shirts', 'demo/shirt-indigo.jpg', array( 'demo/detail-5.jpg' ), 375000, 'Lereng', __( 'Cotton', 'bali-batik-v3' ), __( 'Indigo and white', 'bali-batik-v3' ), 'S, M, L, XL', true, __( 'Diagonal Lereng stripes in indigo and white on a short-sleeve cut that works from desk to dinner.', 'bali-batik-v3' ), __( 'Lereng motifs run in diagonal lines across the cloth, which gives a shirt a clean, upright look. Dotted white bands break up the indigo so the pattern feels light rather than heavy. An easy first batik shirt if you usually wear stripes.', 'bali-batik-v3' ), $cotton_care ),
		'green-parang-shirt'      => array( __( 'Green Parang Batik Shirt', 'bali-batik-v3' ), 'shirts', 'demo/shirt-hijau.jpg', array(), 395000, 'Parang', __( 'Cotton', 'bali-batik-v3' ), __( 'Forest green and cream', 'bali-batik-v3' ), 'M, L, XL', false, __( 'Flowing Parang bands in cream across a forest-green shirt. A bold motif with an easy, everyday fit.', 'bali-batik-v3' ), __( 'Parang is recognised by its unbroken, wave-like bands that run diagonally across the fabric. On forest green the cream bands stand out without shouting. Pair it with neutral trousers and let the motif do the talking.', 'bali-batik-v3' ), $cotton_care ),
		'terracotta-sisik-dress'  => array( __( 'Terracotta Sisik Batik Dress', 'bali-batik-v3' ), 'dresses', 'demo/dress-terracotta.jpg', array(), 520000, 'Sisik', __( 'Rayon', 'bali-batik-v3' ), __( 'Terracotta and cream', 'bali-batik-v3' ), 'S, M, L', true, __( 'A sleeveless A-line dress in warm terracotta, patterned with layered Sisik arches that move as you walk.', 'bali-batik-v3' ), __( 'Sisik means “scales”: rows of overlapping arches that give the cloth a soft sense of rhythm. The A-line cut lets the motif flow from shoulder to hem. Add a light scarf in the evening and it is ready for dinner.', 'bali-batik-v3' ), $rayon_care ),
		'ivory-truntum-dress'     => array( __( 'Ivory Truntum Batik Dress', 'bali-batik-v3' ), 'dresses', 'demo/dress-ivory.jpg', array( 'demo/detail-4.jpg' ), 495000, 'Truntum', __( 'Cotton', 'bali-batik-v3' ), __( 'Ivory and brown', 'bali-batik-v3' ), 'S, M, L', false, __( 'Small brown Truntum blossoms scattered over ivory. A light, sleeveless dress for warm days and garden events.', 'bali-batik-v3' ), __( 'Truntum fills the cloth with small star-like blossoms. In Java it is often worn at weddings, where the scattered flowers are read as a sign of love that keeps growing. On ivory cotton the motif feels fresh and light.', 'bali-batik-v3' ), $cotton_care ),
		'indigo-parang-fabric'    => array( __( 'Indigo Parang Batik Fabric', 'bali-batik-v3' ), 'fabrics', 'demo/fabric-indigo.jpg', array( 'demo/hero-2.jpg' ), 275000, 'Parang', __( 'Cotton', 'bali-batik-v3' ), __( 'Indigo and cream', 'bali-batik-v3' ), '115 × 250 cm', true, __( 'A length of indigo cotton with cream Parang bands, ready for a tailor to turn into a shirt, dress or sarong.', 'bali-batik-v3' ), __( 'Buying batik by the piece lets you choose the cut, the fit and the finish. This length carries the Parang motif in cream on deep indigo, with enough cloth for a long-sleeve shirt or a simple dress. Ask us for close-up photos of the edges before you decide.', 'bali-batik-v3' ), $cotton_care ),
		'green-kawung-fabric'     => array( __( 'Green Kawung Batik Fabric', 'bali-batik-v3' ), 'fabrics', 'demo/fabric-hijau.jpg', array( 'demo/hero-1.jpg' ), 260000, 'Kawung', __( 'Cotton', 'bali-batik-v3' ), __( 'Green and cream', 'bali-batik-v3' ), '115 × 250 cm', false, __( 'Cream Kawung ovals on forest green. A balanced repeat that suits shirts, skirts and home textiles.', 'bali-batik-v3' ), __( 'The Kawung repeat is even in every direction, which makes it forgiving to cut and easy to match at the seams. Use it for a shirt, a wrap skirt or cushion covers.', 'bali-batik-v3' ), $cotton_care ),
		'terracotta-ceplok-fabric' => array( __( 'Terracotta Ceplok Star Fabric', 'bali-batik-v3' ), 'fabrics', 'demo/fabric-terracotta.jpg', array(), 260000, 'Ceplok', __( 'Cotton', 'bali-batik-v3' ), __( 'Terracotta and cream', 'bali-batik-v3' ), '115 × 250 cm', false, __( 'Six-point Ceplok stars in cream on a terracotta ground: a crisp geometric repeat for statement pieces.', 'bali-batik-v3' ), __( 'Ceplok motifs are built from repeating geometric shapes, here a field of cream stars and dots. The warm terracotta ground keeps the look friendly rather than formal.', 'bali-batik-v3' ), $cotton_care ),
		'navy-ceplok-fabric'      => array( __( 'Navy Ceplok Star Fabric', 'bali-batik-v3' ), 'fabrics', 'demo/detail-1.jpg', array(), 265000, 'Ceplok', __( 'Cotton', 'bali-batik-v3' ), __( 'Navy and cream', 'bali-batik-v3' ), '115 × 250 cm', false, __( 'Cream stars and dots on deep navy. A geometric Ceplok repeat that looks sharp as a shirt or a table runner.', 'bali-batik-v3' ), __( 'Navy grounds are a classic choice for batik shirts because they pair with almost anything. The star repeat is small enough for menswear and bold enough for home textiles.', 'bali-batik-v3' ), $cotton_care ),
		'green-sisik-fabric'      => array( __( 'Green Sisik Batik Fabric', 'bali-batik-v3' ), 'fabrics', 'demo/detail-2.jpg', array(), 255000, 'Sisik', __( 'Cotton', 'bali-batik-v3' ), __( 'Green, cream and gold', 'bali-batik-v3' ), '115 × 250 cm', false, __( 'Layered cream and gold arches on green. The Sisik repeat gives fabric a gentle sense of movement.', 'bali-batik-v3' ), __( 'Rows of arches stack like scales, with fine gold lines that catch the light. A good match for a dress or a soft wrap skirt.', 'bali-batik-v3' ), $cotton_care ),
		'green-ceplok-scarf'      => array( __( 'Green Ceplok Batik Scarf', 'bali-batik-v3' ), 'scarves', 'demo/scarf-hijau.jpg', array(), 185000, 'Ceplok', __( 'Rayon', 'bali-batik-v3' ), __( 'Green and cream', 'bali-batik-v3' ), '50 × 180 cm', false, __( 'A long green scarf dotted with small Ceplok blossoms. Light enough to wear all day, finished with a fringe.', 'bali-batik-v3' ), __( 'A scarf is the easiest way to bring batik into a plain outfit. This one drapes softly and folds small enough to keep in a bag.', 'bali-batik-v3' ), $rayon_care ),
		'ivory-lereng-scarf'      => array( __( 'Ivory Lereng Batik Scarf', 'bali-batik-v3' ), 'scarves', 'demo/scarf-ivory.jpg', array(), 195000, 'Lereng', __( 'Rayon', 'bali-batik-v3' ), __( 'Ivory and maroon', 'bali-batik-v3' ), '50 × 180 cm', true, __( 'Maroon Lereng stripes on ivory: the simple way to add batik to a plain outfit.', 'bali-batik-v3' ), __( 'Diagonal stripes look good tied, draped or worn loose. Ivory keeps the scarf easy to match with both light and dark clothes.', 'bali-batik-v3' ), $rayon_care ),
	);
}

/** Find a demo item by its key. */
function bb3_demo_find( string $key, string $post_type = 'any' ): int {
	$ids = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_bb3_demo_key', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/** Copy a bundled image into the media library once. */
function bb3_demo_image( string $relative, string $alt = '' ): int {
	$key = 'img:' . $relative;
	$id  = bb3_demo_find( $key, 'attachment' );
	if ( $id ) {
		return $id;
	}
	$file = BB3_DIR . '/assets/' . $relative;
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$upload = wp_upload_bits( 'bb3-' . basename( $relative ), null, (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_title'     => '' !== $alt ? $alt : ucwords( str_replace( array( '-', '_' ), ' ', pathinfo( $relative, PATHINFO_FILENAME ) ) ),
			'post_mime_type' => $type['type'],
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_bb3_demo', '1' );
	update_post_meta( $id, '_bb3_demo_key', $key );
	return (int) $id;
}

/** Set a theme mod only when the owner has not set it. */
function bb3_demo_mod( string $key, $value ): void {
	if ( null === get_theme_mod( 'bb3_' . $key, null ) ) {
		set_theme_mod( 'bb3_' . $key, $value );
	}
}

/** Relative path of a term link, so settings survive a domain change. */
function bb3_relative_link( string $url ): string {
	$path = wp_make_link_relative( $url );
	return '' !== $path ? $path : $url;
}

function bb3_run_demo_import(): array {
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	wp_raise_memory_limit( 'admin' );
	if ( '' === (string) get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
	}
	bb3_register_content_types();
	$log = array(
		'images'     => 0,
		'categories' => 0,
		'products'   => 0,
		'pages'      => 0,
	);

	// Images.
	$alts = array(
		'batik/hero-batik-rack.jpg'   => __( 'Rows of red and black batik cloth hanging on a rack', 'bali-batik-v3' ),
		'batik/hero-batik-fabric.jpg' => __( 'Five folded batik fabrics with bird and mountain motifs', 'bali-batik-v3' ),
		'batik/hero-batik-craft.jpg'  => __( 'Two women working on batik cloth by hand', 'bali-batik-v3' ),
		'demo/hero-1.jpg'             => __( 'Green and cream Kawung batik pattern', 'bali-batik-v3' ),
		'demo/hero-2.jpg'             => __( 'Indigo and cream Parang batik pattern', 'bali-batik-v3' ),
		'demo/hero-3.jpg'             => __( 'Ivory batik pattern with small brown blossoms', 'bali-batik-v3' ),
		'demo/detail-1.jpg'           => __( 'Navy batik pattern with cream stars', 'bali-batik-v3' ),
		'demo/detail-2.jpg'           => __( 'Green batik pattern with layered cream arches', 'bali-batik-v3' ),
		'demo/detail-3.jpg'           => __( 'Maroon Kawung batik pattern close-up', 'bali-batik-v3' ),
		'demo/detail-4.jpg'           => __( 'Ivory Truntum batik pattern close-up', 'bali-batik-v3' ),
		'demo/detail-5.jpg'           => __( 'Indigo Lereng stripe batik pattern close-up', 'bali-batik-v3' ),
		'demo/detail-6.jpg'           => __( 'Terracotta Parang batik pattern close-up', 'bali-batik-v3' ),
	);
	$img  = function ( string $relative, string $alt = '' ) use ( &$alts, &$log ) {
		$before = bb3_demo_find( 'img:' . $relative, 'attachment' );
		$id     = bb3_demo_image( $relative, $alt ?: ( $alts[ $relative ] ?? '' ) );
		if ( ! $before && $id ) {
			++$log['images'];
		}
		return $id;
	};

	// Categories.
	$terms = array();
	foreach ( bb3_demo_categories() as $slug => [ $name, $parent, $order, $cover, $description ] ) {
		$existing = get_term_by( 'slug', $slug, 'bb_product_cat' );
		if ( $existing ) {
			$terms[ $slug ] = (int) $existing->term_id;
			continue;
		}
		$result = wp_insert_term(
			$name,
			'bb_product_cat',
			array(
				'slug'        => $slug,
				'parent'      => $parent && isset( $terms[ $parent ] ) ? $terms[ $parent ] : 0,
				'description' => $description,
			)
		);
		if ( is_wp_error( $result ) ) {
			continue;
		}
		$terms[ $slug ] = (int) $result['term_id'];
		update_term_meta( $terms[ $slug ], 'bb_order', $order );
		update_term_meta( $terms[ $slug ], 'bb_cover', $img( $cover ) );
		update_term_meta( $terms[ $slug ], 'bb3_demo', '1' );
		++$log['categories'];
	}

	// Products.
	$n = 0;
	foreach ( bb3_demo_products() as $key => $p ) {
		++$n;
		[ $title, $cat, $image, $gallery, $price, $motif, $material, $color, $size, $featured, $excerpt, $story, $care ] = $p;
		if ( bb3_demo_find( 'product:' . $key, 'bb_product' ) ) {
			continue;
		}
		$thumb = $img( $image, $title );
		$id    = wp_insert_post(
			array(
				'post_type'    => 'bb_product',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $key,
				'post_excerpt' => $excerpt,
				'post_content' => '<p>' . $story . '</p>',
				'menu_order'   => $n,
				'post_date'    => gmdate( 'Y-m-d H:i:s', time() - ( 13 - $n ) * DAY_IN_SECONDS + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
		$gallery_ids = array_filter( array_map( fn( $g ) => $img( $g ), $gallery ) );
		$meta        = array(
			'price'         => $price,
			'compare_price' => '',
			'sku'           => 'DEMO-' . str_pad( (string) $n, 2, '0', STR_PAD_LEFT ),
			'motif'         => $motif,
			'material'      => $material,
			'technique'     => '',
			'dimensions'    => $size,
			'color'         => $color,
			'care'          => $care,
			'availability'  => '',
			'featured'      => $featured ? '1' : '',
			'gallery'       => implode( ',', $gallery_ids ),
		);
		foreach ( $meta as $meta_key => $value ) {
			update_post_meta( $id, '_bb_' . $meta_key, $value );
		}
		if ( isset( $terms[ $cat ] ) ) {
			wp_set_object_terms( $id, array( $terms[ $cat ] ), 'bb_product_cat' );
		}
		update_post_meta( $id, '_bb3_demo', '1' );
		update_post_meta( $id, '_bb3_demo_key', 'product:' . $key );
		update_post_meta( $id, '_bb3_demo_modified', get_post_field( 'post_modified_gmt', $id ) );
		++$log['products'];
	}

	// Pages.
	$pages = array(
		'about'   => array(
			__( 'About Us', 'bali-batik-v3' ),
			'',
			'<p>' . __( '[bb3_brand] is a catalogue of batik fabrics and clothing, put together for people who want to wear batik with confidence.', 'bali-batik-v3' ) . '</p>'
			. '<h2>' . __( 'What you will find here', 'bali-batik-v3' ) . '</h2><p>' . __( 'Shirts, dresses, scarves and lengths of fabric in classic motifs such as Kawung, Parang, Truntum and Ceplok. Every product page lists the motif, material, size and price, with care notes to keep each piece looking its best.', 'bali-batik-v3' ) . '</p>'
			. '<h2>' . __( 'How buying works', 'bali-batik-v3' ) . '</h2><ol><li>' . __( 'Browse the collection and save the pieces you like.', 'bali-batik-v3' ) . '</li><li>' . __( 'Open a product and tap “Ask on WhatsApp”. Your message already includes the product name and link.', 'bali-batik-v3' ) . '</li><li>' . __( 'Ask us about availability, sizing and delivery. We confirm the details with you before anything is final.', 'bali-batik-v3' ) . '</li></ol>'
			. '<h2>' . __( 'A few words about batik', 'bali-batik-v3' ) . '</h2><p>' . __( 'Batik is a wax-resist technique. Wax is applied to the cloth so the covered areas resist the dye, and repeating the process with different colors builds up the motif. Many motifs carry traditional meanings, which is part of what makes choosing batik so personal.', 'bali-batik-v3' ) . '</p>',
			__( '{brand} is a catalogue of batik fabrics and clothing. Learn how to browse, compare motifs and ask about any piece on WhatsApp.', 'bali-batik-v3' ),
		),
		'contact' => array(
			__( 'Contact', 'bali-batik-v3' ),
			'page-templates/contact.php',
			'<p>' . __( 'Have a question about a piece, its size or delivery? Send us a message and include the product name or link so we can help you faster.', 'bali-batik-v3' ) . '</p>',
			__( 'Contact {brand} about any batik fabric, shirt, dress or scarf in the collection. Message us on WhatsApp or by e-mail.', 'bali-batik-v3' ),
		),
	);
	foreach ( $pages as $slug => [ $title, $template, $content, $desc ] ) {
		if ( bb3_demo_find( 'page:' . $slug, 'page' ) || get_page_by_path( $slug ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		if ( $template ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}
		update_post_meta( $id, '_bb3_seo_desc', $desc );
		update_post_meta( $id, '_bb3_demo', '1' );
		update_post_meta( $id, '_bb3_demo_key', 'page:' . $slug );
		update_post_meta( $id, '_bb3_demo_modified', get_post_field( 'post_modified_gmt', $id ) );
		++$log['pages'];
	}

	bb3_demo_block_home();

	// Homepage images and links (only where the owner has not chosen yet).
	$link = fn( string $slug ) => isset( $terms[ $slug ] ) ? bb3_relative_link( (string) get_term_link( $terms[ $slug ], 'bb_product_cat' ) ) : '/koleksi/';
	bb3_demo_mod( 'hero_1_image', $img( 'batik/hero-batik-rack.jpg' ) );
	bb3_demo_mod( 'hero_2_image', $img( 'batik/hero-batik-fabric.jpg' ) );
	bb3_demo_mod( 'hero_3_image', $img( 'batik/hero-batik-craft.jpg' ) );
	bb3_demo_mod( 'hero_2_url', $link( 'fabrics' ) );
	bb3_demo_mod( 'arrivals_promo_image', $img( 'demo/fabric-terracotta.jpg' ) );
	bb3_demo_mod( 'story_image', $img( 'batik/hero-batik-craft.jpg' ) );
	bb3_demo_mod( 'banner_1_image', $img( 'demo/detail-5.jpg' ) );
	bb3_demo_mod( 'banner_1_url', $link( 'shirts' ) );
	bb3_demo_mod( 'banner_2_image', $img( 'demo/detail-6.jpg' ) );
	bb3_demo_mod( 'banner_2_url', $link( 'dresses' ) );
	bb3_demo_mod( 'banner_3_image', $img( 'demo/detail-2.jpg' ) );
	bb3_demo_mod( 'banner_3_url', $link( 'scarves' ) );
	bb3_demo_mod( 'spot_image', $img( 'demo/hero-1.jpg' ) );
	foreach ( array(
		1 => array( 'demo/shirt-indigo.jpg', 'shirts' ),
		2 => array( 'demo/dress-ivory.jpg', 'dresses' ),
		3 => array( 'demo/dress-terracotta.jpg', 'dresses' ),
		4 => array( 'demo/shirt-hijau.jpg', 'clothing' ),
		5 => array( 'demo/scarf-ivory.jpg', 'accessories' ),
	) as $i => [ $file, $slug ] ) {
		bb3_demo_mod( "occ_{$i}_image", $img( $file ) );
		bb3_demo_mod( "occ_{$i}_url", $link( $slug ) );
	}
	bb3_demo_mod( 'statement_img_1', $img( 'demo/detail-3.jpg' ) );
	bb3_demo_mod( 'statement_img_2', $img( 'demo/detail-1.jpg' ) );
	foreach ( array( 'batik/hero-batik-rack.jpg', 'demo/detail-6.jpg', 'demo/hero-3.jpg', 'demo/detail-4.jpg', 'demo/hero-2.jpg' ) as $i => $file ) {
		bb3_demo_mod( 'gal_' . ( $i + 1 ) . '_image', $img( $file ) );
	}

	// Site identity on a fresh install (title still equals the domain).
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	if ( in_array( get_option( 'blogname' ), array( $host, 'My WordPress Website', 'My Blog', '' ), true ) ) {
		update_option( 'blogname', 'Bali Batik' );
	}
	if ( in_array( get_option( 'blogdescription' ), array( '', 'Just another WordPress site' ), true ) ) {
		update_option( 'blogdescription', __( 'Batik fabrics, shirts, dresses & scarves', 'bali-batik-v3' ) );
	}

	// Default WordPress sample content, only while untouched.
	foreach ( array(
		array( 'hello-world', 'post' ),
		array( 'sample-page', 'page' ),
	) as [ $slug, $type ] ) {
		$sample = get_page_by_path( $slug, OBJECT, $type );
		if ( $sample && 'publish' === $sample->post_status && $sample->post_date_gmt === $sample->post_modified_gmt ) {
			wp_trash_post( $sample->ID );
		}
	}

	flush_rewrite_rules();
	update_option( 'bb3_media_ver', 3, false ); // images were just created with the current sizes
	update_option( 'bb3_demo_state', 'done', false );
	update_option( 'bb3_demo_log', array( 'time' => time() ) + $log, false );
	return $log;
}

/**
 * Draft page with the homepage built from Bali Batik blocks, ready for
 * inline editing in the block editor. Not public until someone publishes it.
 */
function bb3_demo_block_home(): int {
	$existing = bb3_demo_find( 'page:home-blocks', 'page' );
	if ( $existing ) {
		return $existing;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'draft',
			'post_title'   => __( 'Home – Block Editor', 'bali-batik-v3' ),
			'post_name'    => 'home-blocks',
			'post_content' => bb3_home_blocks_markup(),
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	update_post_meta( $id, '_bb3_demo', '1' );
	update_post_meta( $id, '_bb3_demo_key', 'page:home-blocks' );
	update_post_meta( $id, '_bb3_demo_modified', get_post_field( 'post_modified_gmt', $id ) );
	return (int) $id;
}

/** Existing sites get the block homepage draft once after this update. */
add_action(
	'init',
	function () {
		if ( 'done' === get_option( 'bb3_demo_state' ) && ! get_option( 'bb3_block_home_v1' ) && ! wp_doing_cron() ) {
			update_option( 'bb3_block_home_v1', 1, false );
			bb3_demo_block_home();
		}
	},
	40
);

/** Remove demo items that nobody edited. Edited items stay. */
function bb3_remove_demo(): array {
	$removed = array(
		'products' => 0,
		'pages'    => 0,
		'kept'     => 0,
	);
	$posts   = get_posts(
		array(
			'post_type'      => array( 'bb_product', 'page' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_bb3_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	foreach ( $posts as $post ) {
		$untouched = get_post_meta( $post->ID, '_bb3_demo_modified', true ) === $post->post_modified_gmt;
		if ( ! $untouched ) {
			++$removed['kept'];
			continue;
		}
		wp_trash_post( $post->ID );
		++$removed[ 'page' === $post->post_type ? 'pages' : 'products' ];
	}
	foreach ( bb3_categories( -1, false ) as $term ) {
		if ( get_term_meta( $term->term_id, 'bb3_demo', true ) && 0 === (int) $term->count ) {
			wp_delete_term( $term->term_id, 'bb_product_cat' );
		}
	}
	update_option( 'bb3_demo_state', 'removed', false );
	return $removed;
}

/* ── Automatic first run on a fresh site ──────────────────────────── */

add_action(
	'after_switch_theme',
	function () {
		$has_products = get_posts(
			array(
				'post_type'      => 'bb_product',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( ! $has_products && ! get_option( 'bb3_demo_state' ) ) {
			update_option( 'bb3_demo_state', 'pending', false );
		}
	},
	20
);

/** Runs (or resumes) a pending import; a short lock stops parallel runs. */
add_action(
	'init',
	function () {
		if ( 'pending' !== get_option( 'bb3_demo_state' ) || get_transient( 'bb3_demo_lock' ) || wp_doing_cron() ) {
			return;
		}
		set_transient( 'bb3_demo_lock', 1, 5 * MINUTE_IN_SECONDS );
		ignore_user_abort( true );
		bb3_run_demo_import();
		delete_transient( 'bb3_demo_lock' );
	},
	30
);

/* ── Appearance → Bali Batik Setup ─────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_theme_page( __( 'Bali Batik Setup', 'bali-batik-v3' ), __( 'Bali Batik Setup', 'bali-batik-v3' ), 'manage_options', 'bb3-setup', 'bb3_render_setup_page' );
	}
);

function bb3_demo_counts(): array {
	$count = function ( $type ) {
		return count(
			get_posts(
				array(
					'post_type'      => $type,
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'meta_key'       => '_bb3_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			)
		);
	};
	return array(
		__( 'Demo products', 'bali-batik-v3' ) => $count( 'bb_product' ),
		__( 'Demo pages', 'bali-batik-v3' )    => $count( 'page' ),
		__( 'Demo images', 'bali-batik-v3' )   => $count( 'attachment' ),
		__( 'All products', 'bali-batik-v3' )  => (int) wp_count_posts( 'bb_product' )->publish,
	);
}

function bb3_render_setup_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = isset( $_GET['bb3_done'] ) ? sanitize_key( wp_unslash( $_GET['bb3_done'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	echo '<div class="wrap"><h1>' . esc_html__( 'Bali Batik Setup', 'bali-batik-v3' ) . '</h1>';
	if ( 'import' === $done ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo content is in place. Existing items were kept as they were.', 'bali-batik-v3' ) . '</p></div>';
	} elseif ( 'remove' === $done ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Untouched demo items were moved to the trash. Anything you edited was kept.', 'bali-batik-v3' ) . '</p></div>';
	}
	echo '<h2>' . esc_html__( 'Current content', 'bali-batik-v3' ) . '</h2><table class="widefat striped" style="max-width:520px"><tbody>';
	foreach ( bb3_demo_counts() as $label => $value ) {
		echo '<tr><td>' . esc_html( $label ) . '</td><td><strong>' . (int) $value . '</strong></td></tr>';
	}
	echo '</tbody></table>';
	echo '<h2>' . esc_html__( 'Demo content', 'bali-batik-v3' ) . '</h2><p style="max-width:680px">' . esc_html__( 'Adds 12 sample batik products in 6 categories, an About page, a Contact page and homepage images. Sample products are marked “Demo” in the product list. Running it again never duplicates items or overwrites your changes. Make a backup first on a live site.', 'bali-batik-v3' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="display:inline-block;margin-right:12px">';
	wp_nonce_field( 'bb3_demo_import' );
	echo '<input type="hidden" name="action" value="bb3_demo_import"><button class="button button-primary">' . esc_html__( 'Import demo content', 'bali-batik-v3' ) . '</button></form>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="display:inline-block">';
	wp_nonce_field( 'bb3_demo_remove' );
	echo '<input type="hidden" name="action" value="bb3_demo_remove"><button class="button">' . esc_html__( 'Remove untouched demo content', 'bali-batik-v3' ) . '</button></form>';
	echo '<p class="description" style="margin-top:10px">' . esc_html__( 'Removal moves demo products and pages to the trash only if nobody edited them. Images stay in the Media Library.', 'bali-batik-v3' ) . '</p>';
	bb3_render_update_settings();
	echo '</div>';
}

add_action(
	'admin_post_bb3_demo_import',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'bali-batik-v3' ), 403 );
		}
		check_admin_referer( 'bb3_demo_import' );
		bb3_run_demo_import();
		wp_safe_redirect( admin_url( 'themes.php?page=bb3-setup&bb3_done=import' ) );
		exit;
	}
);

add_action(
	'admin_post_bb3_demo_remove',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'bali-batik-v3' ), 403 );
		}
		check_admin_referer( 'bb3_demo_remove' );
		bb3_remove_demo();
		wp_safe_redirect( admin_url( 'themes.php?page=bb3-setup&bb3_done=remove' ) );
		exit;
	}
);
