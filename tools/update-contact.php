<?php
/**
 * Contact pages: replace the hard-coded detail blocks with [va_contact_details],
 * which shows the same address, phone, email and social links as the footer.
 * Also adds the "Social media" string translations.
 *
 *     require $root . '/tools/update-contact.php';
 *     return va_update_contact();
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/update-home.php';

function va_update_contact() {
	$log = array();

	foreach ( VA_SEED_LANGS as $lang ) {
		$page_id = va_update_page_id( 'page-contact-' . $lang );
		$data    = $page_id ? json_decode( get_post_meta( $page_id, '_elementor_data', true ), true ) : null;
		// Section 2 → row → left column: [ label, detail, detail, … ].
		$column = &$data[1]['elements'][0]['elements'][0]['elements'];
		if ( ! is_array( $column ) || 'heading' !== ( $column[0]['widgetType'] ?? '' ) ) {
			$log[] = "contact $lang: layout not as expected, skipped";
			continue;
		}
		$column = array(
			$column[0],
			va_w( 'shortcode', array( 'shortcode' => '[va_contact_details]', '_css_classes' => 'va-reveal' ) ),
		);
		unset( $column );
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $page_id, '_elementor_css' );
		$log[] = "contact $lang: page $page_id uses [va_contact_details]";
	}

	$strings = array(
		'az' => array( 'Social media' => 'Sosial media' ),
		'uz' => array( 'Social media' => 'Ijtimoiy tarmoqlar' ),
	);
	foreach ( $strings as $lang => $pairs ) {
		$language = PLL()->model->get_language( $lang );
		$mo       = new PLL_MO();
		$mo->import_from_db( $language );
		foreach ( $pairs as $source => $translated ) {
			$mo->add_entry( $mo->make_entry( $source, $translated ) );
		}
		$mo->export_to_db( $language );
	}
	$log[] = 'strings: Social media (az, uz)';

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	wp_cache_flush();
	return $log;
}
