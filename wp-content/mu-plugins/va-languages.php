<?php
/**
 * Plugin Name: Via Adlucem Hidden Languages
 * Description: Hides languages listed in the `va_hidden_languages` option (e.g. array( 'az' )): removed from the language switcher and hreflang links, never chosen from the visitor's browser language, and new auto-translations into them are saved as drafts.
 * Version:     1.0.0
 *
 * Hide:  update_option( 'va_hidden_languages', array( 'az' ) );   (see tools/hide-language.php)
 * Show:  update_option( 'va_hidden_languages', array() );
 */

defined( 'ABSPATH' ) || exit;

/** @return string[] Language slugs that are hidden from visitors. */
function va_hidden_languages() {
	return array_values( array_filter( (array) get_option( 'va_hidden_languages', array() ) ) );
}

function va_is_hidden_language( $slug ) {
	return in_array( $slug, va_hidden_languages(), true );
}

// Language switcher (menu items added by Polylang carry the class "lang-item-<slug>").
add_filter(
	'wp_nav_menu_objects',
	function ( $items ) {
		$hidden = va_hidden_languages();
		if ( ! $hidden ) {
			return $items;
		}
		return array_filter(
			$items,
			function ( $item ) use ( $hidden ) {
				foreach ( $hidden as $slug ) {
					if ( in_array( 'lang-item-' . $slug, (array) $item->classes, true ) ) {
						return false;
					}
				}
				return true;
			}
		);
	},
	30
);

// Never send a visitor to a hidden language because of their browser settings.
add_filter(
	'pll_preferred_language',
	function ( $slug ) {
		return $slug && va_is_hidden_language( $slug ) ? false : $slug;
	}
);

// No hreflang alternates pointing at hidden languages.
add_filter(
	'pll_rel_hreflang_attributes',
	function ( $hreflangs ) {
		foreach ( va_hidden_languages() as $slug ) {
			foreach ( array_keys( (array) $hreflangs ) as $code ) {
				if ( 0 === strpos( strtolower( $code ), $slug ) ) {
					unset( $hreflangs[ $code ] );
				}
			}
		}
		return $hreflangs;
	}
);

// The saved status belongs to one language version only; keep it out of Polylang's custom-field sync.
add_filter(
	'pll_copy_post_metas',
	function ( $keys ) {
		return array_values( array_diff( (array) $keys, array( '_va_status_before_hide' ) ) );
	}
);
