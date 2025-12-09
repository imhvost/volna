<?php
/**
 * Footer
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$volna_messengers        = carbon_get_theme_option( 'volna_messengers' );
$volna_footer_img        = carbon_get_theme_option( 'volna_footer_img' );
$volna_footer_tels       = carbon_get_theme_option( 'volna_footer_tels' );
$volna_footer_emails     = carbon_get_theme_option( 'volna_footer_emails' );
$volna_footer_btn        = carbon_get_theme_option( 'volna_footer_btn' );
$volna_footer_menu_title = carbon_get_theme_option( 'volna_footer_menu_title' );
$volna_footer_info_title = carbon_get_theme_option( 'volna_footer_info_title' );
$volna_footer_info       = carbon_get_theme_option( 'volna_footer_info' );
$volna_copyright         = carbon_get_theme_option( 'volna_copyright' );
$volna_developer_text    = carbon_get_theme_option( 'volna_developer_text' );
$volna_developer_link    = carbon_get_theme_option( 'volna_developer_link' );

$volna_form_title = carbon_get_theme_option( 'volna_form_title' );
$volna_form_desc  = carbon_get_theme_option( 'volna_form_desc' );
$volna_sent_title = carbon_get_theme_option( 'volna_sent_title' );
$volna_sent_desc  = carbon_get_theme_option( 'volna_sent_desc' );

?>
<div class="volna-feedback">
	<button class="volna-feedback-open swiper swiper-no-swiping">
		<span class="swiper-wrapper">
			<?php if ( $volna_messengers ) : ?>
				<?php foreach ( $volna_messengers as $key => $item ) : ?>
					<span class="swiper-slide">
						<?php echo wp_get_attachment_image( $item['icon'], 'full' ); ?>
					</span>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( $volna_footer_tels[0] ?? null ) : ?>
				<i class="swiper-slide">
					<svg class="volna-icon"><use xlink:href="#icon-call-calling"/></svg>
				</i>
			<?php endif; ?>
			<?php if ( $volna_footer_emails[0] ?? null ) : ?>
				<i class="swiper-slide">
					<svg class="volna-icon"><use xlink:href="#icon-sms-edit"/></svg>
				</i>
			<?php endif; ?>
		</span>
	</button>
	<button class="volna-feedback-close">
		<svg class="volna-icon"><use xlink:href="#icon-close"/></svg>
	</button>
	<div class="volna-feedback-links">
		<?php get_template_part( 'template-parts/messengers', '', array( 'messengers' => $volna_messengers ) ); ?>
		<?php if ( $volna_footer_tels[0] ?? null ) : ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $volna_footer_tels[0]['tel'] ) ); ?>" class="volna-feedback-link" aria-label="<?php esc_attr_e( 'Позвонить', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-call-calling"/></svg>
			</a>
		<?php endif; ?>
		<?php if ( $volna_footer_emails[0] ?? null ) : ?>
			<a href="mailto:<?php echo esc_attr( $volna_footer_emails[0]['email'] ); ?>" class="volna-feedback-link" aria-label="<?php esc_attr_e( 'Написать', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-sms-edit"/></svg>
			</a>
		<?php endif; ?>
	</div>
</div>
<footer class="volna-footer">
	<div class="volna-container">
		<div class="volna-footer-body">
			<?php get_template_part( 'template-parts/logo' ); ?>
			<?php if ( $volna_footer_tels ) : ?>
				<div class="volna-footer-contact-links volna-h5">
					<?php foreach ( $volna_footer_tels as $item ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $item['tel'] ) ); ?>" class="volna-footer-contact-link">
							<svg class="volna-icon"><use xlink:href="#icon-call"/></svg>
							<span><?php echo esc_html( $item['tel'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif ?>
			<?php if ( $volna_footer_emails ) : ?>
				<div class="volna-footer-contact-links volna-h5">
					<?php foreach ( $volna_footer_emails as $item ) : ?>
						<a href="mailto:<?php echo esc_attr( $item['email'] ); ?>" class="volna-footer-contact-link">
							<svg class="volna-icon"><use xlink:href="#icon-email"/></svg>
							<span><?php echo esc_html( $item['email'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif ?>
			<?php get_template_part( 'template-parts/messengers', '', array( 'messengers' => $volna_messengers ) ); ?>
			<?php if ( $volna_footer_btn ) : ?>
				<button class="volna-header-btn volna-btn" data-modal-open="volna-modal-application">
					<?php echo esc_html( $volna_footer_btn ); ?>
				</button>
			<?php endif; ?>
		</div>
		<div class="volna-footer-content">
			<?php if ( has_nav_menu( 'volna_header' ) ) : ?>
				<nav class="volna-footer-nav">
					<?php if ( $volna_footer_menu_title ) : ?>
						<div class="volna-footer-nav-title">
							<?php echo esc_html( $volna_footer_menu_title ); ?>
						</div>
					<?php endif; ?>
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'volna_footer',
								'container'      => false,
								'menu_class'     => 'volna-footer-menu',
							)
						);
					?>
				</nav>
			<?php endif; ?>
			<?php if ( $volna_footer_info || has_nav_menu( 'volna_rules' ) || $volna_copyright || ( $volna_developer_text && $volna_developer_link ) ) : ?>
				<div class="volna-footer-nav">
					<?php if ( $volna_footer_info_title ) : ?>
						<div class="volna-footer-nav-title">
							<?php echo esc_html( $volna_footer_info_title ); ?>
						</div>
					<?php endif; ?>
					<div class="volna-footer-info">
						<?php if ( $volna_footer_info ) : ?>
							<div class="volna-footer-info-item volna-content-text">
								<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo apply_filters( 'the_content', $volna_footer_info );
								?>
							</div>
						<?php endif; ?>
						<?php if ( has_nav_menu( 'volna_rules' ) || $volna_copyright || ( $volna_developer_text && $volna_developer_link ) ) : ?>
							<div class="volna-footer-info-item">
								<?php
								if ( has_nav_menu( 'volna_rules' ) ) {
									wp_nav_menu(
										array(
											'theme_location' => 'volna_rules',
											'container'  => false,
											'menu_class' => 'volna-rules-menu',
										)
									);
								}
								?>
								<?php if ( $volna_copyright ) : ?>
									<div class="volna-copyright">
										<?php echo esc_html( str_replace( '{Y}', gmdate( 'Y' ), $volna_copyright ) ); ?>
									</div>
								<?php endif; ?>
								<?php if ( $volna_developer_text && $volna_developer_link ) : ?>
									<a href="<?php echo esc_url( $volna_developer_link ); ?>" class="volna-developer" target="_blank">
										<?php echo esc_html( $volna_developer_text ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $volna_footer_img ) : ?>
		<div class="volna-footer-img">
			<?php echo wp_get_attachment_image( $volna_footer_img, 'full' ); ?>
		</div>
	<?php endif; ?>
</footer>
<div id="volna-modal-product" class="volna-modal volna-modal-product">
	<div tabindex="-1" class="volna-modal-wrapp">
		<div role="dialog" aria-modal="true" class="volna-modal-body">
			<button class="volna-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-close"/></svg>
			</button>
			<div class="volna-modal-content"></div>
		</div>
	</div>
</div>
<div id="volna-modal-application" class="volna-modal volna-modal-application">
	<div tabindex="-1" class="volna-modal-wrapp">
		<div role="dialog" aria-modal="true" class="volna-modal-body">
			<button class="volna-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-close"/></svg>
			</button>
			<div class="volna-modal-head">
				<?php if ( $volna_form_title ) : ?>
					<div class="volna-modal-title volna-h2">
						<?php echo esc_html( $volna_form_title ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $volna_form_desc ) : ?>
					<div class="volna-modal-desc">
						<?php echo esc_html( $volna_form_desc ); ?>
					</div>
				<?php endif; ?>
				<form action="?" class="volna-application-form volna-contact-form">
					<input type="hidden" name="subject">
					<input type="hidden" name="post_id">
					<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Имя', 'volna' ); ?>">
						<input
							type="text"
							name="name"
							class="volna-input"
							required
							placeholder="Имя"
						>
					</label>
					<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Телефон', 'volna' ); ?>">
						<input
							type="tel"
							name="tel"
							class="volna-input"
							required
							placeholder="+7 (999) 999-99-99"
							data-alert="<?php esc_attr_e( 'Введите телефон', 'volna' ); ?>"
						>
					</label>
					<label class="volna-input-block" aria-label="<?php esc_attr_e( 'Оставьте свой вопрос', 'volna' ); ?>">
						<textarea
							name="text"
							class="volna-input volna-textarea"
							required
							placeholder="Оставьте свой вопрос"
						></textarea>
					</label>
					<?php get_template_part( 'template-parts/form', 'agreement' ); ?>
					<button type="submit" class="volna-btn volna-submit">
						<?php esc_html_e( 'Отправить', 'volna' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="volna-modal-sent" class="volna-modal volna-modal-sent">
	<div tabindex="-1" class="volna-modal-wrapp">
		<div role="dialog" aria-modal="true" class="volna-modal-body">
			<button class="volna-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-close"/></svg>
			</button>
			<div class="volna-modal-head">
				<?php if ( $volna_sent_title ) : ?>
					<div class="volna-modal-title volna-h2">
						<?php echo esc_html( $volna_sent_title ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( $volna_sent_desc ) : ?>
					<div class="volna-modal-desc">
						<?php echo esc_html( $volna_sent_desc ); ?>
					</div>
				<?php endif; ?>
			</div>
			<svg class="modal-sent-icon" width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M60 110C87.6142 110 110 87.6142 110 60C110 32.3858 87.6142 10 60 10C32.3858 10 10 32.3858 10 60C10 87.6142 32.3858 110 60 110Z" stroke="#1D7AF2" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M45 60L55 70L75 50" stroke="#1D7AF2" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<button type="button" class="volna-btn" data-modal-close>
				<?php esc_html_e( 'Закрыть', 'volna' ); ?>
			</button>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
