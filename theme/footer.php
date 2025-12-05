<?php
/**
 * Footer
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$volna_form_title = carbon_get_theme_option( 'volna_form_title' );
$volna_form_desc  = carbon_get_theme_option( 'volna_form_desc' );
$volna_sent_title = carbon_get_theme_option( 'volna_sent_title' );
$volna_sent_desc  = carbon_get_theme_option( 'volna_sent_desc' );

?>
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
