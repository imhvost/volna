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
<<?php echo $volna_section_title_tag ? esc_attr( $volna_section_title_tag ) : 'div'; ?> class="volna-section-title volna-h2">
		<?php echo wp_kses_post( nl2br( $volna_section_title ) ); ?>
</<?php echo $volna_section_title_tag ? esc_attr( $volna_section_title_tag ) : 'div'; ?>>
		<?php
endif;
