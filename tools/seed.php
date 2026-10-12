<?php
/**
 * One-time site setup: languages, theme settings, media, forms, pages, menus and
 * Polylang string translations, built from content/i18n/*.php.
 *
 * Run on the server after a deploy:
 *     $r = va_deploy( '<ref>' );
 *     require $r['repo_root'] . '/tools/seed.php';
 *     return va_seed( $r['repo_root'] );
 *
 * Idempotent: anything already created (tracked by `_va_seed` meta) is left alone,
 * so content edited in Elementor is never overwritten. Pass array( 'force' => true )
 * to rebuild the seeded pages from scratch.
 */

defined( 'ABSPATH' ) || exit;

const VA_SEED_LANGS = array( 'en', 'az', 'uz' );

function va_seed( $root, $opts = array() ) {
	$force = ! empty( $opts['force'] );
	$log   = array();

	if ( ! function_exists( 'PLL' ) || ! function_exists( 'pll_set_post_language' ) ) {
		return new WP_Error( 'va_seed', 'Polylang is not active.' );
	}
	if ( 'via-adlucem' !== get_stylesheet() ) {
		return new WP_Error( 'va_seed', 'Activate the via-adlucem theme first.' );
	}

	$content = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$content[ $lang ] = require $root . '/content/i18n/' . $lang . '.php';
	}

	$log[] = va_seed_languages();
	$log[] = va_seed_settings( $content['en'] );
	$log[] = va_seed_elementor_kit();

	$media = va_seed_media( $root . '/content/placeholders' );
	$log[] = 'media: ' . count( $media ) . ' images';

	$forms = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		$forms[ $lang ] = va_seed_form( $lang, $content[ $lang ]['form'] );
	}
	$log[] = 'forms: ' . wp_json_encode( $forms );

	// Contact pages first: the home page links to them.
	$pages = array( 'contact' => array(), 'home' => array() );
	foreach ( VA_SEED_LANGS as $lang ) {
		$c = $content[ $lang ];
		$pages['contact'][ $lang ] = va_seed_page( 'contact', $lang, $c, va_seed_contact_data( $c, $forms[ $lang ] ), $force );
	}
	foreach ( VA_SEED_LANGS as $lang ) {
		$c = $content[ $lang ];
		$pages['home'][ $lang ] = va_seed_page( 'home', $lang, $c, va_seed_home_data( $c, $media, get_permalink( $pages['contact'][ $lang ] ) ), $force );
	}
	pll_save_post_translations( $pages['home'] );
	pll_save_post_translations( $pages['contact'] );
	$log[] = 'pages: ' . wp_json_encode( $pages );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages['home']['en'] );

	$log[] = va_seed_menus( $content, $pages );
	$log[] = va_seed_strings( $content );

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	PLL()->model->clean_languages_cache();
	flush_rewrite_rules( false );
	// The persistent object cache keeps Polylang's language list (home URLs) from before the changes.
	wp_cache_flush();
	$log[] = 'Then run flush_rewrite_rules() in a new request: rules built in this one still use the old default language.';

	return $log;
}

/* -------------------------------------------------------------------------
 * Languages and settings
 * ---------------------------------------------------------------------- */

function va_seed_languages() {
	$options                 = get_option( 'polylang' );
	$options['default_lang'] = 'en';
	// Translated front pages live at /az/ and /uz/ instead of /az/<page-slug>/.
	$options['redirect_lang'] = 1;
	update_option( 'polylang', $options );

	// Order in the switcher: EN, AZ, UZ.
	foreach ( array( 'en' => 0, 'az' => 1, 'uz' => 2 ) as $slug => $order ) {
		$term = get_term_by( 'slug', $slug, 'language' );
		if ( $term ) {
			wp_update_term( $term->term_id, 'language', array( 'term_group' => $order ) );
		}
	}
	PLL()->model->clean_languages_cache();
	return 'languages: default en, order en/az/uz';
}

