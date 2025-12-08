<?php
/**
 * Articles page
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
	Template Name: Статьи
	Template Post Type: page
*/

?>
<?php get_header(); ?>
<?php the_post(); ?>
<?php get_template_part( 'template-parts/loop', '', array( 'target_post_type' => 'volna-article' ) ); ?>
<?php
get_footer();
