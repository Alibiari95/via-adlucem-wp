<?php
/**
 * Applies content/home/az-2.php to the Azerbaijani home page: slider texts, About heading,
 * text and button, and the four service boxes. Finds the widgets by type and CSS class,
 * so it works whatever sections were added around them.
 *
 *     require $root . '/tools/update-home-az.php';
 *     return va_update_home_az( $root );
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

function va_update_home_az( $root ) {
	$c       = require $root . '/content/home/az-2.php';
	$page_id = va_update_page_id( 'page-home-az' );
	$data    = json_decode( get_post_meta( $page_id, '_elementor_data', true ), true );
	$log     = array();

	// Service page the boxes link to.
	$service = get_posts( array( 'post_type' => 'va_service', 'lang' => 'az', 'numberposts' => 1, 'orderby' => 'menu_order', 'order' => 'ASC', 'menu_order' => $c['services']['link_service'], 'fields' => 'ids' ) );
	$service_url = $service ? get_permalink( $service[0] ) : '';

	$about_done = false;
	$box        = 0;
	$walk       = function ( &$nodes, $in_services = false ) use ( &$walk, $c, &$log, &$about_done, &$box, $service_url ) {
		foreach ( $nodes as &$el ) {
			$classes = ( $el['settings']['css_classes'] ?? '' ) . ' ' . ( $el['settings']['_css_classes'] ?? '' );
			$type    = $el['widgetType'] ?? '';

			if ( 'va-hero-slider' === $type ) {
				foreach ( $el['settings']['slides'] as $i => &$slide ) {
					if ( isset( $c['slides'][ $i ] ) ) {
						$slide['title']       = $c['slides'][ $i ]['title'];
						$slide['subtitle']    = $c['slides'][ $i ]['subtitle'];
						$slide['button_text'] = $c['slides'][ $i ]['button'];
					}
				}
				unset( $slide );
				$log[] = 'slider: 3 slides';
			} elseif ( ! $about_done && 'heading' === $type && false !== strpos( $classes, 'va-statement' ) ) {
				// The first statement heading on the page is the About heading; its text and button follow it.
				$el['settings']['title'] = $c['about']['statement'];
				$about_done              = 'heading';
			} elseif ( 'heading' === $about_done && 'text-editor' === $type ) {
				$el['settings']['editor'] = '<p>' . esc_html( $c['about']['text'] ) . '</p>';
				$about_done               = 'text';
			} elseif ( 'text' === $about_done && 'button' === $type ) {
				$el['settings']['text'] = $c['about']['button'];
				$about_done             = true;
				$log[]                  = 'about: heading, text, button';
			} elseif ( $in_services && 'icon-box' === $type && isset( $c['services']['items'][ $box ] ) ) {
				$item                                = $c['services']['items'][ $box ];
				$el['settings']['title_text']        = $item['title'];
				$el['settings']['description_text']  = $item['text'];
				$el['settings']['selected_icon']     = array( 'value' => $item['icon'], 'library' => 'fa-solid' );
				$el['settings']['link']              = array( 'url' => $service_url, 'is_external' => '', 'nofollow' => '' );
				++$box;
			}
			if ( ! empty( $el['elements'] ) ) {
				$walk( $el['elements'], $in_services || false !== strpos( $classes, 'va-services' ) );
			}
		}
	};
	$walk( $data );
	$log[] = "services: $box boxes → " . wp_make_link_relative( $service_url );

	update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $page_id, '_elementor_css' );
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $log;
}
