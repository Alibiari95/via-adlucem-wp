<?php
/**
 * Applies content/home/*.php (via-website-copy.pdf) to the live site:
 * home slider, About and Services sections (EN/AZ/UZ), home SEO, footer
 * strings, contact details on the Contact pages and the Instagram link.
 *
 *     require $root . '/tools/update-home.php';
 *     return va_update_home( $root );
 *
 * Only the targeted widgets are changed; every other section stays as it is.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/seed-services.php';

function va_update_home( $root ) {
	$log     = array();
	$content = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$content[ $lang ] = require $root . '/content/home/' . $lang . '.php';
	}
	$en = $content['en'];

	// Real photos uploaded with the services (tools/seed-services.php).
	$media = array();
	foreach ( $en['slides'] as $slide ) {
		$ids                       = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_va_seed', 'meta_value' => 'media-' . $slide['image'], 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
		$media[ $slide['image'] ] = $ids ? (int) $ids[0] : 0;
	}

	$services = get_posts( array( 'post_type' => 'va_service', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC' ), 'fields' => 'ids', 'lang' => 'en' ) );

	foreach ( VA_SEED_LANGS as $lang ) {
		$c       = $content[ $lang ];
		$page_id = va_update_page_id( 'page-home-' . $lang );
		if ( ! $page_id ) {
			$log[] = "home $lang: page not found";
			continue;
		}
		$data = json_decode( get_post_meta( $page_id, '_elementor_data', true ), true );

		// 1. Slider.
		$slider = &$data[0]['elements'][0];
		if ( 'va-hero-slider' !== ( $slider['widgetType'] ?? '' ) ) {
			$log[] = "home $lang: slider not where expected, skipped";
			continue;
		}
		$links = array(
			// Polylang adds no language prefix here (no current language in this request).
			'services' => home_url( 'en' === $lang ? '/services/' : '/' . $lang . '/services/' ),
			'contact'  => va_seed_contact_url( $lang ),
		);
		$slides = array();
		foreach ( $en['slides'] as $i => $base ) {
			$t        = $c['slides'][ $i ];
			$image_id = $media[ $base['image'] ];
			$url      = $links[ $base['link'] ] ?? $base['link'];
			$slides[] = array(
				'_id'         => va_el_id(),
				'image'       => array( 'id' => $image_id, 'url' => $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '' ),
				'kicker'      => '',
				'title'       => $t['title'],
				'subtitle'    => $t['subtitle'],
				'button_text' => $t['button'],
				'button_link' => array( 'url' => $url, 'is_external' => '', 'nofollow' => '' ),
			);
		}
		$slider['settings']['slides'] = $slides;
		unset( $slider );

		// 2. About: label | statement, text (button kept).
		$about_cols = &$data[1]['elements'][0]['elements'];
		$about_cols[0]['elements'][0]['settings']['title'] = $c['about']['label'];
		$about_cols[1]['elements'][0]['settings']['title'] = $c['about']['statement'];
		$about_cols[1]['elements'][1]['settings']['editor'] = '<p>' . esc_html( $c['about']['text'] ) . '</p>';
		unset( $about_cols );

		// 3. Services: label, title and the four boxes, each linking to its service page.
		$head = &$data[2]['elements'][0]['elements'];
		$head[0]['elements'][1]['settings']['title'] = $c['services']['label'];
		$head[1]['elements'][0]['settings']['title'] = $c['services']['title'];
		unset( $head );
		$boxes = &$data[2]['elements'][1]['elements'];
		foreach ( $boxes as $i => &$box ) {
			$item       = $c['services']['items'][ $i ];
			$service_id = isset( $services[ $i ] ) ? pll_get_post( $services[ $i ], $lang ) : 0;
			$box['settings']['title_text']       = $item['title'];
			$box['settings']['description_text'] = $item['text'];
			$box['settings']['selected_icon']    = array( 'value' => $en['services']['items'][ $i ]['icon'], 'library' => 'fa-solid' );
			$box['settings']['link']             = array( 'url' => $service_id ? get_permalink( $service_id ) : '', 'is_external' => '', 'nofollow' => '' );
		}
		unset( $box, $boxes );

		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $page_id, '_elementor_css' );
		va_seed_seo( $page_id, $c['seo']['title'], $c['seo']['description'] );
		$log[] = "home $lang: updated page $page_id";
	}

	// Contact pages: replace the sample address / phone and point the map at the office.
	$replace = array(
		'28 May Street 15, Baku, Azerbaijan' => 'address',
		'28 May küçəsi 15, Bakı, Azərbaycan' => 'address',
		'28 May koʻchasi 15, Boku, Ozarbayjon' => 'address',
	);
	foreach ( VA_SEED_LANGS as $lang ) {
		$page_id = va_update_page_id( 'page-contact-' . $lang );
		if ( ! $page_id ) {
			continue;
		}
		$address = $content[ $lang ]['strings']['16e Keykab Khanim Safaraliyeva St, Time Tower, 3rd floor, Baku 1010'] ?? '16e Keykab Khanim Safaraliyeva St, Time Tower, 3rd floor, Baku 1010';
		$json    = get_post_meta( $page_id, '_elementor_data', true );
		$json    = str_replace( array_keys( $replace ), $address, $json );
		$json    = str_replace( array( '+994 12 345 67 89', '+994123456789' ), array( '+994 55 533 41 47', '+994555334147' ), $json );
		$data    = json_decode( $json, true );
		array_walk_recursive(
			$data,
			function ( &$value, $key ) use ( $en ) {
				if ( 'address' === $key ) {
					$value = $en['map'];
				}
			}
		);
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $page_id, '_elementor_css' );
		$log[] = "contact $lang: details updated";
	}

	// Footer strings (Languages → Translations).
	foreach ( VA_SEED_LANGS as $lang ) {
		if ( empty( $content[ $lang ]['strings'] ) ) {
			continue;
		}
		$mo = new PLL_MO();
		$mo->import_from_db( PLL()->model->get_language( $lang ) );
		foreach ( $content[ $lang ]['strings'] as $source => $translated ) {
			$mo->add_entry( $mo->make_entry( $source, $translated ) );
		}
		$mo->export_to_db( PLL()->model->get_language( $lang ) );
		$log[] = "strings $lang: " . count( $content[ $lang ]['strings'] );
	}

	set_theme_mod( 'va_social_instagram', 'https://www.instagram.com/viaadlucem_official/' );
	$log[] = 'instagram link set';

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	wp_cache_flush();
	return $log;
}

function va_update_page_id( $seed_key ) {
	$ids = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'meta_key' => '_va_seed', 'meta_value' => $seed_key, 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
	return $ids ? (int) $ids[0] : 0;
}
