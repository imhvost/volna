<?php
/**
 * Block contacts
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
		$block_name  = 'contacts';
		$block_title = __( 'Контакты', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'offices', __( 'Офисы', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'text', 'name', __( 'Название', 'volna' ) ),
									Field::make( 'textarea', 'address', __( 'Адрес', 'volna' ) )
										->set_rows( 2 ),
									Field::make( 'text', 'regime', __( 'Режим работы', 'volna' ) ),
									Field::make( 'text', 'tel', __( 'Телефон', 'volna' ) ),
								)
							)
							->set_header_template( '<%= name %>' ),
						Field::make( 'text', 'email', __( 'Почта', 'volna' ) ),
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
						Field::make( 'file', 'details', __( 'Реквизиты для юр.лиц', 'volna' ) ),
						Field::make( 'text', 'btn_text', __( 'Кнопка - текст', 'volna' ) )
							->set_width( 50 )
							->set_default_value( __( 'Получить обратный звонок', 'volna' ) ),
						Field::make( 'text', 'btn_link', __( 'Кнопка - ссылка', 'volna' ) )
							->set_width( 50 )
							->set_help_text( __( 'Post ID или URL', 'volna' ) )
							->set_default_value( '#modal-application' ),
						Field::make( 'textarea', 'map_iframe', __( 'Iframe карта', 'volna' ) )
							->set_rows( 3 ),
						Field::make( 'complex', 'get_there', __( 'Как добраться', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
									Field::make( 'rich_text', 'desc', __( 'Описание', 'volna' ) ),
								)
							)
							->set_header_template( '<%= title %>' ),
						Field::make( 'textarea', 'places_iframe', __( 'Iframe места рядом', 'volna' ) )
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

					if ( $fields['volna_section_title'] || $fields['offices'] || $fields['email'] || $messengers || $fields['details'] || ( $fields['btn_text'] && $fields['btn_link'] ) || $fields['map_iframe'] || $fields['get_there'] || $fields['places_iframe'] ) :
						$block_id = uniqid();
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php if ( $fields['volna_section_title'] || $fields['offices'] || $fields['email'] || $messengers || $fields['details'] || ( $fields['btn_text'] && $fields['btn_link'] ) ) : ?>
									<div class="volna-contacts-body">
										<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
										<?php if ( $fields['offices'] ) : ?>
											<div class="volna-contacts-offices">
												<?php foreach ( $fields['offices'] as $item ) : ?>
													<div class="volna-contacts-office">
														<?php if ( $item['name'] ) : ?>
															<div class="volna-contacts-office-title">
																<?php echo esc_html( $item['name'] ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['address'] ) : ?>
															<div class="volna-contacts-office-address">
																<?php echo wp_kses_post( nl2br( $item['address'] ) ); ?>
															</div>
														<?php endif; ?>
														<?php if ( $item['regime'] || $item['tel'] ) : ?>
															<div class="volna-contacts-block">
																<svg class="volna-icon"><use xlink:href="#icon-call"/></svg>
																<div class="volna-contacts-block-body">
																	<?php if ( $item['regime'] ) : ?>
																		<div class="volna-contacts-block-title">
																			<?php echo wp_kses_post( $item['regime'] ); ?>
																		</div>
																	<?php endif; ?>
																	<?php if ( $item['tel'] ) : ?>
																		<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $item['tel'] ) ); ?>" class="volna-contacts-block-link volna-content-custom-link">
																			<?php echo esc_html( $item['tel'] ); ?>
																		</a>
																	<?php endif; ?>
																</div>
															</div>
														<?php endif; ?>
													</div>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
										<?php if ( $fields['email'] ) : ?>
											<div class="volna-contacts-links">
												<?php if ( $fields['email'] ) : ?>
													<div class="volna-contacts-block">
														<svg class="volna-icon"><use xlink:href="#icon-email"/></svg>
														<div class="volna-contacts-block-body">
															<div class="volna-contacts-block-title">
																<?php esc_html_e( 'Почта:', 'volna' ); ?>
															</div>
															<a href="mailto:<?php echo esc_attr( $fields['email'] ); ?>" class="volna-contacts-block-link volna-content-custom-link">
																<?php echo esc_html( $fields['email'] ); ?>
															</a>
														</div>
													</div>
												<?php endif; ?>
												<?php if ( $messengers || $fields['details'] ) : ?>
													<div class="volna-contacts-messengers">
														<?php get_template_part( 'template-parts/messengers', '', array( 'messengers' => $messengers ) ); ?>
														<?php if ( $fields['details'] ) : ?>
															<a href="<?php echo esc_url( wp_get_attachment_url( $fields['details'] ) ); ?>" download class="volna-contacts-details volna-content-custom-link volna-link">
																<?php esc_html_e( 'Реквизиты для юр.лиц', 'volna' ); ?>
															</a>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endif; ?>
										<?php if ( $fields['btn_link'] && $fields['btn_text'] ) : ?>
											<a href="<?php echo esc_attr( volna_get_btn_link( $fields['btn_link'] ) ); ?>" class="volna-btn">
												<?php echo esc_html( $fields['btn_text'] ); ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php
								if ( $fields['map_iframe'] || $fields['get_there'] || $fields['places_iframe'] ) :
									$tabs = array();
									if ( $fields['map_iframe'] ) {
										$tabs[] = array(
											'name'  => 'location',
											'title' => __( 'Посёлок на карте', 'volna' ),
											'field' => $fields['map_iframe'],
										);
									}
									if ( $fields['get_there'] ) {
										$tabs[] = array(
											'name'  => 'routing',
											'title' => __( 'Как добраться', 'volna' ),
											'field' => $fields['get_there'],
										);
									}
									if ( $fields['places_iframe'] ) {
										$tabs[] = array(
											'name'  => 'map',
											'title' => __( 'Места рядом', 'volna' ),
											'field' => $fields['places_iframe'],
										);
									}
									?>
									<div class="volna-contacts-info">
										<nav class="volna-contacts-info-nav">
											<?php foreach ( $tabs as $key => $item ) : ?>
												<button
													type="button"
													class="volna-contacts-info-nav-btn volna-tab-btn <?php echo 0 === $key ? 'volna-active' : ''; ?>"
													data-volna-tabs="volna-contacts-info-tabs-<?php echo esc_attr( $block_id ); ?>"
													data-volna-tab="volna-contacts-info-tab-<?php echo esc_attr( $block_id ); ?>-<?php echo esc_attr( $key ); ?>"
												>
													<svg class="volna-icon"><use xlink:href="#icon-<?php echo esc_attr( $item['name'] ); ?>"/></svg>
													<span><?php echo esc_attr( $item['title'] ); ?></span>
												</button>
											<?php endforeach; ?>
										</nav>
										<div class="volna-contacts-info-tabs">
											<?php foreach ( $tabs as $key => $item ) : ?>
												<div
													class="volna-contacts-info-tab volna-contacts-info-tab-<?php echo esc_attr( $item['name'] ); ?> volna-tab-block <?php echo 0 === $key ? 'volna-active' : ''; ?>"
													data-volna-tabs="volna-contacts-info-tabs-<?php echo esc_attr( $block_id ); ?>"
													data-volna-tab="volna-contacts-info-tab-<?php echo esc_attr( $block_id ); ?>-<?php echo esc_attr( $key ); ?>"
												>
													<?php if ( in_array( $item['name'], array( 'location', 'map' ), true ) ) : ?>
														<div class="volna-contacts-info-map">
															<?php
																// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																echo $item['field'];
															?>
														</div>
													<?php endif; ?>
													<?php if ( 'routing' === $item['name'] ) : ?>
														<div class="volna-contacts-info-routing">
															<?php foreach ( $item['field'] as $i ) : ?>
																<div class="volna-contacts-info-routing-item">
																	<?php if ( $i['title'] ) : ?>
																		<div class="volna-contacts-info-routing-item-title volna-h5">
																			<?php echo esc_html( $i['title'] ); ?>
																		</div>
																	<?php endif; ?>
																	<?php if ( $i['desc'] ) : ?>
																		<div class="volna-contacts-info-routing-item-desc volna-content-text">
																			<?php
																				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																				echo apply_filters( 'the_content', $i['desc'] );
																			?>
																		</div>
																	<?php endif; ?>
																</div>
															<?php endforeach; ?>
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