function va_seed_settings( $en ) {
	update_option( 'blogname', 'Via Adlucem' );
	update_option( 'blogdescription', $en['tagline'] );

	// Elementor: let the theme stylesheet own default colours and fonts.
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	$cpt = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	update_option( 'elementor_cpt_support', array_values( array_unique( array_merge( (array) $cpt, array( 'page', 'post' ) ) ) ) );

	return 'settings: site title, tagline, Elementor defaults';
}

function va_seed_elementor_kit() {
	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( ! $kit_id ) {
		return 'kit: none';
	}
	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();

	$settings['system_colors'] = array(
		array( '_id' => 'primary', 'title' => 'Primary (navy)', 'color' => '#003695' ),
		array( '_id' => 'secondary', 'title' => 'Secondary (orange)', 'color' => '#FDAD34' ),
		array( '_id' => 'text', 'title' => 'Text', 'color' => '#121110' ),
		array( '_id' => 'accent', 'title' => 'Accent (orange)', 'color' => '#FDAD34' ),
	);
	$palette = array(
		'Orange 50'  => '#FEE0C8', 'Orange 600' => '#CC891B', 'Orange 900' => '#1C0F01',
		'Blue 50'    => '#F7F8FF', 'Blue 100' => '#C5CDFF', 'Blue 500' => '#0054DD', 'Navy 900' => '#001B54',
		'Grey 100'   => '#E6E5E3', 'Grey 200' => '#C2BCB8', 'Grey 300' => '#9B9692', 'Grey 400' => '#75716F',
		'Grey 500'   => '#524F4D', 'Grey 600' => '#312F2E', 'Ink' => '#121110', 'White' => '#FFFFFF',
	);
	$settings['custom_colors'] = array();
	foreach ( $palette as $title => $color ) {
		$settings['custom_colors'][] = array( '_id' => substr( md5( $title ), 0, 7 ), 'title' => $title, 'color' => $color );
	}
	$font                          = array( 'typography_typography' => 'custom', 'typography_font_family' => 'Space Grotesk', 'typography_font_weight' => '400' );
	$settings['system_typography'] = array(
		array_merge( array( '_id' => 'primary', 'title' => 'Primary' ), $font ),
		array_merge( array( '_id' => 'secondary', 'title' => 'Secondary' ), $font ),
		array_merge( array( '_id' => 'text', 'title' => 'Text' ), $font ),
		array_merge( array( '_id' => 'accent', 'title' => 'Accent' ), $font, array( 'typography_font_weight' => '500' ) ),
	);
	$settings['container_width']       = array( 'unit' => 'px', 'size' => 1400, 'sizes' => array() );
	$settings['space_between_widgets'] = array( 'column' => '0', 'row' => '0', 'unit' => 'px', 'isLinked' => true );
	$settings['site_name']             = 'Via Adlucem';

	update_post_meta( $kit_id, '_elementor_page_settings', $settings );
	return 'kit: colours, fonts, 1400px container';
}

/* -------------------------------------------------------------------------
 * Media
 * ---------------------------------------------------------------------- */

function va_seed_media( $dir ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$ids = array();
	foreach ( glob( $dir . '/*.jpg' ) as $file ) {
		$key      = basename( $file, '.jpg' );
		$existing = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'meta_key'    => '_va_seed',
				'meta_value'  => 'media-' . $key,
				'fields'      => 'ids',
				'numberposts' => 1,
				'lang'        => '',
			)
		);
		if ( $existing ) {
			$ids[ $key ] = (int) $existing[0];
			continue;
		}
		$tmp = wp_tempnam( $key . '.jpg' );
		copy( $file, $tmp );
		$id = media_handle_sideload(
			array( 'name' => 'va-placeholder-' . $key . '.jpg', 'tmp_name' => $tmp ),
			0,
			'Placeholder — ' . $key
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		update_post_meta( $id, '_va_seed', 'media-' . $key );
		update_post_meta( $id, '_wp_attachment_image_alt', '' );
		pll_set_post_language( $id, 'en' );
		$ids[ $key ] = (int) $id;
	}
	return $ids;
}

