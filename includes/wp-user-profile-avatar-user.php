
<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WPEM_User_Profile_Avatar_User class.
 */

class WPEM_User_Profile_Avatar_User {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{
		add_filter( 'get_avatar_url', array($this,'wp_get_avatar_url'), 10, 3 );
	}

	public function wp_get_avatar_url($url, $id_or_email, $args)
	{
		$wp_user_profile_avatar_disable_gravatar = get_option('wp_user_profile_avatar_disable_gravatar');

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
	    if( has_wp_user_avatar( $user_id ) ) 
	    {	
	      	$url = wpem_get_user_avatar_url( $user_id, ['size' => 'thumbnail'] );
	    } 
	    else if( $wp_user_profile_avatar_disable_gravatar ) 
	    {
	      	$url = wpem_get_default_avatar_url(['size' => 'admin']);
	    }
	    else 
	    {
	      	$has_valid_url = wpua_has_gravatar($id_or_email);
	      	if(!$has_valid_url)
	      	{
	        	$url = wpem_get_default_avatar_url(['size' => 'admin']);
	      	}	    
	    }

	    return $url;
	}

	


}

new WPEM_User_Profile_Avatar_User();