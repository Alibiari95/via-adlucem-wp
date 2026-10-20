<?php
/**
 * Creates or updates the projects from content/projects/{az,en,uz,ru}.php (az is the source),
 * with their categories, tags, cover photos and block-editor content, linked as Polylang
 * translations. Also makes sure each language menu has a "Projects" item.
 *
 *     require $root . '/tools/seed-projects.php';
 *     return va_seed_projects( $root );
 *
 * Existing projects are found by their `_va_seed` meta (project-1-en …) and overwritten, so
 * re-running brings the site back to the content files. Languages missing from Polylang are skipped.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

if ( ! defined( 'VA_SEEDING' ) ) {
	define( 'VA_SEEDING', true ); // All languages are written here; skip auto-translation.
}

/**
 * Shared layout of each project (same index as the `projects` lists in the content files).
 * `seed` keeps the post IDs of the first sample projects: project-3 (newest) → Göytəpə, etc.
 */
function va_projects_layout() {
	return array(
		array( 'seed' => 3, 'photo' => 'project-goytepe', 'date' => '2026-10-06 10:00:00', 'period' => '2024–2026', 'category' => 'infrastructure', 'tags' => array( 'master-plan', 'architecture', 'structural', 'mep', 'agdam' ) ),
		array( 'seed' => 2, 'photo' => 'project-gulluce', 'date' => '2026-09-22 10:00:00', 'period' => '2024–2026', 'category' => 'infrastructure', 'tags' => array( 'master-plan', 'architecture', 'structural', 'mep', 'agdam' ) ),
		array( 'seed' => 1, 'photo' => 'project-salahli', 'date' => '2026-09-08 10:00:00', 'period' => '2024–2026', 'category' => 'infrastructure', 'tags' => array( 'master-plan', 'architecture', 'structural', 'mep', 'agdam' ) ),
	);
}

function va_projects_langs() {
	return array_values( array_intersect( array( 'az', 'en', 'uz', 'ru' ), pll_languages_list() ) );
}

