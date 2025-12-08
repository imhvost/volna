<?php
/**
 * Breadcrumbs
 *
 * @package volna
 */

?>
<ul class="volna-breadcrumbs">
	<li>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Главная', 'volna' ); ?>
		</a>
	</li>
	<?php
	if ( 'page' === get_post_type() ) :
		$ancestors = get_post_ancestors( get_the_ID() );
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
	<?php
		$page_id    = null;
		$post_types = array(
			'volna-article' => 'page-articles.php',
		);
		foreach ( $post_types as $key => $page_template ) {
			if ( get_post_type() === $key ) {
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
	if ( 'page' !== get_post_type() ) :
		$ancestors = get_post_ancestors( get_the_ID() );
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
		?>
	<li>
		<span><?php echo esc_html( $queried_object->name ); ?></span>
	</li>
	<?php endif; ?>
	<?php if ( is_404() ) : ?>
	<li>
		<span>404</span>
	</li>
	<?php endif; ?>
</ul>
