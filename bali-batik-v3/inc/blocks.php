<?php
/**
 * Bali Batik blocks for the block editor. Text is edited directly on the
 * canvas (inline); everything renders on the server, so the markup always
 * matches the theme and never needs "block recovery".
 *
 * - bali-batik/hero      Hero banner (photo, small line, title, button)
 * - bali-batik/banner    Promo banner card
 * - bali-batik/products  Product grid (latest, Our Picks or a category)
 * - bali-batik/section   Any homepage section (category row, story, services…)
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'block_categories_all',
	function ( $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'bali-batik',
				'title' => __( 'Bali Batik', 'bali-batik-v3' ),
				'icon'  => null,
			)
		);
		return $categories;
	}
);

add_action( 'init', 'bb3_register_blocks' );

function bb3_register_blocks(): void {
	wp_register_script(
		'bb3-blocks',
		BB3_URI . '/assets/js/blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
		bb3_asset_ver( 'assets/js/blocks.js' ),
		true
	);
	$text = array(
		'type'    => 'string',
		'default' => '',
	);

	register_block_type(
		'bali-batik/hero',
		array(
			'api_version'     => 3,
			'editor_script'   => 'bb3-blocks',
			'render_callback' => 'bb3_render_hero_block',
			'attributes'      => array(
				'imageId'    => array(
					'type'    => 'number',
					'default' => 0,
				),
				'imageUrl'   => $text,
				'asset'      => array(
					'type'    => 'string',
					'default' => 'batik/hero-batik-rack.jpg',
				),
				'focus'      => array(
					'type'    => 'string',
					'default' => '50% 50%',
				),
				'eyebrow'    => $text,
				'title'      => $text,
				'buttonText' => $text,
				'buttonUrl'  => $text,
				'level'      => array(
					'type'    => 'string',
					'default' => 'h1',
				),
			),
		)
	);

	register_block_type(
		'bali-batik/banner',
		array(
			'api_version'     => 3,
			'editor_script'   => 'bb3-blocks',
			'render_callback' => 'bb3_render_banner_block',
			'attributes'      => array(
				'imageId'    => array(
					'type'    => 'number',
					'default' => 0,
				),
				'imageUrl'   => $text,
				'asset'      => array(
					'type'    => 'string',
					'default' => 'demo/detail-5.jpg',
				),
				'eyebrow'    => $text,
				'title'      => $text,
				'buttonText' => $text,
				'url'        => $text,
			),
		)
	);

	register_block_type(
		'bali-batik/products',
		array(
			'api_version'     => 3,
			'editor_script'   => 'bb3-blocks',
			'render_callback' => 'bb3_render_products_block',
			'attributes'      => array(
				'heading'  => $text,
				'intro'    => $text,
				'source'   => array(
					'type'    => 'string',
					'default' => 'latest',
				),
				'category' => $text,
				'count'    => array(
					'type'    => 'number',
					'default' => 8,
				),
				'columns'  => array(
					'type'    => 'number',
					'default' => 4,
				),
				'linkText' => $text,
				'gridOnly' => array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
		)
	);

	register_block_type(
		'bali-batik/section',
		array(
			'api_version'     => 3,
			'editor_script'   => 'bb3-blocks',
			'render_callback' => 'bb3_render_section_block',
			'attributes'      => array(
				'section' => array(
					'type'    => 'string',
					'default' => 'categories',
				),
			),
		)
	);

	// Styles for core blocks so plain text blocks match the theme.
	register_block_style( 'core/paragraph', array( 'name' => 'bb3-statement', 'label' => __( 'Big statement', 'bali-batik-v3' ) ) );
	register_block_style( 'core/heading', array( 'name' => 'bb3-serif', 'label' => __( 'Serif title', 'bali-batik-v3' ) ) );
	register_block_style( 'core/button', array( 'name' => 'bb3-green', 'label' => __( 'Bali Batik main button', 'bali-batik-v3' ) ) );
	register_block_style( 'core/button', array( 'name' => 'bb3-dark', 'label' => __( 'Bali Batik dark button', 'bali-batik-v3' ) ) );
	register_block_style( 'core/columns', array( 'name' => 'bb3-soft', 'label' => __( 'Soft background', 'bali-batik-v3' ) ) );

	bb3_register_patterns();
}

/** Image from the media library, a pasted URL, or a bundled asset. */
function bb3_block_image( array $a, string $size, array $attr ): string {
	if ( ! empty( $a['imageId'] ) && wp_attachment_is_image( (int) $a['imageId'] ) ) {
		return bb3_image( (int) $a['imageId'], $size, $attr );
	}
	if ( ! empty( $a['imageUrl'] ) ) {
		return '<img src="' . esc_url( $a['imageUrl'] ) . '" alt="' . esc_attr( $attr['alt'] ?? '' ) . '" loading="' . esc_attr( $attr['loading'] ?? 'lazy' ) . '" decoding="async">';
	}
	$asset = (string) ( $a['asset'] ?? '' );
	return preg_match( '#^(batik|demo|img)/[a-z0-9-]+\.(jpg|png)$#', $asset ) ? bb3_image( 0, $size, $attr, $asset ) : '';
}

