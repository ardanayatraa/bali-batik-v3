<?php
/**
 * Site header: announcement bar, navigation, brand, actions, mobile drawer.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

$bb3_overlay  = bb3_has_overlay_header();
$bb3_contact  = bb3_contact_action();
$bb3_help_url = bb3_contact_page_url() ?: $bb3_contact['url'];
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="bb3-skip" href="#main"><?php esc_html_e( 'Skip to content', 'bali-batik-v3' ); ?></a>

<?php get_template_part( 'template-parts/site/topbar' ); ?>

<header class="bb3-header<?php echo $bb3_overlay ? ' bb3-header--overlay' : ''; ?>" data-bb3-header>
	<div class="bb3-shell bb3-header__inner">
		<button type="button" class="bb3-icon-btn bb3-header__menu" aria-controls="bb3-drawer" aria-expanded="false" data-bb3-drawer-open>
			<?php echo bb3_icon( 'menu', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'bali-batik-v3' ); ?></span>
		</button>

		<nav class="bb3-nav" aria-label="<?php esc_attr_e( 'Main', 'bali-batik-v3' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'bb3-nav__list',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
			} else {
				echo '<ul class="bb3-nav__list">';
				foreach ( bb3_categories( 0 ) as $bb3_term ) {
					$bb3_children = bb3_categories( $bb3_term->term_id );
					echo '<li class="' . ( $bb3_children ? 'menu-item-has-children' : '' ) . '"><a href="' . esc_url( get_term_link( $bb3_term ) ) . '">' . esc_html( $bb3_term->name ) . ( $bb3_children ? bb3_icon( 'chevron-down', 14 ) : '' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
					if ( $bb3_children ) {
						echo '<ul class="sub-menu">';
						foreach ( $bb3_children as $bb3_child ) {
							echo '<li><a href="' . esc_url( get_term_link( $bb3_child ) ) . '">' . esc_html( $bb3_child->name ) . '</a></li>';
						}
						/* translators: %s: category name. */
						echo '<li class="bb3-nav__all"><a href="' . esc_url( get_term_link( $bb3_term ) ) . '">' . esc_html( sprintf( __( 'All %s', 'bali-batik-v3' ), $bb3_term->name ) ) . '</a></li></ul>';
					}
					echo '</li>';
				}
				echo '<li><a href="' . esc_url( bb3_collection_url() ) . '">' . esc_html__( 'Collection', 'bali-batik-v3' ) . '</a></li>';
				echo '</ul>';
			}
			?>
		</nav>

		<a class="bb3-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			$bb3_logo = (int) get_theme_mod( 'custom_logo' );
			if ( $bb3_logo ) {
				echo wp_get_attachment_image( $bb3_logo, 'medium', false, array( 'alt' => bb3_brand(), 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			} else {
				echo esc_html( bb3_brand() );
			}
			?>
		</a>

		<div class="bb3-header__actions">
			<button type="button" class="bb3-icon-btn" aria-controls="bb3-search-panel" aria-expanded="false" data-bb3-search-toggle>
				<?php echo bb3_icon( 'search', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'bali-batik-v3' ); ?></span>
			</button>
			<a class="bb3-icon-btn bb3-hide-sm" href="<?php echo esc_url( $bb3_help_url ); ?>">
				<?php echo bb3_icon( 'help', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Help and contact', 'bali-batik-v3' ); ?></span>
			</a>
			<button type="button" class="bb3-icon-btn" data-bb3-fav-open aria-haspopup="dialog">
				<?php echo bb3_icon( 'heart', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Favorites', 'bali-batik-v3' ); ?></span>
				<span class="bb3-count" data-bb3-fav-count hidden>0</span>
			</button>
			<a class="bb3-icon-btn" href="<?php echo esc_url( $bb3_contact['url'] ); ?>"<?php echo $bb3_contact['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
				<?php echo bb3_icon( $bb3_contact['whatsapp'] ? 'whatsapp' : 'chat', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php echo esc_html( $bb3_contact['label'] ); ?></span>
			</a>
		</div>
	</div>
	<div class="bb3-search-panel" id="bb3-search-panel" hidden>
		<div class="bb3-shell"><?php bb3_search_form( 'bb3-header-search' ); ?></div>
	</div>
</header>

<div class="bb3-drawer" id="bb3-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'bali-batik-v3' ); ?>" hidden>
	<div class="bb3-drawer__panel">
		<div class="bb3-drawer__head">
			<span class="bb3-logo"><?php echo esc_html( bb3_brand() ); ?></span>
			<button type="button" class="bb3-icon-btn" data-bb3-drawer-close><?php echo bb3_icon( 'close', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'bali-batik-v3' ); ?></span></button>
		</div>
		<?php bb3_search_form( 'bb3-drawer-search' ); ?>
		<nav class="bb3-drawer__nav" aria-label="<?php esc_attr_e( 'Mobile', 'bali-batik-v3' ); ?>">
			<ul>
				<?php foreach ( bb3_categories( 0 ) as $bb3_term ) : ?>
					<?php $bb3_children = bb3_categories( $bb3_term->term_id ); ?>
					<li>
						<?php if ( $bb3_children ) : ?>
							<details>
								<summary><?php echo esc_html( $bb3_term->name ); ?><?php echo bb3_icon( 'chevron-down', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></summary>
								<ul>
									<?php foreach ( $bb3_children as $bb3_child ) : ?>
										<li><a href="<?php echo esc_url( get_term_link( $bb3_child ) ); ?>"><?php echo esc_html( $bb3_child->name ); ?></a></li>
									<?php endforeach; ?>
									<?php /* translators: %s: category name. */ ?>
									<li><a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( sprintf( __( 'All %s', 'bali-batik-v3' ), $bb3_term->name ) ); ?></a></li>
								</ul>
							</details>
						<?php else : ?>
							<a href="<?php echo esc_url( get_term_link( $bb3_term ) ); ?>"><?php echo esc_html( $bb3_term->name ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
				<li><a href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'Full Collection', 'bali-batik-v3' ); ?></a></li>
				<?php if ( get_page_by_path( 'about' ) ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>"><?php esc_html_e( 'About', 'bali-batik-v3' ); ?></a></li>
				<?php endif; ?>
				<li><button type="button" class="bb3-drawer__fav" data-bb3-fav-open><?php esc_html_e( 'Favorites', 'bali-batik-v3' ); ?></button></li>
			</ul>
		</nav>
		<a class="bb3-btn bb3-btn--dark bb3-btn--block" href="<?php echo esc_url( $bb3_contact['url'] ); ?>"<?php echo $bb3_contact['external'] ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo bb3_icon( $bb3_contact['whatsapp'] ? 'whatsapp' : 'chat', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $bb3_contact['label'] ); ?></a>
	</div>
</div>
