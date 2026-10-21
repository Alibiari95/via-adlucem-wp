<?php
/**
 * Seeds the Services custom post type: images, 4 services × 3 languages (linked as
 * Polylang translations), SEO meta, menu entries and Polylang strings.
 *
 *     require $root . '/tools/seed-services.php';
 *     return va_seed_services( $root );
 *
 * Idempotent like tools/seed.php: existing services (by `_va_seed` meta) are kept
 * unless array( 'force' => true ) is passed.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/seed.php';

function va_seed_services( $root, $opts = array() ) {
	$force = ! empty( $opts['force'] );
	$log   = array();

	if ( ! post_type_exists( 'va_service' ) ) {
		return new WP_Error( 'va_seed', 'The va_service post type is not registered (deploy mu-plugins/va-services.php first).' );
	}

	$content = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$content[ $lang ] = require $root . '/content/services/' . $lang . '.php';
	}

	// Translatable in Polylang, editable with Elementor.
	$pll = get_option( 'polylang' );
	$pll['post_types'] = array_values( array_unique( array_merge( (array) $pll['post_types'], array( 'va_service' ) ) ) );
	update_option( 'polylang', $pll );
	$cpt = (array) get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	update_option( 'elementor_cpt_support', array_values( array_unique( array_merge( $cpt, array( 'va_service' ) ) ) ) );

	$media = va_seed_media(
		$root . '/content/media',
		'webp',
		array(
			'floor-plans'          => 'Architectural floor plans with a spirit level, keys and a safety helmet',
			'engineers-blueprints' => 'Two engineers in hard hats reviewing blueprints at a concrete structure',
			'engineers-shadows'    => 'Engineers in hard hats on site, casting shadows on a white wall',
			'pipeline-walkway'     => 'Large steel pipeline with valves and a service walkway',
			'pipes-tiles'          => 'Painted plumbing pipes and fittings against a tiled wall',
		)
	);
	$log[] = 'media: ' . wp_json_encode( $media );

	$ids = array(); // [ index ][ lang ] => post ID
	foreach ( VA_SEED_LANGS as $lang ) {
		$contact_url = va_seed_contact_url( $lang );
		foreach ( $content[ $lang ]['services'] as $i => $service ) {
			$images                = $content['en']['services'][ $i ];
			$ids[ $i ][ $lang ]    = va_seed_service( $lang, $i, $service, $content[ $lang ]['labels'], $media[ $images['header'] ] ?? 0, $media[ $images['body'] ] ?? 0, $contact_url, $force );
		}
	}
	foreach ( $ids as $translations ) {
		pll_save_post_translations( $translations );
	}
	$log[] = 'services: ' . wp_json_encode( $ids );

	$log[] = va_seed_services_menus( $content, $ids );
	$log[] = va_seed_services_strings( $content );

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	PLL()->model->clean_languages_cache();
	wp_cache_flush();
	$log[] = 'Then run flush_rewrite_rules() in a new request so /services/ URLs resolve.';

	return $log;
}

function va_seed_contact_url( $lang ) {
	$ids = get_posts(
		array(
			'post_type'   => 'page',
			'meta_key'    => '_va_seed',
			'meta_value'  => 'page-contact-' . $lang,
			'fields'      => 'ids',
			'numberposts' => 1,
			'lang'        => '',
		)
	);
	return $ids ? get_permalink( $ids[0] ) : home_url( '/' );
}

function va_seed_service( $lang, $index, $s, $labels, $header_id, $body_id, $contact_url, $force ) {
	$key      = 'service-' . ( $index + 1 ) . '-' . $lang;
	$existing = get_posts(
		array(
			'post_type'   => 'va_service',
			'post_status' => 'any',
			'meta_key'    => '_va_seed',
			'meta_value'  => $key,
			'fields'      => 'ids',
			'numberposts' => 1,
			'lang'        => '',
		)
	);
	if ( $existing && ! $force ) {
		return (int) $existing[0];
	}

	$postarr = array(
		'post_type'    => 'va_service',
		'post_status'  => 'publish',
		'post_title'   => $s['title'],
		'post_name'    => $s['slug'],
		'post_excerpt' => $s['tagline'],
		'post_content' => '',
		'menu_order'   => $index + 1,
	);
	if ( $existing ) {
		$postarr['ID'] = (int) $existing[0];
	}
	$id = wp_insert_post( $postarr, true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	pll_set_post_language( $id, $lang );
	if ( $header_id ) {
		set_post_thumbnail( $id, $header_id );
	}

	update_post_meta( $id, '_va_seed', $key );
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-post' );
	update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '' );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( va_seed_service_data( $s, $labels, $body_id, $contact_url ) ) ) );
	delete_post_meta( $id, '_elementor_css' );

	va_seed_seo( $id, $s['seo_title'], $s['seo_description'] );

	return (int) $id;
}

/** Elementor body: overview, core services grid, image + "why us" with a contact button. */
function va_seed_service_data( $s, $labels, $body_id, $contact_url ) {
	$overview = va_section(
		'',
		array(
			va_row(
				array(
					array( va_label( $labels['overview'] ) ),
					array( va_text( '<p>' . esc_html( $s['intro'] ) . '</p>', 'va-lead--xl' ) ),
				),
				array( 30, 70 )
			),
		)
	);

	$items = array();
	foreach ( $s['items'] as $item ) {
		$items[] = va_con(
			array(
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'padding'        => va_el_pad( 64, 0, 0, 0 ),
				'flex_gap'       => array( 'unit' => 'px', 'size' => 12, 'column' => '12', 'row' => '12', 'isLinked' => true ),
				'css_classes'    => 'va-reveal',
			),
			array(
				va_w( 'heading', array( 'title' => $item[0], 'header_size' => 'h3' ) ),
				va_w( 'text-editor', array( 'editor' => '<p>' . esc_html( $item[1] ) . '</p>' ) ),
			),
			true
		);
	}
	$core = va_section(
		'va-section--surface',
		array(
			va_row(
				array(
					array( va_label( $labels['core'] ) ),
					array(
						va_con(
							array(
								'container_type'           => 'grid',
								'grid_columns_grid'        => array( 'unit' => 'fr', 'size' => 2 ),
								'grid_columns_grid_mobile' => array( 'unit' => 'fr', 'size' => 1 ),
								'grid_rows_grid'           => array( 'unit' => 'fr', 'size' => (int) ceil( count( $items ) / 2 ) ),
								'grid_gaps'                => array( 'unit' => 'px', 'column' => '50', 'row' => '50', 'isLinked' => false ),
								'content_width'            => 'full',
								'padding'                  => va_el_pad( 0 ),
								'css_classes'              => 'va-features',
							),
							$items,
							true
						),
					),
				),
				array( 30, 70 )
			),
		)
	);

	$why = va_section(
		'',
		array(
			va_row(
				array(
					array(
						va_w(
							'image',
							array(
								'image'        => array( 'id' => $body_id, 'url' => $body_id ? wp_get_attachment_image_url( $body_id, 'full' ) : '' ),
								'image_size'   => 'large',
								'_css_classes' => 'va-image-tall va-reveal',
							)
						),
					),
					array(
						va_label( $labels['why'] ),
						va_text( '<p>' . esc_html( $s['why'] ) . '</p>', 'va-lead--xl' ),
						va_button( $labels['cta'], $contact_url ),
					),
				),
				array( 45, 55 ),
				100,
				array( 'align_items' => 'center' )
			),
		)
	);

	return array( $overview, $core, $why );
}

