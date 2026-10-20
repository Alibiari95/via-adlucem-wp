<?php
/**
 * Adds Russian (ru, ru_RU) to the site and builds the Russian version from content/ru/site.php:
 * contact form, contact page, the 4 services and the home page (copies of the current English
 * Elementor pages with the texts translated), menu, Polylang strings and SEO meta.
 *
 *     require $root . '/tools/add-russian.php';
 *     va_ru_add_language();          // request 1
 *     return va_ru_build( $root );   // request 2 (after the language exists)
 *
 * Then run tools/seed-projects.php (Russian projects) and tools/home-banner.php.
 * Idempotent: existing Russian items (by `_va_seed` meta) are rebuilt in place.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

function va_ru_add_language() {
	if ( PLL()->model->get_language( 'ru' ) ) {
		return 'ru already exists';
	}
	$model = method_exists( PLL()->model, 'add_language' ) ? PLL()->model : new PLL_Admin_Model( PLL()->options );
	$added = $model->add_language(
		array(
			'name'       => 'Русский',
			'slug'       => 'ru',
			'locale'     => 'ru_RU',
			'rtl'        => false,
			'term_group' => 3,
			'flag'       => 'ru',
		)
	);
	if ( is_wp_error( $added ) ) {
		return $added;
	}
	require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	$pack = wp_download_language_pack( 'ru_RU' );
	PLL()->model->clean_languages_cache();
	return 'ru added; WordPress language pack: ' . ( $pack ? $pack : 'not downloaded' );
}

function va_ru_build( $root ) {
	if ( ! PLL()->model->get_language( 'ru' ) ) {
		return new WP_Error( 'va_ru', 'Run va_ru_add_language() first.' );
	}
	$c   = require $root . '/content/ru/site.php';
	$log = array();

	// URL pairs English => Russian, filled as the Russian items are created.
	$urls = array(
		home_url( '/services/' ) => home_url( '/ru/services/' ),
		home_url( '/projects/' ) => home_url( '/ru/projects/' ),
	);

	// 1. Contact form + contact page.
	$form_ru    = va_seed_form( 'ru', $c['form'] );
	$form_en    = (int) get_posts( array( 'post_type' => 'wpcf7_contact_form', 'meta_key' => '_va_seed', 'meta_value' => 'form-en', 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) )[0];
	$contact_en = va_update_page_id( 'page-contact-en' );
	$contact_ru = va_ru_clone_page( $contact_en, 'contact', $c['pages']['contact'], $c['map'], $urls, array( '[contact-form-7 id="' . $form_en . '"]' => '[contact-form-7 id="' . $form_ru . '"]' ) );
	$urls[ get_permalink( $contact_en ) ] = get_permalink( $contact_ru );
	$log[] = "contact: page $contact_ru, form $form_ru";

	// 2. Services (English ones in menu order).
	$services_en = get_posts( array( 'post_type' => 'va_service', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC' ), 'lang' => 'en' ) );
	$services_ru = array();
	foreach ( $services_en as $i => $en ) {
		$s  = $c['services'][ $i ];
		$id = va_ru_clone_post( $en, 'service-' . ( $i + 1 ) . '-ru', $s['title'], $s['slug'], $s['tagline'], $c['map'], $urls );
		va_seed_seo( $id, $s['seo_title'], $s['seo_description'] );
		$services_ru[] = $id;
		$urls[ get_permalink( $en ) ] = get_permalink( $id );
	}
	$log[] = 'services: ' . implode( ', ', $services_ru );

	// 3. Home page (after services and contact, so its links point to the Russian pages).
	$home_en = va_update_page_id( 'page-home-en' );
	$home_ru = va_ru_clone_page( $home_en, 'home', $c['pages']['home'], $c['map'], $urls );
	va_seed_seo( $home_ru, $c['seo']['title'], $c['seo']['description'] );
	$log[] = "home: page $home_ru";

	// 4. Menu.
	$log[] = va_ru_menu( $c['menu'], $home_ru, $contact_ru, $services_ru );

	// 5. Strings (header, footer, services pages) + site tagline.
	$mo = new PLL_MO();
	$mo->import_from_db( PLL()->model->get_language( 'ru' ) );
	foreach ( $c['strings'] + array( get_option( 'blogdescription' ) => $c['tagline'] ) as $source => $translated ) {
		$mo->add_entry( $mo->make_entry( $source, $translated ) );
	}
	$mo->export_to_db( PLL()->model->get_language( 'ru' ) );
	$log[] = 'strings: ' . ( count( $c['strings'] ) + 1 );

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	PLL()->model->clean_languages_cache();
	wp_cache_flush();
	$log[] = 'Then flush_rewrite_rules() in a new request.';
	return $log;
}

/** Replaces translated texts (exact matches) and English links in an Elementor tree. */
function va_ru_translate_tree( $node, $map, $urls, $extra = array() ) {
	if ( is_array( $node ) ) {
		foreach ( $node as $key => $value ) {
			$node[ $key ] = va_ru_translate_tree( $value, $map, $urls, $extra );
		}
		return $node;
	}
	if ( ! is_string( $node ) ) {
		return $node;
	}
	if ( isset( $map[ $node ] ) ) {
		return $map[ $node ];
	}
	if ( isset( $extra[ $node ] ) ) {
		return $extra[ $node ];
	}
	if ( isset( $urls[ $node ] ) ) {
		return $urls[ $node ];
	}
	return $node;
}

