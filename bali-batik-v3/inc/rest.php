<?php
/**
 * Public read-only endpoint for the favorites dialog:
 * GET /wp-json/bb3/v1/products?ids=1,2,3 → published products only.
 * Saved IDs that are missing from the reply are shown as "no longer available".
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'bb3/v1',
			'/products',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'args'                => array(
					'ids' => array(
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => fn( $value ) => implode( ',', array_slice( array_filter( array_map( 'absint', explode( ',', (string) $value ) ) ), 0, 60 ) ),
					),
				),
				'callback'            => function ( WP_REST_Request $request ) {
					$ids = array_filter( array_map( 'absint', explode( ',', (string) $request['ids'] ) ) );
					if ( ! $ids ) {
						return rest_ensure_response( array() );
					}
					$posts = get_posts(
						array(
							'post_type'      => 'bb_product',
							'post_status'    => 'publish',
							'post__in'       => $ids,
							'posts_per_page' => count( $ids ),
							'orderby'        => 'post__in',
							'no_found_rows'  => true,
						)
					);
					$out   = array();
					foreach ( $posts as $post ) {
						$p     = bb3_prices( $post->ID );
						$out[] = array(
							'id'    => $post->ID,
							'title' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ),
							'url'   => get_permalink( $post ),
							'image' => (string) get_the_post_thumbnail_url( $post, 'thumbnail' ),
							'price' => null === $p['price'] ? __( 'Price on request', 'bali-batik-v3' ) : bb3_format_price( $p['price'] ),
						);
					}
					$response = rest_ensure_response( $out );
					$response->header( 'Cache-Control', 'public, max-age=300' );
					return $response;
				},
			)
		);
	}
);
