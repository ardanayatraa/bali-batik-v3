<?php
/**
 * Announcement bar (refreshes live in the Customizer).
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_messages = array_values( array_filter( array( bb3_mod( 'announce_1' ), bb3_mod( 'announce_2' ), bb3_mod( 'announce_3' ) ) ) );
?>
<div class="bb3-topbar">
	<div class="bb3-shell bb3-topbar__inner">
		<div class="bb3-socials">
			<?php foreach ( bb3_socials() as $bb3_key => $bb3_social ) : ?>
				<a href="<?php echo esc_url( $bb3_social['url'] ); ?>" target="_blank" rel="noopener me"><?php echo bb3_icon( $bb3_key, 17, $bb3_social['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php endforeach; ?>
		</div>
		<?php if ( $bb3_messages ) : ?>
			<div class="bb3-announce" data-bb3-announce>
				<?php if ( count( $bb3_messages ) > 1 ) : ?>
					<button type="button" class="bb3-announce__btn" data-dir="-1" aria-label="<?php esc_attr_e( 'Previous message', 'bali-batik-v3' ); ?>"><?php echo bb3_icon( 'chevron-left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
				<?php endif; ?>
				<div class="bb3-announce__track">
					<?php foreach ( $bb3_messages as $bb3_i => $bb3_message ) : ?>
						<p<?php echo $bb3_i ? ' hidden' : ''; ?>><?php echo esc_html( $bb3_message ); ?></p>
					<?php endforeach; ?>
				</div>
				<?php if ( count( $bb3_messages ) > 1 ) : ?>
					<button type="button" class="bb3-announce__btn" data-dir="1" aria-label="<?php esc_attr_e( 'Next message', 'bali-batik-v3' ); ?>"><?php echo bb3_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<p class="bb3-locale"><?php esc_html_e( 'Prices in IDR', 'bali-batik-v3' ); ?></p>
	</div>
</div>
