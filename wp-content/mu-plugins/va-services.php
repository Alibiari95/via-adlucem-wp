<?php
/**
 * Plugin Name: Via Adlucem Services
 * Description: "Services" custom post type (translatable with Polylang, editable with Elementor).
 * Version:     1.0.0
 *
 * Kept as a must-use plugin so the content type survives a theme change.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'va_service',
			array(
				'labels'        => array(
					'name'               => 'Services',
					'singular_name'      => 'Service',
					'menu_name'          => 'Services',
					'add_new'            => 'Add service',
					'add_new_item'       => 'Add new service',
					'edit_item'          => 'Edit service',
					'new_item'           => 'New service',
					'view_item'          => 'View service',
					'view_items'         => 'View services',
					'search_items'       => 'Search services',
					'not_found'          => 'No services found',
					'all_items'          => 'All services',
					'archives'           => 'Services',
					'featured_image'     => 'Header image',
					'set_featured_image' => 'Set header image',
				),
				'description'   => 'Engineering and design services.',
				'public'        => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-building',
				'menu_position' => 21,
				'has_archive'   => 'services',
				'rewrite'       => array( 'slug' => 'services', 'with_front' => false ),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
				'hierarchical'  => false,
			)
		);
	}
);

// Show services in their menu order (Page Attributes → Order) everywhere.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'va_service' ) ) {
			$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
			$query->set( 'posts_per_page', -1 );
		}
	}
);

// Make the post type translatable in Polylang.
add_filter(
	'pll_get_post_types',
	function ( $post_types, $is_settings ) {
		if ( ! $is_settings ) {
			$post_types['va_service'] = 'va_service';
		}
		return $post_types;
	},
	10,
	2
);
