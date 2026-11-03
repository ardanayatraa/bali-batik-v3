<?php
/**
 * Fallback listing (blog, archives).
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="bb3-main bb3-page">
	<div class="bb3-shell bb3-shell--narrow">
		<?php bb3_breadcrumbs_html(); ?>
		<h1 class="bb3-page__title">
			<?php
			if ( is_archive() ) {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			} else {
				esc_html_e( 'Journal', 'bali-batik-v3' );
			}
			?>
		</h1>
		<?php if ( have_posts() ) : ?>
			<ul class="bb3-postlist">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="bb3-page__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
						<?php the_excerpt(); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php the_posts_pagination( array( 'class' => 'bb3-pagination' ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing here yet.', 'bali-batik-v3' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