/** SEO title + description through the Hostinger AI plugin (it prints them in <head>). */
function va_seed_seo( $post_id, $title, $description ) {
	if ( function_exists( 'wp_get_ability' ) && ( $ability = wp_get_ability( 'hostinger-ai/seo-update' ) ) ) {
		$result = $ability->execute(
			array(
				'id'               => $post_id,
				'seo_title'        => $title,
				'meta_description' => $description,
			)
		);
		if ( ! is_wp_error( $result ) ) {
			return;
		}
	}
	update_post_meta( $post_id, 'hostinger_ai_assistant_seo_title', $title );
	update_post_meta( $post_id, 'hostinger_ai_assistant_seo_description', $description );
}

/** Adds "Services" (archive link) with the 4 services as sub-items after "Home" in each language menu. */
function va_seed_services_menus( $content, $ids ) {
	$options = get_option( 'polylang' );
	$theme   = get_stylesheet();
	$done    = array();

	foreach ( VA_SEED_LANGS as $lang ) {
		$menu_id = $options['nav_menus'][ $theme ]['va-primary'][ $lang ] ?? 0;
		if ( ! $menu_id ) {
			continue;
		}
		$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
		foreach ( (array) $items as $item ) {
			if ( 'post_type_archive' === $item->type && 'va_service' === $item->object ) {
				continue 2; // Already added.
			}
		}

		$parent = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $content[ $lang ]['menu'],
				'menu-item-type'   => 'post_type_archive',
				'menu-item-object' => 'va_service',
				'menu-item-status' => 'publish',
			)
		);
		$children = array();
		foreach ( $ids as $i => $translations ) {
			$children[] = wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $content[ $lang ]['services'][ $i ]['title'],
					'menu-item-object'    => 'va_service',
					'menu-item-object-id' => $translations[ $lang ],
					'menu-item-type'      => 'post_type',
					'menu-item-parent-id' => $parent,
					'menu-item-status'    => 'publish',
				)
			);
		}

		// Order: Home, Services (+ children), everything else (Contact, language switcher).
		$order = array();
		foreach ( (array) $items as $item ) {
			$order[] = $item->ID;
			if ( 'post_type' === $item->type && (int) get_option( 'page_on_front' ) === (int) pll_get_post( $item->object_id, 'en' ) ) {
				$order[] = $parent;
				$order   = array_merge( $order, $children );
			}
		}
		if ( ! in_array( $parent, $order, true ) ) {
			$order = array_merge( $order, array( $parent ), $children );
		}
		foreach ( $order as $position => $item_id ) {
			wp_update_post( array( 'ID' => $item_id, 'menu_order' => $position + 1 ) );
		}
		$done[] = $lang . ':' . $parent;
	}
	return 'menus: ' . implode( ', ', $done );
}

function va_seed_services_strings( $content ) {
	if ( ! class_exists( 'PLL_MO' ) ) {
		return 'strings: PLL_MO missing';
	}
	$done = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		if ( empty( $content[ $lang ]['strings'] ) ) {
			continue;
		}
		$language = PLL()->model->get_language( $lang );
		$mo       = new PLL_MO();
		$mo->import_from_db( $language );
		foreach ( $content[ $lang ]['strings'] as $source => $translated ) {
			$mo->add_entry( $mo->make_entry( $source, $translated ) );
		}
		$mo->export_to_db( $language );
		$done[] = $lang . ':' . count( $content[ $lang ]['strings'] );
	}
	return 'strings: ' . implode( ', ', $done );
}