function va_seed_img( $media, $key ) {
	$id = $media[ $key ] ?? 0;
	return array(
		'id'  => $id,
		'url' => $id ? wp_get_attachment_image_url( $id, 'full' ) : '',
	);
}

/* -------------------------------------------------------------------------
 * Contact Form 7
 * ---------------------------------------------------------------------- */

function va_seed_form( $lang, $f ) {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}
	$existing = get_posts(
		array(
			'post_type'   => 'wpcf7_contact_form',
			'meta_key'    => '_va_seed',
			'meta_value'  => 'form-' . $lang,
			'fields'      => 'ids',
			'numberposts' => 1,
			'lang'        => '',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$form = implode(
		"\n",
		array(
			'<p class="va-field"><label>' . $f['name'] . ' *[text* your-name autocomplete:name]</label></p>',
			'<p class="va-field"><label>' . $f['email'] . ' *[email* your-email autocomplete:email]</label></p>',
			'<p class="va-field"><label>' . $f['phone'] . '[tel your-phone autocomplete:tel]</label></p>',
			'<p class="va-field"><label>' . $f['subject'] . '[text your-subject]</label></p>',
			'<p class="va-field va-field--full"><label>' . $f['message'] . ' *[textarea* your-message]</label></p>',
			'<p class="va-form-submit">[submit "' . $f['submit'] . '"]</p>',
		)
	);

	$cf = WPCF7_ContactForm::get_template( array( 'title' => $f['title'] ) );
	$mail                       = $cf->prop( 'mail' );
	$mail['subject']            = '[_site_title] — [your-subject]';
	$mail['body']               = "From: [your-name] <[your-email]>\nPhone: [your-phone]\nSubject: [your-subject]\nLanguage: " . strtoupper( $lang ) . "\n\n[your-message]\n\n-- \nSent from [_site_title] ([_site_url])";
	$mail['additional_headers'] = 'Reply-To: [your-email]';
	$messages                   = array_merge( $cf->prop( 'messages' ), $f['messages'] );

	$cf->set_properties(
		array(
			'form'     => $form,
			'mail'     => $mail,
			'messages' => $messages,
		)
	);
	$id = $cf->save();
	update_post_meta( $id, '_va_seed', 'form-' . $lang );
	return (int) $id;
}

/* -------------------------------------------------------------------------
 * Pages
 * ---------------------------------------------------------------------- */

function va_seed_page( $role, $lang, $c, $data, $force ) {
	$key      = 'page-' . $role . '-' . $lang;
	$existing = get_posts(
		array(
			'post_type'   => 'page',
			'post_status' => 'any',
			'meta_key'    => '_va_seed',
			'meta_value'  => $key,
			'fields'      => 'ids',
			'numberposts' => 1,
			'lang'        => '',
		)
	);
	if ( $existing && ! $force ) {
		return (int) $existing[0];
	}

	$postarr = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $c['titles'][ $role ],
		'post_name'    => $c['slugs'][ $role ],
		'post_content' => '',
	);
	if ( $existing ) {
		$postarr['ID'] = (int) $existing[0];
	}
	$id = wp_insert_post( $postarr, true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	pll_set_post_language( $id, $lang );

	update_post_meta( $id, '_va_seed', $key );
	update_post_meta( $id, '_va_page_role', $role );
	update_post_meta( $id, '_va_transparent_header', '1' );
	update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '' );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $id, '_elementor_page_settings', array( 'hide_title' => 'yes' ) );
	delete_post_meta( $id, '_elementor_css' );

	return (int) $id;
}

/* Elementor element builders ------------------------------------------- */

