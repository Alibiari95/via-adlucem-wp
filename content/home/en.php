<?php
/**
 * Home page copy — English (source: via-website-copy.pdf). Applied by tools/update-home.php.
 * Slide links: "services" = services overview, "contact" = contact page, anything else is used as-is.
 */

return array(
	'seo'      => array(
		'title'       => 'Top BIM & Engineering Services in Azerbaijan | Via Ad Lucem',
		'description' => 'Expert Architecture, Structural Engineering, MEP, and Civil Infrastructure services in Azerbaijan and Central Asia. Over 10 years of delivering advanced BIM solutions.',
	),
	'slides'   => array(
		array(
			'image'    => 'engineers-blueprints',
			'title'    => 'Comprehensive BIM & Engineering Solutions in Azerbaijan',
			'subtitle' => 'From master planning to advanced MEP and civil infrastructure, we engineer precision at every phase of your project.',
			'button'   => 'Explore Our Services',
			'link'     => 'services',
		),
		array(
			'image'    => 'engineers-shadows',
			'title'    => 'Delivering Excellence: Discover Our Latest Project',
			'subtitle' => 'See how our advanced modeling and integrated engineering translate into world-class architectural success.',
			'button'   => 'View Portfolio',
			'link'     => '#projects',
		),
		array(
			'image'    => 'pipeline-walkway',
			'title'    => 'Ready to Build the Future? Let’s Talk.',
			'subtitle' => 'Partner with Azerbaijan\'s premier structural, architectural, and MEP experts for your next development.',
			'button'   => 'Get in Touch',
			'link'     => 'contact',
		),
	),
	'about'    => array(
		'label'     => 'About us',
		'statement' => 'Building the Future with <em>Precision</em>',
		'text'      => 'We are a results-driven engineering and design firm dedicated to bringing complex visions to reality. With more than 10 years of experience in Azerbaijan in BIM (Building Information Modeling), we deliver integrated, clash-free solutions across architecture, structural engineering, and MEP systems.',
	),
	'services' => array(
		'label' => 'What we do',
		'title' => 'Our Services',
		// In the order of the service posts (menu order), so each box links to its page.
		'items' => array(
			array( 'icon' => 'fas fa-drafting-compass', 'title' => 'Architecture & Master Planning', 'text' => 'From initial master planning to highly detailed construction documents, we design visionary spaces tailored to your project goals. Our expert team ensures every schematic and working drawing is flawlessly executed for seamless development.' ),
			array( 'icon' => 'fas fa-building', 'title' => 'Structural Engineering, Compliance & Documentation', 'text' => 'We provide robust structural calculations, environmental impact assessments, and detailed project specifications. Our engineering guarantees that your building is safe, legally compliant, and perfectly planned for the construction phase.' ),
			array( 'icon' => 'fas fa-fan', 'title' => 'MEP Engineering & Technology', 'text' => 'Our advanced designs seamlessly integrate efficient HVAC, secure electrical grids, and precise plumbing into your building\'s framework. We optimize energy performance and incorporate modern life safety, ELV, and process piping technologies.' ),
			array( 'icon' => 'fas fa-road', 'title' => 'Infrastructure & Civil Engineering', 'text' => 'We engineer resilient external civil utilities—including water, gas, and power grids—to effectively connect your site to the wider network. Our expert roadway, pavement, and stormwater management designs ensure durable and sustainable surface infrastructure.' ),
		),
	),
	'map'      => 'Time Tower, Kovkab Safaraliyeva St 16e, Baku',
	// Polylang string translations (English source => translation); none for English.
	'strings'  => array(),
);
