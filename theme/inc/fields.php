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
			)
			->add_tab(
				__( 'Футер', 'volna' ),
				array(
					Field::make( 'image', 'volna_footer_img', __( 'Картинка', 'volna' ) ),
					Field::make( 'complex', 'volna_footer_tels', __( 'Телефоны', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'tel', __( 'Телефон', 'volna' ) ),
							)
						)
						->set_header_template( '<%= tel %>' ),
					Field::make( 'complex', 'volna_footer_emails', __( 'Почты', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'email', __( 'Почта', 'volna' ) ),
							)
						)
						->set_header_template( '<%= email %>' ),
					Field::make( 'text', 'volna_footer_btn', __( 'Кнопка', 'volna' ) ),
					Field::make( 'text', 'volna_footer_menu_title', __( 'Меню - заголовок', 'volna' ) ),
					Field::make( 'text', 'volna_footer_info_title', __( 'Информация - заголовок', 'volna' ) ),
					Field::make( 'rich_text', 'volna_footer_info', __( 'Информация', 'volna' ) ),
					Field::make( 'text', 'volna_copyright', __( 'Копирайт', 'volna' ) )
						->set_help_text( __( '{Y} - Поточный год', 'volna' ) ),
					Field::make( 'text', 'volna_developer_text', __( 'Разработчик - текст', 'volna' ) )
						->set_width( 50 ),
					Field::make( 'text', 'volna_developer_link', __( 'Разработчик - ссылка', 'volna' ) )
						->set_width( 50 ),

				)
			)
			->add_tab(
				__( 'Формы', 'volna' ),
				array(
					Field::make( 'textarea', 'volna_form_title', __( 'Заголовок', 'volna' ) )
						->set_rows( 2 ),
					Field::make( 'textarea', 'volna_form_desc', __( 'Описание', 'volna' ) )
						->set_rows( 3 ),
					Field::make( 'textarea', 'volna_sent_title', __( 'Отправлено - заголовок', 'volna' ) )
						->set_rows( 2 ),
					Field::make( 'textarea', 'volna_sent_desc', __( 'Отправлено - описание', 'volna' ) )
						->set_rows( 3 ),
				)
			);

		/* volna-land */
		Container::make( 'post_meta', __( 'Поля', 'volna' ) )
			->where( 'post_type', '=', 'volna-land' )
			->add_fields(
				array(
					Field::make( 'text', 'volna_area', __( 'Соток', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_price', __( 'Цена', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_price_old', __( 'Старая цена', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_price_area', __( 'Цена за сотку', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'media_gallery', 'volna_gallery', __( 'Галерея', 'volna' ) )
						->set_type( array( 'image' ) ),
					Field::make( 'complex', 'volna_characteristics', __( 'Характеристики', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
								Field::make( 'text', 'desc', __( 'Описание', 'volna' ) ),
							)
						)
						->set_header_template( '<%= title %>' ),

				)
			);

		/* volna-project */
		Container::make( 'post_meta', __( 'Поля', 'volna' ) )
			->where( 'post_type', '=', 'volna-project' )
			->add_fields(
				array(
					Field::make( 'text', 'volna_area', __( 'Площадь', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_price', __( 'Цена', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_price_area', __( 'Цена за м²', 'volna' ) )
						->set_attribute( 'type', 'number' ),
					Field::make( 'text', 'volna_bedrooms', __( 'Спальни', 'volna' ) )
						->set_attribute( 'type', 'number' )
						->set_width( 50 ),
					Field::make( 'text', 'volna_bathrooms', __( 'Санузлы', 'volna' ) )
						->set_attribute( 'type', 'number' )
						->set_width( 50 ),
					Field::make( 'media_gallery', 'volna_gallery', __( 'Галерея', 'volna' ) )
						->set_type( array( 'image' ) ),
					Field::make( 'complex', 'volna_characteristics', __( 'Характеристики', 'volna' ) )
						->set_collapsed( true )
						->set_layout( 'tabbed-vertical' )
						->add_fields(
							array(
								Field::make( 'text', 'title', __( 'Заголовок', 'volna' ) ),
								Field::make( 'text', 'desc', __( 'Описание', 'volna' ) ),
							)
						)
						->set_header_template( '<%= title %>' ),

				)
			);
	}
);
