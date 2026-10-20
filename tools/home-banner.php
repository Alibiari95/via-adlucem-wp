<?php
/**
 * Inserts the full-width photo band (content/home/banner.php) into every home page,
 * right after the "About us" section. Skips pages that already have it.
 *
 *     require $root . '/tools/home-banner.php';
 *     return va_home_banner( $root );
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

function va_home_banner( $root ) {
	$c     = require $root . '/content/home/banner.php';
	$ids   = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_va_seed', 'meta_value' => 'media-' . $c['image'], 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
	$image = $ids ? array( 'id' => (int) $ids[0], 'url' => wp_get_attachment_image_url( $ids[0], 'full' ) ) : array( 'id' => '', 'url' => '' );
	$log   = array();

	foreach ( pll_languages_list() as $lang ) {
		$page_id = va_update_page_id( 'page-home-' . $lang );
		if ( ! $page_id || empty( $c['text'][ $lang ] ) ) {
			continue;
		}
		$data = json_decode( get_post_meta( $page_id, '_elementor_data', true ), true );
		if ( false !== strpos( wp_json_encode( $data ), 'va-photo-banner' ) ) {
			$log[] = "home $lang: already has the photo band";
			continue;
		}
		$band = va_con(
			array(
				'content_width'         => 'boxed',
				'flex_direction'        => 'column',
				'flex_justify_content'  => 'center',
				'css_classes'           => 'va-photo-banner',
				'background_background' => 'classic',
				'background_image'      => $image,
				'background_position'   => 'center center',
				'background_size'       => 'cover',
				'background_repeat'     => 'no-repeat',
			),
			array( va_heading( $c['text'][ $lang ], 'h2', 'va-photo-banner__title' ) )
		);
		array_splice( $data, 2, 0, array( $band ) ); // After hero (0) and About (1).
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $page_id, '_elementor_css' );
		$log[] = "home $lang: photo band added";
	}
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $log;
}
