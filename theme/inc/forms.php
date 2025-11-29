<?php
/**
 * Form functions
 *
 * @package volna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_nopriv_volna_contact_form', 'volna_contact_form' );
add_action( 'wp_ajax_volna_contact_form', 'volna_contact_form' );

/**
 * Ajax form function
 *
 * @return void
 */
function volna_contact_form() {

	check_ajax_referer( 'volna_ajaxnonce', 'nonce' );

	$fields = volna_check_ajax_text_fields(
		array(
			'name',
			'email',
			'tel',
			'user_id',
			'post_type',
			'post_id',
			'subject',
		),
		false
	);

	$fields['text'] = isset( $_POST['text'] )
		? sanitize_textarea_field( wp_unslash( $_POST['text'] ) )
		: '';

	$titles = array(
		'name'  => __( 'Name', 'volna' ),
		'email' => __( 'Email', 'volna' ),
		'tel'   => __( 'Phone', 'volna' ),
		'text'  => __( 'Message', 'volna' ),
	);

	global $_FILES;

	$attachments = array();
	$file_links  = '';
	$file_urls   = array();

	if ( ! empty( $_FILES ) && count( $_FILES ) ) {
		foreach ( $_FILES as $key => $file ) {
			if ( ! empty( $file['name'] ) && is_uploaded_file( $file['tmp_name'] ) ) {
				$filetype = wp_check_filetype( $file['name'] );
				if ( ! in_array( $filetype['type'], array( 'image/jpeg', 'image/png', 'application/pdf' ), true ) ) {
					continue;
				}
				$upload_overrides = array( 'test_form' => false );
				$upload_result    = wp_handle_upload( $file, $upload_overrides );

				if ( ! empty( $upload_result['file'] ) ) {
					$attachments[]     = $upload_result['file'];
					$file_urls[ $key ] = $upload_result['url'];
					$file_links       .= '<a href="' . esc_url( $upload_result['url'] ) . '" download>' . esc_html( $file['name'] ) . '</a>';
				}
			}
		}
	}

	$send_to = get_bloginfo( 'admin_email' );

	$headers = array(
		'From: ' . ( $fields['email'] ? $fields['email'] : $send_to ) . '',
		'Content-Type: text/html',
	);

	if ( ! $fields['subject'] ) {
		$fields['subject'] = esc_html( __( 'Нова заявка', 'volna' ) );
	}

	$msg = '<p><b>' . esc_html__( 'Дата', 'volna' ) . ':</b> ' . wp_date( 'Y-m-d H:i:s' ) . '</p>';
	foreach ( $titles as $key => $item ) {
		if ( $fields[ $key ] ) {
			$msg .= '<p><b>' . esc_html( $item ) . ':</b> ' . esc_html( $fields[ $key ] ) . '</p>';
		}
	}
	if ( $fields['user_id'] ) {
		$msg .= '<p><b>' . esc_html__( 'User', 'volna' ) . ': </b> <a href="' . esc_url( admin_url( 'user-edit.php?user_id=' . $fields['user_id'] ) ) . '">' . esc_html( $fields['user_id'] ) . '</a></p>';
	}
	if ( $fields['post_type'] && $fields['post_id'] ) {
		$msg .= '<p><b>' . esc_html__( 'Post', 'volna' ) . ': </b> <a href="' . esc_url( get_edit_post_link( $fields['post_id'] ) ) . '">' . esc_html( $fields['post_id'] ) . '</a></p>';
	}
	if ( $file_links ) {
		$msg .= '<p><b>' . esc_html__( 'Files', 'volna' ) . ': </b> ' . $file_links . '</p>';
	}

	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$remote_addr = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		$msg        .= '<p><b>REMOTE_ADDR:</b> ' . esc_html( $remote_addr ) . '</p>';
	}

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer = sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
		$msg    .= '<p><b>HTTP_REFERER:</b> <a href="' . esc_url( $referer ) . '" target="_blank">' . esc_html( $referer ) . '</a></p>';
	}

	if ( $fields['email'] || $fields['tel'] ) {
		$mail = wp_mail( $send_to, $fields['subject'], $msg, $headers, $attachments );
		if ( $mail ) {
			wp_send_json_success();
		}
	}

	wp_send_json_error( array( 'message' => esc_html__( 'Error submitting form.', 'volna' ) ) );
}
