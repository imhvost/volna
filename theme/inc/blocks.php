<?php
/**
 * Gutenberg blocks
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'block_categories_all',
	function ( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'theme_blocks',
					'title' => __( 'Theme Blocks', 'volna' ),
				),
			),
			$categories
		);
	},
	10
);

add_action(
	'after_setup_theme',
	function () {
		if ( class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
			$blocks_dir = get_template_directory() . '/inc/blocks/';
			foreach ( glob( $blocks_dir . '*.php' ) as $item ) {
				require_once $item;
			}

			\Carbon_Fields\Carbon_Fields::boot();
		}
	}
);

/**
 * Return block preview image in admin
 *
 * @param string $fields Block fields.
 * @param string $image_slug Image slug.
 * @return boolean Image html.
 */
function volna_render_block_preview_in_admin( $fields, $image_slug = '' ) {
	$preview = $fields['preview'] ?? null;
	if ( ! $preview && defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		if ( $image_slug ) {
			echo '<img src="' . esc_url( get_template_directory_uri() ) . '/img/blocks/' . esc_attr( $image_slug ) . '.webp" class="volna-block-preview-img" style="display: block; max-width: 100%; height: auto; margin:0 auto;" alt="">';
		}
		return true;
	}
	return false;
}