function bb3_render_hero_block( array $a ): string {
	$level = in_array( $a['level'] ?? 'h1', array( 'h1', 'h2' ), true ) ? $a['level'] : 'h1';
	$url   = bb3_url( $a['buttonUrl'] ?? '' );
	ob_start();
	?>
	<div class="bb3-sec alignfull">
		<section class="bb3-hero bb3-block-hero">
			<div class="bb3-hero__slide is-active">
				<div class="bb3-hero__bg" style="--bb3-focus:<?php echo esc_attr( bb3_sanitize_focus( $a['focus'] ?? '' ) ); ?>">
					<?php
					echo bb3_block_image( // phpcs:ignore WordPress.Security.EscapeOutput
						$a,
						'full',
						array(
							'alt'           => '',
							'sizes'         => '100vw',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
						)
					);
					?>
				</div>
				<div class="bb3-hero__caption">
					<?php if ( '' !== trim( (string) $a['eyebrow'] ) ) : ?>
						<p class="bb3-hero__eyebrow"><?php echo wp_kses_post( $a['eyebrow'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $a['title'] ) ) : ?>
						<<?php echo esc_html( $level ); ?> class="bb3-hero__title"><?php echo wp_kses_post( $a['title'] ); ?></<?php echo esc_html( $level ); ?>>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $a['buttonText'] ) && $url ) : ?>
						<a class="bb3-btn bb3-btn--hero" href="<?php echo esc_url( $url ); ?>"><?php echo wp_kses_post( $a['buttonText'] ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</div>
	<?php
	return (string) ob_get_clean();
}

