<?php
/**
 * Rewrite rules
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'init',
	function () {
		$pages_with_pagination = array(
			'page-articles.php',
			'page-lands.php',
			'page-projects.php',
		);
		foreach ( $pages_with_pagination as $item ) {
			$page_id   = volna_get_page_id_by_template( $item );
			$page_slug = get_page_uri( $page_id );
			add_rewrite_rule( '^' . $page_slug . '/page/?([0-9]{1,})/?', 'index.php?page_id=' . $page_id . '&paged=$matches[1]', 'top' );
		}
	}
);
