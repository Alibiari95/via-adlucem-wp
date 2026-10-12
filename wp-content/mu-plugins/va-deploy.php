<?php
/**
 * Plugin Name: Via Adlucem Deploy
 * Description: Pulls the via-adlucem-wp GitHub repository and installs its wp-content files (themes, plugins, mu-plugins).
 * Version:     1.0.0
 *
 * Usage:  va_deploy( 'main' )            // branch name or commit SHA
 *         wp va deploy --ref=main
 */

defined( 'ABSPATH' ) || exit;

const VA_DEPLOY_REPO = 'Alibiari95/via-adlucem-wp';

/**
 * Download the repository at $ref and install it.
 *
 * @param string $ref Branch name or commit SHA.
 * @return array|WP_Error Report of what was installed.
 */
function va_deploy( $ref = 'main' ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$work = trailingslashit( wp_upload_dir()['basedir'] ) . 'va-deploy';
	wp_mkdir_p( $work );
	file_put_contents( $work . '/index.php', '<?php // Silence.' );
	file_put_contents( $work . '/.htaccess', "Require all denied\nDeny from all\n" );

	$url  = 'https://codeload.github.com/' . VA_DEPLOY_REPO . '/zip/' . rawurlencode( $ref );
	$url  = str_replace( '%2F', '/', $url );
	$args = array( 'timeout' => 60 );
	if ( defined( 'VA_GITHUB_TOKEN' ) && VA_GITHUB_TOKEN ) {
		$args['headers'] = array( 'Authorization' => 'token ' . VA_GITHUB_TOKEN );
	}
	$res = wp_remote_get( $url, $args );
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	if ( 200 !== wp_remote_retrieve_response_code( $res ) ) {
		return new WP_Error( 'va_deploy_download', 'GitHub returned HTTP ' . wp_remote_retrieve_response_code( $res ) . ' for ' . $url );
	}

	$zip_file = $work . '/repo.zip';
	$dest     = $work . '/src';
	file_put_contents( $zip_file, wp_remote_retrieve_body( $res ) );
	va_deploy_rrmdir( $dest );

	WP_Filesystem();
	$unzipped = unzip_file( $zip_file, $dest );
	unlink( $zip_file );
	if ( is_wp_error( $unzipped ) ) {
		return $unzipped;
	}

	$roots = glob( $dest . '/*', GLOB_ONLYDIR );
	if ( empty( $roots ) ) {
		return new WP_Error( 'va_deploy_empty', 'Archive is empty.' );
	}
	$root = $roots[0];
	$src  = $root . '/wp-content';

	// Refuse to install anything if a PHP file does not parse.
	$errors = array();
	$it     = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $src, FilesystemIterator::SKIP_DOTS ) );
	foreach ( $it as $file ) {
		if ( 'php' !== $file->getExtension() ) {
			continue;
		}
		try {
			token_get_all( file_get_contents( $file->getPathname() ), TOKEN_PARSE );
		} catch ( ParseError $e ) {
			$errors[] = str_replace( $src, '', $file->getPathname() ) . ':' . $e->getLine() . ' ' . $e->getMessage();
		}
	}
	if ( $errors ) {
		return new WP_Error( 'va_deploy_syntax', 'Syntax errors, nothing installed.', $errors );
	}

	$installed = array();
	foreach ( array( 'themes', 'plugins' ) as $type ) {
		foreach ( glob( $src . '/' . $type . '/*', GLOB_ONLYDIR ) ?: array() as $dir ) {
			// Copy next to the target, then swap with renames so live requests never see a half-copied folder.
			$target = WP_CONTENT_DIR . '/' . $type . '/' . basename( $dir );
			va_deploy_rrmdir( $target . '.va-new' );
			va_deploy_rrmdir( $target . '.va-old' );
			va_deploy_copy( $dir, $target . '.va-new' );
			if ( is_dir( $target ) ) {
				rename( $target, $target . '.va-old' );
			}
			rename( $target . '.va-new', $target );
			va_deploy_rrmdir( $target . '.va-old' );
			$installed[] = $type . '/' . basename( $dir );
		}
	}
	foreach ( glob( $src . '/mu-plugins/*.php' ) ?: array() as $file ) {
		copy( $file, WPMU_PLUGIN_DIR . '/' . basename( $file ) );
		$installed[] = 'mu-plugins/' . basename( $file );
	}

	if ( function_exists( 'opcache_reset' ) ) {
		opcache_reset();
	}
	update_option( 'va_deploy_last', array( 'ref' => $ref, 'time' => time(), 'installed' => $installed ), false );

	return array(
		'ref'       => $ref,
		'installed' => $installed,
		'repo_root' => $root, // Kept until the next deploy so content/ and tools/ can be used.
	);
}

function va_deploy_copy( $from, $to ) {
	wp_mkdir_p( $to );
	foreach ( new DirectoryIterator( $from ) as $item ) {
		if ( $item->isDot() ) {
			continue;
		}
		$target = $to . '/' . $item->getFilename();
		if ( $item->isDir() ) {
			va_deploy_copy( $item->getPathname(), $target );
		} else {
			copy( $item->getPathname(), $target );
		}
	}
}

function va_deploy_rrmdir( $dir ) {
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
	foreach ( $it as $item ) {
		$item->isDir() ? rmdir( $item->getPathname() ) : unlink( $item->getPathname() );
	}
	rmdir( $dir );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'va deploy',
		function ( $args, $assoc ) {
			$result = va_deploy( $assoc['ref'] ?? 'main' );
			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() . ' ' . wp_json_encode( $result->get_error_data() ) );
			}
			WP_CLI::success( 'Installed: ' . implode( ', ', $result['installed'] ) );
		}
	);
}
