<?php
/**
 * Block telegram
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
		$block_name  = 'telegram';
		$block_title = __( 'Telegram', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
							->set_rows( 3 ),
						Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
							->set_width( 50 ),
						Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
							->set_width( 50 ),
						Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
					)
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
					if ( $fields['volna_section_title'] || $fields['desc'] || ( $fields['btn_text'] && $fields['btn_link'] ) || $fields['img'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<div class="volna-telegram-main">
									<div class="volna-telegram-body">
										<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
										<?php if ( $fields['desc'] ) : ?>
											<div class="volna-telegram-desc">
												<?php echo wp_kses_post( nl2br( $fields['desc'] ) ); ?>
											</div>
										<?php endif; ?>
										<?php if ( $fields['btn_link'] && $fields['btn_text'] ) : ?>
											<a href="<?php echo esc_attr( volna_get_btn_link( $fields['btn_link'] ) ); ?>" class="volna-btn volna-btn-white">
												<?php echo esc_html( $fields['btn_text'] ); ?>
											</a>
										<?php endif; ?>
									</div>
									<?php if ( $fields['img'] ) : ?>
										<div class="volna-telegram-img">
											<?php echo wp_get_attachment_image( $fields['img'], 'full' ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
