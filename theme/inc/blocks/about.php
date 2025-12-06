<?php
/**
 * Block about
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
		$block_name  = 'about';
		$block_title = __( 'Про компанию', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'advantages', __( 'Преимущества', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'image', 'icon', __( 'Иконка', 'volna' ) ),
									Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
									Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
										->set_rows( 2 ),
								)
							)
							->set_header_template( '<%= title %>' ),
						Field::make( 'complex', 'cards', __( 'Карточки', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
									Field::make( 'textarea', 'title', __( 'Заголовок', 'volna' ) )
									->set_rows( 2 ),
									Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
										->set_rows( 3 ),
									Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
										->set_width( 50 )
										->set_default_value( __( 'Подобрать участок', 'volna' ) ),
									Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
										->set_width( 50 )
										->set_help_text( __( 'Post ID или URL', 'volna' ) )
										->set_default_value( '#calculator' ),
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
					if ( $fields['volna_section_title'] || $fields['advantages'] || $fields['cards'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php if ( $fields['advantages'] ) : ?>
									<div class="volna-about-advantages">
										<?php foreach ( $fields['advantages'] as $item ) : ?>
											<div class="volna-about-advantage">
												<?php if ( $item['icon'] || $item['title'] ) : ?>
													<div class="volna-about-advantage-head">
														<?php if ( $item['icon'] ) : ?>
															<div class="volna-about-advantage-icon volna-cover-img">
																<?php echo wp_get_attachment_image( $item['icon'], 'full' ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['title'] ) : ?>
															<div class="volna-about-advantage-title">
																<?php echo esc_html( $item['title'] ); ?>
															</div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
												<?php if ( $item['desc'] ) : ?>
													<div class="volna-about-advantage-desc">
														<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['cards'] ) : ?>
									<div class="volna-about-cards">
										<?php foreach ( $fields['cards'] as $item ) : ?>
											<div class="volna-about-card">
												<?php if ( $item['img'] ) : ?>
													<div class="volna-about-card-img volna-cover-img">
														<?php echo wp_get_attachment_image( $item['img'], 'full' ); ?>
													</div>
												<?php endif; ?>
												<?php if ( $item['title'] || $item['desc'] || $item['btn_text'] ) : ?>
													<div class="volna-about-card-body">
														<?php if ( $item['title'] ) : ?>
															<div class="volna-about-card-title volna-h5">
																<?php echo wp_kses_post( nl2br( $item['title'] ) ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['desc'] || $item['btn_text'] ) : ?>
															<div class="volna-about-card-content">
																<div class="volna-about-card-content-body">
																	<?php if ( $item['desc'] ) : ?>
																		<div class="volna-about-card-desc">
																			<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
																		</div>
																	<?php endif; ?>
																	<?php if ( $item['btn_text'] ) : ?>
																		<a href="<?php echo esc_attr( volna_get_btn_link( $item['btn_link'] ) ); ?>" class="volna-btn volna-btn-small">
																			<?php echo esc_html( $item['btn_text'] ); ?>
																		</a>
																	<?php endif; ?>
																</div>
															</div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
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
