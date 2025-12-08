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
<?php get_template_part( 'template-parts/loop', '', array( 'target_post_type' => 'volna-project' ) ); ?>
<?php
get_footer();