function bb3_render_banner_block( array $a ): string {
	$url = bb3_url( $a['url'] ?? '' ) ?: bb3_collection_url();
	ob_start();
	?>
	<a class="bb3-promo" href="<?php echo esc_url( $url ); ?>">
		<?php echo bb3_block_image( $a, 'large', array( 'alt' => '', 'sizes' => '(max-width: 767px) 100vw, 33vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<span class="bb3-promo__text">
			<span class="bb3-promo__eyebrow"><?php echo wp_kses_post( $a['eyebrow'] ); ?></span>
			<span class="bb3-promo__title"><?php echo wp_kses_post( $a['title'] ); ?></span>
			<?php if ( '' !== trim( (string) $a['buttonText'] ) ) : ?>
				<span class="bb3-btn bb3-btn--light"><?php echo wp_kses_post( $a['buttonText'] ); ?></span>
			<?php endif; ?>
		</span>
	</a>
	<?php
	return (string) ob_get_clean();
}

function bb3_render_products_block( array $a ): string {
	$count = max( 1, min( 24, (int) $a['count'] ) );
	$cols  = max( 2, min( 5, (int) $a['columns'] ) );
	$args  = array( 'posts_per_page' => $count );
	$link  = bb3_collection_url();
	switch ( $a['source'] ) {
		case 'featured':
			$args['meta_key']   = '_bb_featured'; // phpcs:ignore WordPress.DB.SlowDBQuery
			$args['meta_value'] = '1'; // phpcs:ignore WordPress.DB.SlowDBQuery
			break;
		case 'category':
			$term = $a['category'] ? get_term_by( 'slug', $a['category'], 'bb_product_cat' ) : false;
			if ( $term ) {
				$args['tax_query'] = array( array( 'taxonomy' => 'bb_product_cat', 'terms' => $term->term_id ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
				$link              = (string) get_term_link( $term );
			}
			break;
		case 'latest':
			$args['orderby'] = array( 'date' => 'DESC', 'ID' => 'DESC' );
			$link            = add_query_arg( 'sort', 'latest', $link );
			break;
	}
	$query = bb3_products( $args );
	if ( ! $query->have_posts() ) {
		return bb3_is_block_preview() ? '<p class="bb3-block-empty">' . esc_html__( 'No published products match yet.', 'bali-batik-v3' ) . '</p>' : '';
	}
	$id = 'bb3-products-' . wp_unique_id();
	ob_start();
	echo '<div class="bb3-sec alignfull"><section class="bb3-section bb3-shell"' . ( empty( $a['gridOnly'] ) && '' !== trim( (string) $a['heading'] ) ? ' aria-labelledby="' . esc_attr( $id ) . '"' : '' ) . '>';
	if ( empty( $a['gridOnly'] ) && '' !== trim( wp_strip_all_tags( (string) $a['heading'] ) ) ) {
		bb3_section_heading( wp_strip_all_tags( (string) $a['heading'] ), wp_strip_all_tags( (string) $a['intro'] ), $a['linkText'] ? $link : '', wp_strip_all_tags( (string) $a['linkText'] ), $id );
	}
	echo '<div class="bb3-grid bb3-grid--cols" style="--bb3-cols:' . (int) $cols . '">';
	foreach ( $query->posts as $post ) {
		get_template_part( 'template-parts/product/card', null, array( 'id' => $post->ID ) );
	}
	echo '</div></section></div>';
	return (string) ob_get_clean();
}

function bb3_render_section_block( array $a ): string {
	$key = (string) ( $a['section'] ?? '' );
	if ( ! isset( bb3_home_section_labels()[ $key ] ) ) {
		return '';
	}
	ob_start();
	bb3_render_home_section( $key );
	$html = (string) ob_get_clean();
	if ( bb3_is_block_preview() && '' === trim( wp_strip_all_tags( $html, true ) ) && false === strpos( $html, '<img' ) ) {
		/* translators: %s: section name. */
		return '<p class="bb3-block-empty">' . esc_html( sprintf( __( '“%s” has no content yet. Fill it in under Customize → Bali Batik.', 'bali-batik-v3' ), bb3_home_section_labels()[ $key ] ) ) . '</p>';
	}
	return $html;
}

/** True while the block editor asks the server for a block preview. */
function bb3_is_block_preview(): bool {
	return ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || wp_is_json_request();
}

/** Editor data (categories, sections) is only needed inside the editor. */
add_action(
	'enqueue_block_editor_assets',
	function () {
		$cats = array( '' => __( '— Choose a category —', 'bali-batik-v3' ) );
		foreach ( bb3_categories( -1, false ) as $term ) {
			$cats[ $term->slug ] = $term->name;
		}
		wp_localize_script(
			'bb3-blocks',
			'bb3Blocks',
			array(
				'assets'     => BB3_URI . '/assets/',
				'sections'   => bb3_home_section_labels(),
				'categories' => $cats,
				'customize'  => admin_url( 'customize.php?autofocus[panel]=bb3_panel' ),
			)
		);
	}
);

/* ── Patterns ──────────────────────────────────────────────────────── */

function bb3_block_markup( string $name, array $attrs = array() ): string {
	return '<!-- wp:' . $name . ( $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '' ) . ' /-->';
}

function bb3_banner_row_markup(): string {
	$banners = array(
		array( 'demo/detail-5.jpg', __( 'For everyday wear', 'bali-batik-v3' ), __( 'Batik Shirts', 'bali-batik-v3' ), __( 'See Shirts', 'bali-batik-v3' ), '/koleksi/kategori/clothing/shirts/' ),
		array( 'demo/detail-6.jpg', __( 'Made to move', 'bali-batik-v3' ), __( 'Batik Dresses', 'bali-batik-v3' ), __( 'See Dresses', 'bali-batik-v3' ), '/koleksi/kategori/clothing/dresses/' ),
		array( 'demo/detail-2.jpg', __( 'Finish the look', 'bali-batik-v3' ), __( 'Scarves & Accessories', 'bali-batik-v3' ), __( 'See Scarves', 'bali-batik-v3' ), '/koleksi/kategori/accessories/scarves/' ),
	);
	$html    = "<!-- wp:columns {\"className\":\"bb3-banner-row\"} -->\n<div class=\"wp-block-columns bb3-banner-row\">";
	foreach ( $banners as [ $asset, $eyebrow, $title, $cta, $url ] ) {
		$html .= "<!-- wp:column -->\n<div class=\"wp-block-column\">" . bb3_block_markup(
			'bali-batik/banner',
			array(
				'asset'      => $asset,
				'eyebrow'    => $eyebrow,
				'title'      => $title,
				'buttonText' => $cta,
				'url'        => $url,
			)
		) . "</div>\n<!-- /wp:column -->\n\n";
	}
	return $html . "</div>\n<!-- /wp:columns -->";
}

function bb3_statement_markup(): string {
	return "<!-- wp:paragraph {\"className\":\"is-style-bb3-statement\"} -->\n<p class=\"is-style-bb3-statement\">" . esc_html__( 'Batik is more than a pattern. It is a story you can wear.', 'bali-batik-v3' ) . "</p>\n<!-- /wp:paragraph -->";
}

function bb3_about_markup(): string {
	return "<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"30%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:30%\"><!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html__( 'About [bb3_brand]', 'bali-batik-v3' ) . "</h2>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"70%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:70%\"><!-- wp:paragraph -->\n<p>" . esc_html( str_replace( '{brand}', '[bb3_brand]', bb3_defaults()['about_text'] ) ) . "</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->";
}

/** Full homepage built from blocks: every text can be edited on the canvas. */
function bb3_home_blocks_markup(): string {
	$parts = array(
		bb3_block_markup(
			'bali-batik/hero',
			array(
				'asset'      => 'batik/hero-batik-rack.jpg',
				'eyebrow'    => __( 'Batik, chosen with care', 'bali-batik-v3' ),
				'title'      => __( 'Find the Batik That Feels Like You', 'bali-batik-v3' ),
				'buttonText' => __( 'Explore the Collection', 'bali-batik-v3' ),
				'buttonUrl'  => '/koleksi/',
			)
		),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'categories' ) ),
		bb3_block_markup(
			'bali-batik/products',
			array(
				'heading'  => __( 'New Arrivals', 'bali-batik-v3' ),
				'intro'    => __( 'The latest pieces added to the collection.', 'bali-batik-v3' ),
				'source'   => 'latest',
				'count'    => 8,
				'columns'  => 4,
				'linkText' => __( 'View all new pieces', 'bali-batik-v3' ),
			)
		),
		bb3_banner_row_markup(),
		bb3_block_markup(
			'bali-batik/products',
			array(
				'heading' => __( 'Our Picks', 'bali-batik-v3' ),
				'intro'   => __( 'Pieces we keep coming back to, and think you will too.', 'bali-batik-v3' ),
				'source'  => 'featured',
				'count'   => 4,
				'columns' => 4,
			)
		),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'story' ) ),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'spotlight' ) ),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'occasions' ) ),
		bb3_statement_markup(),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'services' ) ),
		bb3_block_markup( 'bali-batik/section', array( 'section' => 'gallery' ) ),
		bb3_about_markup(),
	);
	return implode( "\n\n", $parts );
}

function bb3_register_patterns(): void {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}
	register_block_pattern_category( 'bali-batik', array( 'label' => __( 'Bali Batik', 'bali-batik-v3' ) ) );
	$patterns = array(
		'home'       => array( __( 'Bali Batik: full homepage', 'bali-batik-v3' ), bb3_home_blocks_markup() ),
		'banner-row' => array( __( 'Bali Batik: three promo banners', 'bali-batik-v3' ), bb3_banner_row_markup() ),
		'statement'  => array( __( 'Bali Batik: brand statement', 'bali-batik-v3' ), bb3_statement_markup() ),
		'about'      => array( __( 'Bali Batik: about text', 'bali-batik-v3' ), bb3_about_markup() ),
	);
	foreach ( $patterns as $slug => [ $title, $content ] ) {
		register_block_pattern(
			'bali-batik/' . $slug,
			array(
				'title'      => $title,
				'categories' => array( 'bali-batik' ),
				'content'    => $content,
			)
		);
	}
}
