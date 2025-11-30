<?php
/**
 * Block plan
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
		$block_name  = 'plan';
		$block_title = __( 'Генплан', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'text', 'iframe_src', __( 'Iframe src', 'volna' ) ),
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
					if ( $fields['volna_section_title'] || $fields['iframe_src'] ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $fields['iframe_src'] ) : ?>
									<div class="volna-plab-iframe">
										<?php if ( volna_is_localhost() ) : ?>
											<iframe src="<?php echo esc_url( $fields['iframe_src'] ); ?>" loading='lazy'></iframe>
										<?php endif; ?>
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
