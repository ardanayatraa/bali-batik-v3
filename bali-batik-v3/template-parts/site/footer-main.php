<?php
/**
 * Footer content (refreshes live in the Customizer).
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_contact = bb3_contact_action();
$bb3_email   = sanitize_email( (string) bb3_mod( 'email' ) );
$bb3_phone   = (string) bb3_mod( 'phone' );
$bb3_address = (string) bb3_mod( 'address' );
$bb3_hours   = (string) bb3_mod( 'hours' );
$bb3_about   = get_page_by_path( 'about' );
$bb3_privacy = (int) get_option( 'wp_page_for_privacy_policy' );

/** Fallback footer list when no menu is assigned. */
$bb3_links = function ( string $location, array $fallback ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'depth'          => 1,
			)
		);
		return;
	}
	echo '<ul>';
	foreach ( $fallback as $item ) {
		echo '<li>' . $item . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</ul>';
};
?>
<footer class="bb3-footer" id="site-footer">
	<div class="bb3-shell">
		<div class="bb3-footer__grid">
			<div class="bb3-footer__intro">
				<a class="bb3-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( bb3_brand() ); ?></a>
				<p class="bb3-footer__lead"><?php echo esc_html( bb3_mod( 'footer_intro' ) ); ?></p>
				<div class="bb3-footer__cta">
					<span><?php esc_html_e( 'Questions about a piece?', 'bali-batik-v3' ); ?></span>
					<a class="bb3-btn bb3-btn--dark" href="<?php echo esc_url( $bb3_contact['url'] ); ?>"<?php echo $bb3_contact['external'] ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo bb3_icon( $bb3_contact['whatsapp'] ? 'whatsapp' : 'chat', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $bb3_contact['label'] ); ?></a>
				</div>
				<?php if ( $bb3_email || $bb3_phone || $bb3_address || $bb3_hours ) : ?>
					<ul class="bb3-footer__contact">
						<?php if ( $bb3_email ) : ?>
							<li><?php echo bb3_icon( 'mail', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><a href="mailto:<?php echo esc_attr( antispambot( $bb3_email ) ); ?>"><?php echo esc_html( antispambot( $bb3_email ) ); ?></a></li>
						<?php endif; ?>
						<?php if ( $bb3_phone ) : ?>
							<li><?php echo bb3_icon( 'phone', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><a href="tel:+<?php echo esc_attr( bb3_normalize_phone( $bb3_phone ) ); ?>"><?php echo esc_html( $bb3_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $bb3_address ) : ?>
							<li><?php echo bb3_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo nl2br( esc_html( $bb3_address ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></li>
						<?php endif; ?>
						<?php if ( $bb3_hours ) : ?>
							<li><?php echo bb3_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $bb3_hours ); ?></span></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
				<?php if ( bb3_socials() ) : ?>
					<div class="bb3-socials bb3-socials--dark">
						<?php foreach ( bb3_socials() as $bb3_key => $bb3_social ) : ?>
							<a href="<?php echo esc_url( $bb3_social['url'] ); ?>" target="_blank" rel="noopener me"><?php echo bb3_icon( $bb3_key, 18, $bb3_social['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<nav aria-labelledby="bb3-foot-collection">
				<h2 id="bb3-foot-collection"><?php esc_html_e( 'Collection', 'bali-batik-v3' ); ?></h2>
				<ul>
					<?php foreach ( bb3_categories( 0 ) as $bb3_term ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( $bb3_term->name ); ?></a></li>
					<?php endforeach; ?>
					<li><a href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'All batik', 'bali-batik-v3' ); ?></a></li>
				</ul>
			</nav>

			<nav aria-labelledby="bb3-foot-help">
				<h2 id="bb3-foot-help"><?php esc_html_e( 'Help', 'bali-batik-v3' ); ?></h2>
				<?php
				$bb3_help = array();
				if ( bb3_contact_page_url() ) {
					$bb3_help[] = '<a href="' . esc_url( bb3_contact_page_url() ) . '">' . esc_html__( 'Contact us', 'bali-batik-v3' ) . '</a>';
				}
				$bb3_help[] = '<a href="' . esc_url( $bb3_contact['url'] ) . '"' . ( $bb3_contact['external'] ? ' target="_blank" rel="noopener"' : '' ) . '>' . esc_html__( 'Ask about a product', 'bali-batik-v3' ) . '</a>';
				$bb3_help[] = '<button type="button" class="bb3-plain-btn" data-bb3-fav-open>' . esc_html__( 'My favorites', 'bali-batik-v3' ) . '</button>';
				$bb3_links( 'footer_help', $bb3_help );
				?>
			</nav>

			<nav aria-labelledby="bb3-foot-info">
				<h2 id="bb3-foot-info"><?php esc_html_e( 'Information', 'bali-batik-v3' ); ?></h2>
				<?php
				$bb3_info = array();
				if ( $bb3_about && 'publish' === $bb3_about->post_status ) {
					$bb3_info[] = '<a href="' . esc_url( get_permalink( $bb3_about ) ) . '">' . esc_html__( 'About us', 'bali-batik-v3' ) . '</a>';
				}
				if ( $bb3_privacy && 'publish' === get_post_status( $bb3_privacy ) ) {
					$bb3_info[] = '<a href="' . esc_url( get_permalink( $bb3_privacy ) ) . '">' . esc_html__( 'Privacy policy', 'bali-batik-v3' ) . '</a>';
				}
				$bb3_info[] = '<a href="' . esc_url( bb3_collection_url() . '?sort=latest' ) . '">' . esc_html__( 'New arrivals', 'bali-batik-v3' ) . '</a>';
				$bb3_links( 'footer_info', $bb3_info );
				?>
			</nav>
		</div>

		<div class="bb3-footer__bottom">
			<p><?php echo esc_html( str_replace( '{year}', wp_date( 'Y' ), (string) bb3_mod( 'copyright' ) ) ); ?></p>
			<p><?php esc_html_e( 'Prices in Indonesian Rupiah (IDR). Orders are confirmed by message.', 'bali-batik-v3' ); ?></p>
		</div>
	</div>
</footer>
