<?php
/**
 * Collection page: search (?q=), sort (?sort=), 12 per page.
 * Categories use their own URLs (/koleksi/kategori/<slug>/), so links stay shareable.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

function bb3_sort_options(): array {
	return array(
		'default'    => __( 'Featured', 'bali-batik-v3' ),
		'latest'     => __( 'Newest', 'bali-batik-v3' ),
		'name'       => __( 'Name (A–Z)', 'bali-batik-v3' ),
		'price-asc'  => __( 'Price: low to high', 'bali-batik-v3' ),
		'price-desc' => __( 'Price: high to low', 'bali-batik-v3' ),
	);
}

/** Validated catalogue parameters; invalid values fall back to safe defaults. */
function bb3_catalog_params(): array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$q    = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	$sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'default';
	// phpcs:enable
	return array(
		'q'    => mb_substr( trim( $q ), 0, 80 ),
		'sort' => array_key_exists( $sort, bb3_sort_options() ) ? $sort : 'default',
	);
}

function bb3_is_catalog(): bool {
	return is_post_type_archive( 'bb_product' ) || is_tax( 'bb_product_cat' );
}

add_action(
	'pre_get_posts',
	function ( WP_Query $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( ! $query->is_post_type_archive( 'bb_product' ) && ! $query->is_tax( 'bb_product_cat' ) ) {
			return;
		}
		$params = bb3_catalog_params();
		$query->set( 'post_type', 'bb_product' );
		$query->set( 'post_status', 'publish' );
		$query->set( 'posts_per_page', 12 );
		if ( '' !== $params['q'] ) {
			$query->set( 's', $params['q'] );
		}
		switch ( $params['sort'] ) {
			case 'latest':
				$query->set( 'orderby', array( 'date' => 'DESC', 'ID' => 'DESC' ) );
				break;
			case 'name':
				$query->set( 'orderby', array( 'title' => 'ASC' ) );
				break;
			case 'price-asc':
			case 'price-desc':
				$query->set(
					'meta_query',
					array(
						'relation'  => 'OR',
						'bb3_price' => array(
							'key'     => '_bb_price',
							'compare' => 'EXISTS',
							'type'    => 'NUMERIC',
						),
						array(
							'key'     => '_bb_price',
							'compare' => 'NOT EXISTS',
						),
					)
				);
				$query->set(
					'orderby',
					array(
						'bb3_price' => 'price-asc' === $params['sort'] ? 'ASC' : 'DESC',
						'title'     => 'ASC',
					)
				);
				break;
			default:
				$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		}
	}
);

/** Old-style product searches (/?s=…&post_type=bb_product) go to the collection. */
add_action(
	'template_redirect',
	function () {
		if ( is_search() && 'bb_product' === get_query_var( 'post_type' ) ) {
			wp_safe_redirect( add_query_arg( 'q', rawurlencode( get_search_query( false ) ), bb3_collection_url() ), 301 );
			exit;
		}
	}
);

/** Build a catalogue URL that keeps the current search and sort. */
function bb3_catalog_link( string $base, array $override = array() ): string {
	$params = array_merge( bb3_catalog_params(), $override );
	$args   = array();
	if ( '' !== $params['q'] ) {
		$args['q'] = $params['q'];
	}
	if ( 'default' !== $params['sort'] ) {
		$args['sort'] = $params['sort'];
	}
	return $args ? add_query_arg( array_map( 'rawurlencode', $args ), $base ) : $base;
}

/** Nested category list for the sidebar (three levels). */
function bb3_category_tree_html( int $parent = 0, int $depth = 0 ): string {
	if ( $depth > 2 ) {
		return '';
	}
	$terms = bb3_categories( $parent );
	if ( ! $terms ) {
		return '';
	}
	$current = is_tax( 'bb_product_cat' ) ? get_queried_object_id() : 0;
	$open    = $current ? array_merge( array( $current ), get_ancestors( $current, 'bb_product_cat' ) ) : array();
	$html    = '<ul class="bb3-tree bb3-tree--' . $depth . '">';
	foreach ( $terms as $term ) {
		$is_current = $term->term_id === $current;
		$children   = in_array( $term->term_id, $open, true ) || 0 === $depth ? bb3_category_tree_html( $term->term_id, $depth + 1 ) : '';
		$html      .= '<li' . ( in_array( $term->term_id, $open, true ) ? ' class="is-open"' : '' ) . '><a href="' . esc_url( bb3_catalog_link( (string) get_term_link( $term ) ) ) . '"' . ( $is_current ? ' aria-current="page"' : '' ) . '>' . esc_html( $term->name ) . ' <span class="bb3-tree__count">' . (int) $term->count . '</span></a>' . $children . '</li>';
	}
	return $html . '</ul>';
}
