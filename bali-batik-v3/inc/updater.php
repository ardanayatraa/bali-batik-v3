<?php
/**
 * Theme updates from a trusted source, shown in Dashboard → Updates like any
 * other theme and installed automatically when enabled.
 *
 * Update source (Appearance → Bali Batik Setup, or the BB3_UPDATE_SOURCE
 * constant in wp-config.php). Out of the box it is the official GitHub
 * channel BB3_DEFAULT_UPDATE_SOURCE, so new installs update without setup:
 * - "owner/repo" → latest GitHub Release; its asset bali-batik-v3-*.zip is installed.
 * - "https://…/bali-batik-v3.json" → manifest:
 *   {"version":"3.2.1","package":"https://…/bali-batik-v3-3.2.1.zip",
 *    "sha256":"…","url":"https://…/changelog","requires":"6.4","requires_php":"8.0"}
 *
 * Only HTTPS packages are accepted. When a SHA-256 is published (manifest field,
 * GitHub asset digest, or "sha256: …" in the release notes) the download must match it.
 * Products, settings and Customizer values are kept on update.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

const BB3_SLUG = 'bali-batik-v3';

/** Official release channel, used until the site owner sets another source (or clears it). */
const BB3_DEFAULT_UPDATE_SOURCE = 'ardanayatraa/bali-batik-v3';

/** @return array{0:string,1:string} [type github|json|'', value] */
function bb3_update_source(): array {
	if ( defined( 'BB3_UPDATE_SOURCE' ) ) {
		$value = (string) BB3_UPDATE_SOURCE;
	} else {
		$saved = get_option( 'bb3_update_source', null ); // null = never saved → official channel
		$value = null === $saved ? BB3_DEFAULT_UPDATE_SOURCE : (string) $saved;
	}
	$value = trim( $value );
	if ( preg_match( '#^[A-Za-z0-9_.\-]+/[A-Za-z0-9_.\-]+$#', $value ) ) {
		return array( 'github', $value );
	}
	if ( str_starts_with( $value, 'https://' ) && wp_http_validate_url( $value ) ) {
		return array( 'json', $value );
	}
	return array( '', '' );
}

/** Enabled by default, including the setup checkbox, unless the owner opts out. */
function bb3_auto_update_enabled(): bool {
	if ( defined( 'BB3_AUTO_UPDATE' ) ) {
		return (bool) BB3_AUTO_UPDATE;
	}
	return (bool) get_option( 'bb3_auto_update', 1 );
}

/**
 * Latest release from the configured source (cached 6 hours).
 *
 * @return array|null {version, package, url, sha256, requires, requires_php}
 */
function bb3_remote_release( bool $force = false ): ?array {
	[ $type, $source ] = bb3_update_source();
	if ( '' === $type ) {
		return null;
	}
	$key    = 'bb3_release_' . md5( $type . $source );
	$cached = $force ? false : get_site_transient( $key );
	if ( is_array( $cached ) ) {
		return $cached['release'] ?? null;
	}

	$release = null;
	$error   = '';
	$args    = array(
		'timeout' => 15,
		'headers' => array(
			'Accept'     => 'github' === $type ? 'application/vnd.github+json' : 'application/json',
			'User-Agent' => 'BaliBatikV3/' . wp_get_theme( BB3_SLUG )->get( 'Version' ) . '; ' . home_url( '/' ),
		),
	);
	$url      = 'github' === $type ? 'https://api.github.com/repos/' . $source . '/releases/latest' : $source;
	$response = wp_safe_remote_get( $url, $args );
	$code     = (int) wp_remote_retrieve_response_code( $response );
	$data     = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( is_wp_error( $response ) ) {
		$error = $response->get_error_message();
	} elseif ( 200 !== $code || ! is_array( $data ) ) {
		/* translators: %d: HTTP status. */
		$error = sprintf( __( 'Update source answered with HTTP %d.', 'bali-batik-v3' ), $code );
	} elseif ( 'github' === $type ) {
		$asset = null;
		foreach ( (array) ( $data['assets'] ?? array() ) as $item ) {
			if ( preg_match( '/^bali-batik-v3.*\.zip$/i', (string) ( $item['name'] ?? '' ) ) ) {
				$asset = $item;
				break;
			}
		}
		$sha = '';
		if ( $asset && preg_match( '/^sha256:([a-f0-9]{64})$/i', (string) ( $asset['digest'] ?? '' ), $m ) ) {
			$sha = $m[1];
		} elseif ( preg_match( '/sha-?256[:\s`]+([a-f0-9]{64})/i', (string) ( $data['body'] ?? '' ), $m ) ) {
			$sha = $m[1];
		}
		$release = $asset ? array(
			'version'      => ltrim( (string) ( $data['tag_name'] ?? '' ), 'vV' ),
			'package'      => (string) $asset['browser_download_url'],
			'url'          => (string) ( $data['html_url'] ?? '' ),
			'sha256'       => $sha,
			'requires'     => '',
			'requires_php' => '',
		) : null;
		if ( ! $asset ) {
			$error = __( 'The latest GitHub release has no bali-batik-v3 ZIP attached.', 'bali-batik-v3' );
		}
	} else {
		$release = array(
			'version'      => (string) ( $data['version'] ?? '' ),
			'package'      => (string) ( $data['package'] ?? '' ),
			'url'          => (string) ( $data['url'] ?? '' ),
			'sha256'       => (string) ( $data['sha256'] ?? '' ),
			'requires'     => (string) ( $data['requires'] ?? '' ),
			'requires_php' => (string) ( $data['requires_php'] ?? '' ),
		);
	}

	if ( $release && ( ! preg_match( '/^\d+(\.\d+){1,3}$/', $release['version'] ) || ! str_starts_with( $release['package'], 'https://' ) || ( '' !== $release['sha256'] && ! preg_match( '/^[a-f0-9]{64}$/i', $release['sha256'] ) ) ) ) {
		$release = null;
		$error   = __( 'The release information is incomplete or not served over HTTPS.', 'bali-batik-v3' );
	}

	set_site_transient(
		$key,
		array(
			'release' => $release,
			'error'   => $error,
			'checked' => time(),
		),
		$release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS
	);
	return $release;
}

