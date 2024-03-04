<?php
if (!function_exists('get_wpupa_rating')) {

    /**
     * get_wpupa_rating function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_rating() {
        return apply_filters('wp_user_avatar_rating', array(
            'G' => __('G &#8212; Suitable for all audiences', 'wp-user-profile-avatar'),
            'PG' => __('PG &#8212; Possibly offensive, usually for audiences 13 and above', 'wp-user-profile-avatar'),
            'R' => __('R &#8212; Intended for adult audiences above 17', 'wp-user-profile-avatar'),
            'X' => __('X &#8212; Even more mature than above', 'wp-user-profile-avatar')
        ));
    }
}

if (!function_exists('get_wpupa_file_size')) {

    /**
     * get_wpupa_file_size function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_file_size() {
        return apply_filters('wp_user_avatar_file_size', array(
            '1' => __('1MB', 'wp-user-profile-avatar'),
            '2' => __('2MB', 'wp-user-profile-avatar'),
            '4' => __('4MB', 'wp-user-profile-avatar'),
            '8' => __('8MB', 'wp-user-profile-avatar'),
            '16' => __('16MB', 'wp-user-profile-avatar'),
            '32' => __('32MB', 'wp-user-profile-avatar'),
            '64' => __('64MB', 'wp-user-profile-avatar'),
            '128' => __('128MB', 'wp-user-profile-avatar'),
            '256' => __('256MB', 'wp-user-profile-avatar'),
            '512' => __('512MB', 'wp-user-profile-avatar'),
            '1024' => __('1024MB', 'wp-user-profile-avatar')
        ));
    }

}

if (!function_exists('get_wpupa_default_avatar')) {

    /**
     * get_wpupa_default_avatar function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_default_avatar() {
        return apply_filters('wp_user_default_avatar', array(
            'mystery' => __('Mystery Man', 'wp-user-profile-avatar'),
            'blank' => __('Blank', 'wp-user-profile-avatar'),
            'gravatar_default' => __('Gravatar Logo', 'wp-user-profile-avatar'),
            'identicon' => __('Identicon (Generated)', 'wp-user-profile-avatar'),
            'wavatar' => __('Wavatar (Generated)', 'wp-user-profile-avatar'),
            'monsterid' => __('MonsterID (Generated)', 'wp-user-profile-avatar'),
            'retro' => __('Retro (Generated)', 'wp-user-profile-avatar')
        ));
    }

}

if (!function_exists('get_wpupa_selected_avatar_url')) {

    /**
     * get_wpupa_selected_avatar_url function used to retrive avatr image based on avatar.
     *
     * @access public
     * @param string
     * @return string
     * @since 1.0.2
     */
    function get_wpupa_selected_avatar_url($name) {
        $avatar_urls = apply_filters('wp_user_default_avatar_urls', array(
            'mystery' => 'http://2.gravatar.com/avatar/?s=32&d=mystery&r=g&forcedefault=1',
            'blank' => 'http://2.gravatar.com/avatar/?s=32&d=blank&r=g&forcedefault=1',
            'gravatar_default' => 'http://2.gravatar.com/avatar/?s=32&r=g&forcedefault=1',
            'identicon' => 'http://2.gravatar.com/avatar/?s=32&d=identicon&r=g&forcedefault=1',
            'wavatar' => 'http://2.gravatar.com/avatar/?s=32&d=wavatar&r=g&forcedefault=1',
            'monsterid' => 'http://2.gravatar.com/avatar/?s=32&d=monsterid&r=g&forcedefault=1',
            'retro' => 'http://2.gravatar.com/avatar/?s=32&d=retro&r=g&forcedefault=1',
        ));

        return $avatar_urls[$name];
    }

}

