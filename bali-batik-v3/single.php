<?php
/**
 * Single blog post.
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
				<p class="bb3-page__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="bb3-page__image"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></div>
				<?php endif; ?>
				<div class="bb3-prose"><?php the_content(); ?></div>
			</article>
		</div>
	</main>
	<?php
endwhile;
get_footer();
