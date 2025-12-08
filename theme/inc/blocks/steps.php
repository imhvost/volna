<?php
/**
 * Block steps
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
		$block_name  = 'steps';
		$block_title = __( 'Шаги', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'steps', __( 'Шаги', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
									Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
										->set_rows( 3 ),
								)
							)
							->set_header_template( '<%= title %>' ),
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
					?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['steps'] ) : ?>
									<div class="volna-steps-list">
										<?php foreach ( $fields['steps'] as $item ) : ?>
											<div class="volna-steps-item">
												<div class="volna-steps-item-title">
													<?php if ( $item['title'] ) : ?>
														<div class="volna-steps-item-title-text volna-h5">
															<?php echo esc_html( $item['title'] ); ?>
														</div>
													<?php endif; ?>
												</div>
												<?php if ( $item['desc'] ) : ?>
													<div class="volna-steps-item-desc">
														<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<form action="?" class="volna-steps-form volna-contact-form">
									<input type="hidden" name="subject" value="<?php esc_attr_e( 'Заказ шаги', 'volna' ); ?>">
									<div class="volna-steps-form-inputs">
										<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Имя', 'volna' ); ?>">
											<input
												type="text"
												name="name"
												class="volna-input"
												required
												placeholder="Имя"
											>
										</label>
										<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Телефон', 'volna' ); ?>">
											<input
												type="tel"
												name="tel"
												class="volna-input"
												required
												placeholder="+7 (999) 999-99-99"
												data-alert="<?php esc_attr_e( 'Введите телефон', 'volna' ); ?>"
											>
										</label>
										<button type="submit" class="volna-btn volna-submit">
											<?php esc_html_e( 'Оставить заявку', 'volna' ); ?>
										</button>
									</div>
									<?php get_template_part( 'template-parts/form', 'agreement' ); ?>
								</form>
							</div>
						</div>
						<?php
				}
			);
	}
);
