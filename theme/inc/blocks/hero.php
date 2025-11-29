<?php
/**
 * Block hero
 *
 * @package lac
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action(
	'carbon_fields_register_fields',
	function () {
		$block_name  = 'hero';
		$block_title = __( 'Герой', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
		->add_fields(
			array(
				Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
					->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
				Field::make( 'text', 'volna_section_id', __( 'ID', 'volna' ) ),
				Field::make( 'complex', 'slider', __( 'Слайдер', 'volna' ) )
					->set_collapsed( true )
					->set_layout( 'tabbed-vertical' )
					->add_fields(
						array_merge(
							volna_get_section_title_fields(),
							array(
								Field::make( 'image', 'img', __( 'Картинка', 'volna' ) ),
								Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
									->set_rows( 3 ),
								Field::make( 'complex', 'btns', __( 'Кнопки', 'volna' ) )
									->set_collapsed( true )
									->set_layout( 'tabbed-vertical' )
									->add_fields(
										array(
											Field::make( 'text', 'text', __( 'Текст', 'volna' ) )
												->set_width( 50 ),
											Field::make( 'text', 'link', __( 'Ссылка', 'volna' ) )
												->set_width( 50 )
												->set_help_text( __( 'Post ID или URL', 'volna' ) ),
										)
									)
									->set_header_template( '<%= text %>' )
									->set_max( 2 ),
							)
						)
					)
					->set_header_template( '<%= volna_section_title %>' ),
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
				if ( $fields['slider'] ) :
					?>
					<div
						<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
						class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
						<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
					>
						<div class="volna-hero-slider swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $fields['slider'] as $item ) : ?>
									<div class="volna-hero-slide swiper-slide">
										<?php if ( $item['img'] ) : ?>
											<div class="volna-hero-slide-img volna-cover-img">
												<?php echo wp_get_attachment_image( $item['img'], 'full' ); ?>
											</div>
										<?php endif; ?>
										<div class="volna-container">
											<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
											<?php if ( $item['desc'] ) : ?>
												<div class="volna-hero-slide-desc">
													<?php echo wp_kses_post( nl2br( $item['desc'] ) ); ?>
												</div>
											<?php endif; ?>
											<?php if ( $item['btns'] ) : ?>
												<div class="volna-hero-slide-btns">
													<?php foreach ( $item['btns'] as $i => $btn ) : ?>
														<a href="<?php echo esc_attr( volna_get_btn_link( $btn['link'] ) ); ?>" class="volna-btn <?php echo 1 === $i ? 'volna-btn-white-border' : ''; ?>">
															<?php echo esc_html( $btn['text'] ); ?>
														</a>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="volna-hero-slider-nav">
							<div class="volna-container">
								<div class="volna-hero-slider-pagination"></div>
							</div>
						</div>
					</div>
					<?php
				endif;
			}
		);
	}
);
