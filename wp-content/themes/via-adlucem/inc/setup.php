<?php
/**
 * Theme setup: supports, menus, assets, body classes, customizer.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

		register_nav_menus(
			array(
				'va-primary' => __( 'Primary menu (header)', 'via-adlucem' ),
				'va-footer'  => __( 'Footer menu', 'via-adlucem' ),
			)
		);
	},
	20
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'va-fonts', 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap', array(), null );
		wp_enqueue_style( 'va-main', VA_THEME_URI . '/assets/css/main.css', array( 'va-fonts' ), va_asset_version( 'assets/css/main.css' ) );
		wp_enqueue_script( 'va-main', VA_THEME_URI . '/assets/js/main.js', array(), va_asset_version( 'assets/js/main.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );

		// Our header.php / footer.php replace Hello's, so its header/footer stylesheet is not needed.
		wp_dequeue_style( 'hello-elementor-header-footer' );
	},
	20
);

/**
 * Cache-busting version from the file's modification time.
 */
function va_asset_version( $relative ) {
	$path = VA_THEME_DIR . '/' . $relative;
	return file_exists( $path ) ? (string) filemtime( $path ) : VA_THEME_VERSION;
}

/**
 * Pages that start with a dark hero/banner get a transparent header over it.
 * Set per page with the `_va_transparent_header` meta (the seed script does this).
 */
function va_has_transparent_header() {
	if ( is_singular( 'va_service' ) || is_post_type_archive( 'va_service' ) ) {
		return true; // These templates always start with a dark banner.
	}
	return is_singular() && (bool) get_post_meta( get_queried_object_id(), '_va_transparent_header', true );
}

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = va_has_transparent_header() ? 'va-header-transparent' : 'va-header-solid';
		return $classes;
	}
);

// Favicon from the logo until a Site Icon is set in Settings → General.
add_action(
	'wp_head',
	function () {
		if ( ! has_site_icon() ) {
			printf( '<link rel="icon" type="image/png" href="%s">' . "\n", esc_url( VA_THEME_URI . '/assets/img/icon-512.png' ) );
		}
	}
);

/**
 * Social links: Appearance → Customize → Via Adlucem.
 */
function va_social_networks() {
	return array(
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'facebook'  => 'Facebook',
		'behance'   => 'Behance',
	);
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section(
			'va_social',
			array(
				'title'    => __( 'Via Adlucem — social links', 'via-adlucem' ),
				'priority' => 30,
			)
		);
		foreach ( va_social_networks() as $key => $label ) {
			$wp_customize->add_setting( 'va_social_' . $key, array( 'sanitize_callback' => 'esc_url_raw' ) );
			$wp_customize->add_control(
				'va_social_' . $key,
				array(
					'label'   => $label,
					'section' => 'va_social',
					'type'    => 'url',
				)
			);
		}
	}
);
