<?php
/**
 * Plugin Name: Via Adlucem Projects
 * Description: "Projects" custom post type with Project Categories and Project Tags. Translatable with Polylang; written in the block editor.
 * Version:     1.0.0
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'va_project',
			array(
				'labels'        => array(
					'name'               => 'Projects',
					'singular_name'      => 'Project',
					'menu_name'          => 'Projects',
					'add_new'            => 'Add project',
					'add_new_item'       => 'Add new project',
					'edit_item'          => 'Edit project',
					'new_item'           => 'New project',
					'view_item'          => 'View project',
					'view_items'         => 'View projects',
					'search_items'       => 'Search projects',
					'not_found'          => 'No projects found',
					'all_items'          => 'All projects',
					'archives'           => 'Projects',
					'featured_image'     => 'Cover image',
					'set_featured_image' => 'Set cover image',
				),
				'public'        => true,
				'show_in_rest'  => true, // Block editor.
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 22,
				'has_archive'   => 'projects',
				'rewrite'       => array( 'slug' => 'projects', 'with_front' => false ),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
				// Starting layout for a new project: text, photo, heading, text, gallery. Blocks can be added, removed or reordered.
				'template'      => array(
					array( 'core/paragraph', array( 'placeholder' => 'Introduce the project: what it is, where, and what we did.' ) ),
					array( 'core/image', array( 'sizeSlug' => 'large' ) ),
					array( 'core/heading', array( 'placeholder' => 'Section heading' ) ),
					array( 'core/paragraph', array( 'placeholder' => 'Describe the design or engineering approach.' ) ),
					array( 'core/gallery', array( 'linkTo' => 'none' ) ),
				),
			)
		);

		register_taxonomy(
			'va_project_category',
			'va_project',
			array(
				'labels'            => array(
					'name'          => 'Project categories',
					'singular_name' => 'Project category',
					'menu_name'     => 'Categories',
					'all_items'     => 'All categories',
					'edit_item'     => 'Edit category',
					'add_new_item'  => 'Add new category',
				),
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'project-category', 'with_front' => false ),
			)
		);

		register_taxonomy(
			'va_project_tag',
			'va_project',
			array(
				'labels'            => array(
					'name'          => 'Project tags',
					'singular_name' => 'Project tag',
					'menu_name'     => 'Tags',
					'all_items'     => 'All tags',
					'edit_item'     => 'Edit tag',
					'add_new_item'  => 'Add new tag',
				),
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'project-tag', 'with_front' => false ),
			)
		);
	}
);

// 9 projects per page (3 rows) on the projects page and its category/tag pages.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( $query->is_post_type_archive( 'va_project' ) || $query->is_tax( array( 'va_project_category', 'va_project_tag' ) ) ) {
			$query->set( 'posts_per_page', 9 );
		}
	}
);

// Translatable in Polylang.
add_filter(
	'pll_get_post_types',
	function ( $types, $is_settings ) {
		if ( ! $is_settings ) {
			$types['va_project'] = 'va_project';
		}
		return $types;
	},
	10,
	2
);
add_filter(
	'pll_get_taxonomies',
	function ( $taxonomies, $is_settings ) {
		if ( ! $is_settings ) {
			$taxonomies['va_project_category'] = 'va_project_category';
			$taxonomies['va_project_tag']      = 'va_project_tag';
		}
		return $taxonomies;
	},
	10,
	2
);

// Readable addresses: WordPress transliterates most accented letters but not Azerbaijani ə or the Uzbek ʻ / ʼ.
add_filter(
	'sanitize_title',
	function ( $title, $raw_title = '', $context = 'display' ) {
		if ( 'save' !== $context ) {
			return $title;
		}
		return strtr( $title, array( 'ə' => 'e', 'Ə' => 'e', 'ʻ' => '', 'ʼ' => '', '‘' => '', '’' => '' ) );
	},
	9,
	3
);
