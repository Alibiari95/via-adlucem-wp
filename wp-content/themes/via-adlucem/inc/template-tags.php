<?php
/**
 * Small helpers used by header.php / footer.php.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Home URL in the current language.
 */
function va_home_url() {
	return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
}

/**
 * The page with the given `_va_page_role` meta (e.g. "contact") in the current language.
 */
function va_page_url_by_role( $role ) {
	$args = array(
		'post_type'   => 'page',
		'numberposts' => 1,
		'meta_key'    => '_va_page_role',
		'meta_value'  => $role,
		'fields'      => 'ids',
	);
	if ( function_exists( 'pll_current_language' ) && pll_current_language() ) {
		$args['lang'] = pll_current_language();
	}
	$ids = get_posts( $args );
	return $ids ? get_permalink( $ids[0] ) : va_home_url();
}

/**
 * Logo: mark + wordmark. $variant is "dark" (for light backgrounds) or "light".
 */
function va_logo( $variant = 'dark' ) {
	$file = 'light' === $variant ? 'logo-light.png' : 'logo.png';
	printf(
		'<a class="va-logo va-logo--%1$s" href="%2$s" rel="home"><img class="va-logo__mark" src="%3$s" alt="" width="378" height="396"><span class="va-logo__text">%4$s</span></a>',
		esc_attr( $variant ),
		esc_url( va_home_url() ),
		esc_url( VA_THEME_URI . '/assets/img/' . $file ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Social links that have a URL set in the Customizer.
 */
function va_social_links() {
	$items = '';
	foreach ( va_social_networks() as $key => $label ) {
		$url = get_theme_mod( 'va_social_' . $key );
		if ( $url ) {
			$items .= sprintf( '<li><a href="%s" target="_blank" rel="noopener">%s</a></li>', esc_url( $url ), esc_html( $label ) );
		}
	}
	if ( $items ) {
		echo '<ul class="va-social">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
	}
}
