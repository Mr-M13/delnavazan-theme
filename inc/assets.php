<?php
/**
 * Front-end assets.
 *
 * @package DelnavazanTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load one stylesheet and the minimal navigation script.
 */
function dzn_theme_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'delnavazan-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		$version
	);

	wp_enqueue_script(
		'delnavazan-navigation',
		get_theme_file_uri( 'assets/js/navigation.js' ),
		array(),
		$version,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'dzn_theme_enqueue_assets' );
