<?php
/**
 * Three promo banners with editable image, text and link.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_fallbacks = array( 1 => 'demo/detail-5.jpg', 2 => 'demo/detail-6.jpg', 3 => 'demo/detail-2.jpg' );
$bb3_banners   = array();
for ( $bb3_i = 1; $bb3_i <= 3; $bb3_i++ ) {
	if ( '' !== bb3_mod( "banner_{$bb3_i}_title" ) ) {
		$bb3_banners[ $bb3_i ] = bb3_url( bb3_mod( "banner_{$bb3_i}_url" ) ) ?: bb3_collection_url();
	}
}
if ( ! $bb3_banners ) {
	return;
}
?>
<section class="bb3-section bb3-shell bb3-banners" aria-label="<?php esc_attr_e( 'Featured collections', 'bali-batik-v3' ); ?>">
	<?php foreach ( $bb3_banners as $bb3_i => $bb3_url ) : ?>
		<a class="bb3-promo" href="<?php echo esc_url( $bb3_url ); ?>">
			<?php echo bb3_image( (int) bb3_mod( "banner_{$bb3_i}_image" ), 'large', array( 'alt' => '', 'sizes' => '(max-width: 767px) 100vw, 33vw' ), $bb3_fallbacks[ $bb3_i ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="bb3-promo__text">
				<span class="bb3-promo__eyebrow"><?php echo esc_html( bb3_mod( "banner_{$bb3_i}_eyebrow" ) ); ?></span>
				<span class="bb3-promo__title"><?php echo esc_html( bb3_mod( "banner_{$bb3_i}_title" ) ); ?></span>
				<?php if ( '' !== bb3_mod( "banner_{$bb3_i}_cta" ) ) : ?>
					<span class="bb3-btn bb3-btn--light"><?php echo esc_html( bb3_mod( "banner_{$bb3_i}_cta" ) ); ?></span>
				<?php endif; ?>
			</span>
		</a>
	<?php endforeach; ?>
</section>
