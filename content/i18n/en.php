<?php
/**
 * English sample content (default language). Used once by tools/seed.php;
 * after seeding, edit the pages in Elementor and strings in Languages → Translations.
 */

return array(
	'slugs'    => array( 'home' => 'home', 'contact' => 'contact' ),
	'titles'   => array( 'home' => 'Home', 'contact' => 'Contact' ),
	'menu'     => 'Main menu',

	'hero'     => array(
		array( 'kicker' => 'Architecture & interior design studio', 'title' => "Spaces shaped\nby light", 'button' => 'Our projects', 'link' => '#projects' ),
		array( 'kicker' => 'Residential · Commercial · Public', 'title' => "Designing\nwith purpose", 'button' => 'Our services', 'link' => '#services' ),
		array( 'kicker' => 'Let’s talk about your project', 'title' => "From first sketch\nto final detail", 'button' => 'Get in touch', 'link' => 'contact' ),
	),

	'intro'    => array(
		'label'     => 'About the studio',
		'statement' => 'We design <em>timeless architecture</em> and interiors where light, material and purpose meet.',
		'text'      => 'Via Adlucem is an independent studio working across architecture, interior design and planning. Every project begins with listening — to people, to place and to light — and ends with spaces that feel effortless to live in.',
		'button'    => 'Contact us',
	),

	'services' => array(
		'label' => 'What we do',
		'title' => 'Four disciplines, one approach',
		'items' => array(
			array( 'icon' => 'fas fa-building', 'title' => 'Architecture', 'text' => 'Residential and commercial buildings designed from concept to construction documents.' ),
			array( 'icon' => 'fas fa-couch', 'title' => 'Interior design', 'text' => 'Interiors with a clear spatial idea, honest materials and carefully crafted details.' ),
			array( 'icon' => 'fas fa-city', 'title' => 'Urban planning', 'text' => 'Master plans and public spaces that connect buildings with the life of the city.' ),
			array( 'icon' => 'fas fa-drafting-compass', 'title' => 'Consulting', 'text' => 'Feasibility studies, design reviews and technical guidance at every stage.' ),
		),
	),

	'projects' => array(
		'label' => 'Selected work',
		'title' => 'Recent projects',
		'items' => array(
			array( 'title' => 'Caspian Residence', 'category' => 'Residential · Baku' ),
			array( 'title' => 'Light Gallery', 'category' => 'Cultural · Ganja' ),
			array( 'title' => 'Atrium Office', 'category' => 'Commercial · Baku' ),
			array( 'title' => 'Stone House', 'category' => 'Residential · Gabala' ),
			array( 'title' => 'Harbour Pavilion', 'category' => 'Public · Sumgait' ),
			array( 'title' => 'Loft Interior', 'category' => 'Interior · Tashkent' ),
		),
	),

	'offer'    => array(
		'label' => 'Services',
		'title' => 'A complete service, from the first idea to the finished space.',
		'text'  => 'We guide each project through every phase, so that the design intent survives all the way to the final detail.',
		'items' => array(
			array( 'title' => 'Concept & feasibility', 'text' => 'We study the site, the brief and the budget, and turn them into a clear concept that sets the direction for the whole project.' ),
			array( 'title' => 'Architectural design', 'text' => 'From schematic design to detailed drawings, we develop buildings that are functional, efficient and full of natural light.' ),
			array( 'title' => 'Interior design', 'text' => 'Layouts, materials, lighting and custom furniture — composed into interiors that are calm, practical and personal.' ),
			array( 'title' => 'Construction documents', 'text' => 'Precise technical drawings and specifications that let contractors build exactly what was designed.' ),
			array( 'title' => 'Site supervision', 'text' => 'Regular site visits and coordination with contractors to keep quality, cost and schedule under control.' ),
		),
	),

	'studio'   => array(
		'label'     => 'Our studio',
		'statement' => 'Building spaces with purpose since 2015',
		'text'      => 'Small enough to give every project personal attention, experienced enough to deliver complex buildings on time.',
		'numbers'   => array(
			array( 'number' => 120, 'suffix' => '+', 'title' => 'Realized projects' ),
			array( 'number' => 10, 'suffix' => '+', 'title' => 'Years of experience' ),
			array( 'number' => 25, 'suffix' => '', 'title' => 'Team members' ),
			array( 'number' => 8, 'suffix' => '', 'title' => 'Cities' ),
		),
	),

	'contact'  => array(
		'label'         => 'Contact',
		'title'         => 'Let’s talk about your next project',
		'text'          => 'Tell us about your plans — we usually reply within one working day.',
		'details_label' => 'Contact details',
		'studio'        => 'Studio',
		'phone'         => 'Phone',
		'email'         => 'Email',
		'hours'         => 'Working hours',
		'hours_text'    => 'Monday – Friday, 9:00 – 18:00',
		'form_label'    => 'Write to us',
		'form_title'    => 'Send us a message',
		'form_text'     => 'Fill in the form and our team will get back to you shortly.',
		'map'           => 'Baku, Azerbaijan',
	),

	'form'     => array(
		'title'    => 'Contact form (EN)',
		'name'     => 'Your name',
		'email'    => 'Email',
		'phone'    => 'Phone',
		'subject'  => 'Subject',
		'message'  => 'Message',
		'submit'   => 'Send message',
		'messages' => array(
			'mail_sent_ok'     => 'Thank you! Your message has been sent.',
			'mail_sent_ng'     => 'There was an error sending your message. Please try again later.',
			'validation_error' => 'One or more fields have an error. Please check and try again.',
			'invalid_required' => 'Please fill out this field.',
			'invalid_email'    => 'Please enter a valid email address.',
		),
	),

	// Theme strings (Languages → Translations). English is the source text, so nothing to translate.
	'strings'  => array(),
	'tagline'  => 'Architecture & interior design studio',
);
