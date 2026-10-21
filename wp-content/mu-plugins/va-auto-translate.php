<?php
/**
 * Plugin Name: Via Adlucem Auto-translation
 * Description: When a project is published, creates its missing Polylang translations and machine-translates title, summary, content, categories and tags (Claude or Google Translate). Settings: Projects → Auto-translation.
 * Version:     1.0.0
 *
 * Translations run in the background (WP-Cron), one language per job, so saving a project stays fast.
 * Credentials: Projects → Auto-translation, or in wp-config.php:
 *     define( 'VA_TRANSLATE_PROVIDER', 'claude' ); // or 'google'
 *     define( 'VA_TRANSLATE_API_KEY', '...' );
 */

defined( 'ABSPATH' ) || exit;

const VA_AT_OPTION = 'va_auto_translate';

/** Post types that are auto-translated. */
function va_at_post_types() {
	return apply_filters( 'va_auto_translate_post_types', array( 'va_project' ) );
}

function va_at_settings() {
	$saved = wp_parse_args( (array) get_option( VA_AT_OPTION, array() ), array( 'provider' => 'claude', 'api_key' => '', 'publish' => 1 ) );
	if ( defined( 'VA_TRANSLATE_PROVIDER' ) ) {
		$saved['provider'] = VA_TRANSLATE_PROVIDER;
	}
	if ( defined( 'VA_TRANSLATE_API_KEY' ) ) {
		$saved['api_key'] = VA_TRANSLATE_API_KEY;
	}
	return $saved;
}

/** True while this plugin is creating translations (so its own inserts don't trigger new jobs). */
function va_at_running( $set = null ) {
	static $running = false;
	if ( null !== $set ) {
		$running = (bool) $set;
	}
	return $running;
}

/* -------------------------------------------------------------------------
 * Triggers
 * ---------------------------------------------------------------------- */

// First publish of a project → translate it into every language it is missing.
add_action(
	'transition_post_status',
	function ( $new_status, $old_status, $post ) {
		if ( 'publish' !== $new_status || 'publish' === $old_status || ! in_array( $post->post_type, va_at_post_types(), true ) ) {
			return;
		}
		if ( defined( 'VA_SEEDING' ) || va_at_running() || get_post_meta( $post->ID, '_va_translated_from', true ) ) {
			return;
		}
		// The language is assigned later in the same save request, so look it up when the job runs.
		wp_schedule_single_event( time() + 10, 'va_at_plan', array( $post->ID ) );
	},
	20,
	3
);

/** Schedules one job per missing language, a minute apart so each gets its own request (and time limit). */
add_action(
	'va_at_plan',
	function ( $source_id ) {
		$delay = 0;
		foreach ( va_at_missing_languages( $source_id ) as $lang ) {
			wp_schedule_single_event( time() + $delay, 'va_at_translate', array( (int) $source_id, $lang, 0 ) );
			$delay += 60;
		}
		spawn_cron();
	}
);

add_action( 'va_at_translate', 'va_at_translate_post', 10, 3 );

function va_at_missing_languages( $source_id ) {
	if ( ! function_exists( 'pll_get_post_language' ) ) {
		return array();
	}
	$source_lang  = pll_get_post_language( $source_id );
	$translations = pll_get_post_translations( $source_id );
	$missing      = array();
	foreach ( pll_languages_list() as $lang ) {
		if ( $lang !== $source_lang && empty( $translations[ $lang ] ) ) {
			$missing[] = $lang;
		}
	}
	return $source_lang ? $missing : array();
}

/* -------------------------------------------------------------------------
 * The translation job
 * ---------------------------------------------------------------------- */

/**
 * Creates (or, with $target_id, overwrites) the $lang translation of $source_id.
 *
 * @return int|WP_Error Translation post ID.
 */
