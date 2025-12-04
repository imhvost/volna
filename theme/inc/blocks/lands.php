<?php
/**
 * Block lands
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
		$block_name  = 'lands';
		$block_title = __( 'Участки', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'textarea', 'desc', __( 'Описание', 'volna' ) )
							->set_rows( 3 ),
						Field::make( 'association', 'lands', __( 'Автор', 'volna' ) )
							->set_types(
								array(
									array(
										'type'      => 'post',
										'post_type' => 'volna-land',
									),
								)
							)
							->set_max( 6 ),
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

					$lands = volna_get_association_ids( $fields['lands'] );

					if ( $fields['volna_section_title'] || $fields['desc'] || $lands ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php if ( $fields['volna_section_title'] || $fields['desc'] ) : ?>
									<div class="volna-section-head">
										<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
										<?php if ( $fields['desc'] ) : ?>
											<div class="volna-section-desc">
												<?php echo wp_kses_post( nl2br( $fields['desc'] ) ); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ( $lands ) : ?>
									<div class="volna-products-list">
										<?php
										foreach ( $lands as $target_post_id ) {
											if ( 'publish' !== get_post_status( $target_post_id ) ) {
												continue;
											}
											get_template_part( 'template-parts/land', 'item', array( 'target_post_id' => $target_post_id ) );
										}
										?>
									</div>
									<?php
										$lans_page_id = volna_get_page_id_by_template( 'page-lands.php' );
									if ( $lans_page_id ) :
										?>
										<div class="volna-read-more-btn">
											<a href="<?php echo esc_url( get_the_permalink( $lans_page_id ) ); ?>" class="volna-btn">
												<?php esc_html_e( 'Показать ещё', 'volna' ); ?>
											</a>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php
					endif;
				}
			);
	}
);
