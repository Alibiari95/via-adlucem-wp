<?php
/**
 * Seeds 3 sample projects × EN/AZ/UZ (block-editor content, categories, tags, cover
 * images), adds "Projects" to each language menu, translates the project strings and
 * points the home slider's "View Portfolio" button at the projects page.
 *
 *     require $root . '/tools/seed-projects.php';
 *     return va_seed_projects( $root );
 *
 * Idempotent: existing items (tracked by `_va_seed` meta) are kept.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

if ( ! defined( 'VA_SEEDING' ) ) {
	define( 'VA_SEEDING', true ); // The sample translations are written here; skip auto-translation.
}

function va_seed_projects( $root ) {
	if ( ! post_type_exists( 'va_project' ) ) {
		return new WP_Error( 'va_seed', 'Deploy mu-plugins/va-projects.php first.' );
	}
	$log     = array();
	$content = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$content[ $lang ] = require $root . '/content/projects/' . $lang . '.php';
	}
	$en = $content['en'];

	// Terms in all languages, linked as translations.
	$terms = array(); // [ taxonomy ][ key ][ lang ] => term_id
	foreach ( array( 'va_project_category' => 'categories', 'va_project_tag' => 'tags' ) as $taxonomy => $group ) {
		foreach ( array_keys( $en[ $group ] ) as $key ) {
			foreach ( VA_SEED_LANGS as $lang ) {
				$terms[ $taxonomy ][ $key ][ $lang ] = va_seed_project_term( $taxonomy, $key, $lang, $content[ $lang ][ $group ][ $key ] );
			}
			pll_save_term_translations( $terms[ $taxonomy ][ $key ] );
		}
	}
	$log[] = 'terms: ' . wp_json_encode( $terms );

	// Placeholder images already in the Media Library.
	$media = array();
	foreach ( glob( $root . '/content/placeholders/*.jpg' ) as $file ) {
		$key           = basename( $file, '.jpg' );
		$ids           = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_va_seed', 'meta_value' => 'media-' . $key, 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
		$media[ $key ] = $ids ? (int) $ids[0] : 0;
	}

	$ids = array();
	foreach ( $en['projects'] as $i => $layout ) {
		foreach ( VA_SEED_LANGS as $lang ) {
			$ids[ $i ][ $lang ] = va_seed_project( $i, $lang, $layout, $content[ $lang ]['projects'][ $i ], $media, $terms );
		}
		pll_save_post_translations( $ids[ $i ] );
	}
	$log[] = 'projects: ' . wp_json_encode( $ids );

	$log[] = va_seed_projects_menus( $content );

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
	}
	$log[] = 'strings translated';

	// Home slider: "View Portfolio" → projects page in each language.
	foreach ( VA_SEED_LANGS as $lang ) {
		$page_id = va_update_page_id( 'page-home-' . $lang );
		$data    = $page_id ? json_decode( get_post_meta( $page_id, '_elementor_data', true ), true ) : null;
		if ( isset( $data[0]['elements'][0]['settings']['slides'][1] ) ) {
			$data[0]['elements'][0]['settings']['slides'][1]['button_link']['url'] = home_url( 'en' === $lang ? '/projects/' : '/' . $lang . '/projects/' );
			update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
			delete_post_meta( $page_id, '_elementor_css' );
		}
	}
	$log[] = 'home: View Portfolio → projects page';

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	wp_cache_flush();
	$log[] = 'Then run flush_rewrite_rules() in a new request.';
	return $log;
}

function va_seed_project_term( $taxonomy, $key, $lang, $name ) {
	$seed     = $taxonomy . '-' . $key . '-' . $lang;
	$existing = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'meta_key' => '_va_seed', 'meta_value' => $seed, 'fields' => 'ids', 'lang' => '' ) );
	if ( $existing && ! is_wp_error( $existing ) ) {
		return (int) $existing[0];
	}
	$slug = sanitize_title( $name );
	if ( 'en' !== $lang || term_exists( $slug, $taxonomy ) ) {
		$slug .= '-' . $lang;
	}
	$insert = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
	if ( is_wp_error( $insert ) ) {
		return 0;
	}
	pll_set_term_language( $insert['term_id'], $lang );
	update_term_meta( $insert['term_id'], '_va_seed', $seed );
	return (int) $insert['term_id'];
}

function va_seed_project( $index, $lang, $layout, $text, $media, $terms ) {
	$key      = 'project-' . ( $index + 1 ) . '-' . $lang;
	$existing = get_posts( array( 'post_type' => 'va_project', 'post_status' => 'any', 'meta_key' => '_va_seed', 'meta_value' => $key, 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
	if ( $existing ) {
		return (int) $existing[0];
	}

	$id = wp_insert_post(
		array(
			'post_type'    => 'va_project',
			'post_status'  => 'publish',
			'post_title'   => $text['title'],
			'post_name'    => $text['slug'],
			'post_excerpt' => $text['excerpt'],
			'post_content' => va_seed_project_blocks( $layout['layout'], $text['texts'], $media ),
			'post_date'    => $layout['date'],
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	pll_set_post_language( $id, $lang );
	update_post_meta( $id, '_va_seed', $key );
	if ( ! empty( $media[ $layout['cover'] ] ) ) {
		set_post_thumbnail( $id, $media[ $layout['cover'] ] );
	}
	wp_set_object_terms( $id, array( $terms['va_project_category'][ $layout['category'] ][ $lang ] ), 'va_project_category' );
	wp_set_object_terms( $id, array_map( fn( $t ) => $terms['va_project_tag'][ $t ][ $lang ], $layout['tags'] ), 'va_project_tag' );
	return (int) $id;
}

/** Block-editor markup from a layout list and the texts (consumed in order). */
function va_seed_project_blocks( $layout, $texts, $media ) {
	$image = function ( $key ) use ( $media ) {
		$id = (int) ( $media[ $key ] ?? 0 );
		return sprintf(
			"<!-- wp:image {\"id\":%1\$d,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"%2\$s\" alt=\"\" class=\"wp-image-%1\$d\"/></figure>\n<!-- /wp:image -->",
			$id,
			esc_url( (string) wp_get_attachment_image_url( $id, 'large' ) )
		);
	};
	$blocks = array();
	foreach ( $layout as $item ) {
		$type = is_array( $item ) ? $item[0] : $item;
		switch ( $type ) {
			case 'p':
				$blocks[] = "<!-- wp:paragraph -->\n<p>" . esc_html( array_shift( $texts ) ) . "</p>\n<!-- /wp:paragraph -->";
				break;
			case 'h2':
				$blocks[] = "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( array_shift( $texts ) ) . "</h2>\n<!-- /wp:heading -->";
				break;
			case 'quote':
				$blocks[] = "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( array_shift( $texts ) ) . "</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->";
				break;
			case 'img':
				$blocks[] = $image( $item[1] );
				break;
			case 'gallery':
				$blocks[] = "<!-- wp:gallery {\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">" . implode( "\n\n", array_map( $image, $item[1] ) ) . "</figure>\n<!-- /wp:gallery -->";
				break;
		}
	}
	return implode( "\n\n", $blocks );
}

