<?php
/**
 * Hero: 1–3 photo slides with dark shade, serif title and green button.
 * Manual arrows only; controls are hidden when there is a single slide.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_slides = array();
for ( $bb3_i = 1; $bb3_i <= 3; $bb3_i++ ) {
	if ( ! bb3_mod( "hero_{$bb3_i}_on" ) || '' === bb3_mod( "hero_{$bb3_i}_title" ) ) {
		continue;
	}
	$bb3_slides[] = array(
		'image'   => (int) bb3_mod( "hero_{$bb3_i}_image" ),
		'asset'   => (string) bb3_mod( "hero_{$bb3_i}_asset" ),
		'focus'   => (string) bb3_mod( "hero_{$bb3_i}_focus" ),
		'eyebrow' => (string) bb3_mod( "hero_{$bb3_i}_eyebrow" ),
		'title'   => (string) bb3_mod( "hero_{$bb3_i}_title" ),
		'cta'     => (string) bb3_mod( "hero_{$bb3_i}_cta" ),
		'url'     => bb3_url( bb3_mod( "hero_{$bb3_i}_url" ) ),
	);
}
if ( ! $bb3_slides ) {
	echo '<h1 class="screen-reader-text">' . esc_html( bb3_brand() ) . '</h1>';
	return;
}
$bb3_total = count( $bb3_slides );
?>
<section class="bb3-hero" aria-roledescription="<?php esc_attr_e( 'carousel', 'bali-batik-v3' ); ?>" aria-label="<?php esc_attr_e( 'Featured', 'bali-batik-v3' ); ?>" data-bb3-hero>
	<?php foreach ( $bb3_slides as $bb3_i => $bb3_slide ) : ?>
		<div class="bb3-hero__slide<?php echo 0 === $bb3_i ? ' is-active' : ''; ?>" role="group" aria-roledescription="<?php esc_attr_e( 'slide', 'bali-batik-v3' ); ?>"
			<?php /* translators: 1: slide number, 2: total slides. */ ?>
			aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'bali-batik-v3' ), $bb3_i + 1, $bb3_total ) ); ?>"<?php echo $bb3_i ? ' inert' : ''; ?>>
			<div class="bb3-hero__bg" style="--bb3-focus:<?php echo esc_attr( $bb3_slide['focus'] ); ?>">
				<?php
				echo bb3_image( // phpcs:ignore WordPress.Security.EscapeOutput
					$bb3_slide['image'],
					'full',
					array(
						'alt'           => '',
						'sizes'         => '100vw',
						'loading'       => 0 === $bb3_i ? 'eager' : 'lazy',
						'fetchpriority' => 0 === $bb3_i ? 'high' : 'low',
						'decoding'      => 0 === $bb3_i ? 'sync' : 'async',
					),
					$bb3_slide['asset']
				);
				?>
			</div>
			<div class="bb3-hero__caption">
				<?php if ( '' !== $bb3_slide['eyebrow'] ) : ?>
					<p class="bb3-hero__eyebrow"><?php echo esc_html( $bb3_slide['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php $bb3_tag = 0 === $bb3_i ? 'h1' : 'h2'; ?>
				<<?php echo esc_html( $bb3_tag ); ?> class="bb3-hero__title"><?php echo esc_html( $bb3_slide['title'] ); ?></<?php echo esc_html( $bb3_tag ); ?>>
				<?php if ( '' !== $bb3_slide['cta'] && '' !== $bb3_slide['url'] ) : ?>
					<a class="bb3-btn bb3-btn--hero" href="<?php echo esc_url( $bb3_slide['url'] ); ?>"><?php echo esc_html( $bb3_slide['cta'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if ( $bb3_total > 1 ) : ?>
		<button type="button" class="bb3-hero__arrow bb3-hero__arrow--prev" data-dir="-1"><?php echo bb3_icon( 'arrow-left', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Previous slide', 'bali-batik-v3' ); ?></span></button>
		<button type="button" class="bb3-hero__arrow bb3-hero__arrow--next" data-dir="1"><?php echo bb3_icon( 'arrow-right', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Next slide', 'bali-batik-v3' ); ?></span></button>
	<?php endif; ?>
</section>
