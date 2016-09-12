<?php

require( '../../../wp-load.php' );
include( ABSPATH . 'wp-admin/includes/image.php' );

require( 'CNP/ImageTrim.php' );

if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
	$id   = (int) $_GET['id'];
	$post = get_post( $id );

	if ( ! $post || 'attachment' !== $post->post_type || ! $post->post_mime_type ) {
		return;
	}
	$uri = get_attached_file( $id );

	$file = CNP\ImageTrim::trim( $post->guid, $post->post_mime_type, $uri, $id );
	$meta = wp_generate_attachment_metadata( $id, $uri );
	wp_update_attachment_metadata( $id, $meta );

	if ( wp_get_referer() ) {
		wp_safe_redirect( wp_get_referer() );
	} else {
		wp_safe_redirect( get_home_url() );
	}
}
