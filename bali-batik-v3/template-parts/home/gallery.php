<?php
/**
 * Inspiration gallery. Images link to Instagram only when a profile is set.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_fallbacks = array( 1 => 'batik/hero-batik-rack.jpg', 2 => 'demo/detail-6.jpg', 3 => 'demo/hero-3.jpg', 4 => 'demo/detail-4.jpg', 5 => 'demo/hero-2.jpg' );
$bb3_insta     = esc_url( (string) bb3_mod( 'instagram' ) );
?>
<section class="bb3-section bb3-shell bb3-gallery" aria-labelledby="bb3-gal-title">
	<?php bb3_section_heading( (string) bb3_mod( 'gal_title' ), (string) bb3_mod( 'gal_text' ), $bb3_insta, $bb3_insta ? __( 'Follow us on Instagram', 'bali-batik-v3' ) : '', 'bb3-gal-title' ); ?>
	<ul class="bb3-gallery__grid">
		<?php for ( $bb3_i = 1; $bb3_i <= 5; $bb3_i++ ) : ?>
			<?php
			$bb3_id  = (int) bb3_mod( "gal_{$bb3_i}_image" );
			$bb3_alt = $bb3_id ? (string) get_post_meta( $bb3_id, '_wp_attachment_image_alt', true ) : '';
			$bb3_img = bb3_image( $bb3_id, 'bb3-square', array( 'alt' => $bb3_alt ?: __( 'Batik detail', 'bali-batik-v3' ), 'sizes' => '(max-width: 767px) 45vw, 20vw' ), $bb3_fallbacks[ $bb3_i ] );
			?>
			<li>
				<?php if ( $bb3_insta ) : ?>
					<a href="<?php echo esc_url( $bb3_insta ); ?>" target="_blank" rel="noopener"><?php echo $bb3_img; // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo bb3_icon( 'instagram', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'View on Instagram', 'bali-batik-v3' ); ?></span></span></a>
				<?php else : ?>
					<?php echo $bb3_img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php endif; ?>
			</li>
		<?php endfor; ?>
	</ul>
</section>
