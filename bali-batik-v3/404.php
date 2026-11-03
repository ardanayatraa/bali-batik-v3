<?php
/**
 * Not found.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="bb3-main bb3-page bb3-404">
	<div class="bb3-shell bb3-shell--narrow">
		<p class="bb3-eyebrow">404</p>
		<h1 class="bb3-page__title"><?php esc_html_e( 'This page has wandered off', 'bali-batik-v3' ); ?></h1>
		<p><?php esc_html_e( 'The link may be old or the page may have moved. Search for a motif or start from one of our categories.', 'bali-batik-v3' ); ?></p>
		<?php bb3_search_form( 'bb3-search-404' ); ?>
		<ul class="bb3-404__cats">
			<?php foreach ( bb3_categories( 0 ) as $bb3_term ) : ?>
				<li><a class="bb3-btn bb3-btn--outline" href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( $bb3_term->name ); ?></a></li>
			<?php endforeach; ?>
			<li><a class="bb3-btn bb3-btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'bali-batik-v3' ); ?></a></li>
		</ul>
	</div>
</main>
<?php
get_footer();
