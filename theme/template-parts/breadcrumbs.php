<?php
/**
 * Breadcrumbs
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_page_id     = volna_get_page_id_by_template( 'front-page.php' );
$target_post_type = get_post_type();
$target_post_id   = get_the_ID();
?>
<ul class="volna-breadcrumbs">
	<li>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php echo $home_page_id ? esc_html( get_the_title( $home_page_id ) ) : esc_html__( 'Головна', 'volna' ); ?>
		</a>
	</li>

	<?php
	if ( 'page' === $target_post_type ) :
		$ancestors = get_post_ancestors( $target_post_id );
		if ( $ancestors ) :
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $item ) :
				?>
	<li>
		<a href="<?php echo esc_url( get_the_permalink( $item ) ); ?>">
				<?php echo esc_html( get_the_title( $item ) ); ?>
		</a>
	</li>
				<?php
				endforeach;
		endif;
		endif;
	?>

	<?php
		$is_apartments_page = 'page-apartments.php' === basename( get_page_template() );
	if ( is_singular( 'volna-apartment' ) || $is_apartments_page ) :
		$building_and_floor = volna_get_building_and_floor( $target_post_id, $is_apartments_page );
		if ( $building_and_floor['building'] ) :
			?>
		<li>
			<a href="<?php echo esc_url( get_term_link( (int) $building_and_floor['building']->term_id ) ); ?>">
			<?php esc_html_e( 'Вибір корпусу', 'volna' ); ?>
			</a>
		</li>
			<?php
			endif;
		endif;
	?>

	<?php
		$page_id    = null;
		$post_types = array(
			// 'volna-apartment' => 'page-buildings.php',
		);
		foreach ( $post_types as $key => $page_template ) {
			if ( $target_post_type === $key ) {
				$page_id = volna_get_page_id_by_template( $page_template );
				break;
			}
		}
		if ( $page_id ) :
				$ancestors = get_post_ancestors( $page_id );
			if ( $ancestors ) :
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $item ) :
					?>
	<li>
		<a href="<?php echo esc_url( get_the_permalink( $item ) ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></a>
	</li>
						<?php
						endforeach;
		endif;
			?>
	<li>
		<a href="<?php echo esc_url( get_the_permalink( $page_id ) ); ?>"><?php echo esc_html( get_the_title( $page_id ) ); ?></a>
	</li>
	<?php endif; ?>

	<?php
	if ( 'page' !== $target_post_type ) :
		$ancestors = get_post_ancestors( $target_post_id );
		if ( $ancestors ) :
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $item ) :
				?>
	<li>
		<a href="<?php echo esc_url( get_the_permalink( $item ) ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></a>
	</li>
				<?php
				endforeach;
		endif;
		endif;
	?>

	<?php if ( is_singular() ) : ?>
		<li>
			<span><?php the_title(); ?></span>
		</li>
	<?php endif; ?>

	<?php
	if ( is_tax() ) :
		$queried_object = get_queried_object();
		$term_name      = $queried_object->name;
		if ( is_tax( 'volna-building-floor' ) ) {
			$term_name = __( 'Вибір корпусу', 'volna' );
		}
		?>
		<li>
			<span><?php echo esc_html( $term_name ); ?></span>
		</li>
	<?php endif; ?>

	<?php if ( is_404() ) : ?>
	<li>
		<span>404</span>
	</li>
	<?php endif; ?>
	
</ul>
