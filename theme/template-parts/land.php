<?php
/**
 * Land
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$target_post_id = $args['target_post_id'] ?? null;

if ( $target_post_id ) :
	$volna_price           = carbon_get_post_meta( $target_post_id, 'volna_price' );
	$volna_price_area      = carbon_get_post_meta( $target_post_id, 'volna_price_area' );
	$volna_gallery         = carbon_get_post_meta( $target_post_id, 'volna_gallery' );
	$volna_characteristics = carbon_get_post_meta( $target_post_id, 'volna_characteristics' );
	$target_content        = get_the_content( null, false, $target_post_id );
	?>
<div class="volna-product">
	<div class="volna-product-head">
		<div class="volna-product-title volna-h2">
			<?php echo esc_html( get_the_title( $target_post_id ) ); ?>
		</div>
		<?php if ( $volna_price || $volna_price_area ) : ?>
			<div class="volna-product-prices">
				<?php if ( $volna_price ) : ?>
					<div class="volna-product-price volna-h4">
						<?php esc_html_e( 'от', 'volna' ); ?>
						<span>
							<?php echo esc_html( number_format( $volna_price, 0, '', ' ' ) ); ?> ₽
						</span>
					</div>
				<?php endif; ?>
				<?php if ( $volna_price_area ) : ?>
					<div class="volna-product-price-area">
						<?php esc_html_e( 'от', 'volna' ); ?>
						<span>
							<?php echo esc_html( number_format( $volna_price_area, 0, '', ' ' ) ); ?> ₽
						</span>
						<?php esc_html_e( 'за м²', 'volna' ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $volna_gallery ) : ?>
		<div class="volna-product-gallery">
			<div class="volna-product-gallery-slider swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $volna_gallery as $item ) : ?>
						<div class="volna-product-gallery-slide volna-cover-img swiper-slide">
							<?php echo wp_get_attachment_image( $item, 'full' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="volna-product-gallery-nav swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $volna_gallery as $item ) : ?>
						<div class="volna-product-gallery-nav-slide volna-cover-img swiper-slide">
							<?php echo wp_get_attachment_image( $item, 'full' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="volna-product-body">
		<?php if ( $volna_characteristics ) : ?>
			<div class="volna-product-section">
				<div class="volna-product-section-title">
					<?php esc_html_e( 'Характеристики', 'volna' ); ?>
				</div>
				<ul class="volna-product-characteristics volna-content-custom-list">
					<?php foreach ( $volna_characteristics as $item ) : ?>
						<li>
							<span>
								<?php echo esc_html( $item['title'] ); ?>
							</span>
							<span>
								<?php echo esc_html( $item['desc'] ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ( $target_content ) : ?>
			<div class="volna-product-section">
				<div class="volna-product-section-title">
					<?php esc_html_e( 'О проекте', 'volna' ); ?>
				</div>
				<div class="volna-product-content volna-content-text">
					<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo apply_filters( 'the_content', $target_content );
					?>
				</div>
			</div>
		<?php endif; ?>
		<button class="volna-btn" data-modal-open="modal-application" data-post-id="<?php esc_attr( $target_post_id ); ?>">
			<?php esc_html_e( 'Узнать детали', 'volna' ); ?>
		</button>
	</div>
</div>
<?php endif; ?>
