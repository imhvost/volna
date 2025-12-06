<?php
/**
 * Slider Nav
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="volna-slider-nav">
	<button class="volna-slider-arrow volna-slider-arrow-prev" aria-label="<?php esc_attr_e( 'Назад', 'volna' ); ?>">
		<svg class="volna-icon"><use xlink:href="#icon-chevron-left"/></svg>
	</button>
	<div class="volna-slider-pagination"></div>
	<button class="volna-slider-arrow volna-slider-arrow-next" aria-label="<?php esc_attr_e( 'Вперед', 'volna' ); ?>">
		<svg class="volna-icon"><use xlink:href="#icon-chevron-right"/></svg>
	</button>
</div>
