<?php
/**
 * Block how-buy
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
		$block_name  = 'how-buy';
		$block_title = __( 'Как купить', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
							->set_rows( 3 ),
						Field::make( 'media_gallery', 'banks', __( 'Банки', 'volna' ) )
							->set_type( array( 'image' ) ),
						Field::make( 'complex', 'how_buy', __( 'Варианты покупки', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'text', 'name', __( 'Название', 'volna' ) ),
									Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
									Field::make( 'textarea', 'title', __( 'Заголовок', 'volna' ) )
										->set_rows( 3 ),
									Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
										->set_rows( 3 ),
									Field::make( 'complex', 'info', __( 'Информация', 'volna' ) )
										->set_collapsed( true )
										->set_layout( 'tabbed-vertical' )
										->add_fields(
											array(
												Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
												Field::make( 'text', 'desc', __( 'Описание', 'volna' ) ),
											)
										)
										->set_header_template( '<%= title %>' ),
									Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
										->set_width( 50 ),
									Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
										->set_width( 50 )
										->set_help_text( __( 'Post ID или URL', 'volna' ) ),
									Field::make( 'text', 'more_text', __( 'Подробнее - текст', 'volna' ) )
										->set_width( 50 ),
									Field::make( 'text', 'more_link', __( 'Подробнее - ссылка', 'volna' ) )
										->set_width( 50 )
										->set_help_text( __( 'Post ID или URL', 'volna' ) ),
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

					if ( $fields['volna_section_title'] || $fields['desc'] || $fields['banks'] || $fields['how_buy'] ) :
						$block_id = uniqid();
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<div class="volna-section-head">
									<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
									<?php if ( $fields['desc'] ) : ?>
										<div class="volna-section-desc">
											<?php echo wp_kses_post( nl2br( $fields['desc'] ) ); ?>
										</div>
									<?php endif; ?>
								</div>
								<?php if ( $fields['banks'] ) : ?>
									<div class="volna-how-buy-banks">
										<?php foreach ( $fields['banks'] as $item ) : ?>
											<div class="volna-how-buy-bank volna-contain-img">
												<?php echo wp_get_attachment_image( $item, 'full' ); ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php if ( $fields['how_buy'] ) : ?>
									<div class="volna-how-buy-variants">
										<nav class="volna-how-buy-variants-nav">
											<?php foreach ( $fields['how_buy'] as $key => $item ) : ?>
												<button
													type="button"
													class="volna-how-buy-variants-nav-btn volna-tab-btn <?php echo 0 === $key ? 'volna-active' : ''; ?>"
													data-volna-tabs="volna-how-buy-tabs-<?php echo esc_attr( $block_id ); ?>"
													data-volna-tab="volna-how-buy-tab-<?php echo esc_attr( $block_id ); ?>-<?php echo esc_attr( $key ); ?>"
												>
													<?php echo esc_html( $item['name'] ); ?>
												</button>
											<?php endforeach; ?>
										</nav>
										<div class="volna-how-buy-variants-tabs">
											<?php foreach ( $fields['how_buy'] as $key => $item ) : ?>
												<div
													class="volna-how-buy-variants-tab volna-tab-block <?php echo 0 === $key ? 'volna-active' : ''; ?>"
													data-volna-tabs="volna-how-buy-tabs-<?php echo esc_attr( $block_id ); ?>"
													data-volna-tab="volna-how-buy-tab-<?php echo esc_attr( $block_id ); ?>-<?php echo esc_attr( $key ); ?>"
												>
													<div class="volna-how-buy-variants-tab-body">
														<?php if ( $item['title'] ) : ?>
															<div class="volna-how-buy-variants-tab-title volna-h2">
																<?php echo wp_kses_post( nl2br( $item['title'] ) ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['desc'] ) : ?>
															<div class="volna-how-buy-variants-tab-desc">
																<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['info'] ) : ?>
															<div class="volna-how-buy-variants-tab-info">
																<?php foreach ( $item['info'] as $i ) : ?>
																	<div class="volna-how-buy-variants-tab-info-item">
																		<?php if ( $i['title'] ) : ?>
																			<div class="volna-how-buy-variants-tab-info-item-title">
																				<?php echo esc_html( $i['title'] ); ?>
																			</div>
																		<?php endif; ?>
																		<?php if ( $i['desc'] ) : ?>
																			<div class="volna-how-buy-variants-tab-info-item-desc volna-h2">
																				<?php echo esc_html( $i['desc'] ); ?>
																			</div>
																		<?php endif; ?>
																	</div>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
														<?php if ( ( $item['btn_link'] && $item['btn_text'] ) || ( $item['more_link'] && $item['more_text'] ) ) : ?>
															<div class="volna-how-buy-variants-tab-btns">
																<?php if ( $item['btn_link'] && $item['btn_text'] ) : ?>
																	<a href="<?php echo esc_attr( volna_get_btn_link( $item['btn_link'] ) ); ?>" class="volna-btn">
																		<?php echo esc_html( $item['btn_text'] ); ?>
																	</a>
																<?php endif; ?>
																<?php if ( $item['more_link'] && $item['more_text'] ) : ?>
																	<a href="<?php echo esc_attr( volna_get_btn_link( $item['more_link'] ) ); ?>" class="volna-btn volna-btn-white">
																		<?php echo esc_html( $item['more_text'] ); ?>
																	</a>
																<?php endif; ?>
															</div>
														<?php endif; ?>
													</div>
													<?php if ( $item['img'] ) : ?>
														<div class="volna-how-buy-variants-tab-img volna-cover-img volna-hidden-tablet">
															<?php echo wp_get_attachment_image( $item['img'], 'full' ); ?>
														</div>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
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