/** Russian copy of an Elementor page with the given role (home / contact). */
function va_ru_clone_page( $en_id, $role, $page, $map, $urls, $extra = array() ) {
	$data    = va_ru_translate_tree( json_decode( get_post_meta( $en_id, '_elementor_data', true ), true ), $map, $urls, $extra );
	$seed    = 'page-' . $role . '-ru';
	$postarr = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $page['title'],
		'post_name'    => $page['slug'],
		'post_content' => '',
	);
	$existing = va_update_page_id( $seed );
	if ( $existing ) {
		$postarr['ID'] = $existing;
	}
	$id = $existing ? wp_update_post( $postarr ) : wp_insert_post( $postarr );
	pll_set_post_language( $id, 'ru' );
	foreach ( array( '_va_page_role', '_va_transparent_header', '_wp_page_template', '_elementor_edit_mode', '_elementor_template_type', '_elementor_version', '_elementor_page_settings' ) as $meta ) {
		update_post_meta( $id, $meta, get_post_meta( $en_id, $meta, true ) );
	}
	update_post_meta( $id, '_va_seed', $seed );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $id, '_elementor_css' );

	$translations       = pll_get_post_translations( $en_id );
	$translations['ru'] = $id;
	pll_save_post_translations( $translations );
	return (int) $id;
}

/** Russian copy of an Elementor-built post (services). */
function va_ru_clone_post( WP_Post $en, $seed, $title, $slug, $excerpt, $map, $urls ) {
	$existing = get_posts( array( 'post_type' => $en->post_type, 'post_status' => 'any', 'meta_key' => '_va_seed', 'meta_value' => $seed, 'fields' => 'ids', 'numberposts' => 1, 'lang' => '' ) );
	$postarr  = array(
		'post_type'    => $en->post_type,
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_excerpt' => $excerpt,
		'post_content' => '',
		'menu_order'   => $en->menu_order,
	);
	if ( $existing ) {
		$postarr['ID'] = (int) $existing[0];
	}
	$id = $existing ? wp_update_post( $postarr ) : wp_insert_post( $postarr );
	pll_set_post_language( $id, 'ru' );
	foreach ( array( '_elementor_edit_mode', '_elementor_template_type', '_elementor_version', '_elementor_page_settings', '_thumbnail_id' ) as $meta ) {
		update_post_meta( $id, $meta, get_post_meta( $en->ID, $meta, true ) );
	}
	$data = va_ru_translate_tree( json_decode( get_post_meta( $en->ID, '_elementor_data', true ), true ), $map, $urls );
	update_post_meta( $id, '_va_seed', $seed );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $id, '_elementor_css' );

	$translations       = pll_get_post_translations( $en->ID );
	$translations['ru'] = $id;
	pll_save_post_translations( $translations );
	return (int) $id;
}

/** Russian main menu: Home, Services (+ the 4 services), Projects, Contact, language switcher. */
function va_ru_menu( $labels, $home, $contact, $services ) {
	$menu = wp_get_nav_menu_object( $labels['name'] );
	if ( $menu ) {
		return 'menu exists: ' . $menu->term_id;
	}
	$menu_id = wp_create_nav_menu( $labels['name'] );
	$add     = fn( $args ) => wp_update_nav_menu_item( $menu_id, 0, $args + array( 'menu-item-status' => 'publish' ) );

	$add( array( 'menu-item-title' => $labels['home'], 'menu-item-object' => 'page', 'menu-item-object-id' => $home, 'menu-item-type' => 'post_type' ) );
	$parent = $add( array( 'menu-item-title' => $labels['services'], 'menu-item-type' => 'post_type_archive', 'menu-item-object' => 'va_service' ) );
	foreach ( $services as $id ) {
		$add( array( 'menu-item-title' => get_the_title( $id ), 'menu-item-object' => 'va_service', 'menu-item-object-id' => $id, 'menu-item-type' => 'post_type', 'menu-item-parent-id' => $parent ) );
	}
	$add( array( 'menu-item-title' => $labels['projects'], 'menu-item-type' => 'post_type_archive', 'menu-item-object' => 'va_project' ) );
	$add( array( 'menu-item-title' => $labels['contact'], 'menu-item-object' => 'page', 'menu-item-object-id' => $contact, 'menu-item-type' => 'post_type' ) );
	$switcher = $add( array( 'menu-item-title' => 'Languages', 'menu-item-url' => '#pll_switcher', 'menu-item-type' => 'custom' ) );
	update_post_meta( $switcher, '_pll_menu_item', array( 'hide_if_no_translation' => 0, 'hide_current' => 0, 'force_home' => 0, 'show_flags' => 0, 'show_names' => 1, 'dropdown' => 0 ) );

	$options = get_option( 'polylang' );
	$theme   = get_stylesheet();
	$options['nav_menus'][ $theme ]['va-primary']['ru'] = $menu_id;
	$options['nav_menus'][ $theme ]['va-footer']['ru']  = $menu_id;
	update_option( 'polylang', $options );
	return 'menu: ' . $menu_id;
}