function va_at_translate_post( $source_id, $lang, $target_id = 0 ) {
	$source      = get_post( $source_id );
	$source_lang = $source ? pll_get_post_language( $source_id ) : '';
	if ( ! $source || ! $source_lang || $source_lang === $lang ) {
		return new WP_Error( 'va_at', 'Nothing to translate.' );
	}
	$translations = pll_get_post_translations( $source_id );
	if ( ! $target_id && ! empty( $translations[ $lang ] ) ) {
		return (int) $translations[ $lang ]; // Already exists (e.g. created by hand meanwhile).
	}
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}

	// Terms: translate the names of terms that don't exist in the target language yet.
	$terms = array();
	foreach ( get_object_taxonomies( $source->post_type ) as $taxonomy ) {
		if ( ! pll_is_translated_taxonomy( $taxonomy ) ) {
			continue;
		}
		foreach ( (array) get_the_terms( $source_id, $taxonomy ) as $term ) {
			if ( $term instanceof WP_Term ) {
				$terms[] = $term;
			}
		}
	}
	$to_name = array_values( array_filter( $terms, fn( $t ) => ! pll_get_term( $t->term_id, $lang ) ) );

	$texts = array(
		'title'   => $source->post_title,
		'excerpt' => $source->post_excerpt,
		'content' => $source->post_content,
		'terms'   => array_map( fn( $t ) => $t->name, $to_name ),
	);
	$result = va_at_translate_texts( $texts, $source_lang, $lang );
	$error  = is_wp_error( $result ) ? $result->get_error_message() : '';
	$failed = '' !== $error;
	if ( $failed && $target_id ) {
		// Re-translation failed: leave the existing translation untouched, just record why.
		update_post_meta( $target_id, '_va_translation_error', $error );
		return $result;
	}
	if ( $failed ) {
		$result = $texts; // Keep the source text so the translation can be finished by hand.
	}

	$settings = va_at_settings();
	// Hidden languages (mu-plugins/va-languages.php) only ever get drafts.
	$publish  = ! $failed && ! empty( $settings['publish'] ) && ! ( function_exists( 'va_is_hidden_language' ) && va_is_hidden_language( $lang ) );
	$postarr  = array(
		'post_type'      => $source->post_type,
		'post_title'     => $result['title'],
		'post_excerpt'   => $result['excerpt'],
		'post_content'   => $result['content'],
		'post_status'    => $publish ? 'publish' : 'draft',
		'post_author'    => $source->post_author,
		'post_date'      => $source->post_date,
		'post_date_gmt'  => $source->post_date_gmt,
		'menu_order'     => $source->menu_order,
		'comment_status' => $source->comment_status,
	);
	if ( ! $target_id ) {
		$postarr['post_name'] = sanitize_title( $result['title'] );
	} else {
		$postarr['ID'] = $target_id;
		unset( $postarr['post_status'], $postarr['post_date'], $postarr['post_date_gmt'] );
		if ( $publish ) {
			$postarr['post_status'] = 'publish';
		}
	}

	va_at_running( true );
	$new_id = $target_id ? wp_update_post( wp_slash( $postarr ), true ) : wp_insert_post( wp_slash( $postarr ), true );
	if ( is_wp_error( $new_id ) ) {
		va_at_running( false );
		return $new_id;
	}
	pll_set_post_language( $new_id, $lang );
	$translations[ $lang ] = $new_id;
	pll_save_post_translations( $translations );

	if ( has_post_thumbnail( $source_id ) ) {
		set_post_thumbnail( $new_id, get_post_thumbnail_id( $source_id ) );
	}

	// Assign the target-language terms, creating translated terms where needed.
	$names   = array_combine( array_map( fn( $t ) => $t->term_id, $to_name ), $result['terms'] ) ?: array();
	$by_tax  = array();
	foreach ( $terms as $term ) {
		$tid = va_at_term_translation( $term, $lang, $names[ $term->term_id ] ?? $term->name );
		if ( $tid ) {
			$by_tax[ $term->taxonomy ][] = $tid;
		}
	}
	foreach ( $by_tax as $taxonomy => $ids ) {
		wp_set_object_terms( $new_id, $ids, $taxonomy );
	}
	va_at_running( false );

	update_post_meta( $new_id, '_va_translated_from', $source_id );
	update_post_meta( $new_id, '_va_translation_status', $failed ? 'failed' : 'machine' );
	update_post_meta( $new_id, '_va_translation_provider', $settings['provider'] );
	if ( $failed ) {
		update_post_meta( $new_id, '_va_translation_error', $error );
	} else {
		delete_post_meta( $new_id, '_va_translation_error' );
	}
	return $new_id;
}

