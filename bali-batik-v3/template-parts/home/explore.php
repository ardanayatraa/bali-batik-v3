<?php
/**
 * Explore the Collection: up to ten products, five columns on desktop.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_query = bb3_products( array( 'posts_per_page' => 10 ) );
if ( ! $bb3_query->have_posts() ) {
	return;
}
?>
<section class="bb3-section bb3-shell" aria-labelledby="bb3-explore-title">
	<?php bb3_section_heading( (string) bb3_mod( 'explore_title' ), (string) bb3_mod( 'explore_text' ), bb3_collection_url(), __( 'Browse the full collection', 'bali-batik-v3' ), 'bb3-explore-title' ); ?>
	<div class="bb3-grid bb3-grid--five">
		<?php
		foreach ( $bb3_query->posts as $bb3_post ) {
			get_template_part( 'template-parts/product/card', null, array( 'id' => $bb3_post->ID ) );
		}
		?>
	</div>
</section>
