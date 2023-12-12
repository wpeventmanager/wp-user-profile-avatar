<?php
/**
* Plugin Name: WP User Profile Avatar
* Plugin URI: https://www.wp-eventmanager.com
* Description: WP User Profile Avatar
* Author: WP Event Manager
* Author URI: https://www.wp-eventmanager.com
* Text Domain: wp-user-profile-avatar
* Domain Path: /languages
* Version: 1.0.1
* Since: 1.0.0
* Requires WordPress Version at least: 5.8
* Copyright: 2020 WP Event Manager
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * WP_User_Profile_Avatar class.
 */
class WP_User_Profile_Avatar {

    /**
     * The single instance of the class.
     *
     * @var self
     * @since 1.0.0
     */
    private static $_instance = null;

    /**
     * Main WP User Profile Avatar Instance.
     *
     * Ensures only one instance of WP User Profile Avatar is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WP_User_Profile_Avatar()
     * @return self Main instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {

        // Define constants
        define('WPUPA_VERSION', '1.0.1');
        define('WPUPA_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
        define('WPUPA_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));

        //Includes
        include( 'includes/wp-user-profile-avatar-install.php' );
        include( 'includes/wp-user-profile-avatar-user.php' );
        include( 'wp-user-profile-avatar-functions.php' );
        include_once( 'admin/templates/wp-username-change.php' );
        include_once( 'disable-comments.php' );
        include_once( 'templates/wp-author-box-social-info.php' );
        include_once( 'templates/wp-add-new-avatar.php' );
        include_once( 'templates/wp-avatar-social profile-picture.php' );

        //shortcodes
        include( 'shortcodes/wp-user-profile-avatar-shortcodes.php' );
        include( 'shortcodes/wp-user-display.php' );
        include( 'shortcodes/wp-author-social-info-shortcodes.php' );

        //external 
        include('external/external.php');
        
        include_once( 'templates/wp-author-box-social-info.php' );

        // Activation / deactivation - works with symlinks
        register_activation_hook(basename(dirname(__FILE__)) . '/' . basename(__FILE__), array($this, 'activate'));

        // Actions
        add_action('after_setup_theme', array($this, 'load_plugin_textdomain'));

        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));

        add_action('admin_init', array($this, 'updater'));

        if (is_admin()) {
            include('admin/wp-user-profile-avatar-admin.php');
        }

        // Filters
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'wpupa_settings_link'));

    }

    /**
     * activate function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0.0
     */
    public function activate() {
        //installation process after activating
        WPUPA_Install::install();
    }

    /**
     * updater function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0.0
     */
    public function updater() {
        if (version_compare(WPUPA_VERSION, get_option('wpupa_version'), '>')) {
            WPUPA_Install::update();
            flush_rewrite_rules();
        }
    }

    /**
     * load_plugin_textdomain function.
     *
     * @access public
     * @param
     * @return 
     * @since 1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = 'wp-user-profile-avatar';

        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . "/wp-user-profile-avatar/" . $domain . "-" . $locale . ".mo");

        load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * frontend_scripts function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function frontend_scripts() {

        wp_enqueue_media();

        wp_enqueue_style('wp-user-profile-avatar-frontend', WPUPA_PLUGIN_URL . '/assets/css/frontend.min.css');

        wp_register_script('wp-user-profile-avatar-frontend-avatar', WPUPA_PLUGIN_URL . '/assets/js/frontend-avatar.min.js', array('jquery'), WPUPA_VERSION, true);
        wp_enqueue_script('wp-user-profile-avatar-frontend-avatar-custom', WPUPA_PLUGIN_URL . '/assets/js/frontend-custom.js', array('jquery'), WPUPA_VERSION, true);
        wp_localize_script('wp-user-profile-avatar-frontend-avatar', 'wp_user_profile_avatar_frontend_avatar', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'wp_user_profile_avatar_security' => wp_create_nonce("_nonce_user_profile_avatar_security"),
            'media_box_title' => __('Choose Image: Default Avatar', 'wp-user-profile-avatar'),
            'default_avatar' => WPUPA_PLUGIN_URL . '/assets/images/wp-user-thumbnail.png',
                )
        );
    }

    /**
     * wpupa_settings_link function.
     * 
     * Create link on plugin page for wp user profile avatar plugin settings.
     * 
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public static function wpupa_settings_link($links) {
        $links[] = '<a href="' . admin_url('profile.php') . '">' . __('Settings', 'wp-user-profile-avatar') . '</a>';
        return $links;
    }
}

/**
 * Main instance of WP Event Manager Zoom.
 *
 * Returns the main instance of WP Event Manager Zoom to prevent the need to use globals.
 *
 * @since 1.0.0
 * @return WP_Event_Manager_Pluginslug
 */
function WPUPA() {
    // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
    return WP_User_Profile_Avatar::instance();
}
$GLOBALS['wp_user_profile_avatar'] = WPUPA();