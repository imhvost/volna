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
	<button class="volna-slider-arrow volna-slider-arrow-prev volna-btn" aria-label="<?php esc_attr_e( 'Prev', 'volna' ); ?>">
		<svg class="volna-icon"><use xlink:href="#icon-arrow-left"/></svg>
	</button>
	<button class="volna-slider-arrow volna-slider-arrow-next volna-btn" aria-label="<?php esc_attr_e( 'Next', 'volna' ); ?>">
		<svg class="volna-icon"><use xlink:href="#icon-arrow-right"/></svg>
	</button>
</div>
