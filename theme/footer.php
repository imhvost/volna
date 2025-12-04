<?php
/**
 * Footer
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="volna-product-modal" class="volna-modal volna-product-modal">
	<div tabindex="-1" class="volna-modal-wrapp">
		<div role="dialog" aria-modal="true" class="volna-modal-body">
			<button class="volna-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
				<svg class="volna-icon"><use xlink:href="#icon-close"/></svg>
			</button>
			<div class="volna-modal-content"></div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
