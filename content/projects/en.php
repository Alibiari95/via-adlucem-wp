<?php
/**
 * Sample projects — English. Defines the layout (blocks, images, categories, tags) used by
 * tools/seed-projects.php; az.php / uz.php hold only the translated texts in the same order.
 *
 * Block layout keys: p (paragraph), h2 (heading), quote, img (one image), gallery (several images).
 * Images refer to placeholder files already in the Media Library (content/placeholders/).
 */

return array(
	'menu'       => 'Projects',
	'categories' => array(
		'residential'    => 'Residential',
		'commercial'     => 'Commercial',
		'infrastructure' => 'Public & Infrastructure',
	),
	'tags'       => array(
		'bim'          => 'BIM',
		'architecture' => 'Architecture',
		'structural'   => 'Structural engineering',
		'mep'          => 'MEP',
		'civil'        => 'Civil infrastructure',
	),
	'projects'   => array(
		array(
			'date'     => '2026-09-08 10:00:00',
			'cover'    => 'hero-1',
			'category' => 'residential',
			'tags'     => array( 'bim', 'architecture', 'mep' ),
			'layout'   => array( 'p', array( 'img', 'project-1' ), 'h2', 'p', 'quote', array( 'gallery', array( 'project-2', 'project-3' ) ), 'p' ),
			'slug'     => 'caspian-residence',
			'title'    => 'Caspian Residence',
			'excerpt'  => 'A seven-storey residential building on the Caspian waterfront, delivered with a fully coordinated BIM model.',
			'texts'    => array(
				'Caspian Residence is a seven-storey residential building with 42 apartments, underground parking and a landscaped courtyard. Via Ad Lucem delivered the architectural design, structural engineering and MEP systems from concept to construction documentation.',
				'A coordinated model from day one',
				'Every discipline worked in a single BIM model, so architectural layouts, structural grids and building services were checked against each other continuously. More than 300 clashes were resolved before the drawings reached the site.',
				'The model let us make decisions early — while they were still easy to change.',
				'The result is a calm, light-filled building whose technical systems stay out of sight and out of the way.',
			),
		),
		array(
			'date'     => '2026-09-22 10:00:00',
			'cover'    => 'hero-2',
			'category' => 'commercial',
			'tags'     => array( 'bim', 'structural', 'mep' ),
			'layout'   => array( 'p', array( 'img', 'project-4' ), 'h2', 'p', 'quote', array( 'gallery', array( 'project-5', 'about' ) ), 'p' ),
			'slug'     => 'atrium-office',
			'title'    => 'Atrium Office',
			'excerpt'  => 'A twelve-storey office building organised around a daylit atrium, with integrated structural and MEP design.',
			'texts'    => array(
				'Atrium Office is a twelve-storey commercial building organised around a central atrium that brings daylight deep into the floor plates. Our team provided structural engineering, MEP design and technical documentation for the whole project.',
				'Engineering for flexible workspace',
				'Long-span floors give tenants open, column-free space, while a raised-floor system and a coordinated ceiling void keep power, data and air distribution easy to adapt.',
				'Flexible floors and coordinated services let the building change with its tenants.',
				'Energy modelling guided the façade and HVAC design, reducing the building’s expected cooling load and operating costs.',
			),
		),
		array(
			'date'     => '2026-10-06 10:00:00',
			'cover'    => 'hero-3',
			'category' => 'infrastructure',
			'tags'     => array( 'bim', 'civil' ),
			'layout'   => array( 'p', array( 'img', 'project-6' ), 'h2', 'p', 'quote', array( 'gallery', array( 'project-1', 'project-4' ) ), 'p' ),
			'slug'     => 'harbour-pavilion',
			'title'    => 'Harbour Pavilion',
			'excerpt'  => 'A public pavilion and promenade with new external utilities, stormwater management and access roads.',
			'texts'    => array(
				'Harbour Pavilion is a public pavilion on a new waterfront promenade. Alongside the building itself, Via Ad Lucem designed the site infrastructure: external water, gas and power connections, access roads and stormwater drainage.',
				'Infrastructure that stays out of sight',
				'All utility routes were modelled in 3D together with the landscape, so trenches, manholes and drainage lines fit around trees, paving and seating without conflicts.',
				'Good infrastructure is the part of a project nobody notices — because it simply works.',
				'Sustainable drainage swales collect surface water from the promenade and release it slowly, protecting the harbour from peak flows.',
			),
		),
	),
	'strings'    => array(),
);
