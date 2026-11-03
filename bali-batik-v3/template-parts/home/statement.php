<?php
/**
 * Brand statement in large type with two small inline motif images.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_text = (string) bb3_mod( 'statement' );
if ( '' === $bb3_text ) {
	return;
}
$bb3_html = esc_html( $bb3_text );
foreach ( array( 1 => 'demo/detail-3.jpg', 2 => 'demo/detail-1.jpg' ) as $bb3_i => $bb3_fallback ) {
	$bb3_img  = bb3_image( (int) bb3_mod( "statement_img_{$bb3_i}" ), 'thumbnail', array( 'alt' => '', 'sizes' => '120px' ), $bb3_fallback );
	$bb3_html = str_replace( '{img' . $bb3_i . '}', $bb3_img, $bb3_html );
}
?>
<section class="bb3-statement bb3-shell" aria-label="<?php esc_attr_e( 'Our view on batik', 'bali-batik-v3' ); ?>">
	<p><?php echo $bb3_html; // phpcs:ignore WordPress.Security.EscapeOutput -- text escaped above, images built by bb3_image(). ?></p>
</section>
