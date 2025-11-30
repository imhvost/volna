<?php
/**
 * Settings
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* theme */

add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_post_type_support( 'page', 'excerpt' );

/* images */

add_filter(
	'upload_mimes',
	function ( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
);

// add_image_size( 'volna-thumbnail', 322, 220, true );
// add_image_size( 'volna-full-hd', 1920, 1080, false );

add_filter(
	'excerpt_length',
	function () {
		return INF;
	},
	999
);

add_filter( 'use_default_gallery_style', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );

/* content */

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );

add_filter(
	'tiny_mce_before_init',
	function ( $init ) {
		$init['wpautop']      = false;
		$init['indent']       = true;
		$init['tadv_noautop'] = true;
		return $init;
	}
);

/* clear */

add_action(
	'init',
	function () {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}
);

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_povolna_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_povolna_rel_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
remove_action( 'wp_head', 'wp_resource_hints', 2 );
add_filter(
	'template_redirect',
	function () {
		if ( is_page() ) {
			remove_action( 'wp_head', 'rel_canonical' );
		}
	}
);
add_filter( 'the_generator', '__return_empty_string' );

add_filter( 'wp_speculation_rules_configuration', '__return_null' );
add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );

/* scripts */

add_action(
	'wp_footer',
	function () {
		wp_deregister_script( 'wp-embed' );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {

		$css_plugins = array(
			'swiper-bundle.min.css',
			// 'glightbox.min.css',
		);

		foreach ( $css_plugins as $item ) {
			wp_enqueue_style( $item, get_template_directory_uri() . '/css/plugins/' . $item, false, filemtime( get_template_directory() . '/css/plugins/' . $item ) );
		}

			wp_enqueue_style( 'volna-styles', get_template_directory_uri() . '/css/styles.css', false, filemtime( get_template_directory() . '/css/styles.css' ) );
			wp_enqueue_style( 'volna-theme-styles', get_template_directory_uri() . '/style.css', false, filemtime( get_template_directory() . '/style.css' ) );

			wp_deregister_script( 'jquery-core' );
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery-core', get_template_directory_uri() . '/js/plugins/jquery.min.js', false, filemtime( get_template_directory() . '/js/plugins/jquery.min.js' ), true );
			wp_register_script( 'jquery', false, array( 'jquery-core' ), filemtime( get_template_directory() . '/js/plugins/jquery.min.js' ), true );
			wp_localize_script(
				'jquery',
				'wp_ajax',
				array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'volna_ajaxnonce' ),
				)
			);
			wp_enqueue_script( 'jquery' );

			$js_plugins = array(
				'accessible-minimodal.umd.js',
				'swiper-bundle.min.js',
				// 'glightbox.min.js',
				'focus-visible.min.js',
				'maska.js',
			);

		foreach ( $js_plugins as $item ) {
			wp_enqueue_script( $item, get_template_directory_uri() . '/js/plugins/' . $item, false, filemtime( get_template_directory() . '/js/plugins/' . $item ), true );
		}

			wp_enqueue_script( 'volna-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery-core' ), filemtime( get_template_directory() . '/js/scripts.js' ), true );

			wp_deregister_style( 'wsl-widget' );
	}
);

add_action(
	'admin_enqueue_scripts',
	function () {
		wp_enqueue_style( 'volna-styles-admin', get_template_directory_uri() . '/css/admin.css', false, filemtime( get_template_directory() . '/css/admin.css' ) );
		wp_enqueue_script( 'volna-scripts-admin', get_template_directory_uri() . '/js/admin.js', array( 'jquery-core', 'carbon-fields-vendor' ), filemtime( get_template_directory() . '/js/admin.js' ), true );
	}
);

/* email */

add_filter(
	'wp_mail_content_type',
	function () {
		return 'text/html';
	}
);

add_filter(
	'wp_mail_from',
	function () {
		return 'no-reply@site.com';
	}
);

add_filter(
	'wp_mail_from_name',
	function () {
		return 'volna';
	}
);

/* languages */

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'volna', get_template_directory() . '/languages' );
	}
);
