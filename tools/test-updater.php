<?php
/** Updater contract checks using WordPress boundary stubs; no site is modified. */
define( 'ABSPATH', __DIR__ . '/' );
define( 'HOUR_IN_SECONDS', 3600 );
$options = array();
$cache = array();
$filters = array();
$requests = 0;
preg_match( '/^Version:\s*([0-9.]+)\s*$/m', file_get_contents( __DIR__ . '/../bali-batik-v3/style.css' ), $version_match );
$installed_version = $version_match[1];
$future_parts = array_map( 'intval', explode( '.', $installed_version ) );
++ $future_parts[2];
$future_version = implode( '.', $future_parts );
$future_url = 'https://github.com/ardanayatraa/bali-batik-v3/releases';
$payload = array(
	'tag_name' => 'v' . $future_version,
	'html_url' => $future_url . '/tag/v' . $future_version,
	'assets' => array( array(
		'name' => 'bali-batik-v3-' . $future_version . '.zip',
		'browser_download_url' => $future_url . '/download/v' . $future_version . '/bali-batik-v3-' . $future_version . '.zip',
		'digest' => 'sha256:' . str_repeat( 'a', 64 ),
	) ),
);
function get_option( $key, $default = false ) { global $options; return $options[$key] ?? $default; }
function get_site_transient( $key ) { global $cache; return $cache[$key] ?? false; }
function set_site_transient( $key, $value, $ttl ) { global $cache; $cache[$key] = $value; }
function add_filter( $key, $callback, ...$args ) { global $filters; $filters[$key] = $callback; }
function add_action( ...$args ) {}
function __( $text, $domain ) { return $text; }
function home_url( $path ) { return 'https://example.org' . $path; }
function wp_http_validate_url( $url ) { return filter_var( $url, FILTER_VALIDATE_URL ); }
function wp_get_theme( $slug ) {
	return new class {
		function exists() { return true; }
		function get( $key ) { global $installed_version; return $installed_version; }
	};
}
function wp_safe_remote_get( $url, $args ) {
	global $payload, $requests;
	++$requests;
	check( 'https://api.github.com/repos/ardanayatraa/bali-batik-v3/releases/latest' === $url, 'official GitHub endpoint' );
	return array( 'response' => array( 'code' => 200 ), 'body' => json_encode( $payload ) );
}
function wp_remote_retrieve_response_code( $response ) { return $response['response']['code']; }
function wp_remote_retrieve_body( $response ) { return $response['body']; }
function is_wp_error( $response ) { return false; }
function check( $condition, $message ) {
	if ( ! $condition ) { fwrite( STDERR, "FAIL: $message\n" ); exit( 1 ); }
}
require __DIR__ . '/../bali-batik-v3/inc/updater.php';

check( array( 'github', 'ardanayatraa/bali-batik-v3' ) === bb3_update_source(), 'fresh install uses official source' );
check( bb3_auto_update_enabled(), 'fresh install enables automatic updates' );
$options['bb3_update_source'] = '';
check( array( '', '' ) === bb3_update_source(), 'explicit opt-out is retained' );
$options['bb3_update_source'] = 'http://example.org/update.json';
check( array( '', '' ) === bb3_update_source(), 'insecure manifest rejected' );
unset( $options['bb3_update_source'] );

$release = bb3_remote_release( true );
check( $future_version === $release['version'], 'release tag parsed' );
check( str_repeat( 'a', 64 ) === $release['sha256'], 'GitHub asset checksum parsed' );
bb3_remote_release();
check( 1 === $requests, 'cached release avoids repeat network calls' );
$result = $filters['pre_set_site_transient_update_themes']( (object) array() );
check( $future_version === $result->response[BB3_SLUG]['new_version'], 'WordPress is offered a newer version' );
check( true === $filters['auto_update_theme']( false, (object) array( 'theme' => BB3_SLUG ) ), 'theme update runs automatically' );
$options['bb3_auto_update'] = 0;
check( false === $filters['auto_update_theme']( true, (object) array( 'theme' => BB3_SLUG ) ), 'owner can disable automatic installation' );
check( true === $filters['auto_update_theme']( true, (object) array( 'theme' => 'other-theme' ) ), 'other themes retain their setting' );

unset( $payload['assets'][0]['digest'] );
$payload['body'] = 'sha256: ' . str_repeat( 'b', 64 );
check( str_repeat( 'b', 64 ) === bb3_remote_release( true )['sha256'], 'release-note checksum fallback' );
$payload['assets'][0]['browser_download_url'] = 'http://example.org/theme.zip';
check( null === bb3_remote_release( true ), 'insecure package rejected' );
$payload['assets'] = array();
check( null === bb3_remote_release( true ), 'release without installable asset rejected' );

// Optional: validate the actual published GitHub API response after deployment.
if ( isset( $argv[1] ) ) {
	$payload = json_decode( file_get_contents( $argv[1] ), true, 512, JSON_THROW_ON_ERROR );
	$release = bb3_remote_release( true );
	check( null !== $release && $installed_version === $release['version'], 'published current version is readable' );
	check( '' !== $release['sha256'], 'published release provides a checksum' );
	$result = $filters['pre_set_site_transient_update_themes']( (object) array() );
	check( ! isset( $result->response[BB3_SLUG] ), 'installed current version is not offered again' );
	check( isset( $result->no_update[BB3_SLUG] ), 'current theme retains auto-update controls' );
}
echo "Updater checks passed.\n";
