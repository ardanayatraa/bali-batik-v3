<?php
/**
 * Brand images generated from the current business name:
 * - default share image (Open Graph / Twitter, 1200 × 630, JPEG)
 * - favicon and touch icon (initials on the header colour), when no Site Icon is set
 *
 * Files are cached in uploads/bb3/ under a hash of the name, tagline, colours
 * and theme version, so a new name produces new images on the next page view.
 * Needs PHP GD with FreeType; without it the plain hero photo is used.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** [dir, url] of the generated-images folder. */
function bb3_brand_upload(): array {
	$u = wp_upload_dir( null, false );
	if ( ! empty( $u['error'] ) ) {
		return array();
	}
	return array( trailingslashit( $u['basedir'] ) . 'bb3', set_url_scheme( trailingslashit( $u['baseurl'] ) . 'bb3' ) );
}

function bb3_can_draw( string $text ): bool {
	return function_exists( 'imagecreatetruecolor' ) && function_exists( 'imagettftext' )
		&& ! preg_match( '/[^\x{0020}-\x{024F}\x{2013}-\x{2026}\x{20AC}]/u', $text ); // bundled font covers Latin only
}

function bb3_text_width( float $size, string $font, string $text ): int {
	$box = imagettfbbox( $size, 0, $font, $text );
	return $box ? abs( $box[2] - $box[0] ) : 0;
}

/**
 * Largest font size (max → min) at which $text fits in $width on at most $max_lines lines.
 *
 * @return array{0:int,1:string[]}
 */
function bb3_fit_lines( string $text, string $font, int $max, int $min, int $width, int $max_lines ): array {
	$words = preg_split( '/\s+/u', trim( $text ) ) ?: array( $text );
	for ( $size = $max; $size >= $min; $size -= 2 ) {
		$lines = array( '' );
		foreach ( $words as $word ) {
			$try = '' === end( $lines ) ? $word : end( $lines ) . ' ' . $word;
			if ( bb3_text_width( $size, $font, $try ) <= $width || '' === end( $lines ) ) {
				$lines[ count( $lines ) - 1 ] = $try;
			} else {
				$lines[] = $word;
			}
		}
		if ( count( $lines ) <= $max_lines && max( array_map( fn( $l ) => bb3_text_width( $size, $font, $l ), $lines ) ) <= $width ) {
			return array( $size, $lines );
		}
	}
	$lines = array_slice( $lines, 0, $max_lines );
	while ( '' !== $lines[ $max_lines - 1 ] && bb3_text_width( $min, $font, $lines[ $max_lines - 1 ] . '…' ) > $width ) {
		$lines[ $max_lines - 1 ] = mb_substr( $lines[ $max_lines - 1 ], 0, -1 );
	}
	$lines[ $max_lines - 1 ] .= '…';
	return array( $min, $lines );
}

function bb3_hex_rgb( string $hex ): array {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	return array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ) );
}

/** Remove older generated files of one kind. */
function bb3_prune( string $dir, string $pattern, string $keep ): void {
	foreach ( (array) glob( $dir . '/' . $pattern ) as $old ) {
		if ( $old && basename( $old ) !== $keep ) {
			wp_delete_file( $old );
		}
	}
}

/** Share image with the current name and tagline. */
function bb3_generated_og_image(): ?array {
	$brand  = bb3_brand();
	$tag    = bb3_tagline();
	$accent = sanitize_hex_color( (string) bb3_mod( 'color_accent' ) ) ?: '#2f8548';
	$dir    = bb3_brand_upload();
	if ( ! $dir || ! bb3_can_draw( $brand . ' ' . $tag ) ) {
		return null;
	}
	$name = 'og-' . substr( md5( implode( '|', array( $brand, $tag, $accent, BB3_VERSION, get_locale() ) ) ), 0, 12 ) . '.jpg';
	if ( ! file_exists( $dir[0] . '/' . $name ) && ! bb3_render_og( $dir[0], $name, $brand, $tag, $accent ) ) {
		return null;
	}
	return array(
		'url'    => $dir[1] . '/' . $name,
		'width'  => 1200,
		'height' => 630,
		'type'   => 'image/jpeg',
		'alt'    => '' !== $tag ? $brand . ' – ' . $tag : $brand,
	);
}

