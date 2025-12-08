<?php
/**
 * Share
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$url          = get_the_permalink();
$target_title = rawurlencode( get_the_title() );

$share = array(
	'telegram' => 'https://t.me/share/url?url=' . $url . '&text=' . $target_title,
	'whatsapp' => 'https://api.whatsapp.com/send?text=' . $target_title . '%20' . $url,
	'vk'       => 'https://vk.com/share.php?url=' . $url . '&target_title=' . $target_title,
	'ok'       => 'https://connect.ok.ru/offer?url=' . $url . '&target_title=' . $target_title,
	'zen'      => 'https://zen.yandex.ru/share/?url=' . $url . '&target_title=' . $target_title,
);

?>
<div class="volna-share" data-share-url="<?php echo esc_attr( rawurldecode( $url ) ); ?>">
	<?php foreach ( $share as $key => $item ) : ?>
		<a
			href="<?php echo esc_url( $item ); ?>"
			class="volna-share-link"
			target="_blank"
			rel="noopener noreferrer"
			aria-label="<?php esc_attr_e( 'Поделиться в', 'volna' ); ?> <?php echo esc_attr( $key ); ?>"
		>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/share-<?php echo esc_attr( $key ); ?>.svg" alt="">
		</a>
	<?php endforeach; ?>
	<button
		type="button"
		class="volna-share-copy"
		data-copy-target="<?php echo esc_attr( rawurldecode( $url ) ); ?>"
		aria-label="<?php echo esc_attr__( 'Скопировать ссылку', 'volna' ); ?>"
	>
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/share-link.svg" alt="">
	</button>
</div>
