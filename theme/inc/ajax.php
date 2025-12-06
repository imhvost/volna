<?php
/**
 * Ajax functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax create order
 *
 * @return void
 */
function volna_get_product() {
	$fields = volna_check_text_fields(
		array(
			'target_post_id',
			'target_post_type',
		)
	);

	if ( get_post_type( $fields['target_post_id'] ) !== $fields['target_post_type'] || 'publish' !== get_post_status( $fields['target_post_id'] ) ) {
		wp_die();
	}

	ob_start();
	get_template_part(
		'template-parts/product',
		'',
		array(
			'target_post_id'   => $fields['target_post_id'],
			'target_post_type' => $fields['target_post_type'],
		)
	);
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo ob_get_clean();

	wp_die();
}

add_action( 'wp_ajax_nopriv_volna_get_product', 'volna_get_product' );
add_action( 'wp_ajax_volna_get_product', 'volna_get_product' );
