<?php
/**
 * Moving category row. Links work without JavaScript; the script adds the
 * loop, controls, pause on hover/focus/touch and respects reduced motion.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_terms = array_slice(
	array_filter( bb3_categories( -1 ), fn( $t ) => $t->count > 0 ),
	0,
	10
);
if ( ! $bb3_terms ) {
	return;
}
?>
<section class="bb3-cats bb3-shell" aria-labelledby="bb3-cats-title">
	<h2 id="bb3-cats-title" class="screen-reader-text"><?php echo esc_html( bb3_mod( 'cats_title' ) ); ?></h2>
	<div class="bb3-cats__viewport" data-bb3-marquee>
		<ul class="bb3-cats__track">
			<?php foreach ( $bb3_terms as $bb3_term ) : ?>
				<li class="bb3-cat">
					<a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>">
						<?php
						echo bb3_image( // phpcs:ignore WordPress.Security.EscapeOutput
							bb3_term_cover( $bb3_term ),
							'bb3-card',
							array(
								'alt'   => '',
								'sizes' => '(max-width: 767px) 60vw, 27vw',
							)
						);
						?>
						<div class="bb3-cat__text">
							<h3 class="bb3-cat__name"><?php echo esc_html( $bb3_term->name ); ?></h3>
							<?php /* translators: %d: number of products. */ ?>
							<span class="bb3-cat__count"><?php echo esc_html( sprintf( _n( '%d piece', '%d pieces', (int) $bb3_term->count, 'bali-batik-v3' ), (int) $bb3_term->count ) ); ?></span>
						</div>
						<?php echo bb3_icon( 'arrow-up-right', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="bb3-cats__controls" data-bb3-marquee-controls hidden>
		<button type="button" data-action="prev"><?php echo bb3_icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Previous categories', 'bali-batik-v3' ); ?></span></button>
		<button type="button" data-action="toggle" aria-pressed="false"><span data-icon-pause><?php echo bb3_icon( 'pause', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span data-icon-play hidden><?php echo bb3_icon( 'play', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span class="screen-reader-text" data-label><?php esc_html_e( 'Pause category carousel', 'bali-batik-v3' ); ?></span></button>
		<button type="button" data-action="next"><?php echo bb3_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Next categories', 'bali-batik-v3' ); ?></span></button>
	</div>
</section>
