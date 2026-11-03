<?php
/**
 * Short "About" note above the footer.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

if ( '' === bb3_mod( 'about_text' ) ) {
	return;
}
$bb3_page = get_page_by_path( 'about' );
?>
<section class="bb3-about bb3-shell" aria-labelledby="bb3-about-title">
	<h2 id="bb3-about-title"><?php echo esc_html( bb3_mod( 'about_title' ) ); ?></h2>
	<div>
		<p><?php echo esc_html( bb3_mod( 'about_text' ) ); ?></p>
		<?php if ( $bb3_page && 'publish' === $bb3_page->post_status ) : ?>
			<a class="bb3-text-link" href="<?php echo esc_url( get_permalink( $bb3_page ) ); ?>"><?php esc_html_e( 'Read more about us', 'bali-batik-v3' ); ?><?php echo bb3_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		<?php endif; ?>
	</div>
</section>
