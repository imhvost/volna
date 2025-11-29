<?php
/**
 * Admin functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* admin svg sprite */

add_action(
	'admin_footer',
	function () {
		if ( is_admin() ) {
			get_template_part( 'template-parts/sprite' );
		}
	}
);

/* menu order */

add_filter( 'custom_menu_order', '__return_true' );
add_filter(
	'menu_order',
	function ( $menu_order ) {
		if ( ! $menu_order ) {
			return true;
		}

		$upload = array_search( 'upload.php', $menu_order, true );
		if ( false !== $upload ) {
			unset( $menu_order[ $upload ] );
		}

		$page_index = array_search( 'edit.php?post_type=page', $menu_order, true );
		if ( false !== $page_index ) {
			$before = array_slice( $menu_order, 0, $page_index + 1 );
			$after  = array_slice( $menu_order, $page_index + 1 );

			return array_merge( $before, array( 'upload.php' ), $after );
		}

		$menu_order[] = 'upload.php';
		return $menu_order;
	}
);
