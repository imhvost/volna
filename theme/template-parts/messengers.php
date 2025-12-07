<?php
/**
 * Messengers
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$messengers = $args['messengers'] ?? carbon_get_theme_option( 'volna_messengers' );
if ( $messengers ) :
	?>
<div class="volna-messengers">
	<?php foreach ( $messengers as $item ) : ?>
		<a
			href="<?php echo esc_url( $item['link'] ); ?>"
			aria-label="<?php echo esc_attr( $item['title'] ); ?>"
			target="_blank"
			class="volna-content-custom-link"
		>
			<?php echo wp_get_attachment_image( $item['icon'], 'full' ); ?>
		</a>
	<?php endforeach; ?>
</div>
<?php endif; ?>
