<?php
/**
 * Template Name: Contact
 *
 * Shows only the contact channels that are filled in.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

get_header();
$bb3_wa      = bb3_whatsapp_url( __( 'Hello, I have a question about your batik collection.', 'bali-batik-v3' ) );
$bb3_email   = sanitize_email( (string) bb3_mod( 'email' ) );
$bb3_phone   = (string) bb3_mod( 'phone' );
$bb3_address = (string) bb3_mod( 'address' );
$bb3_hours   = (string) bb3_mod( 'hours' );
while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="bb3-main bb3-page">
		<div class="bb3-shell bb3-shell--narrow">
			<?php bb3_breadcrumbs_html(); ?>
			<article <?php post_class( 'bb3-page__article' ); ?>>
				<h1 class="bb3-page__title"><?php the_title(); ?></h1>
				<div class="bb3-prose"><?php the_content(); ?></div>
				<ul class="bb3-contact-cards">
					<?php if ( $bb3_wa ) : ?>
						<li><?php echo bb3_icon( 'whatsapp', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><h2><?php esc_html_e( 'WhatsApp', 'bali-batik-v3' ); ?></h2><p><?php esc_html_e( 'The quickest way to ask about a piece.', 'bali-batik-v3' ); ?></p><a class="bb3-btn bb3-btn--hero" href="<?php echo esc_url( $bb3_wa ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Chat on WhatsApp', 'bali-batik-v3' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $bb3_email ) : ?>
						<li><?php echo bb3_icon( 'mail', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><h2><?php esc_html_e( 'E-mail', 'bali-batik-v3' ); ?></h2><p><a href="mailto:<?php echo esc_attr( antispambot( $bb3_email ) ); ?>"><?php echo esc_html( antispambot( $bb3_email ) ); ?></a></p></li>
					<?php endif; ?>
					<?php if ( $bb3_phone ) : ?>
						<li><?php echo bb3_icon( 'phone', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><h2><?php esc_html_e( 'Phone', 'bali-batik-v3' ); ?></h2><p><a href="tel:+<?php echo esc_attr( bb3_normalize_phone( $bb3_phone ) ); ?>"><?php echo esc_html( $bb3_phone ); ?></a></p></li>
					<?php endif; ?>
					<?php if ( $bb3_address ) : ?>
						<li><?php echo bb3_icon( 'pin', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><h2><?php esc_html_e( 'Address', 'bali-batik-v3' ); ?></h2><p><?php echo nl2br( esc_html( $bb3_address ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></li>
					<?php endif; ?>
					<?php if ( $bb3_hours ) : ?>
						<li><?php echo bb3_icon( 'clock', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><h2><?php esc_html_e( 'Opening hours', 'bali-batik-v3' ); ?></h2><p><?php echo esc_html( $bb3_hours ); ?></p></li>
					<?php endif; ?>
				</ul>
				<?php if ( ! $bb3_wa && ! $bb3_email && ! $bb3_phone && ! $bb3_address ) : ?>
					<p class="bb3-notice"><?php esc_html_e( 'Contact details will appear here soon.', 'bali-batik-v3' ); ?></p>
					<?php if ( current_user_can( 'customize' ) ) : ?>
						<p class="bb3-notice bb3-notice--admin"><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=bb3_contact' ) ); ?>"><?php esc_html_e( 'Admin: add WhatsApp, e-mail, phone or address in Customize → Bali Batik → Brand & Contact.', 'bali-batik-v3' ); ?></a></p>
					<?php endif; ?>
				<?php endif; ?>
			</article>
		</div>
	</main>
	<?php
endwhile;
get_footer();
