<?php
/**
 * Block articles
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
		$block_name  = 'articles';
		$block_title = __( 'Статьи', 'volna' );
		Block::make( 'volna-' . $block_name, $block_title )
			->add_fields(
				array_merge(
					volna_get_section_fields(),
					array(
						Field::make( 'block_preview', 'preview', __( 'Предварительный просмотр', 'volna' ) )
							->set_html( '<img src="' . get_template_directory_uri() . '/img/blocks/' . $block_name . '.webp" alt="">' ),
						Field::make( 'association', 'articles', __( 'Статьи', 'volna' ) )
							->set_types(
								array(
									array(
										'type'      => 'post',
										'post_type' => 'volna-article',
									),
								)
							)
							->set_max( 4 ),
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

					$articles = volna_get_association_ids( $fields['articles'] );

					if ( $fields['volna_section_title'] || $articles ) :
						?>
						<div
							<?php echo $fields['volna_section_id'] ? 'id="' . esc_attr( $fields['volna_section_id'] ) . '"' : ''; ?>
							class="volna-section volna-<?php echo esc_attr( $block_name ); ?> <?php echo $attributes['className'] ?? null ? esc_attr( $attributes['className'] ) : ''; ?>"
							<?php echo ( $fields['volna_section_bg'] ?? null ) ? 'style="background-color: ' . esc_attr( $fields['volna_section_bg'] ) . ';"' : ''; ?>		
						>
							<div class="volna-container">
								<?php get_template_part( 'template-parts/section', 'title', array( 'fields' => $fields ) ); ?>
								<?php if ( $articles ) : ?>
									<div class="volna-articles-list">
										<?php
										foreach ( $articles as $target_post_id ) {
											if ( 'publish' !== get_post_status( $target_post_id ) ) {
												continue;
											}
											get_template_part( 'template-parts/article', 'item', array( 'target_post_id' => $target_post_id ) );
										}
										?>
									</div>
									<?php
										$articles_page_id = volna_get_page_id_by_template( 'page-articles.php' );
									if ( $articles_page_id ) :
										?>
										<div class="volna-read-more-btn">
											<a href="<?php echo esc_url( get_the_permalink( $articles_page_id ) ); ?>" class="volna-btn">
												<?php esc_html_e( 'Посмотреть все', 'volna' ); ?>
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