/** Last check result for the settings screen. */
function bb3_update_status(): array {
	[ $type, $source ] = bb3_update_source();
	$cached            = '' === $type ? false : get_site_transient( 'bb3_release_' . md5( $type . $source ) );
	return is_array( $cached ) ? $cached : array();
}

/** Offer the update to WordPress (Dashboard → Updates, Themes screen, auto-updates). */
add_filter(
	'pre_set_site_transient_update_themes',
	function ( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}
		$theme = wp_get_theme( BB3_SLUG );
		if ( ! $theme->exists() ) {
			return $transient;
		}
		$current = (string) $theme->get( 'Version' );
		$release = bb3_remote_release();
		$item    = array(
			'theme'        => BB3_SLUG,
			'new_version'  => $current,
			'url'          => '',
			'package'      => '',
			'requires'     => '',
			'requires_php' => '',
		);
		if ( $release && version_compare( $release['version'], $current, '>' ) ) {
			$transient->response[ BB3_SLUG ] = array_merge(
				$item,
				array(
					'new_version'  => $release['version'],
					'url'          => $release['url'],
					'package'      => $release['package'],
					'requires'     => $release['requires'],
					'requires_php' => $release['requires_php'],
				)
			);
			unset( $transient->no_update[ BB3_SLUG ] );
		} else {
			unset( $transient->response[ BB3_SLUG ] );
			$transient->no_update[ BB3_SLUG ] = $item; // lets WordPress show the auto-update toggle
		}
		return $transient;
	}
);

/** Install automatically when the owner turned it on. */
add_filter(
	'auto_update_theme',
	function ( $update, $item ) {
		if ( isset( $item->theme ) && BB3_SLUG === $item->theme && '' !== bb3_update_source()[0] ) {
			return bb3_auto_update_enabled();
		}
		return $update;
	},
	10,
	2
);

