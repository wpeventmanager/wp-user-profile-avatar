<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class with User details functions . 
 */
class WPUPA_User {
    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {
        add_filter( 'get_avatar_url', array( $this, 'wpupa_get_user_avatar_url' ), 10, 3 );
        add_filter( 'get_avatar', array( $this, 'wpupa_integrate_user_avatar_to_bbpress_profile' ), 10, 5);
    }
    /**
     * return user profile avatar url.
     *
     * @access public
     * @param $url, $id_or_email, $args
     * @return
     * @since 1.0
     */
    public function wpupa_get_user_avatar_url( $url, $id_or_email, $args ) {
        $wpupa_disable_gravatar = esc_attr( get_option( 'wpupa_disable_gravatar' ) );

        $wpupa_show_avatars = esc_attr( get_option( 'wpupa_show_avatars' ) );

        $wpupa_default = esc_attr( get_option( 'avatar_default' ) );

        if ( ! $wpupa_show_avatars ) {
            return false;
        }
        $user_id = null;
        if ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->comment_author_email ) ) {
                $user_id = $id_or_email->user_id;
            }
        } else {
            if ( is_email( $id_or_email ) ) {
                $user = get_user_by( 'email', $id_or_email );
                if ( $user ) {
                    $user_id = $user->ID;
                }
            } else {
                $user_id = $id_or_email;
            }
        }
 
        // First checking custom avatar.
        if ( wpupa_check_wpupa_url( $user_id ) ) {
            $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) , $args, $url);
        } elseif ( $wpupa_disable_gravatar ) {
            $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail' ), $args , $url);
        } else {
            $has_valid_url = wpupa_check_wpupa_gravatar( $id_or_email );
            if ( ! $has_valid_url ) {
                $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail' ), $args, $url );
            } else {
                if ( $wpupa_default != 'wp_user_profile_avatar' && ! empty( $user_id ) ) {
                    $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) , $args, $url);
                }
            }
        }
        return esc_url( $url );
    }
    /**
     * Integrate the user profile avatar with bbpress profile function.
     *
     * @access public
     * @param $avatar, $id_or_email, $size, $default, $alt
     * @return
     * @since 1.0
     */
    public function wpupa_integrate_user_avatar_to_bbpress_profile( $avatar, $id_or_email, $size, $default, $alt ) {
        
        // Get the user ID
        if ( is_numeric( $id_or_email ) ) {
            $user_id = ( int ) $id_or_email;
        } elseif ( is_object( $id_or_email ) ) {
            $user_id = $id_or_email->user_id;
        } else {
            $user = get_user_by( 'email', $id_or_email );
            $user_id = $user ? $user->ID : 0;
        }

        // Get the user avatar profile picture URL
        $attachment_id = esc_attr( get_user_meta( $user_id, '_wpupa_attachment_id', true ) );
        $image_source = wp_get_attachment_image_src( $attachment_id );
          
        if ( $image_source ) {
            $avatar = "<img alt='{$alt}' src='{$image_source[0]}' class='avatar avatar-{$size} photo' height='{$image_source[2]}' width='{$image_source[1]}' />";
        }

        return $avatar;
    }
}
new WPUPA_User();
