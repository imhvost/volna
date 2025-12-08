<?php
/**
 * Article item
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$target_post_id = $args['target_post_id'] ?? null;

if ( $target_post_id ) :
	?>
<a
	href="<?php echo esc_url( get_the_permalink( $target_post_id ) ); ?>"
	class="volna-article-item"
>
	<div class="volna-article-item-img volna-cover-img">
		<?php
		if ( has_post_thumbnail( $target_post_id ) ) {
			echo get_the_post_thumbnail( $target_post_id, 'medium' );
		}
		?>
	</div>
	<div class="volna-article-item-body">
		<div class="volna-article-item-date">
			<?php echo esc_html( get_the_date( 'd M Y', $target_post_id ) ); ?>
		</div>
		<div class="volna-article-item-title">
			<?php echo esc_html( get_the_title( $target_post_id ) ); ?>
		</div>
	</div>
</a>
<?php endif; ?>
