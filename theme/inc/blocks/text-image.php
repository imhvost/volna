<?php
/**
 * Block text-image
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
		$block_name  = 'text-image';
		$block_title = __( 'Текст - картинка', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'rich_text', 'content', __( 'Контент', 'volna' ) ),
						Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
							->set_width( 50 ),
						Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
							->set_width( 50 )
							->set_help_text( __( 'Post ID или URL', 'volna' ) ),
						Field::make( 'image', 'img', __( 'Картинка', 'volna' ) )
							->set_width( 50 ),
						Field::make( 'select', 'img_position', __( 'Позиция картинки', 'volna' ) )
							->set_options(
								array(
									'left'  => __( 'Слева', 'volna' ),
									'right' => __( 'Справа', 'volna' ),
								)
							)
							->set_default_value( 'left' )
							->set_width( 50 ),
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
					if ( $fields['volna_section_title'] || $fields['content'] || ( $fields['btn_text'] && $fields['btn_link'] ) || $fields['img'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?> volna-<?php echo esc_attr( $block_name ); ?>-<?php echo esc_attr( $fields['img_position'] ?? '' ); ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php if ( $fields['volna_section_title'] || $fields['content'] || ( $fields['btn_text'] && $fields['btn_link'] ) ) : ?>
									<?php if ( $fields['img'] ) : ?>
										<div class="volna-text-image-img">
											<?php echo wp_get_attachment_image( $fields['img'], 'full' ); ?>
										</div>
									<?php endif; ?>
									<div class="volna-text-image-body">
										<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
										<?php if ( $fields['content'] ) : ?>
											<div class="volna-text-image-content volna-content-text">
												<?php
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo apply_filters( 'the_content', $fields['content'] );
												?>
											</div>
										<?php endif; ?>
										<?php if ( $fields['btn_text'] && $fields['btn_link'] ) : ?>
											<a href="<?php echo esc_attr( volna_get_btn_link( $fields['btn_link'] ) ); ?>" class="volna-link">
												<?php echo esc_html( $fields['btn_text'] ); ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
