<?php
/**
 * Theme setup
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		$carbon_fields_path = get_template_directory() . '/plugins/autoload.php';

		if ( file_exists( $carbon_fields_path ) ) {
			require_once $carbon_fields_path;

			\Carbon_Fields\Carbon_Fields::boot();
		} elseif ( ! class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
			add_action(
				'admin_notices',
				function () {
					?>
<div class="notice notice-error">
	<p><?php esc_html_e( 'Для роботи цієї теми потрібен Carbon Fields. Переконайтеся, що у вас встановлено плагін.', 'volna' ); ?></p>
</div>
					<?php
				}
			);
		}
	}
);
