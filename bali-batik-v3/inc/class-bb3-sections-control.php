<?php
/**
 * Customizer control: sortable list of homepage sections with show/hide boxes.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

class BB3_Sections_Control extends WP_Customize_Control {

	/** @var string */
	public $type = 'bb3-sections';

	public function render_content() {
		$labels = bb3_home_section_labels();
		echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
		echo '<ul class="bb3-sections-list">';
		foreach ( explode( ',', bb3_sanitize_sections( $this->value() ) ) as $item ) {
			$key   = ltrim( $item, '-' );
			$label = $labels[ $key ];
			echo '<li data-key="' . esc_attr( $key ) . '"><span class="bb3-sec-handle dashicons dashicons-menu" aria-hidden="true"></span>';
			echo '<label><input type="checkbox" ' . checked( ! str_starts_with( $item, '-' ), true, false ) . '> ' . esc_html( $label ) . '</label>';
			/* translators: %s: section name. */
			echo '<button type="button" class="button-link bb3-sec-up" aria-label="' . esc_attr( sprintf( __( 'Move %s up', 'bali-batik-v3' ), $label ) ) . '"><span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span></button>';
			/* translators: %s: section name. */
			echo '<button type="button" class="button-link bb3-sec-down" aria-label="' . esc_attr( sprintf( __( 'Move %s down', 'bali-batik-v3' ), $label ) ) . '"><span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span></button></li>';
		}
		echo '</ul><p class="description">' . esc_html__( 'Tip: in the preview, click the pencil next to any section to edit its text and images.', 'bali-batik-v3' ) . '</p>';
	}
}
