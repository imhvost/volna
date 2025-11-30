<?php
/**
 * Utils functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieves the ID of the first page using the specified page template.
 *
 * @param string $template The filename of the page template (e.g. 'templates/custom-template.php').
 * @return int|null The page ID if found, or 0 if not found.
 */
function volna_get_page_id_by_template( $template ) {
	$args  = array(
		'post_type'   => 'page',
		'fields'      => 'ids',
		'numberposts' => 1,
		'meta_key'    => '_wp_page_template',
		'meta_value'  => $template,
	);
	$pages = get_posts( $args );
	if ( $pages ) {
		return $pages[0];
	}
	return null;
}

/**
 * Converts new lines into HTML tags.
 *
 * Wraps each line in the given HTML tag (e.g., <p>, <div>).
 * Also inserts <br> tags between lines that are not already wrapped in tags.
 *
 * @param string $tag The HTML tag to wrap lines with (without angle brackets, e.g., 'p', 'div').
 * @param string $str The input string with possible line breaks.
 * @return string The formatted HTML string with tags and line breaks.
 */
function volna_nl2tag( $tag, $str ) {
	return "<$tag>" . preg_replace(
		array( '/([\n]{1,})/i', '/([^>])\n([^<])/i' ),
		array( "</$tag>\n<$tag>", '$1<br>$2' ),
		trim( $str )
	) . "</$tag>";
}

/**
 * Returns a permalink if the given input is a post ID or post object.
 * If not, returns the value as a URL (escaped).
 *
 * @param int|string $link Post ID or URL.
 * @return string Permalink or escaped URL.
 */
function volna_get_btn_link( $link ) {
	$permalink = get_the_permalink( $link );
	return $permalink ? $permalink : esc_url( $link );
}

/**
 * Adds support for SVG tags to the list of allowed HTML tags for wp_kses.
 *
 * This function extends the allowed tags array to include basic SVG elements
 * and attributes needed for safe inline SVG rendering.
 *
 * @param array|null $allowed_tags The existing array of allowed HTML tags.
 * @return array The modified array including SVG-related tags and attributes.
 */
function volna_allow_svg_in_kses( $allowed_tags = null ) {
	if ( empty( $allowed_tags ) ) {
		$allowed_tags = wp_kses_allowed_html( 'post' );
	}
	$svg_tags = array(
		'svg'  => array(
			'xmlns'       => true,
			'width'       => true,
			'height'      => true,
			'viewbox'     => true,
			'fill'        => true,
			'stroke'      => true,
			'aria-hidden' => true,
			'role'        => true,
			'focusable'   => true,
			'class'       => true,
		),
		'path' => array(
			'd'      => true,
			'fill'   => true,
			'stroke' => true,
			'id'     => true,
		),
		'g'    => array(
			'fill'   => true,
			'stroke' => true,
			'id'     => true,
		),
		'use'  => array(
			'xlink:href' => true,
		),
	);

	return array_merge_recursive( $allowed_tags, $svg_tags );
}

/**
 * Рендерить inline SVG або <img>, незалежно від MIME на сервері.
 *
 * @param int|string $attachment_id Attachment ID.
 * @return string
 */
function volna_render_image_or_svg( $attachment_id ) {
	if ( ! $attachment_id ) {
		return '';
	}

	$cache_key = 'volna_svg_render_' . $attachment_id;
	$cached    = wp_cache_get( $cache_key, 'volna_svg' );

	if ( false !== $cached ) {
		return $cached;
	}

	$url = wp_get_attachment_url( $attachment_id );
	if ( ! $url ) {
		return '';
	}

	$path     = get_attached_file( $attachment_id );
	$url_path = (string) parse_url( $url, PHP_URL_PATH );

	$is_svg = false;
	if ( preg_match( '/\.svgz?$/i', $url_path ) ) {
		$is_svg = true;
	} elseif ( $path && preg_match( '/\.svgz?$/i', $path ) ) {
		$is_svg = true;
	}

	if ( $is_svg ) {
		$svg = '';

		if ( $path && is_readable( $path ) ) {
			$svg = @file_get_contents( $path );
		}

		if ( '' === $svg && $url ) {
			$response = wp_remote_get( $url );
			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$svg = (string) wp_remote_retrieve_body( $response );
			}
		}

		if ( '' !== $svg ) {
			$output = wp_kses( $svg, volna_allow_svg_in_kses() );
			wp_cache_set( $cache_key, $output, 'volna_svg' );
			return $output;
		}

		$output = wp_get_attachment_image( $attachment_id, 'full' );
		wp_cache_set( $cache_key, $output, 'volna_svg' );
		return $output;
	}

	$output = wp_get_attachment_image( $attachment_id, 'full' );
	wp_cache_set( $cache_key, $output, 'volna_svg' );
	return $output;
}


