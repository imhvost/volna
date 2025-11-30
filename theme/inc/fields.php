<?php
/**
 * Meta fields
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Returns section title fields.
 *
 * @return array Fields array
 */
function volna_get_section_title_fields() {

	if ( class_exists( 'Carbon_Fields\Field' ) ) {
		return array(
			Field::make( 'textarea', 'volna_section_title', __( 'Заголовок', 'volna' ) )
				->set_width( 80 )
				->set_rows( 3 ),
			Field::make( 'select', 'volna_section_title_tag', __( 'Заголовок - тег', 'volna' ) )
				->set_width( 20 )
				->set_options(
					array(
						'div' => 'div',
						'h1'  => 'h1',
						'h2'  => 'h2',
						'h3'  => 'h3',
						'h4'  => 'h4',
						'h5'  => 'h5',
						'h6'  => 'h6',
					)
				)
				->set_default_value( 'div' ),
		);
	}
	return array();
}

/**
 * Returns section title fields.
 *
 * @param boolean $without_bg Add background field.
 *
 * @return array Fields array
 */
function volna_get_section_fields( $without_bg = true ) {

	if ( class_exists( 'Carbon_Fields\Field' ) ) {
		return array_merge(
			array(
				Field::make( 'text', 'volna_section_id', __( 'ID', 'volna' ) ),
			),
			volna_get_section_title_fields(),
			array(
				Field::make( 'color', 'volna_section_bg', __( 'Фон блокa', 'volna' ) )
					->set_palette( array( '#F7F7F7' ) )
					->set_conditional_logic(
						$without_bg ? array() : array(
							array(
								'field' => 'volna_section_bg_show',
								'value' => true,
							),
						)
					),
			)
		);
	}
	return array();
}


add_action(
	'carbon_fields_register_fields',
	function () {
		/* options */
		Container::make( 'theme_options', __( 'Опции темы', 'volna' ) )
			->add_tab(
				__( 'Основное', 'volna' ),
				array(
					Field::make( 'image', 'volna_logo', __( 'Логотип', 'volna' ) ),
					Field::make( 'textarea', 'volna_logo_desc', __( 'Логотип - описание', 'volna' ) )
						->set_rows( 2 ),
					Field::make( 'complex', 'volna_messengers', __( 'Мессенджеры', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'title', __( 'Название', 'volna' ) ),
								Field::make( 'image', 'icon', __( 'Иконка', 'volna' ) ),
								Field::make( 'text', 'link', __( 'Ссылка', 'volna' ) ),
							)
						)
						->set_header_template( '<%= title %>' ),
				)
			)
			->add_tab(
				__( 'Хедер', 'volna' ),
				array(
					Field::make( 'textarea', 'volna_header_address', __( 'Адрес', 'volna' ) )
						->set_rows( 2 ),
					Field::make( 'text', 'volna_header_tel', __( 'Телефон', 'volna' ) ),
					Field::make( 'text', 'volna_header_regime', __( 'Режим', 'volna' ) ),
					Field::make( 'text', 'volna_header_btn', __( 'Кнопка', 'volna' ) ),
				)
			);
	}
);
