<?php
/**
 * Home page
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
	Template Name: Главная
	Template Post Type: page
*/

?>
<?php get_header(); ?>
<?php the_post(); ?>
<main class="volna-home-main volna-content-text">
	<?php the_content(); ?>
</main>
<?php
get_footer();
