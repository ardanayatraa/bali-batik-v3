<?php
/**
 * New Arrivals: promo banner + newest products with category tabs.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_query = bb3_products(
	array(
		'posts_per_page' => max( 4, min( 24, (int) bb3_mod( 'arrivals_count' ) ) ),
		'orderby'        => array( 'date' => 'DESC', 'ID' => 'DESC' ),
	)
);
if ( ! $bb3_query->have_posts() ) {
	return;
}

// Tabs: top-level categories that actually appear in this set.
$bb3_present = array();
foreach ( $bb3_query->posts as $bb3_post ) {
	$bb3_present = array_merge( $bb3_present, bb3_term_slugs( $bb3_post->ID ) );
}
$bb3_tabs = array_filter( bb3_categories( 0 ), fn( $t ) => in_array( $t->slug, $bb3_present, true ) );
$bb3_url  = bb3_url( bb3_mod( 'arrivals_promo_url' ) );
?>
<section class="bb3-section bb3-shell" aria-labelledby="bb3-arrivals-title">
	<?php bb3_section_heading( (string) bb3_mod( 'arrivals_title' ), (string) bb3_mod( 'arrivals_text' ), '', '', 'bb3-arrivals-title' ); ?>
	<div class="bb3-arrivals">
		<?php if ( '' !== bb3_mod( 'arrivals_promo_title' ) ) : ?>
			<a class="bb3-promo bb3-promo--tall" href="<?php echo esc_url( $bb3_url ?: bb3_collection_url() ); ?>">
				<?php echo bb3_image( (int) bb3_mod( 'arrivals_promo_image' ), 'large', array( 'alt' => '', 'sizes' => '25vw' ), 'demo/fabric-terracotta.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="bb3-promo__text">
					<span class="bb3-promo__eyebrow"><?php echo esc_html( bb3_mod( 'arrivals_promo_eye' ) ); ?></span>
					<span class="bb3-promo__title"><?php echo esc_html( bb3_mod( 'arrivals_promo_title' ) ); ?></span>
					<?php if ( '' !== bb3_mod( 'arrivals_promo_cta' ) ) : ?>
						<span class="bb3-btn bb3-btn--light"><?php echo esc_html( bb3_mod( 'arrivals_promo_cta' ) ); ?></span>
					<?php endif; ?>
				</span>
			</a>
		<?php endif; ?>
		<div class="bb3-arrivals__products" data-bb3-tabs>
			<?php if ( count( $bb3_tabs ) > 1 ) : ?>
				<div class="bb3-tabs" role="toolbar" aria-label="<?php esc_attr_e( 'Filter new arrivals by category', 'bali-batik-v3' ); ?>">
					<button type="button" class="is-active" aria-pressed="true" data-filter="*"><?php esc_html_e( 'All', 'bali-batik-v3' ); ?></button>
					<?php foreach ( $bb3_tabs as $bb3_tab ) : ?>
						<button type="button" aria-pressed="false" data-filter="<?php echo esc_attr( $bb3_tab->slug ); ?>"><?php echo esc_html( $bb3_tab->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="bb3-grid bb3-grid--arrivals">
				<?php
				foreach ( $bb3_query->posts as $bb3_post ) {
					get_template_part( 'template-parts/product/card', null, array( 'id' => $bb3_post->ID ) );
				}
				?>
			</div>
			<p class="bb3-empty" data-bb3-tabs-empty hidden><?php esc_html_e( 'No new pieces in this category yet.', 'bali-batik-v3' ); ?></p>
			<p class="bb3-more"><a class="bb3-text-link" href="<?php echo esc_url( add_query_arg( 'sort', 'latest', bb3_collection_url() ) ); ?>"><?php esc_html_e( 'View all new pieces', 'bali-batik-v3' ); ?><?php echo bb3_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		</div>
	</div>
</section>
