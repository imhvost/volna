<?php
/**
 * Loop
 *
 * @package centrmed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$target_post_type = $args['target_post_type'] ?? null;

$current_page = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

$query_args = array(
	'post_type'      => $target_post_type,
	'fields'         => 'ids',
	'posts_per_page' => 'volna-article' === $target_post_type ? 8 : 6,
	'paged'          => $current_page,
);

$query = new WP_Query( $query_args );
?>
<main class="volna-page-main">
	<div class="volna-container">
		<div class="volna-page-head">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			<h1 class="volna-page-title volna-h1">
				<?php the_title(); ?>
			</h1>
		</div>
		<?php if ( $query->have_posts() ) : ?>
			<div class="<?php echo esc_attr( $target_post_type ); ?>s-list">
				<?php
				foreach ( $query->posts as $target_post_id ) {
					get_template_part( 'template-parts/' . str_replace( 'volna-', '', $target_post_type ), 'item', array( 'target_post_id' => $target_post_id ) );
				}
				?>
			</div>
			<?php
				get_template_part(
					'template-parts/pagination',
					'',
					array(
						'current'       => $query_args['paged'],
						'max_num_pages' => $query->max_num_pages,
					)
				);
			?>
		<?php endif; ?>
	</div>
</main>
