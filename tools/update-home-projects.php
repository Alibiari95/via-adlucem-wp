<?php
/**
 * Home page Projects section (EN/AZ/UZ): removes the "Selected work" label and turns
 * off the slider arrows and the "All projects" link.
 *
 *     require $root . '/tools/update-home-projects.php';
 *     return va_update_home_projects();
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

function va_update_home_projects() {
	$log = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$page_id = va_update_page_id( 'page-home-' . $lang );
		$data    = $page_id ? json_decode( get_post_meta( $page_id, '_elementor_data', true ), true ) : null;
		$section = &$data[3]['elements'];
		if ( ! isset( $section[1]['widgetType'] ) || 'va-projects-slider' !== $section[1]['widgetType'] ) {
			$log[] = "home $lang: projects section not where expected, skipped";
			continue;
		}
		// Header row, left column: keep the #projects anchor, drop the label heading.
		$left = &$section[0]['elements'][0]['elements'];
		$left = array_values( array_filter( $left, fn( $el ) => false === strpos( $el['settings']['_css_classes'] ?? '', 'va-label' ) ) );
		unset( $left );
		$section[1]['settings']['show_arrows']   = '';
		$section[1]['settings']['show_all_link'] = '';
		unset( $section );
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $page_id, '_elementor_css' );
		$log[] = "home $lang: updated page $page_id";
	}
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $log;
}