if (!function_exists('get_wpupa_default_avatar_url')) {

    /**
     * get_wpupa_default_avatar_url function.
     *
     * @access public
     * @param $args
     * @return string
     * @since 1.0
     */
    function get_wpupa_default_avatar_url($args = []) {

        $size = !empty($args['size']) ? $args['size'] : 'thumbnail';
        $user_id = !empty($args['user_id']) ? $args['user_id'] : '';
        $wpupa_default = get_option('wpupa_default');
        $avatar_size = get_option('avatar_size');
        if($avatar_size){
            $size = get_option('avatar_size');
        }
        if ($wpupa_default == 'wp_user_profile_avatar' || $size == 'admin') {
            $attachment_id = get_option('wpupa_attachment_id');

            if (!empty($attachment_id)) {
                $image_attributes = wp_get_attachment_image_src($attachment_id, $size);

                if (!empty($image_attributes)) {
                    return $image_attributes[0];
                } else {
                    return WPUPA_PLUGIN_URL . '/assets/images/wp-user-' . $size . '.png';
                }
            } else {
                return WPUPA_PLUGIN_URL . '/assets/images/wp-user-' . $size . '.png';
            }
        } else {
            if (!empty($wpupa_default)) {
                if ($size == 'admin') {
                    return WPUPA_PLUGIN_URL . '/assets/images/wp-user-' . $size . '.png';
                } else if ($size == 'original') {
                    $size_no = 512;
                } else if ($size == 'medium') {
                    $size_no = 150;
                } else if ($size == 'thumbnail') {
                    $size_no = 150;
                } else {
                    $size_no = 32;
                }

                $avatar = get_avatar('unknown@gravatar.com', $size_no, $wpupa_default);

                preg_match('%<img.*?src=["\'](.*?)["\'].*?/>%i', $avatar, $matches);

                if (!empty($matches[1])) {
                    return $matches[1] . 'forcedefault=1';
                } else {
                    return WPUPA_PLUGIN_URL . '/assets/images/wp-user-' . $size . '.png';
                }
            } else {
                return WPUPA_PLUGIN_URL . '/assets/images/wp-user-' . $size . '.png';
            }
        }
    }

}

if (!function_exists('get_wpupa_url')) {

    /**
     * get_wpupa_url function.
     *
     * @access public
     * @param $user_id, $args
     * @return string
     * @since 1.0
     */
    function get_wpupa_url($user_id, $args = []) {
        $size = !empty($args['size']) ? $args['size'] : 'thumbnail';

        $wpupa_url = esc_url(get_user_meta($user_id, '_wpupa_url', true));

        $attachment_id = esc_attr(get_user_meta($user_id, '_wpupa_attachment_id', true));

        $wpupa_default = esc_attr(get_user_meta($user_id, '_wpupa_default', true));

        $wpupa_size = esc_attr(get_user_meta($user_id, 'wpupa-size', true));

        add_image_size( 'wpupavatar-default', $wpupa_size, $wpupa_size, true ); 

        if (!empty($wpupa_url)) {
            return $wpupa_url;
        } else if (!empty($attachment_id)) {

                $image_attributes = wp_get_attachment_image_src($attachment_id, $size);

            if (!empty($image_attributes)) {
                if($size == 'wpupavatar-default') {
                    return $image_attributes;
                } else {
                    return $image_attributes[0];
                }
            } else {
                return get_wpupa_default_avatar_url(['user_id' => $user_id, 'size' => $size]);
            }
        } else {
            return get_wpupa_default_avatar_url(['user_id' => $user_id, 'size' => $size]);
        }

    }

}

if (!function_exists('check_wpupa_url')) {

    /**
     * check_wpupa_url function.
     *
     * @access public
     * @param $user_id
     * @return boolean
     * @since 1.0
     */
    function check_wpupa_url($user_id = '') {
        $attachment_url = esc_url(get_user_meta($user_id, '_wpupa_url', true));

        $attachment_id = esc_attr(get_user_meta($user_id, '_wpupa_attachment_id', true));

        $wpupa_default = esc_attr(get_user_meta($user_id, '_wpupa_default', true));

        if (!empty($attachment_url) || !empty($attachment_id)) {
            return true;
        } else {
            return false;
        }
    }

}

