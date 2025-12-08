<?php
/**
 * Block content-quote
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
		$block_name  = 'content-quote';
		$block_title = __( 'Контент цитата', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array(
					Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
					Field::make( 'textarea', 'quote', __( 'Цитата', 'volna' ) )
							->set_rows( 3 ),
					Field::make( 'image', 'avatar', __( 'Аватар', 'volna' ) ),
					Field::make( 'text', 'name', __( 'Имя', 'volna' ) ),
					Field::make( 'text', 'position', __( 'Должность', 'volna' ) ),
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

					if ( $fields['quote'] ) :
						?>
						<div
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
						>
							<div class="volna-container">
								<div class="volna-content-quote-body">
									<div class="volna-content-quote-content volna-h4">
										<?php echo wp_kses_post( nl2br( $fields['quote'] ) ); ?>
									</div>
									<?php if ( $fields['avatar'] || $fields['name'] || $fields['position'] ) : ?>
										<div class="volna-content-quote-user">
											<?php if ( $fields['avatar'] ) : ?>
												<div class="volna-content-quote-user-avatar volna-cover-img">
													<?php echo wp_get_attachment_image( $fields['avatar'], 'full' ); ?>
												</div>
											<?php endif; ?>
											<?php if ( $fields['avatar'] || $fields['name'] || $fields['position'] ) : ?>
												<div class="volna-content-quote-user-body">
													<?php if ( $fields['name'] ) : ?>
														<div class="volna-content-quote-user-name">
															<?php echo esc_html( $fields['name'] ); ?>
														</div>
													<?php endif; ?>
													<?php if ( $fields['position'] ) : ?>
														<div class="volna-content-quote-user-position">
															<?php echo esc_html( $fields['position'] ); ?>
														</div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
