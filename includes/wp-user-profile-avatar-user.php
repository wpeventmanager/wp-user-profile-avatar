<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

/**
 * WPUPA_User class.
 */

class WPUPA_User {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{
		add_filter( 'get_avatar_url', array($this,'get_user_avatar_url'), 10, 3 );
	}

    /**
     * get_user_avatar_url function.
     *
     * @access public
     * @param $url, $id_or_email, $args
     * @return 
     * @since 1.0
     */
	public function get_user_avatar_url($url, $id_or_email, $args)
	{
		$wp_user_profile_avatar_disable_gravatar = get_option('wp_user_profile_avatar_disable_gravatar');

		$wp_user_profile_avatar_show_avatars = get_option('wp_user_profile_avatar_show_avatars');

		$wp_user_profile_avatar_default = get_option('wp_user_profile_avatar_default');	

		if(!$wp_user_profile_avatar_show_avatars)
		{
			return false;
		}

		$user_id = null;
	    if(is_object($id_or_email))
	    {
	       	if(!empty($id_or_email->comment_author_email)) 
	       	{
	          	$user_id = $id_or_email->user_id;
	        }
	    }
	    else
	    {
	      	if ( is_email( $id_or_email ) ) 
	      	{
	        	$user = get_user_by( 'email', $id_or_email );
	        	if($user)
	        	{
	          		$user_id = $user->ID;
	        	}
	      	} 
      		else 
      		{
        		$user_id = $id_or_email;
      		}
    	}

    	// First checking custom avatar.
	    if( check_wpupa_url( $user_id ) ) 
	    {
	    	$url = get_wpupa_url( $user_id, ['size' => 'thumbnail'] );
	    } 
	    else if( $wp_user_profile_avatar_disable_gravatar ) 
	    {
	    	$url = get_wpupa_default_avatar_url(['size' => 'thumbnail']);
	    }
	    else 
	    {
	    	$has_valid_url = check_wpupa_gravatar($id_or_email);
	      	if(!$has_valid_url)
	      	{
	        	$url = get_wpupa_default_avatar_url(['size' => 'thumbnail']);
	      	}
	      	else
	      	{
	      		if($wp_user_profile_avatar_default != 'wp_user_profile_avatar' && !empty($user_id))
				{
					$url = get_wpupa_url( $user_id, ['size' => 'thumbnail' ] );
				}
	      	}
	    }

	    return $url;
	}

}

new WPUPA_User();