/** Check the published SHA-256 before WordPress unpacks the package. */
add_filter(
	'upgrader_pre_download',
	function ( $reply, $package ) {
		$release = bb3_remote_release();
		if ( false !== $reply || ! $release || $package !== $release['package'] || '' === $release['sha256'] ) {
			return $reply;
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$file = download_url( $package, 300 );
		if ( is_wp_error( $file ) ) {
			return $file;
		}
		if ( ! hash_equals( strtolower( $release['sha256'] ), (string) hash_file( 'sha256', $file ) ) ) {
			wp_delete_file( $file );
			return new WP_Error( 'bb3_checksum', __( 'Bali Batik update stopped: the downloaded file does not match its published SHA-256 checksum.', 'bali-batik-v3' ) );
		}
		return $file;
	},
	10,
	2
);

/** Keep the folder name bali-batik-v3 whatever the ZIP's top folder is called. */
add_filter(
	'upgrader_source_selection',
	function ( $source, $remote_source, $upgrader, $extra = array() ) {
		if ( ( $extra['theme'] ?? '' ) !== BB3_SLUG || ! is_string( $source ) ) {
			return $source;
		}
		$target = trailingslashit( $remote_source ) . BB3_SLUG . '/';
		if ( untrailingslashit( $source ) === untrailingslashit( $target ) ) {
			return $source;
		}
		global $wp_filesystem;
		return ( $wp_filesystem && $wp_filesystem->move( $source, $target, true ) ) ? $target : $source;
	},
	10,
	4
);

/* ── Settings (Appearance → Bali Batik Setup) ──────────────────────── */

function bb3_render_update_settings(): void {
	$theme      = wp_get_theme( BB3_SLUG );
	$status     = bb3_update_status();
	$release    = $status['release'] ?? null;
	$locked     = defined( 'BB3_UPDATE_SOURCE' );
	$source     = $locked ? (string) BB3_UPDATE_SOURCE : (string) get_option( 'bb3_update_source', BB3_DEFAULT_UPDATE_SOURCE );
	$auto_fixed = defined( 'BB3_AUTO_UPDATE' );
	echo '<h2 id="bb3-updates">' . esc_html__( 'Automatic updates', 'bali-batik-v3' ) . '</h2>';
	echo '<p style="max-width:720px">' . esc_html__( 'When a new version of the theme is published, WordPress shows it under Dashboard → Updates and, with automatic updates on, installs it by itself (WordPress checks twice a day). Products, pages and Customizer settings are kept.', 'bali-batik-v3' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	wp_nonce_field( 'bb3_updates' );
	echo '<input type="hidden" name="action" value="bb3_updates">';
	echo '<table class="form-table" role="presentation"><tbody>';
	echo '<tr><th scope="row"><label for="bb3_update_source">' . esc_html__( 'Update source', 'bali-batik-v3' ) . '</label></th><td>';
	echo '<input type="text" class="regular-text code" id="bb3_update_source" name="bb3_update_source" value="' . esc_attr( $source ) . '" placeholder="owner/repository" ' . disabled( $locked, true, false ) . '>';
	echo '<p class="description">' . esc_html__( 'GitHub repository (owner/repository) whose Releases contain bali-batik-v3-x.y.z.zip, or the HTTPS address of a bali-batik-v3.json manifest. Default: the official channel ardanayatraa/bali-batik-v3. Leave empty to turn update checks off.', 'bali-batik-v3' );
	if ( $locked ) {
		echo ' ' . esc_html__( 'Set in wp-config.php (BB3_UPDATE_SOURCE).', 'bali-batik-v3' );
	}
	echo '</p></td></tr>';
	echo '<tr><th scope="row">' . esc_html__( 'Install automatically', 'bali-batik-v3' ) . '</th><td><label><input type="checkbox" name="bb3_auto_update" value="1" ' . checked( bb3_auto_update_enabled(), true, false ) . ' ' . disabled( $auto_fixed, true, false ) . '> ' . esc_html__( 'Install new theme versions automatically', 'bali-batik-v3' ) . '</label></td></tr>';
	echo '<tr><th scope="row">' . esc_html__( 'Status', 'bali-batik-v3' ) . '</th><td>';
	/* translators: %s: version. */
	echo '<p>' . esc_html( sprintf( __( 'Installed version: %s', 'bali-batik-v3' ), $theme->get( 'Version' ) ) ) . '</p>';
	if ( $release ) {
		/* translators: 1: version, 2: "verified with SHA-256" note. */
		echo '<p>' . esc_html( sprintf( __( 'Latest published version: %1$s%2$s', 'bali-batik-v3' ), $release['version'], $release['sha256'] ? ' · ' . __( 'checksum published', 'bali-batik-v3' ) : '' ) ) . '</p>';
	}
	if ( ! empty( $status['error'] ) ) {
		echo '<p style="color:#b32d2e">' . esc_html( $status['error'] ) . '</p>';
	}
	if ( ! empty( $status['checked'] ) ) {
		/* translators: %s: time ago. */
		echo '<p class="description">' . esc_html( sprintf( __( 'Last checked %s ago.', 'bali-batik-v3' ), human_time_diff( (int) $status['checked'] ) ) ) . '</p>';
	}
	echo '</td></tr></tbody></table>';
	echo '<p><button class="button button-primary" name="bb3_do" value="save">' . esc_html__( 'Save', 'bali-batik-v3' ) . '</button> <button class="button" name="bb3_do" value="check">' . esc_html__( 'Save and check now', 'bali-batik-v3' ) . '</button></p>';
	echo '</form>';
}

add_action(
	'admin_post_bb3_updates',
	function () {
		if ( ! current_user_can( 'update_themes' ) || ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'bali-batik-v3' ), 403 );
		}
		check_admin_referer( 'bb3_updates' );
		if ( ! defined( 'BB3_UPDATE_SOURCE' ) ) {
			$value = isset( $_POST['bb3_update_source'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['bb3_update_source'] ) ) ) : '';
			if ( '' !== $value && ! preg_match( '#^[A-Za-z0-9_.\-]+/[A-Za-z0-9_.\-]+$#', $value ) && ! str_starts_with( $value, 'https://' ) ) {
				$value = '';
			}
			update_option( 'bb3_update_source', '' !== $value && str_starts_with( $value, 'https://' ) ? esc_url_raw( $value, array( 'https' ) ) : $value, false );
		}
		if ( ! defined( 'BB3_AUTO_UPDATE' ) ) {
			update_option( 'bb3_auto_update', empty( $_POST['bb3_auto_update'] ) ? 0 : 1, false );
		}
		if ( isset( $_POST['bb3_do'] ) && 'check' === $_POST['bb3_do'] ) {
			bb3_remote_release( true );
			delete_site_transient( 'update_themes' );
			wp_update_themes();
		}
		wp_safe_redirect( admin_url( 'themes.php?page=bb3-setup#bb3-updates' ) );
		exit;
	}
);