/**
 * Forces WordPress to display the 404 page and terminates script execution.
 *
 * @return void
 */
function volna_force_404(): void {
	global $wp_query;

	$wp_query->set_404();
	status_header( 404 );

	$template = get_404_template();
	if ( $template ) {
		load_template( $template );
	} else {
		nocache_headers();
		status_header( 404 );
		echo '<h1>404</h1>';
	}

	exit();
}

/**
 * Retrieves ids from a Carbon Fields association field.
 *
 * @param array $field The association field.
 * @return array The ids array.
 */
function volna_get_association_ids( $field ) {
	if ( is_array( $field ) ) {
		return array_map( 'intval', wp_list_pluck( $field, 'id' ) );
	}
	return array();
}

/**
 * Retrieves id from a Carbon Fields association field.
 *
 * @param array $field The association field.
 * @return int|null The associated ID, or null if not found.
 */
function volna_get_association_id( $field ) {
	$ids = volna_get_association_ids( $field );
	return isset( $ids[0] ) ? (int) $ids[0] : null;
}

/**
 * Retrieves the first associated post ID from a Carbon Fields association field.
 *
 * @param int    $post_id The post ID.
 * @param string $field_key The meta field key.
 * @return int|null The associated ID, or null if not found.
 */
function volna_get_post_association_id( $post_id, $field_key ) {
	$field = carbon_get_post_meta( $post_id, $field_key );
	return volna_get_association_id( $field );
}

/**
 * Extracts the YouTube video ID from a given YouTube URL.
 *
 * @param string $url YouTube video URL.
 * @return string|null YouTube video ID or null if not found.
 */
function volna_get_youtube_video_id_from_url( $url ) {
	preg_match(
		'/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([\w-]{11})/',
		$url,
		$matches
	);
	if ( ! isset( $matches[1] ) ) {
		return null;
	}
	return $matches[1];
}

/**
 * Returns the URL of a YouTube video thumbnail.
 *
 * @param string $video_id YouTube video ID.
 * @param string $size     Thumbnail size (e.g. 'default', 'hqdefault', 'mqdefault', 'sddefault', 'maxresdefault').
 * @return string Thumbnail image URL.
 */
function volna_get_youtube_thumbnail( $video_id, $size = 'maxresdefault' ) {
	return 'https://img.youtube.com/vi/' . $video_id . '/' . $size . '.jpg';
}


/**
 * Returns the post excerpt or generates one from the content if none is set.
 *
 * @param int|null $post_id Post ID.
 * @param int      $words Number of words Default is 20.
 * @param string   $more Suffix to append if the content is truncated. Default is '...'.
 * @return string The post excerpt or a truncated portion of the content.
 */
function volna_get_excerpt( $post_id = null, $words = 20, $more = '...' ) {
	if ( has_excerpt( $post_id ) ) {
		return get_the_excerpt( $post_id );
	}

	$volna_desc = $post_id ? carbon_get_post_meta( $post_id, 'volna_desc' ) : carbon_get_the_post_meta( 'volna_desc' );
	if ( $volna_desc ?? null ) {
		return $volna_desc;
	}

	$content = get_the_content( null, null, $post_id );
	$content = strip_shortcodes( $content );
	$content = wp_strip_all_tags( apply_filters( 'the_content', $content ) );

	return wp_trim_words( $content, $words, $more );
}

/**
 * Deep sanitize
 *
 * @param string|array $value String or array.
 *
 * @return string|array sanitized string or array.
 */
function volna_sanitize_deep( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'volna_sanitize_deep', $value );
	}

	if ( is_string( $value ) ) {
		$value   = trim( $value );
		$allowed = '/[^a-zA-Z0-9_\-\=\<\>\!\s]/';
		return preg_replace( $allowed, '', $value );
	}
	return $value;
}

/**
 * Wraps each word in the given text with a <span> tag, preserving spaces and delimiters.
 *
 * @param string $text Input text.
 *
 * @return string Text where each word is wrapped in a <span>.
 */
