<?php
/**
 * Block faq
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
		$block_name  = 'faq';
		$block_title = __( 'Ответы на вопросы', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'complex', 'faq', __( 'Ответы на вопросы', 'volna' ) )
							->set_collapsed( true )
							->set_layout( 'tabbed-vertical' )
							->add_fields(
								array(
									Field::make( 'textarea', 'q', __( 'Вопрос', 'volna' ) )
										->set_rows( 2 ),
									Field::make( 'rich_text', 'a', __( 'Ответ', 'volna' ) )
										->set_rows( 2 ),
								)
							)
							->set_header_template( '<%= q %>' ),
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

					if ( $fields['volna_section_title'] || $fields['faq'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['faq'] ) : ?>
									<div class="volna-accordion">
										<?php foreach ( $fields['faq'] as $key => $item ) : ?>
											<div class="volna-accordion-item <?php echo 0 === $key ? 'volna-active' : ''; ?>">
												<button class="volna-accordion-item-toggle">
													<span class="volna-h5">
														<?php echo wp_kses_post( nl2br( $item['q'] ) ); ?>
													</span>
													<i>
														<svg class="volna-icon"><use xlink:href="#icon-plus-circle"/></svg>
														<svg class="volna-icon"><use xlink:href="#icon-minus-circle"/></svg>
													</i>
												</button>
												<div class="volna-accordion-item-main">
													<div class="volna-accordion-item-body">
														<div class="volna-accordion-item-content">
															<?php
																// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																echo apply_filters( 'the_content', $item['a'] );
															?>
														</div>
													</div>
												</div>
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