function bb3_render_og( string $dir, string $name, string $brand, string $tag, string $accent ): bool {
	if ( ! wp_mkdir_p( $dir ) ) {
		return false;
	}
	$bold   = BB3_DIR . '/assets/fonts/poppins-700.ttf';
	$medium = BB3_DIR . '/assets/fonts/poppins-500.ttf';
	$w      = 1200;
	$h      = 630;
	$im     = imagecreatetruecolor( $w, $h );
	$photo  = function_exists( 'imagecreatefromjpeg' ) ? @imagecreatefromjpeg( BB3_DIR . '/assets/batik/hero-batik-fabric.jpg' ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors
	if ( $photo ) {
		$sw    = imagesx( $photo );
		$sh    = imagesy( $photo );
		$scale = max( $w / $sw, $h / $sh );
		$cw    = (int) round( $w / $scale );
		$ch    = (int) round( $h / $scale );
		imagecopyresampled( $im, $photo, 0, 0, (int) ( ( $sw - $cw ) / 2 ), (int) ( ( $sh - $ch ) / 2 ), $w, $h, $cw, $ch );
		imagedestroy( $photo );
	} else {
		imagefill( $im, 0, 0, imagecolorallocate( $im, 36, 37, 44 ) );
	}
	imagealphablending( $im, true );
	imagefilledrectangle( $im, 0, 0, $w, $h, imagecolorallocatealpha( $im, 17, 19, 29, 50 ) );

	$white            = imagecolorallocate( $im, 255, 255, 255 );
	[ $r, $g, $b ]    = bb3_hex_rgb( $accent );
	$green            = imagecolorallocate( $im, $r, $g, $b );
	[ $bs, $blines ]  = bb3_fit_lines( mb_strtoupper( $brand ), $bold, 92, 44, 1020, 2 );
	[ $ts, $tlines ]  = '' !== $tag ? bb3_fit_lines( $tag, $medium, 40, 26, 1020, 2 ) : array( 0, array() );
	$button           = mb_strtoupper( __( 'View collection', 'bali-batik-v3' ) );
	$blh              = (int) round( $bs * 1.12 );
	$tlh              = (int) round( $ts * 1.35 );
	$total            = count( $blines ) * $blh + ( $tlines ? 26 + count( $tlines ) * $tlh : 0 ) + 48 + 64;
	$y                = (int) max( 40, ( $h - $total ) / 2 );
	$x                = 90;

	foreach ( $blines as $line ) {
		imagettftext( $im, $bs, 0, $x, $y + (int) round( $bs * 0.92 ), $white, $bold, $line );
		$y += $blh;
	}
	if ( $tlines ) {
		$y += 26;
		foreach ( $tlines as $line ) {
			imagettftext( $im, $ts, 0, $x + 2, $y + (int) round( $ts * 0.95 ), $white, $medium, $line );
			$y += $tlh;
		}
	}
	$y     += 48;
	$bw     = bb3_text_width( 20, $bold, $button ) + 64;
	imagefilledrectangle( $im, $x + 2, $y, $x + 2 + $bw, $y + 64, $green );
	imagettftext( $im, 20, 0, $x + 34, $y + 42, $white, $bold, $button );

	$ok = imagejpeg( $im, $dir . '/' . $name, 84 );
	imagedestroy( $im );
	if ( $ok ) {
		bb3_prune( $dir, 'og-*.jpg', $name );
	}
	return $ok && file_exists( $dir . '/' . $name );
}

/** Up to two initials from the business name, e.g. "Bali Batik Product" → "BB". */
function bb3_brand_initials(): string {
	$words    = array_values( array_filter( preg_split( '/[\s\-_]+/u', bb3_brand() ) ?: array() ) );
	$initials = '';
	foreach ( array_slice( $words, 0, 2 ) as $word ) {
		$initials .= mb_strtoupper( mb_substr( $word, 0, 1 ) );
	}
	return '' !== $initials ? $initials : 'B';
}

/** Square icon with the initials on the header colour. */
function bb3_generated_icon( int $size = 512 ): ?array {
	$initials = bb3_brand_initials();
	$dark     = sanitize_hex_color( (string) bb3_mod( 'color_dark' ) ) ?: '#111111';
	$dir      = bb3_brand_upload();
	if ( ! $dir || ! bb3_can_draw( $initials ) || ! function_exists( 'imagepng' ) ) {
		return null;
	}
	$hash = substr( md5( $initials . '|' . $dark . '|' . BB3_VERSION ), 0, 10 );
	$name = 'icon-' . $size . '-' . $hash . '.png';
	$file = $dir[0] . '/' . $name;
	if ( ! file_exists( $file ) ) {
		if ( ! wp_mkdir_p( $dir[0] ) ) {
			return null;
		}
		$font          = BB3_DIR . '/assets/fonts/poppins-700.ttf';
		$im            = imagecreatetruecolor( $size, $size );
		[ $r, $g, $b ] = bb3_hex_rgb( $dark );
		imagefill( $im, 0, 0, imagecolorallocate( $im, $r, $g, $b ) );
		$white = imagecolorallocate( $im, 255, 255, 255 );
		$fs    = $size * ( mb_strlen( $initials ) > 1 ? 0.38 : 0.5 );
		$box   = imagettfbbox( $fs, 0, $font, $initials );
		$tw    = abs( $box[2] - $box[0] );
		$th    = abs( $box[7] - $box[1] );
		imagettftext( $im, $fs, 0, (int) ( ( $size - $tw ) / 2 - $box[0] ), (int) ( ( $size + $th ) / 2 - $box[1] ), $white, $font, $initials );
		$ok = imagepng( $im, $file );
		imagedestroy( $im );
		if ( ! $ok ) {
			return null;
		}
		bb3_prune( $dir[0], 'icon-' . $size . '-*.png', $name );
	}
	return array(
		'url'    => $dir[1] . '/' . $name,
		'width'  => $size,
		'height' => $size,
		'type'   => 'image/png',
		'alt'    => bb3_brand(),
	);
}

/** Favicon + touch icon from the initials while no Site Icon is chosen. */
add_action(
	'wp_head',
	function () {
		if ( has_site_icon() ) {
			return;
		}
		$small = bb3_generated_icon( 64 );
		$touch = bb3_generated_icon( 180 );
		$large = bb3_generated_icon( 512 );
		if ( $small ) {
			echo '<link rel="icon" href="' . esc_url( $small['url'] ) . '" sizes="32x32" type="image/png">' . "\n";
		}
		if ( $large ) {
			echo '<link rel="icon" href="' . esc_url( $large['url'] ) . '" sizes="512x512" type="image/png">' . "\n";
		}
		if ( $touch ) {
			echo '<link rel="apple-touch-icon" href="' . esc_url( $touch['url'] ) . '">' . "\n";
		}
	},
	99
);