function va_at_term_translation( WP_Term $term, $lang, $name ) {
	$existing = pll_get_term( $term->term_id, $lang );
	if ( $existing ) {
		return (int) $existing;
	}
	$slug = sanitize_title( $name );
	if ( term_exists( $slug, $term->taxonomy ) ) {
		$slug .= '-' . $lang;
	}
	$parent = $term->parent ? (int) pll_get_term( $term->parent, $lang ) : 0;
	$insert = wp_insert_term( $name, $term->taxonomy, array( 'slug' => $slug, 'parent' => $parent ) );
	if ( is_wp_error( $insert ) ) {
		$id = (int) $insert->get_error_data( 'term_exists' );
		if ( ! $id ) {
			return 0;
		}
	} else {
		$id = (int) $insert['term_id'];
	}
	pll_set_term_language( $id, $lang );
	$translations          = pll_get_term_translations( $term->term_id );
	$translations[ $lang ] = $id;
	pll_save_term_translations( $translations );
	return $id;
}

/* -------------------------------------------------------------------------
 * Providers
 * ---------------------------------------------------------------------- */

/**
 * @param array  $texts { title, excerpt, content (block HTML), terms (string[]) }.
 * @return array|WP_Error Same shape, translated.
 */
function va_at_translate_texts( array $texts, $from, $to ) {
	$settings = va_at_settings();
	if ( empty( $settings['api_key'] ) || 'none' === $settings['provider'] ) {
		return new WP_Error( 'va_at_no_key', 'No translation service is set up (Projects → Auto-translation).' );
	}
	if ( 'google' === $settings['provider'] ) {
		return va_at_google( $texts, $from, $to, $settings['api_key'] );
	}
	return va_at_claude( $texts, $from, $to, $settings['api_key'] );
}

function va_at_language_name( $slug ) {
	$names = array(
		'en' => 'English',
		'az' => 'Azerbaijani (Latin script)',
		'uz' => 'Uzbek (Latin script; write oʻ and gʻ with the modifier letter ʻ U+02BB)',
		'ru' => 'Russian',
	);
	return $names[ $slug ] ?? $slug;
}

/** Claude (Anthropic Messages API, raw HTTP) with a JSON-schema constrained response. */
function va_at_claude( array $texts, $from, $to, $api_key ) {
	$system = sprintf(
		'You translate website content for Via Ad Lucem, a BIM, architecture and engineering firm in Azerbaijan and Central Asia, from %1$s to %2$s. '
		. 'Translate every value of the JSON object you receive and return the same object. Use natural, professional %2$s and established technical terminology; keep abbreviations such as BIM, MEP, HVAC and ELV, and keep brand and project names unchanged. '
		. '"content" is WordPress block editor HTML: translate only the human-readable text and keep every HTML tag, attribute, URL, class name and block comment (<!-- wp:... -->) exactly as it is. '
		. '"terms" are category and tag names: return them in the same order, one translation per item. Empty strings stay empty.',
		va_at_language_name( $from ),
		va_at_language_name( $to )
	);

	$body = array(
		'model'         => 'claude-opus-5-5',
		'max_tokens'    => 16000,
		'system'        => $system,
		'output_config' => array(
			'effort' => 'low',
			'format' => array(
				'type'   => 'json_schema',
				'schema' => array(
					'type'                 => 'object',
					'properties'           => array(
						'title'   => array( 'type' => 'string' ),
						'excerpt' => array( 'type' => 'string' ),
						'content' => array( 'type' => 'string' ),
						'terms'   => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) ),
					),
					'required'             => array( 'title', 'excerpt', 'content', 'terms' ),
					'additionalProperties' => false,
				),
			),
		),
		// If the model declines on safety grounds, the API retries on a recommended fallback model.
		'fallbacks'     => 'default',
		'messages'      => array(
			array( 'role' => 'user', 'content' => wp_json_encode( $texts, JSON_UNESCAPED_UNICODE ) ),
		),
	);

	$response = wp_remote_post(
		'https://api.anthropic.com/v1/messages',
		array(
			'timeout' => 240,
			'headers' => array(
				'content-type'      => 'application/json',
				'x-api-key'         => $api_key,
				'anthropic-version' => '2023-06-01',
				'anthropic-beta'    => 'server-side-fallback-2026-07-01',
			),
			'body'    => wp_json_encode( $body ),
		)
	);
	if ( is_wp_error( $response ) ) {
		return $response;
	}
	$code = wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( 200 !== $code ) {
		return new WP_Error( 'va_at_claude', 'Claude API error ' . $code . ': ' . ( $data['error']['message'] ?? 'unknown error' ) );
	}
	if ( 'refusal' === ( $data['stop_reason'] ?? '' ) ) {
		return new WP_Error( 'va_at_claude', 'Claude declined to translate this content.' );
	}
	if ( 'max_tokens' === ( $data['stop_reason'] ?? '' ) ) {
		return new WP_Error( 'va_at_claude', 'The project is too long to translate in one request.' );
	}
	foreach ( (array) ( $data['content'] ?? array() ) as $block ) {
		if ( 'text' === ( $block['type'] ?? '' ) ) {
			return va_at_validate( json_decode( $block['text'], true ), $texts );
		}
	}
	return new WP_Error( 'va_at_claude', 'Claude returned no text.' );
}

