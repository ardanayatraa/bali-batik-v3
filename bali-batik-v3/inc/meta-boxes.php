<?php
/**
 * Product details, gallery, SEO overrides and category fields.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

/** Product fields: key => [label, type, hint]. Meta keys are "_bb_{key}". */
function bb3_product_fields(): array {
	return array(
		'price'         => array( __( 'Price (IDR)', 'bali-batik-v3' ), 'price', __( 'Whole number without dots, e.g. 385000. Leave empty to show “Price on request”.', 'bali-batik-v3' ) ),
		'compare_price' => array( __( 'Price before discount (IDR)', 'bali-batik-v3' ), 'price', __( 'Optional. Only fill in a real previous price. It must be higher than the price, otherwise it is cleared.', 'bali-batik-v3' ) ),
		'sku'           => array( __( 'SKU', 'bali-batik-v3' ), 'text', '' ),
		'motif'         => array( __( 'Motif', 'bali-batik-v3' ), 'text', __( 'e.g. Kawung, Parang, Mega Mendung.', 'bali-batik-v3' ) ),
		'material'      => array( __( 'Material', 'bali-batik-v3' ), 'text', __( 'e.g. Cotton, Rayon, Silk.', 'bali-batik-v3' ) ),
		'technique'     => array( __( 'Technique', 'bali-batik-v3' ), 'text', __( 'Only if factual: hand-drawn (tulis), stamped (cap), combination, or printed.', 'bali-batik-v3' ) ),
		'dimensions'    => array( __( 'Size', 'bali-batik-v3' ), 'text', __( 'Include units, e.g. 115 × 250 cm or S, M, L.', 'bali-batik-v3' ) ),
		'color'         => array( __( 'Color', 'bali-batik-v3' ), 'text', '' ),
		'care'          => array( __( 'Care instructions', 'bali-batik-v3' ), 'textarea', '' ),
	);
}

function bb3_availability_choices(): array {
	return array(
		''             => __( 'Not shown', 'bali-batik-v3' ),
		'in_stock'     => __( 'In stock', 'bali-batik-v3' ),
		'made_to_order' => __( 'Made to order', 'bali-batik-v3' ),
		'sold_out'     => __( 'Sold out', 'bali-batik-v3' ),
	);
}

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'bb3_product_details', __( 'Product Details', 'bali-batik-v3' ), 'bb3_render_product_box', 'bb_product', 'normal', 'high' );
		add_meta_box( 'bb3_product_gallery', __( 'Photo Gallery', 'bali-batik-v3' ), 'bb3_render_gallery_box', 'bb_product', 'side', 'default' );
		foreach ( array( 'bb_product', 'page', 'post' ) as $type ) {
			add_meta_box( 'bb3_seo', __( 'Search & Sharing', 'bali-batik-v3' ), 'bb3_render_seo_box', $type, 'normal', 'low' );
		}
	}
);

