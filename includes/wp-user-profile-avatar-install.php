<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WP_User_Profile_Avatar_Install class.
 */
class WP_User_Profile_Avatar_Install {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{

	}

    /**
     * install function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
	public function install() 
	{
		update_option( 'wp_user_profile_avatar_tinymce', 1 );
		update_option( 'wp_user_profile_avatar_show_avatars', 1 );
		update_option( 'wp_user_profile_avatar_rating', 'G' );
		update_option( 'wp_user_profile_avatar_default', 'mystery' );
		update_option( 'wp_user_profile_avatar_version', WP_USER_PROFILE_AVATAR_VERSION );
	}
}