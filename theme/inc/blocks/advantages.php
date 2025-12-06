<?php
/**
 * Block advantages
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
		$block_name  = 'advantages';
		$block_title = __( 'Преимущества', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'advantages', __( 'Карточки', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
									Field::make( 'textarea', 'title', __( 'Заголовок', 'volna' ) )
									->set_rows( 2 ),
									Field::make( 'rich_text', 'desc', __( 'Описание', 'volna' ) ),
								)
							)
							->set_header_template( '<%= title %>' ),
						Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
							->set_width( 50 )
							->set_default_value( __( 'Подобрать участок', 'volna' ) ),
						Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
							->set_width( 50 )
							->set_help_text( __( 'Post ID или URL', 'volna' ) )
							->set_default_value( '#calculator' ),
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
					if ( $fields['volna_section_title'] || $fields['advantages'] || ( $fields['btn_text'] && $fields['btn_link'] ) ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['advantages'] ) : ?>
									<div class="volna-advantages-cards">
										<?php foreach ( $fields['advantages'] as $item ) : ?>
											<div class="volna-advantages-card">
												<?php if ( $item['img'] ) : ?>
													<div class="volna-advantages-card-img volna-cover-img">
														<?php echo wp_get_attachment_image( $item['img'], 'full' ); ?>
													</div>
												<?php endif; ?>
												<?php if ( $item['title'] || $item['desc'] || $item['btn_text'] ) : ?>
													<div class="volna-advantages-card-body">
														<?php if ( $item['title'] ) : ?>
															<div class="volna-advantages-card-title volna-h5">
																<?php echo wp_kses_post( nl2br( $item['title'] ) ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['desc'] ) : ?>
															<div class="volna-advantages-card-desc volna-content-text">
																<?php
																	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																	echo apply_filters( 'the_content', $item['desc'] );
																?>
															</div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php if ( $fields['btn_text'] && $fields['btn_link'] ) : ?>
									<div class="volna-read-more-btn">
										<a href="<?php echo esc_attr( volna_get_btn_link( $fields['btn_link'] ) ); ?>" class="volna-btn">
											<?php echo esc_html( $fields['btn_text'] ); ?>
										</a>
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