function bb3_render_product_box( WP_Post $post ): void {
	wp_nonce_field( 'bb3_save_product', 'bb3_product_nonce' );
	echo '<div class="bb3-admin-fields">';
	if ( get_post_meta( $post->ID, '_bb3_demo', true ) ) {
		echo '<p class="bb3-admin-demo">' . esc_html__( 'Demo product: the name, price and illustration are samples, not real stock. Replace them or delete this product before launch.', 'bali-batik-v3' ) . '</p>';
	}
	echo '<p class="bb3-admin-tip">' . esc_html__( 'Short summary goes in “Excerpt”, the main photo in “Main product photo”, and the full story in the editor above.', 'bali-batik-v3' ) . '</p>';
	foreach ( bb3_product_fields() as $key => [ $label, $type, $hint ] ) {
		$value = bb3_meta( $post->ID, $key );
		$id    = 'bb3_' . $key;
		echo '<p class="bb3-admin-field"><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
		if ( 'textarea' === $type ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="3" class="large-text">' . esc_textarea( (string) $value ) . '</textarea>';
		} elseif ( 'price' === $type ) {
			echo '<input type="number" min="0" step="1" inputmode="numeric" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( '' === $value ? '' : (string) (int) $value ) . '" class="regular-text">';
		} else {
			echo '<input type="text" maxlength="100" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( (string) $value ) . '" class="regular-text">';
		}
		if ( $hint ) {
			echo '<br><span class="description">' . esc_html( $hint ) . '</span>';
		}
		echo '</p>';
	}
	$availability = (string) bb3_meta( $post->ID, 'availability' );
	echo '<p class="bb3-admin-field"><label for="bb3_availability"><strong>' . esc_html__( 'Availability', 'bali-batik-v3' ) . '</strong></label><br><select id="bb3_availability" name="bb3_availability">';
	foreach ( bb3_availability_choices() as $value => $label ) {
		echo '<option value="' . esc_attr( $value ) . '" ' . selected( $availability, $value, false ) . '>' . esc_html( $label ) . '</option>';
	}
	echo '</select><br><span class="description">' . esc_html__( 'Only choose a status you keep up to date. It is shown on the product page and to search engines.', 'bali-batik-v3' ) . '</span></p>';

	$featured = (bool) bb3_meta( $post->ID, 'featured' );
	echo '<p><label><input type="checkbox" name="bb3_featured" value="1" ' . checked( $featured, true, false ) . '> <strong>' . esc_html__( 'Our Pick', 'bali-batik-v3' ) . '</strong> — ' . esc_html__( 'shows in the “Our Picks” section on the homepage.', 'bali-batik-v3' ) . '</label></p>';
	echo '<p class="description">' . esc_html__( 'Display order is set in “Page Attributes → Order” (lower numbers first).', 'bali-batik-v3' ) . '</p>';
	echo '</div>';
}

