<?php
/**
 * Motif spotlight. A countdown only appears for a real promotion end date.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

if ( '' === bb3_mod( 'spot_title' ) ) {
	return;
}
$bb3_deadline = (string) bb3_mod( 'spot_deadline' );
$bb3_end      = '' !== $bb3_deadline ? strtotime( $bb3_deadline . ' ' . wp_timezone_string() ) : false;
$bb3_url      = bb3_url( bb3_mod( 'spot_url' ) );
?>
<section class="bb3-spot bb3-shell" aria-labelledby="bb3-spot-title">
	<div class="bb3-spot__text">
		<p class="bb3-eyebrow"><?php echo esc_html( bb3_mod( 'spot_eyebrow' ) ); ?></p>
		<h2 id="bb3-spot-title"><?php echo esc_html( bb3_mod( 'spot_title' ) ); ?></h2>
		<p><?php echo esc_html( bb3_mod( 'spot_text' ) ); ?></p>
		<?php if ( $bb3_end && $bb3_end > time() ) : ?>
			<p class="bb3-countdown" data-bb3-countdown="<?php echo esc_attr( gmdate( 'c', $bb3_end ) ); ?>">
				<?php /* translators: %s: end date. */ ?>
				<?php echo esc_html( sprintf( __( 'Ends %s', 'bali-batik-v3' ), wp_date( get_option( 'date_format' ), $bb3_end ) ) ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $bb3_url && '' !== bb3_mod( 'spot_cta' ) ) : ?>
			<a class="bb3-btn bb3-btn--dark" href="<?php echo esc_url( $bb3_url ); ?>"><?php echo esc_html( bb3_mod( 'spot_cta' ) ); ?></a>
		<?php endif; ?>
	</div>
	<div class="bb3-spot__image">
		<?php echo bb3_image( (int) bb3_mod( 'spot_image' ), 'large', array( 'alt' => '', 'sizes' => '(max-width: 767px) 100vw, 55vw' ), 'demo/hero-1.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
