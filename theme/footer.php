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
		<button class="volna-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'volna' ); ?>">
			<svg class="volna-icon"><use xlink:href="#icon-close-circle"/></svg>
		</button>
		<div role="dialog" aria-modal="true" class="volna-modal-body"></div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
