<?php
/**
 * Projects page
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
	Template Name: Проекты
	Template Post Type: page
*/

?>
<?php get_header(); ?>
<?php the_post(); ?>
<?php
get_footer();
