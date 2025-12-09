<?php
/**
 * Post types and taxonomy register
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'carbon_fields_register_fields',
	function () {

		$page_articles = volna_get_page_id_by_template( 'page-articles.php' );
		register_post_type(
			'volna-article',
			array(
				'labels'        => array(
					'name'          => __( 'Статьи', 'volna' ),
					'singular_name' => __( 'Статья', 'volna' ),
					'add_new'       => __( 'Добавить', 'volna' ),
				),
				'public'        => true,
				'menu_icon'     => 'dashicons-book',
				'menu_position' => 5,
				'rewrite'       => $page_articles ? array(
					'slug'       => get_page_uri( $page_articles ),
					'with_front' => false,
				) : true,
				'supports'      => array(
					'title',
					'thumbnail',
					'editor',
					'excerpt',
				),
				'show_in_rest'  => true,
			)
		);

		register_taxonomy(
			'volna-land-category',
			array( 'volna-land' ),
			array(
				'labels'             => array(
					'name'          => __( 'Категории', 'volna' ),
					'singular_name' => __( 'Категория', 'volna' ),
					'menu_name'     => __( 'Категория', 'volna' ),
				),
				'public'             => true,
				'show_ui'            => true,
				'show_in_rest'       => false,
				'hierarchical'       => true,
				'publicly_queryable' => false,
				'show_admin_column'  => true,
			)
		);

		register_post_type(
			'volna-land',
			array(
				'labels'             => array(
					'name'          => __( 'Участки', 'volna' ),
					'singular_name' => __( 'Участок', 'volna' ),
					'add_new'       => __( 'Добавить', 'volna' ),
				),
				'public'             => true,
				'menu_icon'          => 'dashicons-palmtree',
				'menu_position'      => 5,
				'publicly_queryable' => false,
				'supports'           => array( 'title', 'thumbnail', 'editor', 'excerpt' ),
				'show_in_rest'       => false,
			)
		);

		register_post_type(
			'volna-project',
			array(
				'labels'             => array(
					'name'          => __( 'Проекты', 'volna' ),
					'singular_name' => __( 'Проект', 'volna' ),
					'add_new'       => __( 'Добавить', 'volna' ),
				),
				'public'             => true,
				'menu_icon'          => 'dashicons-store',
				'menu_position'      => 5,
				'publicly_queryable' => false,
				'supports'           => array( 'title', 'thumbnail', 'editor' ),
				'show_in_rest'       => false,
			)
		);
	}
);
