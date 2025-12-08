<?php
/**
 * Single volna-article
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php get_header(); ?>
<?php the_post(); ?>
<main class="volna-main-single">
	<div class="volna-container">
		<div class="volna-page-head">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			<h1 class="volna-page-title volna-h1">
				<?php the_title(); ?>
			</h1>
		</div>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="volna-single-image">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		<?php endif; ?>
		<div class="volna-single-body">
			<aside class="volna-single-sidebar volna-sticky-top">
				<ul class="volna-single-toc"></ul>
				<?php get_template_part( 'template-parts/share' ); ?>
				<div class="v">
					<?php esc_html_e( 'Опубликовано', 'volna' ); ?>
					<span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
				</div>
			</aside>
			<div class="volna-single-content">
				<?php if ( has_excerpt() ) : ?>
				<div class="volna-single-excerpt">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>
				<div class="volna-single-content-text volna-content-text">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
$articles = get_posts(
	array(
		'post_type'      => 'volna-article',
		'posts_per_page' => 6,
		'fields'         => 'ids',
		'post__not_in'   => array( $post->ID ),
	)
);
if ( $articles ) :
	$page_articles = volna_get_page_id_by_template( 'page-articles.php' );
	?>
<div class="volna-section volna-articles-other">
	<div class="volna-container">
		<div class="volna-section-title volna-h2">
			<span><?php esc_html_e( 'Другие', 'volna' ); ?></span>
			<?php esc_html_e( 'статьи', 'volna' ); ?>
		</div>
		<div class="volna-articles-list">
			<?php
			foreach ( $articles as $target_post_id ) {
				get_template_part( 'template-parts/article', 'item', array( 'target_post_id' => $target_post_id ) );
			}
			?>
		</div>
		<?php if ( $page_articles ) : ?>
			<div class="volna-read-more-btn">
				<a href="<?php echo esc_url( get_the_permalink( $page_articles ) ); ?>" class="volna-btn">
					<?php esc_html_e( 'Все статьи', 'volna' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
<?php
get_footer();
