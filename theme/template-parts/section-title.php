<?php
/**
 * Section title
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fields = $args['fields'] ?? array();

var_dump( $fields );

[
	'volna_section_title'     => $volna_section_title,
	'volna_section_title_tag' => $volna_section_title_tag,
] = wp_parse_args(
	$fields,
	array(
		'volna_section_title'     => '',
		'volna_section_title_tag' => 'div',
	)
);

if ( $volna_section_title ) :
	?>
<<?php echo esc_attr( $volna_section_title_tag ); ?> class="volna-section-title volna-h2">
		<?php echo wp_kses_post( nl2br( $volna_section_title ) ); ?>
</<?php echo esc_attr( $volna_section_title_tag ); ?>>
		<?php
endif;
