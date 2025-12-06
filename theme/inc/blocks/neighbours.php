<?php
/**
 * Block neighbours
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
		$block_name  = 'neighbours';
		$block_title = __( 'Соседи', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'neighbours', __( 'Слайдер', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
									Field::make( 'text', 'position', __( 'Должность', 'volna' ) ),
									Field::make( 'text', 'name', __( 'Имя', 'volna' ) ),
									Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
										->set_rows( 2 ),
									Field::make( 'textarea', 'checklist', __( 'Чеклист', 'volna' ) ),
								)
							)
							->set_header_template( '<%= name %>' ),
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
					if ( $fields['volna_section_title'] || $fields['neighbours'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container volna-slider-wrapp">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['neighbours'] ) : ?>
									<div class="volna-neighbours-slider swiper">
										<div class="swiper-wrapper">
											<?php foreach ( $fields['neighbours'] as $item ) : ?>
												<div class="volna-neighbours-slide swiper-slide">
													<?php if ( $item['img'] ) : ?>
														<div class="volna-neighbours-slide-img volna-cover-img">
															<?php echo wp_get_attachment_image( $item['img'], 'full' ); ?>
														</div>
													<?php endif; ?>
													<?php if ( $item['position'] || $item['name'] ) : ?>
														<div class="volna-neighbours-slide-head">
															<?php if ( $item['position'] ) : ?>
																<div class="volna-neighbours-slide-position">
																	<?php echo esc_html( $item['position'] ); ?>
																</div>
															<?php endif; ?>
															<?php if ( $item['name'] ) : ?>
																<div class="volna-neighbours-slide-name volna-h5">
																	<?php echo esc_html( $item['name'] ); ?>
																</div>
															<?php endif; ?>
														</div>
													<?php endif; ?>
													<?php if ( $item['desc'] || $item['checklist'] ) : ?>
														<div class="volna-neighbours-card-body">
															<?php if ( $item['desc'] ) : ?>
																<div class="volna-neighbours-card-desc">
																	<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
																</div>
															<?php endif; ?>
															<?php
															if ( $item['checklist'] ) :
																$checklist = preg_split( '/\r\n|\r|\n/', $item['checklist'] );
																if ( $checklist ) :
																	?>
																<ul class="volna-neighbours-card-checklist volna-content-custom-list">
																	<?php foreach ( $checklist as $li ) : ?>
																		<li>
																			<svg class="volna-icon"><use xlink:href="#icon-check-circle"/></svg>
																			<span><?php echo esc_html( $li ); ?></span>
																		</li>
																	<?php endforeach; ?>
																</ul>
																	<?php
																	endif;
																endif;
															?>
														</div>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
									<?php get_template_part( 'template-parts/slider', 'nav' ); ?>
								<?php endif; ?>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
