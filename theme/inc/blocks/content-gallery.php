<?php
/**
 * Block content-gallery
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action(
	'carbon_fields_register_fields',
	function () {
		$block_name  = 'content-gallery';
		$block_title = __( 'Контент галерея', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array(
					Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
					Field::make( 'media_gallery', 'gallery', __( 'Галерея', 'volna' ) )
							->set_type( array( 'image' ) ),
				)
			)
			->set_keywords( array( $block_title ) )
			->set_category( 'theme_blocks', __( 'Блоки теми', 'volna' ) )
			->set_description( $block_title )
			->set_mode( 'both' )
			->set_render_callback(
				function ( $fields, $attributes ) use ( $block_name ) {
					if ( volna_render_block_preview_in_admin( $fields, $block_name ) ) {
						return;
					}

					if ( $fields['gallery'] ) :
						$block_id = uniqid();
						?>
						<div
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
						>
							<div class="volna-container volna-slider-wrapp">
								<div class="volna-content-gallery-slider swiper">
									<div class="swiper-wrapper">
										<?php foreach ( $fields['gallery'] as $item ) : ?>
											<a
												href="<?php echo esc_url( wp_get_attachment_image_url( $item, 'full' ) ); ?>"
												class="volna-gallery-link volna-cover-img glightbox swiper-slide"
												data-gallery="gallery-<?php echo esc_attr( $block_id ); ?>"
											>
												<?php echo wp_get_attachment_image( $item, 'full' ); ?>
												<svg class="volna-icon"><use xlink:href="#icon-zoom-in"/></svg>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
								<?php get_template_part( 'template-parts/slider', 'nav' ); ?>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
