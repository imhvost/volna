<?php
/**
 * Index page
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php get_header(); ?>
<?php the_post(); ?>

<?php
get_footer();
