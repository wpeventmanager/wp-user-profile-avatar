<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WPEM_User_Profile_Avatar_Install class.
 */
class WPEM_User_Profile_Avatar_Install {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{

	}

	public function install() 
	{
		update_option( 'wp_user_profile_avatar_tinymce', 1 );
		update_option( 'wp_user_profile_avatar_show_avatars', 1 );
		update_option( 'wp_user_profile_avatar_rating', 'G' );
		update_option( 'wp_user_profile_avatar_default', 'mystery' );
		update_option( 'wp_user_profile_avatar_version', WP_USER_PROFILE_AVATAR_VERSION );
	}
}