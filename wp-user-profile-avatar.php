
<?php
/**
Plugin Name: WP User Profile Avatar

Plugin URI: https://www.wp-eventmanager.com/

Description: User Profile Avatar

Author: WP Event Manager

Author URI: https://www.wp-eventmanager.com

Text Domain: wp-user-profile-avatar

Domain Path: /languages

Version: 1.0

Since: 1.0

Requires WordPress Version at least: 4.1

Copyright: 2020 WP Event Manager

License: GNU General Public License v3.0

License URI: http://www.gnu.org/licenses/gpl-3.0.html

**/

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WPEM_User_Profile_Avatar class.
 */

class WPEM_User_Profile_Avatar {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0
	 */
	private static $_instance = null;

	/**
	 * Main WP User Profile Avatar Instance.
	 *
	 * Ensures only one instance of WP User Profile Avatar is loaded or can be loaded.
	 *
	 * @since  1.0
	 * @static
	 * @see WPEM_User_Profile_Avatar()
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 */

	public function __construct() 
	{
		// Define constants
		define( 'WP_USER_PROFILE_AVATAR_VERSION', '1.0' );
		define( 'WP_USER_PROFILE_AVATAR_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WP_USER_PROFILE_AVATAR_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );


		//Core		
		include( 'includes/wp-user-profile-avatar-install.php' );
		include( 'includes/wp-user-profile-avatar-user.php' );

		
		if ( is_admin() ) {
			include( 'admin/wp-user-profile-avatar-admin.php' );
		}

		// Activation - works with symlinks
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'activate' ) );

		// Actions
		add_action( 'after_setup_theme', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
	}

	public function activate() {

		WPEM_User_Profile_Avatar_Install::install();
	}


	/**
	 * Localisation
	 */
	public function load_plugin_textdomain() {

		$domain = 'wp-user-profile-avatar';       

        $locale = apply_filters('plugin_locale', get_locale(), $domain);

		load_textdomain( $domain, WP_LANG_DIR . "/wp-user-profile-avatar/".$domain."-" .$locale. ".mo" );

		load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Load functions
	 */

	public function include_template_functions() {

		include( 'wp-user-profile-avatar-functions.php' );
	}

			
}

/**
 * Main instance of WP User Profile Avatar.
 *
 * Returns the main instance of WP User Profile Avatar to prevent the need to use globals.
 *
 * @since  1.0
 * @return WPEM_User_Profile_Avatar
 */
function WPEM_Avatar() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return WPEM_User_Profile_Avatar::instance();
}
$GLOBALS['WPEM_User_Profile_Avatar'] =  WPEM_Avatar();