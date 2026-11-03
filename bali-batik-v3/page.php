<?php
/**
 * Standard page.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="bb3-main bb3-page">
		<div class="bb3-shell bb3-shell--narrow">
			<?php bb3_breadcrumbs_html(); ?>
			<article <?php post_class( 'bb3-page__article' ); ?>>
				<h1 class="bb3-page__title"><?php the_title(); ?></h1>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="bb3-page__image"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></div>
				<?php endif; ?>
				<div class="bb3-prose"><?php the_content(); ?></div>
			</article>
			<div class="bb3-page__cta">
				<p><?php esc_html_e( 'Ready to find your batik?', 'bali-batik-v3' ); ?></p>
				<a class="bb3-btn bb3-btn--hero" href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'Explore the Collection', 'bali-batik-v3' ); ?></a>
			</div>
		</div>
	</main>
	<?php
endwhile;
get_footer();
