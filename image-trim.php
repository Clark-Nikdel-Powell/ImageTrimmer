<?php

/*
Plugin Name: Image Trim
Plugin URI: http://clarknikdelpowell.com
Description: Trim whitespace around images
Version: 1.0
Author: Joe Cruz
Author URI: http://clarknikdelpowell.com
License: GPL2
*/

add_filter( 'media_row_actions', function ( $actions, $post ) {
	$actions['trim'] = '<a href="' . plugins_url( 'trim.php?id=' . $post->ID, __FILE__ ) . '">Trim Whitespace</a>';

	return $actions;
}, 10, 2 );

add_filter( 'attachment_fields_to_edit', function ( $fields, $post ) {
	$fields[] = array(
		'label'        => '',
		'input'        => 'html',
		'html'         => '<a class="button-primary button-large" href="' . plugins_url( 'trim.php?id=' . $post->ID, __FILE__ ) . '">Trim Whitespace</a>',
		'show_in_edit' => false,
	);

	return $fields;
}, 10, 2 );


add_action( 'admin_init', function () {
	add_meta_box( 'trim-button-meta', 'Trim Image Whitespace',
		function () {
			global $post;
			echo '<a class="button-primary button-large" href="' . plugins_url( 'trim.php?refer=replace&id=' . $post->ID, __FILE__ ) . '">Trim Whitespace</a>';
		},
		'attachment', 'side', 'low'
	);
} );
