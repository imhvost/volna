<?php
/**
 * Walkers functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$walkers_dir = get_template_directory() . '/inc/walkers/';
foreach ( glob( $walkers_dir . '*.php' ) as $item ) {
	require_once $item;
}
