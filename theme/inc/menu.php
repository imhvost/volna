<?php
/**
 * Menu functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'volna_header' => esc_html__( 'Хедер', 'volna' ),
				'volna_footer' => esc_html__( 'Футер', 'volna' ),
				'volna_rules'  => esc_html__( 'Правила', 'volna' ),
			)
		);
	}
);
