<?php
/**
 * Wide story banner with a moving text strip along its bottom edge.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

if ( '' === bb3_mod( 'story_title' ) ) {
	return;
}
$bb3_items = array_filter( array_map( 'trim', explode( "\n", (string) bb3_mod( 'story_ticker' ) ) ) );
$bb3_url   = bb3_url( bb3_mod( 'story_url' ) );
?>
<section class="bb3-story" aria-labelledby="bb3-story-title">
	<div class="bb3-story__bg">
		<?php echo bb3_image( (int) bb3_mod( 'story_image' ), 'full', array( 'alt' => '', 'sizes' => '100vw' ), 'batik/hero-batik-craft.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<div class="bb3-shell bb3-story__content">
		<p class="bb3-story__eyebrow"><?php echo esc_html( bb3_mod( 'story_eyebrow' ) ); ?></p>
		<h2 id="bb3-story-title"><?php echo esc_html( bb3_mod( 'story_title' ) ); ?></h2>
		<?php if ( $bb3_url && '' !== bb3_mod( 'story_cta' ) ) : ?>
			<a class="bb3-btn bb3-btn--light" href="<?php echo esc_url( $bb3_url ); ?>"><?php echo esc_html( bb3_mod( 'story_cta' ) ); ?></a>
		<?php endif; ?>
	</div>
	<?php if ( $bb3_items ) : ?>
		<div class="bb3-ticker">
			<div class="bb3-ticker__track">
				<?php for ( $bb3_copy = 0; $bb3_copy < 2; $bb3_copy++ ) : ?>
					<ul<?php echo $bb3_copy ? ' aria-hidden="true"' : ''; ?>>
						<?php foreach ( $bb3_items as $bb3_item ) : ?>
							<li><?php echo esc_html( $bb3_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endfor; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
