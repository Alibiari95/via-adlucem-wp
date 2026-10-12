<?php
/**
 * Via Adlucem child theme.
 */

defined( 'ABSPATH' ) || exit;

define( 'VA_THEME_VERSION', '1.0.0' );
define( 'VA_THEME_DIR', get_stylesheet_directory() );
define( 'VA_THEME_URI', get_stylesheet_directory_uri() );

require VA_THEME_DIR . '/inc/setup.php';
require VA_THEME_DIR . '/inc/template-tags.php';
require VA_THEME_DIR . '/inc/polylang.php';
require VA_THEME_DIR . '/inc/elementor.php';
