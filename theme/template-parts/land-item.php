<?php
/**
 * Land item
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$target_post_id = $args['target_post_id'] ?? null;

if ( $target_post_id ) :
	$volna_area      = carbon_get_post_meta( $target_post_id, 'volna_area' );
	$volna_price     = carbon_get_post_meta( $target_post_id, 'volna_price' );
	$volna_price_old = carbon_get_post_meta( $target_post_id, 'volna_price_old' );

	$excerpt = volna_get_excerpt( $target_post_id );

	$category = get_the_terms( $target_post_id, 'volna-land-category' );
	if ( $category && ! is_wp_error( $category ) ) {
		$category = $category[0];
	}
	?>
<a href="<?php echo esc_url( get_the_permalink() ); ?>?land=<?php echo esc_attr( $target_post_id ); ?>" class="volna-land-item">
	<div class="volna-land-item-img volna-cover-img">
		<?php
		if ( has_post_thumbnail( $target_post_id ) ) {
			echo get_the_post_thumbnail( $target_post_id, 'medium' );
		}
		?>
		<?php if ( $category ) : ?>
			<div class="volna-land-item-category">
				<?php echo esc_html( $category->name ); ?>
			</div>
		<?php endif; ?>
		<svg class="volna-icon"><use xlink:href="#icon-zoom-in"/></svg>
	</div>
	<?php if ( $volna_area || $volna_price || $volna_price_old ) : ?>
		<div class="volna-land-item-head">
			<div class="volna-land-item-area">
				<?php echo esc_html( $volna_area ); ?>
				<?php
					echo esc_html(
						volna_plural(
							(float) $volna_area,
							array(
								__( 'соток', 'volna' ),
								__( 'соток', 'volna' ),
								__( 'соток', 'volna' ),
							)
						)
					);
				?>
			</div>
			<?php if ( $volna_price || $volna_price_old ) : ?>
				<div class="volna-land-item-prices">
					<?php if ( $volna_price_old ) : ?>
						<div class="volna-land-item-price-old">
							<?php echo esc_html( number_format( $volna_price_old, 0, '', ' ' ) ); ?> ₽
						</div>
					<?php endif; ?>
					<?php if ( $volna_price ) : ?>
						<div class="volna-land-item-price">
							<?php echo esc_html( number_format( $volna_price, 0, '', ' ' ) ); ?> ₽
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( $excerpt ) : ?>
		<div class="volna-land-item-excerpt">
			<?php echo esc_html( $excerpt ); ?>
		</div>
	<?php endif; ?>
</a>
<?php endif; ?>
