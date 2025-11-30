<?php
/**
 * Form agreement
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$white = $args['white'] ?? false;

$privacy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );
?>
<div class="volna-form-agreement <?php echo $white ? 'volna-white' : ''; ?>">
	<label class="volna-checkbox-label">
		<input type="checkbox" class="volna-checkbox-input" required>
		<i class="volna-checkbox-label-icon">
			<svg class="volna-icon"><use xlink:href="#icon-check"/></svg>
		</i>
		<span class="volna-checkbox-label-title">
			<?php esc_html_e( 'Я выражаю свое согласие на', 'volna' ); ?>
			<<?php echo $privacy_page_id ? 'a href="' . esc_url( get_the_permalink( $privacy_page_id ) ) . '" class="volna-content-custom-link"' : 'span'; ?>>
				<?php esc_html_e( 'обработку персональных данных', 'volna' ); ?>
			</<?php echo $privacy_page_id ? 'a' : 'span'; ?>>
		</span>
	</label>
</div>
