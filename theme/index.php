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
<main class="volna-page-main">
	<div class="volna-container">
		<div class="volna-page-head">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			<h1 class="volna-page-title volna-h1">
				<?php the_title(); ?>
			</h1>
		</div>
	</div>
	<div class="volna-page-content volna-content-text volna-section">
		<?php the_content(); ?>
	</div>
</main>
<?php
get_footer();
