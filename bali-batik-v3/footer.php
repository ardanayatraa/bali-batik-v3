<?php
/**
 * Site footer, favorites dialog and scripts.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

?>
<?php get_template_part( 'template-parts/site/footer-main' ); ?>

<dialog class="bb3-fav" id="bb3-favorites" aria-labelledby="bb3-fav-title">
	<div class="bb3-fav__head">
		<h2 id="bb3-fav-title"><?php esc_html_e( 'Your Favorites', 'bali-batik-v3' ); ?></h2>
		<button type="button" class="bb3-icon-btn" data-bb3-fav-close><?php echo bb3_icon( 'close', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Close favorites', 'bali-batik-v3' ); ?></span></button>
	</div>
	<div class="bb3-fav__list" data-bb3-fav-list></div>
	<p class="bb3-fav__note"><?php esc_html_e( 'Favorites are saved in this browser only. No account needed.', 'bali-batik-v3' ); ?></p>
</dialog>

<?php wp_footer(); ?>
</body>
</html>
