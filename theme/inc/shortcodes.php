<?php
/**
 * Shortcodes
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode(
	'volna_site_url',
	function () {
		return site_url();
	}
);

add_shortcode(
	'volna_upload_dir_url',
	function () {
		return wp_get_upload_dir()['url'];
	}
);