if (!function_exists('check_wpupa_gravatar')) {

    /**
     * check_wpupa_gravatar function.
     *
     * @access public
     * @param $id_or_email, $check_gravatar, $user, $email
     * @return boolean 
     * @since 1.0
     */
    function check_wpupa_gravatar($id_or_email = "", $check_gravatar = 0, $user = "", $email = "") {
        $wp_user_hash_gravatar = get_option('wp_user_hash_gravatar');

        $wpupa_default = get_option('wpupa_default');

        if (trim($wpupa_default) != 'wp_user_profile_avatar')
            return true;

        if (!is_object($id_or_email) && !empty($id_or_email)) {
// Find user by ID or e-mail address
            $user = is_numeric($id_or_email) ? get_user_by('id', $id_or_email) : get_user_by('email', $id_or_email);
// Get registered user e-mail address
            $email = !empty($user) ? $user->user_email : "";
        }

        if ($email == "") {

            if (!is_numeric($id_or_email) and!is_object($id_or_email))
                $email = $id_or_email;
            elseif (!is_numeric($id_or_email) and is_object($id_or_email))
                $email = $id_or_email->comment_author_email;
        }
        if ($email != "") {
            $hash = md5(strtolower(trim($email)));
//check if gravatar exists for hashtag using options

            if (is_array($wp_user_hash_gravatar)) {


                if (array_key_exists($hash, $wp_user_hash_gravatar) and is_array($wp_user_hash_gravatar[$hash]) and array_key_exists(date('m-d-Y'), $wp_user_hash_gravatar[$hash])) {
                    return (bool) $wp_user_hash_gravatar[$hash][date('m-d-Y')];
                }
            }

//end
            if (isset($_SERVER['HTTPS']) && ( 'on' == $_SERVER['HTTPS'] || 1 == $_SERVER['HTTPS'] ) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']) {
                $http = 'https';
            } else {
                $http = 'http';
            }
            $gravatar = $http . '://www.gravatar.com/avatar/' . $hash . '?d=404'; 

            $data = wp_cache_get($hash);

            if (false === $data) {
                $response = wp_remote_head($gravatar);
                $data = is_wp_error($response) ? 'not200' : $response['response']['code'];

                wp_cache_set($hash, $data, $group = "", $expire = 60 * 5);
                //here set if hashtag has avatar
                $check_gravatar = ($data == '200') ? true : false;
                if ($wp_user_hash_gravatar == false) {
                    $wp_user_hash_gravatar[$hash][date('m-d-Y')] = (bool) $check_gravatar;
                    add_option('wp_user_hash_gravatar', serialize($wp_user_hash_gravatar));
                } else {

                    if (is_array($wp_user_hash_gravatar) && !empty($wp_user_hash_gravatar)) {

                        if (array_key_exists($hash, $wp_user_hash_gravatar)) {

                            unset($wp_user_hash_gravatar[$hash]);
                            $wp_user_hash_gravatar[$hash][date('m-d-Y')] = (bool) $check_gravatar;
                            update_option('wp_user_hash_gravatar', serialize($wp_user_hash_gravatar));
                        } else {
                            $wp_user_hash_gravatar[$hash][date('m-d-Y')] = (bool) $check_gravatar;
                            update_option('wp_user_hash_gravatar', serialize($wp_user_hash_gravatar));
                        }
                    }
                }
            //end
            }
            $check_gravatar = ($data == '200') ? true : false;
        } else
            $check_gravatar = false;

            // Check if Gravatar image returns 200 (OK) or 404 (Not Found)
        return (bool) $check_gravatar;
    }

}

if (!function_exists('get_wpupa_image_size')) {

    /**
     * get_wpupa_image_size function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_image_sizes() {
        return apply_filters('wp_image_sizes', array(
            'original' => __('Original', 'wp-user-profile-avatar'),
            'large' => __('Large', 'wp-user-profile-avatar'),
            'medium' => __('Medium', 'wp-user-profile-avatar'),
            'thumbnail' => __('Thumbnail', 'wp-user-profile-avatar'),
        ));
    }

}

if (!function_exists('get_wpupa_image_alignment')) {

    /**
     * get_wpupa_image_alignment function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_image_alignment() {
        return apply_filters('wp-image-alignment', array(
            'aligncenter' => __('Center', 'wp-user-profile-avatar'),
            'alignleft' => __('Left', 'wp-user-profile-avatar'),
            'alignright' => __('Right', 'wp-user-profile-avatar'),
        ));
    }

}

if (!function_exists('get_wpupa_image_link_to')) {

    /**
     * get_wpupa_image_link_to function.
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function get_wpupa_image_link_to() {
        return apply_filters('wp-image-link-to', array(
            'none' => __('None', 'wp-user-profile-avatar'),
            'image' => __('Image File', 'wp-user-profile-avatar'),
            'attachment' => __('Attachment Page', 'wp-user-profile-avatar'),
            'custom' => __('Custom URL', 'wp-user-profile-avatar'),
        ));
    }

}
// Restrict access to Media Library (users can only see/select own media)

if (!function_exists('wpb_show_current_user_attachments')) {

    add_filter('ajax_query_attachments_args', 'wpb_show_current_user_attachments');

    /**
     * wpb_show_current_user_attachments function.
     * 
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function wpb_show_current_user_attachments($query) {
        $user_id = get_current_user_id();
        if ($user_id) {
            $query['author'] = $user_id;
            $query['subscriber'] = $user_id;
            $query['contributor'] = $user_id;
            $query['editor'] = $user_id;
        }
        return $query;
    }

}

if (!function_exists('wpupa_file_size_limit')) {

    /**
     * wpupa_file_size_limit function.
     * 
     * Limit upload size for non-admins. Admins get the default limit
     *
     * @access public
     * @param 
     * @return array
     * @since 1.0
     */
    function wpupa_file_size_limit($limit) {
        if (!current_user_can('manage_options')) {
            $wpupa_file_size = get_option('wpupa_file_size');
            $limit = $wpupa_file_size * 1048576;
        }
        return $limit;
    }

    add_filter('upload_size_limit', 'wpupa_file_size_limit');
}