/** Adds "Projects" (link to the projects page) before "Contact" in each language menu. */
function va_seed_projects_menus( $content ) {
	$options = get_option( 'polylang' );
	$theme   = get_stylesheet();
	$done    = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$menu_id = $options['nav_menus'][ $theme ]['va-primary'][ $lang ] ?? 0;
		$items   = $menu_id ? wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) ) : array();
		foreach ( (array) $items as $item ) {
			if ( 'post_type_archive' === $item->type && 'va_project' === $item->object ) {
				continue 2;
			}
		}
		$new   = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $content[ $lang ]['menu'],
				'menu-item-type'   => 'post_type_archive',
				'menu-item-object' => 'va_project',
				'menu-item-status' => 'publish',
			)
		);
		$order = array();
		foreach ( (array) $items as $item ) {
			if ( 'post_type' === $item->type && 'contact' === get_post_meta( $item->object_id, '_va_page_role', true ) ) {
				$order[] = $new;
			}
			$order[] = $item->ID;
		}
		if ( ! in_array( $new, $order, true ) ) {
			$order[] = $new;
		}
		foreach ( $order as $position => $item_id ) {
			wp_update_post( array( 'ID' => $item_id, 'menu_order' => $position + 1 ) );
		}
		$done[] = $lang . ':' . $new;
	}
	return 'menus: ' . implode( ', ', $done );
}
