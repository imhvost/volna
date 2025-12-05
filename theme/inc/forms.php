<?php
/**
 * Form functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax form function
 *
 * @return void
 */
function volna_contact_form() {

	check_ajax_referer( 'volna_ajaxnonce', 'nonce' );

	$fields = volna_check_text_fields(
		array(
			'subject',
			'post_id',
			'name',
			'tel',
			'where',
			'calculator',
		),
		false
	);

	$fields['text'] = isset( $_POST['text'] )
		? sanitize_textarea_field( wp_unslash( $_POST['text'] ) )
		: '';

	if ( $fields['calculator'] ) {
		$fields['calculator'] = str_replace( '|', '<br>', $fields['calculator'] );
	}

	$titles = array(
		'name'       => __( 'Имя', 'volna' ),
		'tel'        => __( 'Телефон', 'volna' ),
		'where'      => __( 'Куда отправить?', 'volna' ),
		'text'       => __( 'Сообщение', 'volna' ),
		'calculator' => __( 'Калькулятор', 'volna' ),
	);

	global $_FILES;
	$files = isset( $_FILES ) ? wp_unslash( $_FILES ) : array();

	$attachments = array();

	if ( ! empty( $files ) && count( $files ) ) {
		foreach ( $files as $key => $file ) {
			if ( ! empty( $file['name'] ) && is_uploaded_file( $file['tmp_name'] ) ) {
				$filetype = wp_check_filetype( $file['name'] );
				if ( ! in_array( $filetype['type'], array( 'image/jpeg', 'image/png', 'application/pdf' ), true ) ) {
					continue;
				}
				$tmp = wp_tempnam( $file['name'] );
				move_uploaded_file( $file['tmp_name'], $tmp );
				$attachments[] = $tmp;
			}
		}
	}

	$send_to = get_bloginfo( 'admin_email' );

	$headers = array(
		'From: <' . ( $send_to ) . '>',
		'Content-Type: text/html; charset=UTF-8',
	);

	$msg  = '<b>' . $fields['subject'] . '</b><br>';
	$msg .= '<b>' . esc_html__( 'Дата', 'volna' ) . ':</b> ' . wp_date( 'Y-m-d H:i:s' ) . '<br>';
	foreach ( $titles as $key => $item ) {
		if ( $fields[ $key ] ) {
			$msg .= '<b>' . esc_html( $item ) . ':</b> ' . esc_html( $fields[ $key ] ) . '<br>';
		}
	}

	if ( $fields['post_id'] ) {
			$msg .= '<b>' . esc_html__( 'Ссылка на пост', 'volna' ) . ': </b> <a href="' . esc_url( get_edit_post_link( $fields['post_id'] ) ) . '">' . esc_html( $fields['post_id'] ) . '</a><br>';
	}

	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$remote_addr = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		$msg        .= '<b>REMOTE_ADDR:</b> ' . esc_html( $remote_addr ) . '<br>';
	}

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer = sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
		$msg    .= '<b>HTTP_REFERER:</b> <a href="' . esc_url( $referer ) . '" target="_blank">' . esc_html( $referer ) . '</a><br>';
	}

	if ( $fields['tel'] ) {
		$mail = wp_mail( $send_to, $fields['subject'], $msg, $headers, $attachments );
		if ( $mail ) {
			wp_send_json_success();
		}
	}

	wp_send_json_error( array( 'message' => esc_html__( 'Ошибка отправки.', 'volna' ) ) );
}

add_action( 'wp_ajax_nopriv_volna_contact_form', 'volna_contact_form' );
add_action( 'wp_ajax_volna_contact_form', 'volna_contact_form' );