function va_el_id() {
	return substr( md5( uniqid( '', true ) . wp_rand() ), 0, 7 );
}

function va_el_pad( $top, $right = null, $bottom = null, $left = null ) {
	return array(
		'unit'     => 'px',
		'top'      => (string) $top,
		'right'    => (string) ( $right ?? $top ),
		'bottom'   => (string) ( $bottom ?? $top ),
		'left'     => (string) ( $left ?? $right ?? $top ),
		'isLinked' => null === $right,
	);
}

function va_con( $settings, $children, $inner = false ) {
	return array(
		'id'       => va_el_id(),
		'elType'   => 'container',
		'isInner'  => $inner,
		'settings' => $settings,
		'elements' => $children,
	);
}

/** Boxed section with the theme's section spacing. */
function va_section( $classes, $children, $extra = array() ) {
	return va_con(
		array_merge(
			array(
				'content_width'  => 'boxed',
				'flex_direction' => 'column',
				'css_classes'    => 'va-section ' . $classes,
				'flex_gap'       => array( 'unit' => 'px', 'size' => 70, 'column' => '70', 'row' => '70', 'isLinked' => true ),
			),
			$extra
		),
		$children
	);
}

/** Two-column row inside a section. $widths like array( 40, 60 ). */
function va_row( $columns, $widths, $gap = 80, $extra = array() ) {
	$children = array();
	foreach ( $columns as $i => $col ) {
		$children[] = va_con(
			array(
				'content_width'  => 'full',
				'width'          => array( 'unit' => '%', 'size' => $widths[ $i ] ),
				'width_mobile'   => array( 'unit' => '%', 'size' => 100 ),
				'flex_direction' => 'column',
				'padding'        => va_el_pad( 0 ),
				'flex_gap'       => array( 'unit' => 'px', 'size' => 28, 'column' => '28', 'row' => '28', 'isLinked' => true ),
			),
			$col,
			true
		);
	}
	return va_con(
		array_merge(
			array(
				'content_width'         => 'full',
				'flex_direction'        => 'row',
				'flex_direction_mobile' => 'column',
				'flex_wrap'             => 'nowrap',
				'padding'               => va_el_pad( 0 ),
				'flex_gap'              => array( 'unit' => 'px', 'size' => $gap, 'column' => (string) $gap, 'row' => '40', 'isLinked' => false ),
			),
			$extra
		),
		$children,
		true
	);
}