/** Google Cloud Translation API v2. */
function va_at_google( array $texts, $from, $to, $api_key ) {
	$q        = array_merge( array( $texts['title'], $texts['excerpt'], $texts['content'] ), $texts['terms'] );
	$response = wp_remote_post(
		'https://translation.googleapis.com/language/translate/v2?key=' . rawurlencode( $api_key ),
		array(
			'timeout' => 120,
			'headers' => array( 'content-type' => 'application/json' ),
			'body'    => wp_json_encode( array( 'q' => array_map( fn( $s ) => '' === $s ? ' ' : $s, $q ), 'source' => $from, 'target' => $to, 'format' => 'html' ) ),
		)
	);
	if ( is_wp_error( $response ) ) {
		return $response;
	}
	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return new WP_Error( 'va_at_google', 'Google Translate error: ' . ( $data['error']['message'] ?? 'unknown error' ) );
	}
	$out   = array_map( fn( $t ) => $t['translatedText'] ?? '', (array) ( $data['data']['translations'] ?? array() ) );
	$plain = fn( $s ) => trim( html_entity_decode( $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	return va_at_validate(
		array(
			'title'   => $plain( $out[0] ?? '' ),
			'excerpt' => $plain( $out[1] ?? '' ),
			'content' => trim( $out[2] ?? '' ),
			'terms'   => array_map( $plain, array_slice( $out, 3 ) ),
		),
		$texts
	);
}

function va_at_validate( $result, array $texts ) {
	if ( ! is_array( $result ) || ! isset( $result['title'], $result['excerpt'], $result['content'], $result['terms'] ) || '' === trim( $result['title'] ) ) {
		return new WP_Error( 'va_at_invalid', 'The translation service returned an unexpected response.' );
	}
	if ( count( $result['terms'] ) !== count( $texts['terms'] ) ) {
		$result['terms'] = $texts['terms']; // Keep source names rather than mismatching them.
	}
	return $result;
}

/* -------------------------------------------------------------------------
 * Polylang: keep the translation bookkeeping out of custom-field sync
 * ---------------------------------------------------------------------- */

add_filter(
	'pll_copy_post_metas',
	function ( $keys ) {
		return array_values( array_filter( (array) $keys, fn( $k ) => 0 !== strpos( $k, '_va_translat' ) ) );
	}
);

/* -------------------------------------------------------------------------
 * Admin: settings page, list column, row actions
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'edit.php?post_type=va_project', 'Auto-translation', 'Auto-translation', 'manage_options', 'va-auto-translate', 'va_at_settings_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'va_auto_translate',
			VA_AT_OPTION,
			array(
				'sanitize_callback' => function ( $value ) {
					$old = (array) get_option( VA_AT_OPTION, array() );
					return array(
						'provider' => in_array( $value['provider'] ?? '', array( 'claude', 'google', 'none' ), true ) ? $value['provider'] : 'claude',
						// An empty field keeps the stored key, so the key is never printed back into the form.
						'api_key'  => '' !== trim( $value['api_key'] ?? '' ) ? trim( $value['api_key'] ) : ( $old['api_key'] ?? '' ),
						'publish'  => empty( $value['publish'] ) ? 0 : 1,
					);
				},
			)
		);
	}
);

function va_at_settings_page() {
	$s         = va_at_settings();
	$constants = defined( 'VA_TRANSLATE_API_KEY' );
	?>
	<div class="wrap">
		<h1>Project auto-translation</h1>
		<p>When a project is published, its missing language versions are created automatically and translated in the background (usually within a few minutes). Categories and tags are translated too.</p>
		<?php if ( empty( $s['api_key'] ) ) : ?>
			<div class="notice notice-warning inline"><p><strong>No API key yet.</strong> Until one is added, new projects get draft copies in the other languages with the original text, marked “Needs translation”.</p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'va_auto_translate' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="va-at-provider">Translation service</label></th>
					<td>
						<select id="va-at-provider" name="<?php echo esc_attr( VA_AT_OPTION ); ?>[provider]" <?php disabled( defined( 'VA_TRANSLATE_PROVIDER' ) ); ?>>
							<option value="claude" <?php selected( $s['provider'], 'claude' ); ?>>Claude (Anthropic) — recommended</option>
							<option value="google" <?php selected( $s['provider'], 'google' ); ?>>Google Cloud Translation</option>
							<option value="none" <?php selected( $s['provider'], 'none' ); ?>>Off</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="va-at-key">API key</label></th>
					<td>
						<?php if ( $constants ) : ?>
							<p>Set in <code>wp-config.php</code>.</p>
						<?php else : ?>
							<input id="va-at-key" type="password" class="regular-text" autocomplete="off" name="<?php echo esc_attr( VA_AT_OPTION ); ?>[api_key]" placeholder="<?php echo $s['api_key'] ? esc_attr( 'Saved — enter a new key to replace it' ) : ''; ?>">
							<p class="description">Claude: an API key from console.anthropic.com. Google: a Cloud Translation API key.</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">Publishing</th>
					<td><label><input type="checkbox" name="<?php echo esc_attr( VA_AT_OPTION ); ?>[publish]" value="1" <?php checked( $s['publish'] ); ?>> Publish translations immediately (otherwise they are saved as drafts for review)</label></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

add_filter(
	'manage_va_project_posts_columns',
	function ( $columns ) {
		$columns['va_translation'] = 'Translation';
		return $columns;
	}
);

add_action(
	'manage_va_project_posts_custom_column',
	function ( $column, $post_id ) {
		if ( 'va_translation' !== $column ) {
			return;
		}
		$status = get_post_meta( $post_id, '_va_translation_status', true );
		$from   = (int) get_post_meta( $post_id, '_va_translated_from', true );
		if ( 'machine' === $status ) {
			printf( 'Machine-translated from %s', esc_html( strtoupper( (string) pll_get_post_language( $from ) ) ) );
		} elseif ( 'failed' === $status ) {
			printf( '<strong style="color:#b32d2e">Needs translation</strong><br><small>%s</small>', esc_html( get_post_meta( $post_id, '_va_translation_error', true ) ) );
		} elseif ( va_at_missing_languages( $post_id ) ) {
			echo 'Missing: ' . esc_html( strtoupper( implode( ', ', va_at_missing_languages( $post_id ) ) ) );
		} else {
			echo '—';
		}
	},
	10,
	2
);

add_filter(
	'post_row_actions',
	function ( $actions, $post ) {
		if ( 'va_project' !== $post->post_type || ! current_user_can( 'edit_post', $post->ID ) ) {
			return $actions;
		}
		$from = (int) get_post_meta( $post->ID, '_va_translated_from', true );
		if ( $from ) {
			$label = 'Re-translate from ' . strtoupper( (string) pll_get_post_language( $from ) );
		} elseif ( 'publish' === $post->post_status && va_at_missing_languages( $post->ID ) ) {
			$label = 'Translate missing languages';
		} else {
			return $actions;
		}
		$url                     = wp_nonce_url( admin_url( 'admin-post.php?action=va_at_translate&post=' . $post->ID ), 'va_at_translate_' . $post->ID );
		$actions['va_translate'] = '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
		return $actions;
	},
	10,
	2
);

add_action(
	'admin_post_va_at_translate',
	function () {
		$post_id = absint( $_GET['post'] ?? 0 );
		check_admin_referer( 'va_at_translate_' . $post_id );
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( 'Not allowed.' );
		}
		$from = (int) get_post_meta( $post_id, '_va_translated_from', true );
		if ( $from ) {
			// Overwrites this translation with a fresh machine translation of its source.
			wp_schedule_single_event( time(), 'va_at_translate', array( $from, pll_get_post_language( $post_id ), $post_id ) );
		} else {
			wp_schedule_single_event( time(), 'va_at_plan', array( $post_id ) );
		}
		spawn_cron();
		wp_safe_redirect( add_query_arg( 'va_at_queued', 1, wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=va_project' ) ) );
		exit;
	}
);

add_action(
	'admin_notices',
	function () {
		if ( ! empty( $_GET['va_at_queued'] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>Translation started — it runs in the background and usually finishes within a few minutes. Reload this page to see it.</p></div>';
		}
	}
);
