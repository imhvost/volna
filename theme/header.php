<?php
/**
 * Header
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$volna_logo           = carbon_get_theme_option( 'volna_logo' );
$volna_logo_desc      = carbon_get_theme_option( 'volna_logo_desc' );
$volna_messengers     = carbon_get_theme_option( 'volna_messengers' );
$volna_header_address = carbon_get_theme_option( 'volna_header_address' );
$volna_header_tel     = carbon_get_theme_option( 'volna_header_tel' );
$volna_header_regime  = carbon_get_theme_option( 'volna_header_regime' );
$volna_header_btn     = carbon_get_theme_option( 'volna_header_btn' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Manrope-VariableFont_wght.woff2" as="font" type="font/woff2" crossorigin>
	<?php wp_site_icon(); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php get_template_part( 'template-parts/sprite' ); ?>
<header class="volna-header">
	<div class="volna-container">
		<?php
		get_template_part(
			'template-parts/logo',
			'',
			array(
				'logo' => $volna_logo,
				'desc' => $volna_logo_desc,
			)
		);
		?>
		<?php if ( has_nav_menu( 'volna_header' ) || $volna_messengers || $volna_header_address || $volna_header_tel || $volna_header_regime || $volna_header_btn ) : ?>
			<nav id="volna-header-nav" class="volna-modal volna-header-nav">
				<div tabindex="-1" class="volna-modal-wrapp">
					<button class="volna-header-nav-close volna-visible-tablet" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
						<svg class="volna-icon"><use xlink:href="#icon-close-circle"/></svg>
					</button>
					<div role="dialog" aria-modal="true" class="volna-modal-body">
						<?php if ( has_nav_menu( 'volna_header' ) ) : ?>
							<div class="volna-header-menu-wrapp">
								<div class="volna-header-menu-body">
									<div class="volna-container">
										<?php
										wp_nav_menu(
											array(
												'theme_location' => 'volna_header',
												'container'      => false,
												'menu_class'     => 'volna-header-menu',
												'link_before'    => '<span>',
												'link_after'     => '</span><svg class="volna-icon"><use xlink:href="#icon-chevron-right"/></svg>',
											)
										);
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="volna-head">
							<?php
							get_template_part(
								'template-parts/logo',
								'',
								array(
									'logo' => $volna_logo,
									'desc' => $volna_logo_desc,
								)
							);
							?>
							<?php if ( $volna_header_address ) : ?>
									<div class="volna-header-address">
										<svg class="volna-icon volna-visible-tablet"><use xlink:href="#icon-location"/></svg>
										<span>
											<?php echo wp_kses_post( nl2br( $volna_header_address ) ); ?>
										</span>
									</div>
								<?php endif; ?>
								<?php if ( $volna_header_tel || $volna_header_regime ) : ?>
									<div class="volna-header-contacts">
										<?php if ( $volna_header_tel ) : ?>
											<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $volna_header_tel ) ); ?>" class="volna-header-tel">
												<svg class="volna-icon volna-visible-tablet"><use xlink:href="#icon-call"/></svg>
												<span>
													<?php echo esc_html( $volna_header_tel ); ?>
												</span>
											</a>
										<?php endif; ?>
										<?php if ( $volna_header_regime ) : ?>
											<div class="volna-header-regime">
												<?php echo esc_html( $volna_header_regime ); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php get_template_part( 'template-parts/messengers', '', array( 'messengers' => $volna_messengers ) ); ?>
								<?php if ( $volna_header_btn ) : ?>
									<button class="volna-header-btn volna-btn volna-btn-small" data-modal-open="modal-application">
										<?php echo esc_html( $volna_header_btn ); ?>
									</button>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</nav>
			<button type="button" class="volna-header-nav-open volna-visible-tablet" data-modal-open="volna-header-nav" aria-label="<?php esc_attr_e( 'Меню', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-menu"/></svg>
			</button>
		<?php endif; ?>
	</div>
</header>
