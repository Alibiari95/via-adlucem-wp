<?php
/**
 * Hides or shows a language for visitors.
 *
 *     require $root . '/tools/hide-language.php';
 *     va_hide_language( 'az' );   // all its pages, services, projects → Draft; removed from the switcher
 *     va_show_language( 'az' );   // restores the statuses saved when it was hidden
 *
 * The previous status of each post is kept in `_va_status_before_hide`.
 */

defined( 'ABSPATH' ) || exit;

function va_language_posts( $lang ) {
	return get_posts(
		array(
			'post_type'   => array( 'page', 'va_service', 'va_project', 'post' ),
			'post_status' => array( 'publish', 'future', 'pending', 'private' ),
			'numberposts' => -1,
			'lang'        => $lang,
		)
	);
}

function va_hide_language( $lang ) {
	$hidden = (array) get_option( 'va_hidden_languages', array() );
	update_option( 'va_hidden_languages', array_values( array_unique( array_merge( $hidden, array( $lang ) ) ) ) );

	$done = array();
	foreach ( va_language_posts( $lang ) as $post ) {
		update_post_meta( $post->ID, '_va_status_before_hide', $post->post_status );
		wp_update_post( array( 'ID' => $post->ID, 'post_status' => 'draft' ) );
		$done[] = $post->post_type . ' ' . $post->ID . ' ' . $post->post_title;
	}
	wp_cache_flush();
	return $done;
}

function va_show_language( $lang ) {
	update_option( 'va_hidden_languages', array_values( array_diff( (array) get_option( 'va_hidden_languages', array() ), array( $lang ) ) ) );

	$done  = array();
	$posts = get_posts( array( 'post_type' => 'any', 'post_status' => 'draft', 'numberposts' => -1, 'lang' => $lang, 'meta_key' => '_va_status_before_hide' ) );
	foreach ( $posts as $post ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_status' => get_post_meta( $post->ID, '_va_status_before_hide', true ) ) );
		delete_post_meta( $post->ID, '_va_status_before_hide' );
		$done[] = $post->post_type . ' ' . $post->ID;
	}
	wp_cache_flush();
	return $done;
}
