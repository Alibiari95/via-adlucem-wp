<?php
/**
 * Elementor: "Via Adlucem" widget category and custom widgets.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'elementor/elements/categories_registered',
	function ( $elements_manager ) {
		$elements_manager->add_category(
			'via-adlucem',
			array(
				'title' => 'Via Adlucem',
				'icon'  => 'eicon-site-logo',
			)
		);
	}
);

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		require_once VA_THEME_DIR . '/inc/widgets/class-va-hero-slider.php';
		require_once VA_THEME_DIR . '/inc/widgets/class-va-projects-slider.php';
		require_once VA_THEME_DIR . '/inc/widgets/class-va-accordion.php';

		$widgets_manager->register( new VA_Hero_Slider_Widget() );
		$widgets_manager->register( new VA_Projects_Slider_Widget() );
		$widgets_manager->register( new VA_Accordion_Widget() );
	}
);
