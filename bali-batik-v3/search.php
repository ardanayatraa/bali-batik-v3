<?php
/**
 * Site-wide search results (products, pages, posts). Not indexed.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="bb3-main bb3-page">
	<div class="bb3-shell">
		<?php bb3_breadcrumbs_html(); ?>
		<header class="bb3-catalog__head">
			<?php /* translators: %s: search words. */ ?>
			<h1><?php echo esc_html( sprintf( __( 'Results for “%s”', 'bali-batik-v3' ), get_search_query( false ) ) ); ?></h1>
			<?php bb3_search_form( 'bb3-search-page' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="bb3-grid bb3-grid--catalog bb3-grid--wide">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'bb_product' === get_post_type() ) {
						get_template_part( 'template-parts/product/card', null, array( 'id' => get_the_ID(), 'heading' => 'h2' ) );
						continue;
					}
					?>
					<article class="bb3-result">
						<p class="bb3-card__cat"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
						<h2 class="bb3-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'bb3-pagination' ) ); ?>
		<?php else : ?>
			<div class="bb3-empty-state">
				<h2><?php esc_html_e( 'Nothing found', 'bali-batik-v3' ); ?></h2>
				<p><?php esc_html_e( 'Try another word, such as a motif or a color, or browse the full collection.', 'bali-batik-v3' ); ?></p>
				<a class="bb3-btn bb3-btn--dark" href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'See all batik', 'bali-batik-v3' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
