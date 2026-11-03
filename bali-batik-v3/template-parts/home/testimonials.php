<?php
/**
 * Testimonials: only rendered when real quotes have been entered.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_quotes = array();
for ( $bb3_i = 1; $bb3_i <= 3; $bb3_i++ ) {
	$bb3_quote = (string) bb3_mod( "testi_{$bb3_i}_quote" );
	if ( '' !== $bb3_quote ) {
		$bb3_quotes[] = array( $bb3_quote, (string) bb3_mod( "testi_{$bb3_i}_name" ) );
	}
}
if ( ! $bb3_quotes ) {
	return;
}
?>
<section class="bb3-section bb3-shell bb3-testimonials" aria-labelledby="bb3-testi-title">
	<h2 id="bb3-testi-title"><?php echo esc_html( bb3_mod( 'testi_title' ) ); ?></h2>
	<div class="bb3-testimonials__grid">
		<?php foreach ( $bb3_quotes as [ $bb3_quote, $bb3_name ] ) : ?>
			<figure>
				<blockquote><p><?php echo esc_html( $bb3_quote ); ?></p></blockquote>
				<?php if ( '' !== $bb3_name ) : ?>
					<figcaption><?php echo esc_html( $bb3_name ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