function va_w( $type, $settings ) {
	return array(
		'id'         => va_el_id(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
		'elements'   => array(),
	);
}

function va_label( $text ) {
	return va_w( 'heading', array( 'title' => $text, 'header_size' => 'h6', '_css_classes' => 'va-label va-reveal' ) );
}

function va_heading( $text, $tag = 'h2', $classes = '' ) {
	return va_w( 'heading', array( 'title' => $text, 'header_size' => $tag, '_css_classes' => trim( $classes . ' va-reveal' ) ) );
}

function va_text( $html, $classes = '' ) {
	return va_w( 'text-editor', array( 'editor' => $html, '_css_classes' => trim( $classes . ' va-reveal' ) ) );
}

function va_button( $text, $url, $classes = '' ) {
	return va_w(
		'button',
		array(
			'text'         => $text,
			'link'         => array( 'url' => $url, 'is_external' => '', 'nofollow' => '' ),
			'_css_classes' => trim( $classes . ' va-reveal' ),
		)
	);
}

/* Home page ------------------------------------------------------------ */

function va_seed_home_data( $c, $media, $contact_url ) {
	$slides = array();
	foreach ( $c['hero'] as $i => $s ) {
		$slides[] = array(
			'_id'         => va_el_id(),
			'image'       => va_seed_img( $media, 'hero-' . ( $i + 1 ) ),
			'kicker'      => $s['kicker'],
			'title'       => $s['title'],
			'button_text' => $s['button'],
			'button_link' => array( 'url' => 'contact' === $s['link'] ? $contact_url : $s['link'], 'is_external' => '', 'nofollow' => '' ),
		);
	}

	$hero = va_con(
		array(
			'content_width' => 'full',
			'padding'       => va_el_pad( 0 ),
			'css_classes'   => 'va-hero-wrap',
		),
		array( va_w( 'va-hero-slider', array( 'slides' => $slides, 'autoplay' => 6 ) ) )
	);

	$intro = va_section(
		'',
		array(
			va_row(
				array(
					array( va_label( $c['intro']['label'] ) ),
					array(
						va_heading( $c['intro']['statement'], 'h2', 'va-statement' ),
						va_text( '<p>' . esc_html( $c['intro']['text'] ) . '</p>', 'va-lead' ),
						va_button( $c['intro']['button'], $contact_url, 'va-btn-outline' ),
					),
				),
				array( 30, 70 )
			),
		)
	);

	$boxes = array();
	foreach ( $c['services']['items'] as $item ) {
		$boxes[] = va_w(
			'icon-box',
			array(
				'selected_icon'    => array( 'value' => $item['icon'], 'library' => 'fa-solid' ),
				'title_text'       => $item['title'],
				'description_text' => $item['text'],
				'title_size'       => 'h3',
				'position'         => 'top',
				'_css_classes'     => 'va-reveal',
			)
		);
	}
	$services = va_section(
		'va-section--tight',
		array(
			va_row(
				array(
					array( va_w( 'menu-anchor', array( 'anchor' => 'services' ) ), va_label( $c['services']['label'] ) ),
					array( va_heading( $c['services']['title'], 'h2', 'va-statement' ) ),
				),
				array( 30, 70 )
			),
			va_con(
				array(
					'container_type'        => 'grid',
					'grid_columns_grid'     => array( 'unit' => 'fr', 'size' => 4 ),
					'grid_columns_grid_tablet' => array( 'unit' => 'fr', 'size' => 2 ),
					'grid_columns_grid_mobile' => array( 'unit' => 'fr', 'size' => 1 ),
					'grid_rows_grid'        => array( 'unit' => 'fr', 'size' => 1 ),
					'grid_gaps'             => array( 'unit' => 'px', 'column' => '40', 'row' => '50', 'isLinked' => false ),
					'content_width'         => 'full',
					'padding'               => va_el_pad( 0 ),
					'css_classes'           => 'va-services',
				),
				$boxes,
				true
			),
		),
	);

	$cards = array();
	foreach ( $c['projects']['items'] as $i => $p ) {
		$cards[] = array(
			'_id'      => va_el_id(),
			'image'    => va_seed_img( $media, 'project-' . ( $i + 1 ) ),
			'title'    => $p['title'],
			'category' => $p['category'],
			'link'     => array( 'url' => '', 'is_external' => '', 'nofollow' => '' ),
		);
	}
	$projects = va_section(
		'va-section--tight',
		array(
			va_row(
				array(
					array( va_w( 'menu-anchor', array( 'anchor' => 'projects' ) ), va_label( $c['projects']['label'] ) ),
					array( va_heading( $c['projects']['title'], 'h2', 'va-statement' ) ),
				),
				array( 30, 70 )
			),
			va_w( 'va-projects-slider', array( 'projects' => $cards, '_css_classes' => 'va-bleed-right' ) ),
		),
		array( 'overflow' => 'hidden' )
	);

	$acc = array();
	foreach ( $c['offer']['items'] as $item ) {
		$acc[] = array( '_id' => va_el_id(), 'title' => $item['title'], 'content' => '<p>' . esc_html( $item['text'] ) . '</p>' );
	}
	$offer = va_section(
		'va-section--surface',
		array(
			va_row(
				array(
					array(
						va_label( $c['offer']['label'] ),
						va_heading( $c['offer']['title'], 'h2', 'va-statement' ),
						va_text( '<p>' . esc_html( $c['offer']['text'] ) . '</p>' ),
					),
					array( va_w( 'va-accordion', array( 'items' => $acc, 'show_numbers' => 'yes', '_css_classes' => 'va-reveal' ) ) ),
				),
				array( 42, 58 ),
				100
			),
		)
	);

	$counters = array();
	foreach ( $c['studio']['numbers'] as $n ) {
		$counters[] = va_w(
			'counter',
			array(
				'starting_number' => 0,
				'ending_number'   => $n['number'],
				'suffix'          => $n['suffix'],
				'title'           => $n['title'],
				'duration'        => 2200,
				'_css_classes'    => 'va-reveal',
			)
		);
	}
	$studio = va_section(
		'',
		array(
			va_row(
				array(
					array( va_w( 'image', array( 'image' => va_seed_img( $media, 'about' ), 'image_size' => 'full', '_css_classes' => 'va-image-tall va-reveal' ) ) ),
					array(
						va_label( $c['studio']['label'] ),
						va_heading( $c['studio']['statement'], 'h2', 'va-statement va-statement--xl' ),
						va_text( '<p>' . esc_html( $c['studio']['text'] ) . '</p>', 'va-lead' ),
						va_con(
							array(
								'container_type'           => 'grid',
								'grid_columns_grid'        => array( 'unit' => 'fr', 'size' => 2 ),
								'grid_columns_grid_mobile' => array( 'unit' => 'fr', 'size' => 1 ),
								'grid_rows_grid'           => array( 'unit' => 'fr', 'size' => 2 ),
								'grid_gaps'                => array( 'unit' => 'px', 'column' => '30', 'row' => '50', 'isLinked' => false ),
								'content_width'            => 'full',
								'padding'                  => va_el_pad( 30, 0, 0, 0 ),
								'css_classes'              => 'va-counters',
							),
							$counters,
							true
						),
					),
				),
				array( 42, 58 ),
				100,
				array( 'align_items' => 'center' )
			),
		)
	);

	return array( $hero, $intro, $services, $projects, $offer, $studio );
}

/* Contact page --------------------------------------------------------- */

function va_seed_contact_data( $c, $form_id ) {
	$k = $c['contact'];

	$banner = va_con(
		array(
			'content_width'  => 'boxed',
			'flex_direction' => 'column',
			'css_classes'    => 'va-page-banner',
			'flex_gap'       => array( 'unit' => 'px', 'size' => 24, 'column' => '24', 'row' => '24', 'isLinked' => true ),
		),
		array(
			va_label( $k['label'] ),
			va_heading( $k['title'], 'h1', 'va-statement va-statement--xl' ),
			va_text( '<p>' . esc_html( $k['text'] ) . '</p>', 'va-lead' ),
		)
	);

	$phone = va_t( 'phone' );
	$email = va_t( 'email' );
	$items = array(
		array( $k['studio'], esc_html( va_t( 'address' ) ) ),
		array( $k['phone'], '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>' ),
		array( $k['email'], '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' ),
		array( $k['hours'], esc_html( $k['hours_text'] ) ),
	);
	$details = array( va_label( $k['details_label'] ) );
	foreach ( $items as $item ) {
		$details[] = va_con(
			array(
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'padding'        => va_el_pad( 26, 0, 0, 0 ),
				'border_border'  => 'solid',
				'border_width'   => array( 'unit' => 'px', 'top' => '1', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ),
				'border_color'   => '#C2BCB8',
				'css_classes'    => 'va-contact-item',
				'flex_gap'       => array( 'unit' => 'px', 'size' => 6, 'column' => '6', 'row' => '6', 'isLinked' => true ),
			),
			array(
				va_w( 'heading', array( 'title' => $item[0], 'header_size' => 'h3', '_css_classes' => 'va-label' ) ),
				va_w( 'text-editor', array( 'editor' => '<p>' . $item[1] . '</p>' ) ),
			),
			true
		);
	}

	$main = va_section(
		'',
		array(
			va_row(
				array(
					$details,
					array(
						va_label( $k['form_label'] ),
						va_heading( $k['form_title'], 'h2', 'va-statement' ),
						va_text( '<p>' . esc_html( $k['form_text'] ) . '</p>' ),
						va_w( 'shortcode', array( 'shortcode' => '[contact-form-7 id="' . (int) $form_id . '"]', '_css_classes' => 'va-reveal' ) ),
					),
				),
				array( 36, 64 ),
				100
			),
		)
	);

	$map = va_con(
		array(
			'content_width' => 'full',
			'padding'       => va_el_pad( 0 ),
		),
		array(
			va_w(
				'google_maps',
				array(
					'address'      => $k['map'],
					'zoom'         => array( 'unit' => 'px', 'size' => 13 ),
					'height'       => array( 'unit' => 'px', 'size' => 480 ),
					'_css_classes' => 'va-map',
				)
			),
		)
	);

	return array( $banner, $main, $map );
}

/* -------------------------------------------------------------------------
 * Menus (one per language) with the Polylang language switcher
 * ---------------------------------------------------------------------- */

function va_seed_menus( $content, $pages ) {
	$options = get_option( 'polylang' );
	$theme   = get_stylesheet();

	foreach ( VA_SEED_LANGS as $lang ) {
		$name = $content[ $lang ]['menu'] . ' (' . strtoupper( $lang ) . ')';
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			$menu_id = $menu->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $name );
			if ( is_wp_error( $menu_id ) ) {
				continue;
			}
			foreach ( array( 'home', 'contact' ) as $role ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => $content[ $lang ]['titles'][ $role ],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $pages[ $role ][ $lang ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
			$switcher = wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => 'Languages',
					'menu-item-url'    => '#pll_switcher',
					'menu-item-type'   => 'custom',
					'menu-item-status' => 'publish',
				)
			);
			update_post_meta(
				$switcher,
				'_pll_menu_item',
				array(
					'hide_if_no_translation' => 0,
					'hide_current'           => 0,
					'force_home'             => 0,
					'show_flags'             => 0,
					'show_names'             => 1,
					'dropdown'               => 0,
				)
			);
		}
		$options['nav_menus'][ $theme ]['va-primary'][ $lang ] = $menu_id;
		$options['nav_menus'][ $theme ]['va-footer'][ $lang ]  = $menu_id;
	}

	update_option( 'polylang', $options );
	set_theme_mod(
		'nav_menu_locations',
		array(
			'va-primary' => $options['nav_menus'][ $theme ]['va-primary']['en'],
			'va-footer'  => $options['nav_menus'][ $theme ]['va-footer']['en'],
		)
	);
	return 'menus: ' . wp_json_encode( $options['nav_menus'][ $theme ] );
}

/* -------------------------------------------------------------------------
 * Polylang string translations (Languages → Translations)
 * ---------------------------------------------------------------------- */

function va_seed_strings( $content ) {
	if ( ! class_exists( 'PLL_MO' ) ) {
		return 'strings: PLL_MO missing';
	}
	$done = array();
	foreach ( VA_SEED_LANGS as $lang ) {
		if ( 'en' === $lang ) {
			continue;
		}
		$language = PLL()->model->get_language( $lang );
		if ( ! $language ) {
			continue;
		}
		$translations = $content[ $lang ]['strings'];
		$translations[ $content['en']['tagline'] ] = $content[ $lang ]['tagline']; // Site tagline.

		$mo = new PLL_MO();
		$mo->import_from_db( $language );
		foreach ( $translations as $source => $translated ) {
			$mo->add_entry( $mo->make_entry( $source, $translated ) );
		}
		$mo->export_to_db( $language );
		$done[] = $lang . ':' . count( $translations );
	}
	return 'strings: ' . implode( ', ', $done );
}
