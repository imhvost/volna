<?php
/**
 * Logo
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$volna_logo      = $args['logo'] ?? carbon_get_theme_option( 'volna_logo' );
$volna_logo_desc = carbon_get_theme_option( 'volna_logo_desc' );
if ( $volna_logo ) :
	?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="volna-logo" aria-label="<?php esc_attr_e( 'Home', 'volna' ); ?>">
	<?php echo wp_get_attachment_image( $volna_logo, 'full' ); ?>
	<?php if ( $volna_logo_desc ) : ?>
		<span class="volna-logo-desc">
			<?php echo wp_kses_post( nl2br( $volna_logo_desc ) ); ?>
		</span>
	<?php endif; ?>
</a>
<?php endif; ?>
