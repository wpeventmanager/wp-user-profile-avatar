<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUPA_Install class.
 */
class WPUPA_Install {

	/**
     * install function.
     *
     * @access public static
     * @param 
     * @return 
     * @since 1.0
     */
	public static function install() 
	{
		update_option( 'wp_user_profile_avatar_tinymce', 1 );
		update_option( 'wp_user_profile_avatar_show_avatars', 1 );
		update_option( 'wp_user_profile_avatar_rating', 'G' );
		update_option( 'wp_user_profile_avatar_default', 'mystery' );
		update_option( 'wp_user_profile_avatar_version', WPUPA_VERSION );
	}
}