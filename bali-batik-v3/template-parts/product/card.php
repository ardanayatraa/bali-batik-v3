<?php
/**
 * Product card.
 *
 * Args: id (int), eager (bool), heading (h2|h3).
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_id      = (int) ( $args['id'] ?? get_the_ID() );
$bb3_eager   = ! empty( $args['eager'] );
$bb3_heading = in_array( $args['heading'] ?? 'h3', array( 'h2', 'h3' ), true ) ? ( $args['heading'] ?? 'h3' ) : 'h3';
$bb3_title   = get_the_title( $bb3_id );
$bb3_url     = get_permalink( $bb3_id );
$bb3_images  = bb3_product_images( $bb3_id );
$bb3_term    = bb3_primary_term( $bb3_id );
$bb3_prices  = bb3_prices( $bb3_id );
$bb3_thumb   = $bb3_images ? (string) wp_get_attachment_image_url( $bb3_images[0], 'thumbnail' ) : '';
$bb3_sizes   = '(max-width: 767px) 50vw, (max-width: 1199px) 33vw, 20vw';
?>
<article class="bb3-card" data-cats="<?php echo esc_attr( implode( ' ', bb3_term_slugs( $bb3_id ) ) ); ?>">
	<div class="bb3-card__media">
		<a class="bb3-card__img" href="<?php echo esc_url( $bb3_url ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( $bb3_images ) {
				$bb3_alt = (string) get_post_meta( $bb3_images[0], '_wp_attachment_image_alt', true );
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
					$bb3_images[0],
					'bb3-card',
					false,
					array(
						'alt'           => '' !== $bb3_alt ? $bb3_alt : $bb3_title,
						'sizes'         => $bb3_sizes,
						'loading'       => $bb3_eager ? 'eager' : 'lazy',
						'fetchpriority' => $bb3_eager ? 'high' : 'auto',
					)
				);
				if ( isset( $bb3_images[1] ) ) {
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
						$bb3_images[1],
						'bb3-card',
						false,
						array(
							'alt'     => '',
							'class'   => 'bb3-card__alt',
							'sizes'   => $bb3_sizes,
							'loading' => 'lazy',
						)
					);
				}
			} else {
				echo '<span class="bb3-card__noimg">' . bb3_icon( 'image', 32 ) . '<span>' . esc_html__( 'Photo coming soon', 'bali-batik-v3' ) . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</a>
		<?php if ( $bb3_prices['compare'] ) : ?>
			<span class="bb3-card__badge"><?php esc_html_e( 'Sale', 'bali-batik-v3' ); ?></span>
		<?php elseif ( 'sold_out' === bb3_meta( $bb3_id, 'availability' ) ) : ?>
			<span class="bb3-card__badge bb3-card__badge--muted"><?php esc_html_e( 'Sold out', 'bali-batik-v3' ); ?></span>
		<?php endif; ?>
		<button type="button" class="bb3-fav-btn" aria-pressed="false"
			data-bb3-fav="<?php echo esc_attr( (string) $bb3_id ); ?>"
			data-title="<?php echo esc_attr( $bb3_title ); ?>"
			data-url="<?php echo esc_url( $bb3_url ); ?>"
			data-image="<?php echo esc_url( $bb3_thumb ); ?>"
			data-price="<?php echo esc_attr( null === $bb3_prices['price'] ? __( 'Price on request', 'bali-batik-v3' ) : bb3_format_price( $bb3_prices['price'] ) ); ?>">
			<?php echo bb3_icon( 'heart', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php /* translators: %s: product name. */ ?>
			<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Save %s to favorites', 'bali-batik-v3' ), $bb3_title ) ); ?></span>
		</button>
	</div>
	<div class="bb3-card__body">
		<?php if ( $bb3_term ) : ?>
			<p class="bb3-card__cat"><?php echo esc_html( $bb3_term->name ); ?></p>
		<?php endif; ?>
		<<?php echo esc_html( $bb3_heading ); ?> class="bb3-card__title"><a href="<?php echo esc_url( $bb3_url ); ?>"><?php echo esc_html( $bb3_title ); ?></a></<?php echo esc_html( $bb3_heading ); ?>>
		<?php echo bb3_price_html( $bb3_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</article>
