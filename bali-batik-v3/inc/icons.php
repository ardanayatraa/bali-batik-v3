<?php
/**
 * Inline SVG icons on a 24 px grid.
 *
 * UI icons follow Lucide (https://lucide.dev, ISC licence — see
 * assets/icons/LICENSE-lucide.txt). The WhatsApp, X and TikTok glyphs were
 * drawn for this theme.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

function bb3_icon_paths(): array {
	return array(
		'search'         => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
		'heart'          => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		'help'           => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
		'chat'           => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
		'whatsapp'       => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path fill="currentColor" stroke="none" d="M8.7 8.1c.3-.5.9-.6 1.3-.2l1 1.4c.3.4.2.9-.1 1.3l-.5.5c.5 1.1 1.4 2 2.5 2.5l.5-.5c.4-.3.9-.4 1.3-.1l1.4 1c.4.3.3 1-.2 1.3-.7.5-1.6.7-2.4.4a8.4 8.4 0 0 1-4.9-4.9c-.3-.8-.1-1.7.1-2.7z"/>',
		'arrow-left'     => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
		'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
		'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
		'chevron-left'   => '<path d="m15 18-6-6 6-6"/>',
		'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
		'menu'           => '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>',
		'close'          => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'pause'          => '<rect x="14" y="4" width="4" height="16" rx="1"/><rect x="6" y="4" width="4" height="16" rx="1"/>',
		'play'           => '<path d="M6 3l14 9-14 9z"/>',
		'mail'           => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'phone'          => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'pin'            => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'info'           => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
		'leaf'           => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
		'gift'           => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
		'tag'            => '<path d="M12.59 2.59A2 2 0 0 0 11.17 2H4a2 2 0 0 0-2 2v7.17a2 2 0 0 0 .59 1.42l8.7 8.7a2.43 2.43 0 0 0 3.42 0l6.58-6.58a2.43 2.43 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
		'shield'         => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
		'ruler'          => '<path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/>',
		'sparkles'       => '<path d="M9.94 15.5A2 2 0 0 0 8.5 14.06l-6.14-1.58a.5.5 0 0 1 0-.96L8.5 9.94A2 2 0 0 0 9.94 8.5l1.58-6.14a.5.5 0 0 1 .96 0L14.06 8.5A2 2 0 0 0 15.5 9.94l6.14 1.58a.5.5 0 0 1 0 .96L15.5 14.06a2 2 0 0 0-1.44 1.44l-1.58 6.14a.5.5 0 0 1-.96 0z"/>',
		'filter'         => '<path d="M21 4h-7"/><path d="M10 4H3"/><path d="M21 12h-9"/><path d="M8 12H3"/><path d="M21 20h-5"/><path d="M12 20H3"/><path d="M14 2v4"/><path d="M8 10v4"/><path d="M16 18v4"/>',
		'expand'         => '<path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="m21 3-7 7"/><path d="m3 21 7-7"/>',
		'image'          => '<rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.09-3.09a2 2 0 0 0-2.82 0L6 21"/>',
		'edit'           => '<path d="M12 20h9"/><path d="M16.38 3.62a1 1 0 0 1 3 3L7.37 18.64a2 2 0 0 1-.86.5l-2.87.84a.5.5 0 0 1-.62-.62l.84-2.87a2 2 0 0 1 .5-.86z"/>',
		'instagram'      => '<rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>',
		'facebook'       => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'youtube'        => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>',
		'tiktok'         => '<path d="M15.5 3v11a4 4 0 1 1-4-4"/><path d="M15.5 3c.35 2.55 2.1 4.25 4.5 4.45"/>',
		'x'              => '<path d="M4 4h4.4L20 20h-4.4z"/><path d="m19.6 4-6.8 7.6"/><path d="m4.4 20 6.8-7.6"/>',
	);
}

/**
 * Inline SVG icon.
 *
 * @param string $name  Icon key.
 * @param int    $size  Pixel size.
 * @param string $label Accessible label; empty = decorative.
 */
function bb3_icon( string $name, int $size = 20, string $label = '' ): string {
	$paths = bb3_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	$a11y = '' === $label ? ' aria-hidden="true" focusable="false"' : ' role="img" aria-label="' . esc_attr( $label ) . '"';
	return '<svg class="bb3-icon bb3-icon--' . esc_attr( $name ) . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"' . $a11y . '>' . $paths[ $name ] . '</svg>';
}

/** Icon choices offered in the Customizer and blocks. */
function bb3_icon_choices(): array {
	return array(
		'whatsapp' => __( 'WhatsApp', 'bali-batik-v3' ),
		'chat'     => __( 'Chat', 'bali-batik-v3' ),
		'info'     => __( 'Info', 'bali-batik-v3' ),
		'heart'    => __( 'Heart', 'bali-batik-v3' ),
		'leaf'     => __( 'Leaf', 'bali-batik-v3' ),
		'gift'     => __( 'Gift', 'bali-batik-v3' ),
		'tag'      => __( 'Tag', 'bali-batik-v3' ),
		'shield'   => __( 'Shield', 'bali-batik-v3' ),
		'ruler'    => __( 'Ruler / size', 'bali-batik-v3' ),
		'sparkles' => __( 'Sparkles', 'bali-batik-v3' ),
		'pin'      => __( 'Location', 'bali-batik-v3' ),
		'clock'    => __( 'Clock', 'bali-batik-v3' ),
		'phone'    => __( 'Phone', 'bali-batik-v3' ),
		'mail'     => __( 'Mail', 'bali-batik-v3' ),
	);
}
