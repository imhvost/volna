<?php
/**
 * Block tour
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
		$block_name  = 'tour';
		$block_title = __( 'Экскурсия', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'checkbox', 'options_messengers', __( 'Мессенджеры из опций темы', 'volna' ) )
							->set_default_value( 'yes' ),
						Field::make( 'complex', 'messengers', __( 'Мессенджеры', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'text', 'title', __( 'Название', 'volna' ) ),
									Field::make( 'image', 'icon', __( 'Иконка', 'volna' ) ),
									Field::make( 'text', 'link', __( 'Ссылка', 'volna' ) ),
								)
							)
							->set_header_template( '<%= title %>' )
							->set_conditional_logic(
								array(
									array(
										'field' => 'options_messengers',
										'value' => false,
									),
								)
							),
						Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
						Field::make( 'textarea', 'img_desc', __( 'Описание картинки', 'volna' ) )
							->set_rows( 3 ),
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
					$messengers = $fields['options_messengers'] ? carbon_get_theme_option( 'volna_messengers' ) : $fields['messengers'];
					?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<div class="volna-tour-main">
									<div class="volna-tour-body">
										<div class="volna-tour-head">
											<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
											<?php
											if ( $messengers ) {
												get_template_part( 'template-parts/messengers', '', array( 'messengers' => $messengers ) );
											}
											?>
										</div>
										<form action="?" class="volna-tour-form volna-contact-form">
											<input type="hidden" name="subject" value="<?php esc_attr_e( 'Заказ экскурсии', 'volna' ); ?>">
											<div class="volna-tour-form-inputs">
												<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Имя', 'volna' ); ?>">
													<input
														type="text"
														name="name"
														class="volna-input volna-input-white"
														required
														placeholder="Имя"
													>
												</label>
												<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Телефон', 'volna' ); ?>">
													<input
														type="tel"
														name="tel"
														class="volna-input volna-input-white"
														required
														placeholder="+7 (999) 999-99-99"
														data-alert="<?php esc_attr_e( 'Введите телефон', 'volna' ); ?>"
													>
												</label>
												<div class="volna-form-block volna-select">
													<div class="volna-select-toggle-wrapp">
														<button type="button" class="volna-select-toggle volna-input volna-input-white">
															<span class="volna-select-toggle-title" data-title="<?php esc_attr_e( 'Формат экскурсии', 'volna' ); ?>">
																<?php esc_html_e( 'Формат экскурсии', 'volna' ); ?>
															</span>
															<svg class="volna-icon volna-select-toggle-arrow"><use xlink:href="#icon-angle-down"/></svg>
														</button>
													</div>
													<div class="volna-select-options">
														<?php
															$options = array(
																__( 'Онлайн', 'volna' ),
																__( 'Выезд', 'volna' ),
															);
														foreach ( $options as $item ) :
															?>
															<label class="volna-select-option">
																<input
																	type="checkbox"
																	name="tour_format"
																	class="volna-select-input"
																	value="<?php echo esc_attr( $item ); ?>"
																>
																<span class="volna-select-option-title"><?php echo esc_html( $item ); ?></span>
															</label>
														<?php endforeach; ?>
													</div>
												</div>
												<button type="submit" class="volna-btn volna-btn-white volna-submit">
													<?php esc_html_e( 'Подобрать участок', 'volna' ); ?>
												</button>
											</div>
											<?php get_template_part( 'template-parts/form', 'agreement', array( 'white' => true ) ); ?>
										</form>
									</div>
									<?php if ( $fields['img'] ) : ?>
										<div class="volna-tour-img">
											<?php echo wp_get_attachment_image( $fields['img'], 'full' ); ?>
											<?php if ( $fields['img_desc'] ) : ?>
												<div class="volna-tour-img-desc">
													<?php echo wp_kses_post( nl2br( $fields['img_desc'] ) ); ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
				}
			);
	}
);
