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
	$volna_bedrooms  = carbon_get_post_meta( $target_post_id, 'volna_bedrooms' );
	$volna_bathrooms = carbon_get_post_meta( $target_post_id, 'volna_bathrooms' );
	?>
<a
	href="<?php echo esc_url( get_the_permalink() ); ?>?project=<?php echo esc_attr( $target_post_id ); ?>"
	class="volna-product-item volna-project-item"
	data-target-post-id="<?php echo esc_attr( $target_post_id ); ?>"
	data-target-post-type="volna-project"
>
	<div class="volna-product-item-img volna-cover-img">
		<?php
		if ( has_post_thumbnail( $target_post_id ) ) {
			echo get_the_post_thumbnail( $target_post_id, 'medium' );
		}
		?>
		<?php if ( $volna_bedrooms || $volna_bathrooms ) : ?>
			<div class="volna-product-item-rooms">
				<?php if ( $volna_bedrooms ) : ?>
					<div class="volna-product-item-room">
						<svg class="volna-icon"><use xlink:href="#icon-bedrooms"/></svg>
						<span>
							<?php echo esc_html( $volna_bedrooms ); ?>
							<?php
							echo esc_html(
								volna_plural(
									(int) $volna_bedrooms,
									array(
										__( 'спальня', 'volna' ),
										__( 'спальни', 'volna' ),
										__( 'спален', 'volna' ),
									)
								)
							)
							?>
						</span>
					</div>
				<?php endif; ?>
				<?php if ( $volna_bathrooms ) : ?>
					<div class="volna-product-item-room">
						<svg class="volna-icon"><use xlink:href="#icon-bathrooms"/></svg>
						<span>
							<?php echo esc_html( $volna_bathrooms ); ?>
							<?php
							echo esc_html(
								volna_plural(
									(int) $volna_bathrooms,
									array(
										__( 'санузел', 'volna' ),
										__( 'санузла', 'volna' ),
										__( 'санузлов', 'volna' ),
									)
								)
							)
							?>
						</span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<svg class="volna-icon"><use xlink:href="#icon-zoom-in"/></svg>
		<div class="volna-loading"></div>
	</div>
	<div class="volna-product-item-title volna-h5">
		<?php echo esc_html( get_the_title( $target_post_id ) ); ?>
	</div>
	<?php if ( $volna_area || $volna_price ) : ?>
		<div class="volna-product-item-head">
			<div class="volna-product-item-area">
				<?php esc_html_e( 'Площадь', 'volna' ); ?> <?php echo esc_html( $volna_area ); ?> м²
			</div>
			<?php if ( $volna_price ) : ?>
				<div class="volna-product-item-prices">
					<?php if ( $volna_price ) : ?>
						<div class="volna-product-item-price">
							<?php echo esc_html( number_format( $volna_price, 0, '', ' ' ) ); ?> ₽
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</a>
<?php endif; ?>
