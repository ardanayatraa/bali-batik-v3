<?php
/**
 * Homepage.
 *
 * Two modes (Customize → Bali Batik → Homepage Layout):
 * - "sections": theme sections in V2 order; show/hide and reorder them there.
 * - "page": the static front page built in the block editor with Bali Batik blocks.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( bb3_home_uses_page() ) :
	?>
	<main id="main" class="bb3-main bb3-home bb3-blocks">
		<?php
		while ( have_posts() ) {
			the_post();
			the_content();
		}
		?>
	</main>
	<?php
else :
	$bb3_sections = bb3_home_sections();
	?>
	<main id="main" class="bb3-main bb3-home" data-bb3-home>
		<?php
		if ( ! in_array( 'hero', $bb3_sections, true ) ) {
			echo '<h1 class="screen-reader-text">' . esc_html( bb3_brand() . ( '' !== bb3_tagline() ? ' – ' . bb3_tagline() : '' ) ) . '</h1>';
		}
		foreach ( $bb3_sections as $bb3_section ) {
			bb3_render_home_section( $bb3_section );
		}
		?>
	</main>
	<?php
endif;

get_footer();