function volna_wrap_words_in_span( $text ) {
	$words = preg_split( '/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE );

	foreach ( $words as &$word ) {
		if ( trim( $word ) !== '' ) {
			$word = '<span>' . esc_html( $word ) . '</span>';
		}
	}

	return implode( '', $words );
}

/**
 * Check text fields.
 *
 * @param array   $fields Text fields array of $_POST or $_GET keys.
 * @param boolean $is_all_required All fields are required.
 * @param string  $array_type 'POST' (default) 'GET' | 'SERVER'.
 * @param boolean $check_ajax_referer is use check_ajax_referer.
 *
 * @return array|void Fields array or wp_send_json_error.
 */
function volna_check_text_fields( $fields = array(), $is_all_required = true, $array_type = 'POST', $check_ajax_referer = true ) {

	if ( 'POST' === $array_type && $check_ajax_referer ) {
		check_ajax_referer( 'volna_ajaxnonce', 'nonce' );
	}

	if ( ! is_array( $fields ) ) {
		return array();
	}

	$array_type = strtoupper( $array_type );
	$source     = $_POST;
	if ( 'GET' === $array_type ) {
		$source = $_GET;
	}
	if ( 'SERVER' === $array_type ) {
		$source = $_SERVER;
	}
	if ( 'COOKIE' === $array_type ) {
		$source = $_COOKIE;
	}

	$output = array();

	foreach ( $fields as $item ) {
		$value = isset( $source[ $item ] ) ? sanitize_text_field( wp_unslash( $source[ $item ] ) ) : '';

		if ( $is_all_required && ! $value && 'POST' === $array_type ) {
				wp_send_json_error( array( 'message' => esc_html__( "Відсутні обов'язкові поля.", 'volna' ) ) );
		}

		$output[ $item ] = $value;
	}

	return $output;
}

/**
 * Get building and floor terms independently from post.
 *
 * @param int     $post_id Apartment post ID (ignored when $get_first = true).
 * @param boolean $get_first Whether to get first parent and first child terms.
 *
 * @return array{
 *     'building': WP_Term|null,
 *     'floor': WP_Term|null
 * }
 */
function volna_get_building_and_floor( $post_id, $get_first = false ) {
	$result = array(
		'building' => null,
		'floor'    => null,
	);

	if ( $get_first ) {
		$parents = get_terms(
			array(
				'taxonomy'   => 'volna-building-floor',
				'parent'     => 0,
				'hide_empty' => false,
				'number'     => 1,
			)
		);

		if ( empty( $parents ) || is_wp_error( $parents ) ) {
			return $result;
		}

		$result['building'] = $parents[0];

		$children = get_terms(
			array(
				'taxonomy'   => 'volna-building-floor',
				'parent'     => $result['building']->term_id,
				'hide_empty' => false,
				'number'     => 1,
			)
		);

		if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
			$result['floor'] = $children[0];
		}

		return $result;
	}

	$terms = get_the_terms( $post_id, 'volna-building-floor' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return $result;
	}

	foreach ( $terms as $term ) {
		if ( $result['building'] && $result['floor'] ) {
			break;
		}
		if ( 0 === (int) $term->parent && ! $result['building'] ) {
			$result['building'] = $term;
		} elseif ( ! $result['floor'] ) {
			$result['floor'] = $term;
		}
	}

	return $result;
}

/**
 * Plural
 *
 * Returns the correct plural form based on the given number.
 * Supports different rules for English and Slavic languages.
 *
 * @param int   $number Number to determine plural form.
 * @param array $forms  Array of already translated plural forms.
 *
 * @return string Correct plural form.
 */
function volna_plural( $number, $forms ) {
	$locale = determine_locale(); // Gets the current WordPress locale

	switch ( substr( $locale, 0, 2 ) ) {
		case 'uk':
			$mod10  = $number % 10;
			$mod100 = $number % 100;

			if ( 1 === $mod10 && 11 !== $mod100 ) {
				return $forms[0]; // singular
			} elseif ( $mod10 >= 2 && $mod10 <= 4 && ( $mod100 < 10 || $mod100 >= 20 ) ) {
				return $forms[1]; // few
			} else {
				return $forms[2]; // many
			}

		case 'en':
		default:
			return ( 1 === $number ) ? $forms[0] : $forms[1];
	}
}

/**
 * Check is localhost.
 *
 * @return bool
 */
function volna_is_localhost(): bool {
	$whitelist = array(
		'127.0.0.1',
		'::1',
		'localhost',
	);

	if ( isset( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], $whitelist, true ) ) {
		return true;
	}
	if ( isset( $_SERVER['HTTP_HOST'] ) && preg_match( '/(localhost|\.test|\.local)/i', sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) ) {
		return true;
	}

	return false;
}
