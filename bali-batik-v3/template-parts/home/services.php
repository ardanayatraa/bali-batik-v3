<?php
/**
 * Four service points. Default copy only describes what the site really does.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_items = array();
for ( $bb3_i = 1; $bb3_i <= 4; $bb3_i++ ) {
	if ( '' !== bb3_mod( "svc_{$bb3_i}_title" ) ) {
		$bb3_items[] = array( (string) bb3_mod( "svc_{$bb3_i}_icon" ), (string) bb3_mod( "svc_{$bb3_i}_title" ), (string) bb3_mod( "svc_{$bb3_i}_text" ) );
	}
}
if ( ! $bb3_items ) {
	return;
}
?>
<section class="bb3-services" aria-labelledby="bb3-svc-title">
	<div class="bb3-shell">
		<h2 id="bb3-svc-title"><?php echo esc_html( bb3_mod( 'svc_title' ) ); ?></h2>
		<?php if ( '' !== bb3_mod( 'svc_text' ) ) : ?>
			<p class="bb3-services__lead"><?php echo esc_html( bb3_mod( 'svc_text' ) ); ?></p>
		<?php endif; ?>
		<ul class="bb3-services__grid">
			<?php foreach ( $bb3_items as [ $bb3_icon, $bb3_title, $bb3_text ] ) : ?>
				<li>
					<?php echo bb3_icon( $bb3_icon, 36 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<h3><?php echo esc_html( $bb3_title ); ?></h3>
					<?php if ( '' !== $bb3_text ) : ?>
						<p><?php echo esc_html( $bb3_text ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
