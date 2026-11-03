<?php
/**
 * Batik for Every Occasion: five round images with labels.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_fallbacks = array( 1 => 'demo/shirt-indigo.jpg', 2 => 'demo/dress-ivory.jpg', 3 => 'demo/dress-terracotta.jpg', 4 => 'demo/shirt-hijau.jpg', 5 => 'demo/scarf-ivory.jpg' );
$bb3_items     = array();
for ( $bb3_i = 1; $bb3_i <= 5; $bb3_i++ ) {
	if ( '' !== bb3_mod( "occ_{$bb3_i}_label" ) ) {
		$bb3_items[ $bb3_i ] = bb3_url( bb3_mod( "occ_{$bb3_i}_url" ) ) ?: bb3_collection_url();
	}
}
if ( ! $bb3_items ) {
	return;
}
?>
<section class="bb3-section bb3-shell bb3-occasions" aria-labelledby="bb3-occ-title">
	<h2 id="bb3-occ-title"><?php echo esc_html( bb3_mod( 'occ_title' ) ); ?></h2>
	<ul>
		<?php foreach ( $bb3_items as $bb3_i => $bb3_url ) : ?>
			<li>
				<a href="<?php echo esc_url( $bb3_url ); ?>">
					<?php echo bb3_image( (int) bb3_mod( "occ_{$bb3_i}_image" ), 'bb3-square', array( 'alt' => '', 'sizes' => '125px' ), $bb3_fallbacks[ $bb3_i ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php echo esc_html( bb3_mod( "occ_{$bb3_i}_label" ) ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
