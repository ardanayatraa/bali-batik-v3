<?php
/**
 * "Product" post type and hierarchical "Product Category" taxonomy.
 * Keys stay bb_product / bb_product_cat so V2 data can be read as-is.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'bb3_register_content_types' );

function bb3_register_content_types(): void {
	register_post_type(
		'bb_product',
		array(
			'labels'        => array(
				'name'                  => __( 'Products', 'bali-batik-v3' ),
				'singular_name'         => __( 'Product', 'bali-batik-v3' ),
				'add_new'               => __( 'Add Product', 'bali-batik-v3' ),
				'add_new_item'          => __( 'Add New Product', 'bali-batik-v3' ),
				'edit_item'             => __( 'Edit Product', 'bali-batik-v3' ),
				'new_item'              => __( 'New Product', 'bali-batik-v3' ),
				'view_item'             => __( 'View Product', 'bali-batik-v3' ),
				'search_items'          => __( 'Search Products', 'bali-batik-v3' ),
				'not_found'             => __( 'No products yet', 'bali-batik-v3' ),
				'not_found_in_trash'    => __( 'No products in the trash', 'bali-batik-v3' ),
				'all_items'             => __( 'All Products', 'bali-batik-v3' ),
				'featured_image'        => __( 'Main product photo', 'bali-batik-v3' ),
				'set_featured_image'    => __( 'Set main photo', 'bali-batik-v3' ),
				'remove_featured_image' => __( 'Remove main photo', 'bali-batik-v3' ),
				'menu_name'             => __( 'Products', 'bali-batik-v3' ),
			),
			'description'   => __( 'Batik fabrics and clothing in the catalogue.', 'bali-batik-v3' ),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-tag',
			'menu_position' => 5,
			'has_archive'   => 'koleksi',
			'rewrite'       => array(
				'slug'       => 'produk',
				'with_front' => false,
			),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'bb_product_cat',
		'bb_product',
		array(
			'labels'            => array(
				'name'              => __( 'Product Categories', 'bali-batik-v3' ),
				'singular_name'     => __( 'Product Category', 'bali-batik-v3' ),
				'search_items'      => __( 'Search Categories', 'bali-batik-v3' ),
				'all_items'         => __( 'All Categories', 'bali-batik-v3' ),
				'parent_item'       => __( 'Parent Category', 'bali-batik-v3' ),
				'parent_item_colon' => __( 'Parent Category:', 'bali-batik-v3' ),
				'edit_item'         => __( 'Edit Category', 'bali-batik-v3' ),
				'add_new_item'      => __( 'Add New Category', 'bali-batik-v3' ),
				'new_item_name'     => __( 'New Category Name', 'bali-batik-v3' ),
				'menu_name'         => __( 'Categories', 'bali-batik-v3' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => 'koleksi/kategori',
				'with_front'   => false,
				'hierarchical' => true,
			),
		)
	);
}

/** Refresh permalinks after the theme is switched on. */
add_action(
	'after_switch_theme',
	function () {
		bb3_register_content_types();
		flush_rewrite_rules();
	},
	5
);

/* ── Admin list columns ────────────────────────────────────────────── */

add_filter(
	'manage_bb_product_posts_columns',
	function ( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			if ( 'title' === $key ) {
				$new['bb3_thumb'] = '<span class="screen-reader-text">' . esc_html__( 'Photo', 'bali-batik-v3' ) . '</span>';
			}
			$new[ $key ] = $label;
			if ( 'title' === $key ) {
				$new['bb3_price']    = __( 'Price', 'bali-batik-v3' );
				$new['bb3_featured'] = __( 'Our Pick', 'bali-batik-v3' );
			}
		}
		return $new;
	}
);

add_action(
	'manage_bb_product_posts_custom_column',
	function ( $column, $post_id ) {
		if ( 'bb3_thumb' === $column ) {
			echo get_the_post_thumbnail( $post_id, array( 48, 48 ), array( 'style' => 'border-radius:4px;object-fit:cover;width:48px;height:48px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			if ( get_post_meta( $post_id, '_bb3_demo', true ) ) {
				echo '<span class="bb3-demo-flag">' . esc_html__( 'Demo', 'bali-batik-v3' ) . '</span>';
			}
		} elseif ( 'bb3_price' === $column ) {
			$price = bb3_meta( $post_id, 'price' );
			echo esc_html( '' === $price ? '—' : bb3_format_price( $price ) );
		} elseif ( 'bb3_featured' === $column ) {
			echo bb3_meta( $post_id, 'featured' ) ? '★' : '—';
		}
	},
	10,
	2
);

add_filter( 'manage_edit-bb_product_sortable_columns', fn( $c ) => $c + array( 'bb3_price' => 'bb3_price' ) );

add_action(
	'pre_get_posts',
	function ( WP_Query $q ) {
		if ( is_admin() && $q->is_main_query() && 'bb3_price' === $q->get( 'orderby' ) ) {
			$q->set( 'meta_key', '_bb_price' );
			$q->set( 'orderby', 'meta_value_num' );
		}
	}
);

add_filter(
	'manage_edit-bb_product_cat_columns',
	function ( $columns ) {
		$columns['bb3_order'] = __( 'Order', 'bali-batik-v3' );
		return $columns;
	}
);

add_filter(
	'manage_bb_product_cat_custom_column',
	function ( $content, $column, $term_id ) {
		return 'bb3_order' === $column ? (string) (int) get_term_meta( $term_id, 'bb_order', true ) : $content;
	},
	10,
	3
);

/** Editors manage products; theme settings stay with administrators. */
add_filter(
	'map_meta_cap',
	function ( $caps, $cap ) {
		if ( 'bb3_run_import' === $cap ) {
			return array( 'manage_options' );
		}
		return $caps;
	},
	10,
	2
);