function bb3_render_gallery_box( WP_Post $post ): void {
	$ids = bb3_gallery_ids( $post->ID );
	echo '<div class="bb3-gallery-field" data-bb3-gallery>';
	echo '<input type="hidden" name="bb3_gallery" value="' . esc_attr( implode( ',', $ids ) ) . '">';
	echo '<ul class="bb3-gallery-list">';
	foreach ( $ids as $id ) {
		echo '<li data-id="' . esc_attr( (string) $id ) . '">' . wp_get_attachment_image( $id, 'thumbnail' ) . '<button type="button" class="bb3-gallery-remove" aria-label="' . esc_attr__( 'Remove photo', 'bali-batik-v3' ) . '">×</button></li>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</ul>';
	echo '<p><button type="button" class="button bb3-gallery-add">' . esc_html__( 'Add or arrange photos', 'bali-batik-v3' ) . '</button></p>';
	echo '<p class="description">' . esc_html__( 'Up to 10 photos. Drag to reorder. Add “Alternative text” to each photo in the Media Library.', 'bali-batik-v3' ) . '</p>';
	echo '</div>';
}

function bb3_render_seo_box( WP_Post $post ): void {
	wp_nonce_field( 'bb3_save_seo', 'bb3_seo_nonce' );
	$title = (string) get_post_meta( $post->ID, '_bb3_seo_title', true );
	$desc  = (string) get_post_meta( $post->ID, '_bb3_seo_desc', true );
	$image = (int) get_post_meta( $post->ID, '_bb3_og_image', true );
	if ( bb3_seo_plugin_active() ) {
		echo '<p class="description">' . esc_html__( 'An SEO plugin is active, so it controls titles, descriptions and share images. These fields are ignored.', 'bali-batik-v3' ) . '</p>';
	}
	echo '<p><label for="bb3_seo_title"><strong>' . esc_html__( 'Search title', 'bali-batik-v3' ) . '</strong></label><br><input type="text" id="bb3_seo_title" name="bb3_seo_title" value="' . esc_attr( $title ) . '" class="large-text" maxlength="70"><span class="description">' . esc_html__( 'Optional. About 50–60 characters. Leave empty to use the page title.', 'bali-batik-v3' ) . '</span></p>';
	echo '<p><label for="bb3_seo_desc"><strong>' . esc_html__( 'Search description', 'bali-batik-v3' ) . '</strong></label><br><textarea id="bb3_seo_desc" name="bb3_seo_desc" rows="2" class="large-text" maxlength="200">' . esc_textarea( $desc ) . '</textarea><span class="description">' . esc_html__( 'Optional. About 120–155 characters. Leave empty to use the excerpt.', 'bali-batik-v3' ) . '</span></p>';
	echo '<div data-bb3-image><p><strong>' . esc_html__( 'Share image (1200 × 630)', 'bali-batik-v3' ) . '</strong></p><input type="hidden" name="bb3_og_image" value="' . esc_attr( (string) $image ) . '"><div class="bb3-image-preview">' . ( $image ? wp_get_attachment_image( $image, 'medium' ) : '' ) . '</div><button type="button" class="button bb3-image-pick">' . esc_html__( 'Choose image', 'bali-batik-v3' ) . '</button> <button type="button" class="button-link bb3-image-clear">' . esc_html__( 'Remove', 'bali-batik-v3' ) . '</button><p class="description">' . esc_html__( 'Optional. Without it, the main photo or the site’s default share image is used.', 'bali-batik-v3' ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['bb3_seo_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bb3_seo_nonce'] ) ), 'bb3_save_seo' ) ) {
			update_post_meta( $post_id, '_bb3_seo_title', isset( $_POST['bb3_seo_title'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['bb3_seo_title'] ) ), 0, 70 ) : '' );
			update_post_meta( $post_id, '_bb3_seo_desc', isset( $_POST['bb3_seo_desc'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['bb3_seo_desc'] ) ), 0, 200 ) : '' );
			$og = isset( $_POST['bb3_og_image'] ) ? absint( $_POST['bb3_og_image'] ) : 0;
			update_post_meta( $post_id, '_bb3_og_image', $og && wp_attachment_is_image( $og ) ? $og : '' );
		}

		if ( 'bb_product' !== $post->post_type || ! isset( $_POST['bb3_product_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bb3_product_nonce'] ) ), 'bb3_save_product' ) ) {
			return;
		}

		foreach ( bb3_product_fields() as $key => [ , $type ] ) {
			$raw = isset( $_POST[ 'bb3_' . $key ] ) ? (string) wp_unslash( $_POST[ 'bb3_' . $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			if ( 'price' === $type ) {
				$value = '' === trim( $raw ) ? '' : absint( preg_replace( '/\D+/', '', $raw ) );
			} elseif ( 'textarea' === $type ) {
				$value = sanitize_textarea_field( $raw );
			} else {
				$value = mb_substr( sanitize_text_field( $raw ), 0, 100 );
			}
			update_post_meta( $post_id, '_bb_' . $key, $value );
		}

		$price   = get_post_meta( $post_id, '_bb_price', true );
		$compare = get_post_meta( $post_id, '_bb_compare_price', true );
		if ( '' !== $compare && ( '' === $price || (int) $compare <= (int) $price ) ) {
			update_post_meta( $post_id, '_bb_compare_price', '' );
			set_transient( 'bb3_notice_' . get_current_user_id(), __( 'The price before discount was cleared because it was not higher than the price.', 'bali-batik-v3' ), 60 );
		}

		$availability = isset( $_POST['bb3_availability'] ) ? sanitize_key( wp_unslash( $_POST['bb3_availability'] ) ) : '';
		update_post_meta( $post_id, '_bb_availability', array_key_exists( $availability, bb3_availability_choices() ) ? $availability : '' );
		update_post_meta( $post_id, '_bb_featured', empty( $_POST['bb3_featured'] ) ? '' : '1' );

		$gallery = isset( $_POST['bb3_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['bb3_gallery'] ) ) : '';
		$ids     = array_slice( array_values( array_filter( array_map( 'absint', explode( ',', $gallery ) ), fn( $id ) => $id && 'attachment' === get_post_type( $id ) ) ), 0, 10 );
		update_post_meta( $post_id, '_bb_gallery', implode( ',', $ids ) );
	},
	10,
	2
);

add_action(
	'admin_notices',
	function () {
		$notice = get_transient( 'bb3_notice_' . get_current_user_id() );
		if ( $notice ) {
			delete_transient( 'bb3_notice_' . get_current_user_id() );
			echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html( $notice ) . '</p></div>';
		}
	}
);

/* ── Category fields: order and cover image ───────────────────────── */

function bb3_render_term_fields( $term = null ): void {
	$is_edit = $term instanceof WP_Term;
	$cover   = $is_edit ? (int) get_term_meta( $term->term_id, 'bb_cover', true ) : 0;
	$order   = $is_edit ? (int) get_term_meta( $term->term_id, 'bb_order', true ) : 0;
	wp_nonce_field( 'bb3_save_term', 'bb3_term_nonce' );

	$rows = array(
		array( 'bb3_order', __( 'Display order', 'bali-batik-v3' ), '<input type="number" name="bb3_order" id="bb3_order" value="' . esc_attr( (string) $order ) . '" class="small-text">', __( 'Lower numbers show first in menus, the category row and the sidebar.', 'bali-batik-v3' ) ),
		array( 'bb3_cover', __( 'Cover image', 'bali-batik-v3' ), '<div data-bb3-image><input type="hidden" name="bb3_cover" value="' . esc_attr( (string) $cover ) . '"><div class="bb3-image-preview">' . ( $cover ? wp_get_attachment_image( $cover, 'thumbnail' ) : '' ) . '</div><button type="button" class="button bb3-image-pick">' . esc_html__( 'Choose image', 'bali-batik-v3' ) . '</button> <button type="button" class="button-link bb3-image-clear">' . esc_html__( 'Remove', 'bali-batik-v3' ) . '</button></div>', __( 'Portrait photo for the moving category row and share previews. Without it, the first product photo is used.', 'bali-batik-v3' ) ),
	);

	foreach ( $rows as [ $id, $label, $field, $hint ] ) {
		if ( $is_edit ) {
			echo '<tr class="form-field"><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>' . $field . '<p class="description">' . esc_html( $hint ) . '</p></td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			echo '<div class="form-field"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>' . $field . '<p>' . esc_html( $hint ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}
add_action( 'bb_product_cat_add_form_fields', 'bb3_render_term_fields' );
add_action( 'bb_product_cat_edit_form_fields', 'bb3_render_term_fields' );

function bb3_save_term_fields( $term_id ): void {
	if ( ! isset( $_POST['bb3_term_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bb3_term_nonce'] ) ), 'bb3_save_term' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$cover = isset( $_POST['bb3_cover'] ) ? absint( $_POST['bb3_cover'] ) : 0;
	update_term_meta( $term_id, 'bb_cover', $cover && wp_attachment_is_image( $cover ) ? $cover : 0 );
	update_term_meta( $term_id, 'bb_order', isset( $_POST['bb3_order'] ) ? (int) $_POST['bb3_order'] : 0 );
}
add_action( 'created_bb_product_cat', 'bb3_save_term_fields' );
add_action( 'edited_bb_product_cat', 'bb3_save_term_fields' );

/** Keep categories at most three levels deep. */
add_filter(
	'pre_insert_term',
	function ( $term, $taxonomy, $args = array() ) {
		if ( 'bb_product_cat' !== $taxonomy ) {
			return $term;
		}
		$parent = isset( $args['parent'] ) ? (int) $args['parent'] : ( isset( $_POST['parent'] ) ? (int) $_POST['parent'] : 0 ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( $parent > 0 && count( get_ancestors( $parent, 'bb_product_cat' ) ) >= 2 ) {
			return new WP_Error( 'bb3_depth', __( 'Categories can be at most three levels deep (main → sub → sub-sub).', 'bali-batik-v3' ) );
		}
		return $term;
	},
	10,
	3
);

add_action(
	'admin_enqueue_scripts',
	function () {
		$screen = get_current_screen();
		if ( ! $screen || ! ( in_array( $screen->post_type, array( 'bb_product', 'page', 'post' ), true ) || 'bb_product_cat' === $screen->taxonomy ) ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'bb3-admin', BB3_URI . '/assets/css/admin.css', array(), bb3_asset_ver( 'assets/css/admin.css' ) );
		wp_enqueue_script( 'bb3-admin', BB3_URI . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), bb3_asset_ver( 'assets/js/admin.js' ), true );
		wp_localize_script(
			'bb3-admin',
			'bb3Admin',
			array(
				'galleryTitle' => __( 'Choose product photos', 'bali-batik-v3' ),
				'imageTitle'   => __( 'Choose image', 'bali-batik-v3' ),
				'useButton'    => __( 'Use', 'bali-batik-v3' ),
				'remove'       => __( 'Remove photo', 'bali-batik-v3' ),
			)
		);
	}
);
