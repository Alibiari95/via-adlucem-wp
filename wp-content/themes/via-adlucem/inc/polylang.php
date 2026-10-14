<?php
/**
 * Polylang integration.
 *
 * Theme texts (header/footer) are registered as Polylang strings, so they are
 * translated in Languages → Translations (group "Via Adlucem").
 */

defined( 'ABSPATH' ) || exit;

/**
 * Translatable theme strings. The value is the English source text.
 */
function va_strings() {
	return array(
		'menu'          => 'Menu',
		'close'         => 'Close',
		'footer_cta'    => 'Let’s design your next space together.',
		'footer_button' => 'Get in touch',
		'footer_about'  => 'An architecture and interior design studio creating timeless, light-filled spaces.',
		'footer_visit'  => 'Visit us',
		'footer_talk'   => 'Talk to us',
		'footer_nav'    => 'Navigate',
		'address'       => '28 May Street 15, Baku, Azerbaijan',
		'phone'         => '+994 12 345 67 89',
		'email'         => 'info@via-adlucem.com',
		'copyright'     => '© %s Via Adlucem. All rights reserved.',
		'back_to_top'   => 'Back to top',
		// Services archive / single.
		'services'       => 'Services',
		'services_intro' => 'Design and engineering services for developers, SMEs and project owners across Azerbaijan and Central Asia.',
		'other_services' => 'Other services',
		'learn_more'     => 'Learn more',
	);
}

/*
 * SEO (Hostinger AI plugin stores the per-page SEO title and description).
 * - A custom SEO title already contains the brand, so don't append the site name again.
 * - The services overview is an archive: Hostinger would print the first service's
 *   description there, so it gets the translated overview intro instead.
 */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_singular() && get_post_meta( get_queried_object_id(), 'hostinger_ai_assistant_seo_title', true ) ) {
			unset( $parts['site'], $parts['tagline'] );
		}
		return $parts;
	},
	20
);

add_filter(
	'get_post_metadata',
	function ( $value, $object_id, $meta_key ) {
		if ( in_array( $meta_key, array( 'hostinger_ai_assistant_seo_title', 'hostinger_ai_assistant_seo_description' ), true ) && ! is_admin() && is_post_type_archive( 'va_service' ) ) {
			return '';
		}
		return $value;
	},
	10,
	3
);

add_action(
	'wp_head',
	function () {
		if ( is_post_type_archive( 'va_service' ) ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( va_t( 'services_intro' ) ) );
		}
	},
	1
);

// Archive heading and <title> for /services/ in the current language.
add_filter(
	'post_type_archive_title',
	function ( $title, $post_type ) {
		return 'va_service' === $post_type ? va_t( 'services' ) : $title;
	},
	10,
	2
);

add_action(
	'init',
	function () {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		foreach ( va_strings() as $key => $text ) {
			pll_register_string( 'va_' . $key, $text, 'Via Adlucem', strlen( $text ) > 60 );
		}
	}
);

/**
 * Translated theme string by key.
 */
function va_t( $key ) {
	$strings = va_strings();
	$text    = $strings[ $key ] ?? $key;
	return function_exists( 'pll__' ) ? pll__( $text ) : $text;
}

/**
 * Language switcher menu items: show the short code (EN / AZ / UZ) with the full name as tooltip.
 * Footer menu: drop the switcher items.
 */
add_filter(
	'wp_nav_menu_objects',
	function ( $items, $args ) {
		$location = $args->theme_location ?? '';
		foreach ( $items as $i => $item ) {
			$classes = (array) $item->classes;
			if ( ! in_array( 'lang-item', $classes, true ) ) {
				continue;
			}
			if ( 'va-footer' === $location ) {
				unset( $items[ $i ] );
				continue;
			}
			foreach ( $classes as $class ) {
				if ( preg_match( '/^lang-item-([a-z]{2,3}(?:-[a-z]+)?)$/', $class, $m ) ) {
					$item->attr_title = wp_strip_all_tags( $item->title );
					$item->title      = strtoupper( $m[1] );
				}
			}
		}
		return $items;
	},
	20,
	2
);
