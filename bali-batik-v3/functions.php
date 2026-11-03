<?php
/**
 * Bali Batik V3 bootstrap.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

define( 'BB3_VERSION', '3.2.0' );
define( 'BB3_DIR', get_template_directory() );
define( 'BB3_URI', get_template_directory_uri() );

foreach ( array(
	'defaults',
	'helpers',
	'icons',
	'setup',
	'post-types',
	'meta-boxes',
	'catalog',
	'customizer',
	'customizer-live',
	'blocks',
	'brand-images',
	'updater',
	'seo',
	'rest',
	'demo-import',
) as $bb3_file ) {
	require_once BB3_DIR . '/inc/' . $bb3_file . '.php';
}
