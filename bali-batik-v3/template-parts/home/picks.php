<?php
/**
 * Our Picks: featured category list beside up to three products marked "Our Pick".
 * Hidden when no product is marked, so no selection is invented.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_query = bb3_products(
	array(
		'posts_per_page' => 3,
		'meta_key'       => '_bb_featured', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
	)
);
if ( ! $bb3_query->have_posts() ) {
	return;
}
?>
<section class="bb3-section bb3-shell" aria-labelledby="bb3-picks-title">
	<?php bb3_section_heading( (string) bb3_mod( 'picks_title' ), (string) bb3_mod( 'picks_text' ), '', '', 'bb3-picks-title' ); ?>
	<div class="bb3-picks">
		<div class="bb3-picks__cats">
			<h3><?php echo esc_html( bb3_mod( 'picks_side' ) ); ?></h3>
			<?php foreach ( bb3_categories( 0 ) as $bb3_term ) : ?>
				<div>
					<h4><a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( $bb3_term->name ); ?></a></h4>
					<?php $bb3_children = bb3_categories( $bb3_term->term_id ); ?>
					<?php if ( $bb3_children ) : ?>
						<ul>
							<?php foreach ( $bb3_children as $bb3_child ) : ?>
								<li><a href="<?php echo esc_url( get_term_link( $bb3_child ) ); ?>"><?php echo esc_html( $bb3_child->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="bb3-grid bb3-grid--picks">
			<?php
			foreach ( $bb3_query->posts as $bb3_post ) {
				get_template_part( 'template-parts/product/card', null, array( 'id' => $bb3_post->ID ) );
			}
			?>
		</div>
	</div>
</section>