function va_seed_projects( $root ) {
	if ( ! post_type_exists( 'va_project' ) ) {
		return new WP_Error( 'va_seed', 'Deploy mu-plugins/va-projects.php first.' );
	}
	$log     = array();
	$langs   = va_projects_langs();
	$content = array();
	foreach ( $langs as $lang ) {
		$content[ $lang ] = require $root . '/content/projects/' . $lang . '.php';
	}

	// Terms in all languages, linked as translations.
	$terms = array();
	foreach ( array( 'va_project_category' => 'categories', 'va_project_tag' => 'tags' ) as $taxonomy => $group ) {
		foreach ( array_keys( $content['az'][ $group ] ) as $key ) {
			foreach ( $langs as $lang ) {
				$terms[ $taxonomy ][ $key ][ $lang ] = va_seed_project_term( $taxonomy, $key, $lang, $content[ $lang ][ $group ][ $key ] );
			}
			pll_save_term_translations( $terms[ $taxonomy ][ $key ] );
		}
	}
	$log[] = 'terms ok';

	$photos = va_seed_media(
		$root . '/content/media',
		'webp',
		array(
			'project-goytepe'      => 'Architectural floor plan with a house model, pencils and rulers',
			'project-gulluce'      => 'Engineer and client reviewing drawings on a construction site',
			'project-salahli'      => 'Architect drawing on floor plans with a pencil',
			'floor-plans'          => 'Architectural floor plans with a spirit level, keys and a safety helmet',
			'engineers-blueprints' => 'Two engineers in hard hats reviewing blueprints at a concrete structure',
			'engineers-shadows'    => 'Engineers in hard hats on site, casting shadows on a white wall',
			'pipeline-walkway'     => 'Large steel pipeline with valves and a service walkway',
			'pipes-tiles'          => 'Painted plumbing pipes and fittings against a tiled wall',
		)
	);

	$ids = array();
	foreach ( va_projects_layout() as $i => $layout ) {
		foreach ( $langs as $lang ) {
			$ids[ $i ][ $lang ] = va_seed_project( $layout, $content[ $lang ], $content[ $lang ]['projects'][ $i ], $lang, $photos, $terms );
		}
		pll_save_post_translations( array_filter( $ids[ $i ] ) );
	}
	$log[] = 'projects: ' . wp_json_encode( $ids );

	$log[] = va_seed_projects_menus( $content );

	foreach ( $langs as $lang ) {
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

	wp_cache_flush();
	return $log;
}

function va_seed_project_term( $taxonomy, $key, $lang, $name ) {
	$seed     = $taxonomy . '-' . $key . '-' . $lang;
	$existing = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'meta_key' => '_va_seed', 'meta_value' => $seed, 'fields' => 'ids', 'lang' => '' ) );
	if ( $existing && ! is_wp_error( $existing ) ) {
		wp_update_term( (int) $existing[0], $taxonomy, array( 'name' => $name ) );
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

function va_seed_project( $layout, $lang_content, $text, $lang, $photos, $terms ) {
	$key      = 'project-' . $layout['seed'] . '-' . $lang;
	$existing = get_posts( array( 'post_type' => 'va_project', 'post_status' => 'any', 'meta_key' => '_va_seed', 'meta_value' => $key, 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );

	$postarr = array(
		'post_type'    => 'va_project',
		'post_status'  => 'publish',
		'post_title'   => $text['title'],
		'post_name'    => $text['slug'],
		'post_excerpt' => $text['excerpt'],
		'post_content' => va_seed_project_blocks( $layout, $lang_content, $text ),
		'post_date'    => $layout['date'],
	);
	if ( $existing ) {
		$postarr['ID'] = (int) $existing[0];
	}
	$id = $existing ? wp_update_post( wp_slash( $postarr ), true ) : wp_insert_post( wp_slash( $postarr ), true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	pll_set_post_language( $id, $lang );
	update_post_meta( $id, '_va_seed', $key );
	if ( ! empty( $photos[ $layout['photo'] ] ) ) {
		set_post_thumbnail( $id, $photos[ $layout['photo'] ] );
	}
	wp_set_object_terms( $id, array( $terms['va_project_category'][ $layout['category'] ][ $lang ] ), 'va_project_category' );
	wp_set_object_terms( $id, array_map( fn( $t ) => $terms['va_project_tag'][ $t ][ $lang ], $layout['tags'] ), 'va_project_tag' );
	return (int) $id;
}

/** Block-editor markup: key facts, services, overview, site features, buildings table. */
function va_seed_project_blocks( $layout, $c, $text ) {
	$l      = $c['labels'];
	$p      = fn( $html ) => "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->";
	$h2     = fn( $title ) => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $title ) . "</h2>\n<!-- /wp:heading -->";
	$list   = function ( $items ) {
		$li = array_map( fn( $html ) => "<!-- wp:list-item -->\n<li>" . $html . "</li>\n<!-- /wp:list-item -->", $items );
		return "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . implode( "\n\n", $li ) . "</ul>\n<!-- /wp:list -->";
	};
	$blocks = array();

	$blocks[] = $p( '<strong>' . esc_html( $l['location'] ) . ':</strong> ' . esc_html( $text['location'] ) . '<br><strong>' . esc_html( $l['period'] ) . ':</strong> ' . esc_html( $layout['period'] ) );

	$blocks[] = $h2( $l['services'] );
	$blocks[] = $p( esc_html( $c['services_intro'] ) );
	$blocks[] = $list( array_map( 'esc_html', $c['services_list'] ) );

	$blocks[] = $h2( $l['overview'] );
	$blocks[] = $p( esc_html( $c['overview'] ) );

	$blocks[] = $h2( $l['features'] );
	$blocks[] = $list( array_map( fn( $f ) => '<strong>' . esc_html( $f[0] ) . '</strong> ' . esc_html( $f[1] ), $c['features'] ) );

	$blocks[] = $h2( $l['objects'] );
	$blocks[] = $p( esc_html( $text['objects_intro'] ) );
	$rows     = '';
	foreach ( $text['objects'] as $o ) {
		$rows .= '<tr><td>' . esc_html( $o[0] ) . '</td><td>' . esc_html( $o[1] ) . '</td><td>' . esc_html( $o[2] ) . '</td></tr>';
	}
	$blocks[] = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table class=\"has-fixed-layout\"><thead><tr><th>" . esc_html( $l['col_object'] ) . '</th><th>' . esc_html( $l['col_area'] ) . '</th><th>' . esc_html( $l['col_elevation'] ) . '</th></tr></thead><tbody>' . $rows . "</tbody></table></figure>\n<!-- /wp:table -->";

	return implode( "\n\n", $blocks );
}

/** Adds "Projects" (link to the projects page) before "Contact" in each language menu, once. */
function va_seed_projects_menus( $content ) {
	$options = get_option( 'polylang' );
	$theme   = get_stylesheet();
	$done    = array();
	foreach ( array_keys( $content ) as $lang ) {
		$menu_id = $options['nav_menus'][ $theme ]['va-primary'][ $lang ] ?? 0;
		if ( ! $menu_id ) {
			continue;
		}
		$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
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
