<?php

if ( ! function_exists( 'wpem_get_avatar_rating' ) ) {
	function wpem_get_avatar_rating() {
		return apply_filters( 'wpem_avatar_rating', array(
			'G' => __('G &#8212; Suitable for all audiences','wp-user-profile-avatar'),
          	'PG' => __('PG &#8212; Possibly offensive, usually for audiences 13 and above','wp-user-profile-avatar'),
          	'R' => __('R &#8212; Intended for adult audiences above 17','wp-user-profile-avatar'),
          	'X' => __('X &#8212; Even more mature than above','wp-user-profile-avatar')
		) );
	}
}

if ( ! function_exists( 'wpem_get_default_avatar' ) ) {
	function wpem_get_default_avatar() {
		return apply_filters( 'wpem_default_avatar', array(
			'mystery' => __('Mystery Man','wp-user-profile-avatar'),
	      	'blank' => __('Blank','wp-user-profile-avatar'),
	      	'gravatar_default' => __('Gravatar Logo','wp-user-profile-avatar'),
	      	'identicon' => __('Identicon (Generated)','wp-user-profile-avatar'),
	      	'wavatar' => __('Wavatar (Generated)','wp-user-profile-avatar'),
	      	'monsterid' => __('MonsterID (Generated)','wp-user-profile-avatar'),
	      	'retro' => __('Retro (Generated)','wp-user-profile-avatar')
		) );
	}
}

if ( ! function_exists( 'wpem_get_default_avatar_url' ) ) {
	function wpem_get_default_avatar_url($args = []) {
		
		$size = !empty($args['size']) ? $args['size'] : 'thumbnail';

		$attachment_id = get_option('wp_user_profile_avatar_attachment_id');

		if(!empty($attachment_id))
		{
			$image_attributes = wp_get_attachment_image_src($attachment_id, $size);

			if(!empty($image_attributes))
			{
				return $image_attributes[0];
			}
			else
			{
				return WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-'. $size .'.png';
			}
		}
		else
		{
			return WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-'. $size .'.png';
		}
		
	}
}

if ( ! function_exists( 'wpem_get_user_avatar_url' ) ) {
	function wpem_get_user_avatar_url($user_id, $args = []) 
	{
		$size = !empty($args['size']) ? $args['size'] : 'thumbnail';

		$wp_user_profile_avatar_url = get_user_meta($user_id, 'wp_user_profile_avatar_url', true);

		$attachment_id = get_user_meta($user_id, 'wp_user_profile_avatar_attachment_id', true);

		if(!empty($wp_user_profile_avatar_url))
		{
			return $wp_user_profile_avatar_url;
		}
		else if(!empty($attachment_id))
		{
			$image_attributes = wp_get_attachment_image_src($attachment_id, $size);

			if(!empty($image_attributes))
			{
				return $image_attributes[0];
			}
			else
			{
				return WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-'. $size .'.png';
			}
		}
		else
		{
			return WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-'. $size .'.png';
		}
	}
}

function has_wp_user_avatar($user_id = '')
{
	$attachment_id = get_user_meta($user_id, 'wp_user_profile_avatar_attachment_id', true);

	if(!empty($attachment_id))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function wpua_has_gravatar($id_or_email="", $has_gravatar=0, $user="", $email="") 
{
    $wpem_hash_gravatar = get_option('wpem_hash_gravatar');

    $wp_user_profile_avatar_default = get_option('wp_user_profile_avatar_default');

    if(trim($wp_user_profile_avatar_default) != 'wp_user_profile_avatar')
      return true;
   
    if(!is_object($id_or_email) && !empty($id_or_email)) {
      // Find user by ID or e-mail address
      $user = is_numeric($id_or_email) ? get_user_by('id', $id_or_email) : get_user_by('email', $id_or_email);
      // Get registered user e-mail address
      $email = !empty($user) ? $user->user_email : "";
    }

    if($email == ""){

      if(!is_numeric($id_or_email) and !is_object($id_or_email))
        $email = $id_or_email;
      elseif(!is_numeric($id_or_email) and is_object($id_or_email))
        $email = $id_or_email->comment_author_email;
    }
    if($email!="")
    {
      $hash = md5(strtolower(trim($email)));
      //check if gravatar exists for hashtag using options
      
      if(is_array($wpem_hash_gravatar)){
    
        
      if ( array_key_exists($hash, $wpem_hash_gravatar) and is_array($wpem_hash_gravatar[$hash]) and array_key_exists(date('m-d-Y'), $wpem_hash_gravatar[$hash]) )
      {
        return (bool) $wpem_hash_gravatar[$hash][date('m-d-Y')];
      } 
      
      }
      
      //end
       if ( isset( $_SERVER['HTTPS'] ) && ( 'on' == $_SERVER['HTTPS'] || 1 == $_SERVER['HTTPS'] ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) { 
        $http='https';
      }else{
        $http='http';
      }
      $gravatar = $http.'://www.gravatar.com/avatar/'.$hash.'?d=404';
      
      $data = wp_cache_get($hash);

      if(false === $data) {
        $response = wp_remote_head($gravatar);
        $data = is_wp_error($response) ? 'not200' : $response['response']['code'];
        
        wp_cache_set($hash, $data, $group="", $expire=60*5);
        //here set if hashtag has avatar
        $has_gravatar = ($data == '200') ? true : false;
        if($wpem_hash_gravatar == false){
        $wpem_hash_gravatar[$hash][date('m-d-Y')] = (bool)$has_gravatar;
        add_option('wpem_hash_gravatar',serialize($wpem_hash_gravatar));
        }
        else{

          if(is_array($wpem_hash_gravatar) && !empty($wpem_hash_gravatar)){

              if (array_key_exists($hash, $wpem_hash_gravatar)){

                  unset($wpem_hash_gravatar[$hash]);
                  $wpem_hash_gravatar[$hash][date('m-d-Y')] = (bool)$has_gravatar;
                  update_option('wpem_hash_gravatar',serialize($wpem_hash_gravatar));
              }
              else
              {
                $wpem_hash_gravatar[$hash][date('m-d-Y')] = (bool)$has_gravatar;
                update_option('wpem_hash_gravatar',serialize($wpem_hash_gravatar));

              }

          }

          /*else{
            $wpem_hash_gravatar[$hash][date('m-d-Y')] = (bool)$has_gravatar;
            update_option('wpem_hash_gravatar',serialize($wpem_hash_gravatar));

          }*/
        
        }
      //end
      }
      $has_gravatar = ($data == '200') ? true : false;
      
    }
    else
      $has_gravatar = false;

    // Check if Gravatar image returns 200 (OK) or 404 (Not Found)
    return (bool) $has_gravatar;
}