<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Load external compatibility tweaks.
 */

// check Elementor Plugin istallation.
if ( ! function_exists( 'wpupa_is_elementor_installed' ) ) {

	function wpupa_is_elementor_installed() {
		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

require_once 'elementor.php';
