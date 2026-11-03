<?php
/**
 * Product detail: gallery with lightbox, price, WhatsApp inquiry, details, related.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$bb3_id      = get_the_ID();
	$bb3_title   = get_the_title();
	$bb3_images  = bb3_product_images( $bb3_id );
	$bb3_count   = count( $bb3_images );
	$bb3_term    = bb3_primary_term( $bb3_id );
	$bb3_prices  = bb3_prices( $bb3_id );
	$bb3_action  = bb3_contact_action( bb3_product_message( $bb3_id ) );
	$bb3_avail   = (string) bb3_meta( $bb3_id, 'availability' );
	$bb3_details = array_filter(
		array(
			__( 'Motif', 'bali-batik-v3' )     => (string) bb3_meta( $bb3_id, 'motif' ),
			__( 'Material', 'bali-batik-v3' )  => (string) bb3_meta( $bb3_id, 'material' ),
			__( 'Technique', 'bali-batik-v3' ) => (string) bb3_meta( $bb3_id, 'technique' ),
			__( 'Size', 'bali-batik-v3' )      => (string) bb3_meta( $bb3_id, 'dimensions' ),
			__( 'Color', 'bali-batik-v3' )     => (string) bb3_meta( $bb3_id, 'color' ),
			__( 'SKU', 'bali-batik-v3' )       => (string) bb3_meta( $bb3_id, 'sku' ),
		),
		fn( $v ) => '' !== trim( $v )
	);
	$bb3_care    = trim( (string) bb3_meta( $bb3_id, 'care' ) );
	$bb3_thumb   = $bb3_images ? (string) wp_get_attachment_image_url( $bb3_images[0], 'thumbnail' ) : '';
	$bb3_price_t = null === $bb3_prices['price'] ? __( 'Price on request', 'bali-batik-v3' ) : bb3_format_price( $bb3_prices['price'] );
	?>
	<main id="main" class="bb3-main bb3-product-page">
		<div class="bb3-shell">
			<?php bb3_breadcrumbs_html(); ?>
			<article class="bb3-product">
				<div class="bb3-pgallery" data-bb3-gallery>
					<div class="bb3-pgallery__stage">
						<?php if ( $bb3_images ) : ?>
							<ul class="bb3-pgallery__track" data-bb3-gallery-track>
								<?php foreach ( $bb3_images as $bb3_i => $bb3_img ) : ?>
									<?php
									$bb3_alt  = (string) get_post_meta( $bb3_img, '_wp_attachment_image_alt', true );
									$bb3_alt  = '' !== $bb3_alt ? $bb3_alt : $bb3_title;
									$bb3_full = wp_get_attachment_image_src( $bb3_img, 'full' );
									?>
									<li>
										<button type="button" class="bb3-pgallery__zoom" data-index="<?php echo (int) $bb3_i; ?>" data-full="<?php echo esc_url( $bb3_full ? $bb3_full[0] : '' ); ?>" data-alt="<?php echo esc_attr( $bb3_alt ); ?>">
											<?php
											echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
												$bb3_img,
												'large',
												false,
												array(
													'alt'     => $bb3_alt,
													'sizes'   => '(max-width: 1023px) 100vw, 55vw',
													'loading' => 0 === $bb3_i ? 'eager' : 'lazy',
													'fetchpriority' => 0 === $bb3_i ? 'high' : 'auto',
												)
											);
											?>
											<?php /* translators: 1: image number, 2: total images. */ ?>
											<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Enlarge image %1$d of %2$d', 'bali-batik-v3' ), $bb3_i + 1, $bb3_count ) ); ?></span>
											<span class="bb3-pgallery__hint" aria-hidden="true"><?php echo bb3_icon( 'expand', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
										</button>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php if ( $bb3_count > 1 ) : ?>
								<button type="button" class="bb3-pgallery__nav bb3-pgallery__nav--prev" data-dir="-1"><?php echo bb3_icon( 'chevron-left', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Previous image', 'bali-batik-v3' ); ?></span></button>
								<button type="button" class="bb3-pgallery__nav bb3-pgallery__nav--next" data-dir="1"><?php echo bb3_icon( 'chevron-right', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Next image', 'bali-batik-v3' ); ?></span></button>
								<p class="bb3-pgallery__counter" aria-hidden="true"><span data-bb3-gallery-current>1</span> / <?php echo (int) $bb3_count; ?></p>
							<?php endif; ?>
						<?php else : ?>
							<div class="bb3-card__noimg bb3-card__noimg--large"><?php echo bb3_icon( 'image', 48 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Photo coming soon', 'bali-batik-v3' ); ?></span></div>
						<?php endif; ?>
					</div>
					<?php if ( $bb3_count > 1 ) : ?>
						<ul class="bb3-pgallery__thumbs">
							<?php foreach ( $bb3_images as $bb3_i => $bb3_img ) : ?>
								<li>
									<button type="button" data-index="<?php echo (int) $bb3_i; ?>"<?php echo 0 === $bb3_i ? ' aria-current="true"' : ''; ?>>
										<?php echo wp_get_attachment_image( $bb3_img, 'thumbnail', false, array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										<?php /* translators: %d: image number. */ ?>
										<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Show image %d', 'bali-batik-v3' ), $bb3_i + 1 ) ); ?></span>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="bb3-product__info">
					<?php if ( $bb3_term ) : ?>
						<p class="bb3-product__cat"><a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( $bb3_term->name ); ?></a></p>
					<?php endif; ?>
					<h1 class="bb3-product__title"><?php echo esc_html( $bb3_title ); ?></h1>
					<div class="bb3-product__price"><?php echo bb3_price_html( $bb3_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<?php if ( $bb3_avail && isset( bb3_availability_choices()[ $bb3_avail ] ) ) : ?>
						<p class="bb3-availability bb3-availability--<?php echo esc_attr( $bb3_avail ); ?>"><?php echo esc_html( bb3_availability_choices()[ $bb3_avail ] ); ?></p>
					<?php endif; ?>
					<?php if ( has_excerpt() ) : ?>
						<div class="bb3-product__summary"><?php the_excerpt(); ?></div>
					<?php endif; ?>

					<div class="bb3-product__actions">
						<a class="bb3-btn bb3-btn--hero bb3-btn--block" href="<?php echo esc_url( $bb3_action['url'] ); ?>"<?php echo $bb3_action['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
							<?php echo bb3_icon( $bb3_action['whatsapp'] ? 'whatsapp' : 'chat', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php echo esc_html( $bb3_action['whatsapp'] ? __( 'Ask on WhatsApp', 'bali-batik-v3' ) : __( 'Contact Us', 'bali-batik-v3' ) ); ?>
						</a>
						<button type="button" class="bb3-btn bb3-btn--outline bb3-fav-btn bb3-fav-btn--wide" aria-pressed="false"
							data-bb3-fav="<?php echo esc_attr( (string) $bb3_id ); ?>"
							data-title="<?php echo esc_attr( $bb3_title ); ?>"
							data-url="<?php echo esc_url( get_permalink() ); ?>"
							data-image="<?php echo esc_url( $bb3_thumb ); ?>"
							data-price="<?php echo esc_attr( $bb3_price_t ); ?>">
							<?php echo bb3_icon( 'heart', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span data-bb3-fav-label><?php esc_html_e( 'Save to favorites', 'bali-batik-v3' ); ?></span>
						</button>
					</div>
					<p class="bb3-product__note">
						<?php
						echo esc_html(
							$bb3_action['whatsapp']
								? __( 'Opens WhatsApp with this product’s name and link already filled in.', 'bali-batik-v3' )
								: __( 'Tell us the product name and we will help you with availability and sizing.', 'bali-batik-v3' )
						);
						?>
					</p>

					<?php if ( $bb3_details ) : ?>
						<h2 class="bb3-product__h"><?php esc_html_e( 'Details', 'bali-batik-v3' ); ?></h2>
						<dl class="bb3-specs">
							<?php foreach ( $bb3_details as $bb3_label => $bb3_value ) : ?>
								<div><dt><?php echo esc_html( $bb3_label ); ?></dt><dd><?php echo esc_html( $bb3_value ); ?></dd></div>
							<?php endforeach; ?>
						</dl>
					<?php endif; ?>
					<?php if ( '' !== $bb3_care ) : ?>
						<h2 class="bb3-product__h"><?php esc_html_e( 'Care', 'bali-batik-v3' ); ?></h2>
						<p class="bb3-product__care"><?php echo nl2br( esc_html( $bb3_care ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
				</div>
			</article>

			<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
				<section class="bb3-product__story" aria-labelledby="bb3-story-h">
					<h2 id="bb3-story-h"><?php esc_html_e( 'The Story Behind This Piece', 'bali-batik-v3' ); ?></h2>
					<div class="bb3-prose"><?php the_content(); ?></div>
				</section>
			<?php endif; ?>

			<?php
			$bb3_related = bb3_products(
				array(
					'posts_per_page' => 4,
					'post__not_in'   => array( $bb3_id ),
					'tax_query'      => $bb3_term ? array( array( 'taxonomy' => 'bb_product_cat', 'terms' => $bb3_term->parent ? array( $bb3_term->term_id, $bb3_term->parent ) : array( $bb3_term->term_id ) ) ) : array(), // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			);
			if ( $bb3_related->post_count < 4 ) {
				$bb3_more    = bb3_products(
					array(
						'posts_per_page' => 4 - $bb3_related->post_count,
						'post__not_in'   => array_merge( array( $bb3_id ), wp_list_pluck( $bb3_related->posts, 'ID' ) ),
					)
				);
				$bb3_related->posts = array_merge( $bb3_related->posts, $bb3_more->posts );
			}
			if ( $bb3_related->posts ) :
				?>
				<section class="bb3-section bb3-related" aria-labelledby="bb3-related-h">
					<?php bb3_section_heading( __( 'You May Also Like', 'bali-batik-v3' ), '', $bb3_term ? (string) get_term_link( $bb3_term ) : bb3_collection_url(), $bb3_term ? sprintf( /* translators: %s: category. */ __( 'More %s', 'bali-batik-v3' ), $bb3_term->name ) : __( 'View all', 'bali-batik-v3' ), 'bb3-related-h' ); ?>
					<div class="bb3-grid bb3-grid--related">
						<?php
						foreach ( $bb3_related->posts as $bb3_post ) {
							get_template_part( 'template-parts/product/card', null, array( 'id' => $bb3_post->ID ) );
						}
						?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</main>

	<?php if ( $bb3_images ) : ?>
		<dialog class="bb3-lightbox" id="bb3-lightbox" aria-label="<?php echo esc_attr( $bb3_title ); ?>" data-bb3-lightbox>
			<button type="button" class="bb3-lightbox__close" data-bb3-lightbox-close><?php echo bb3_icon( 'close', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Close', 'bali-batik-v3' ); ?></span></button>
			<figure><img src="" alt="" data-bb3-lightbox-img><figcaption data-bb3-lightbox-caption></figcaption></figure>
			<?php if ( $bb3_count > 1 ) : ?>
				<button type="button" class="bb3-lightbox__nav bb3-lightbox__nav--prev" data-dir="-1"><?php echo bb3_icon( 'chevron-left', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Previous image', 'bali-batik-v3' ); ?></span></button>
				<button type="button" class="bb3-lightbox__nav bb3-lightbox__nav--next" data-dir="1"><?php echo bb3_icon( 'chevron-right', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Next image', 'bali-batik-v3' ); ?></span></button>
			<?php endif; ?>
		</dialog>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
