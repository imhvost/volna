<?php
/**
 * Block calculator
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
		$block_name  = 'calculator';
		$block_title = __( 'Калькулятор', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'quiz', __( 'Квиз', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
								Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
									->set_rows( 2 ),
								Field::make( 'select', 'type', __( 'Тип', 'volna' ) )
									->set_options(
										array(
											'radio'    => __( 'radio', 'volna' ),
											'checkbox' => __( 'checkbox', 'volna' ),
											'form'     => __( 'Форма', 'volna' ),
										)
									)
									->set_default_value( 'radio' ),
								Field::make( 'textarea', 'options', __( 'Опции', 'volna' ) )
									->set_conditional_logic(
										array(
											array(
												'field'   => 'type',
												'compare' => 'NOT IN',
												'value'   => array( 'form' ),
											),
										)
									),
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
					if ( $fields['volna_section_title'] || $fields['quiz'] ) :
						$block_id = wp_unique_id();
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['quiz'] ) : ?>
									<form action="?" class="volna-calculator-form volna-contact-form">
										<div class="volna-calculator-form-slider swiper">
											<div class="swiper-wrapper">
												<?php foreach ( $fields['quiz'] as $key => $item ) : ?>
													<div class="volna-calculator-form-slide swiper-slide">
														<?php if ( $item['title'] || $item['desc'] ) : ?>
															<div class="volna-calculator-form-slide-head">
																<?php if ( $item['title'] ) : ?>
																	<div class="volna-calculator-form-slide-title volna-h4">
																		<?php echo esc_html( $item['title'] ); ?>
																	</div>
																<?php endif; ?>
																<?php if ( $item['desc'] ) : ?>
																	<div class="volna-calculator-form-slide-desc">
																		<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
																	</div>
																<?php endif; ?>
															</div>
														<?php endif; ?>
														<?php if ( 'form' === $item['type'] ) : ?>
															<div class="volna-calculator-form-slide-inputs">
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
																<?php get_template_part( 'template-parts/form', 'agreement' ); ?>
															</div>
															<?php
															else :
																$options = preg_split( '/\r\n|\r|\n/', $item['options'] );
																if ( $options ) :
																	?>
																<div class="volna-calculator-form-slide-options">
																	<?php foreach ( $options as $option ) : ?>
																		<label class="volna-<?php echo esc_attr( $item['type'] ); ?>-label">
																			<input
																				type="<?php echo esc_attr( $item['type'] ); ?>"
																				class="volna-<?php echo esc_attr( $item['type'] ); ?>-input"
																				required
																				name="calculator-option-<?php echo esc_attr( $block_id ); ?>-<?php echo esc_attr( $key ); ?>"
																				value="<?php echo esc_attr( $option ); ?>"
																			>
																			<i class="volna-<?php echo esc_attr( $item['type'] ); ?>-label-icon">
																				<?php if ( 'checkbox' === $item['type'] ) : ?>
																					<svg class="volna-icon"><use xlink:href="#icon-check"/></svg>
																				<?php endif; ?>
																			</i>
																			<span class="volna-<?php echo esc_attr( $item['type'] ); ?>-label-title">
																				<?php echo esc_html( $option ); ?>
																			</span>
																		</label>
																	<?php endforeach; ?>
																</div>
																	<?php
																endif;
															endif;
															?>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
										<div class="volna-calculator-form-nav">
											<div class="volna-calculator-form-pagination" data-title="<?php esc_attr_e( 'Шаг', 'volna' ); ?>" data-divider="<?php esc_attr_e( 'из', 'volna' ); ?>"></div>
											<div class="volna-calculator-form-btns">
												<button type="button" class="volna-calculator-form-btn-prev" aria-label="<?php esc_attr_e( 'Назад', 'volna' ); ?>">
													<svg class="volna-icon"><use xlink:href="#icon-chevron-left"/></svg>
												</button>
												<button type="submit" class="volna-btn volna-calculator-form-btn-next" data-title="<?php esc_attr_e( 'Далее', 'volna' ); ?>" data-submit="<?php esc_attr_e( 'Получить предложения', 'volna' ); ?>">
													<?php esc_html_e( 'Далее', 'volna' ); ?>
												</button>
											</div>
										</div>
									</form>
								<?php endif; ?>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
