<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WPUPA_User class. 
 */
#[AllowDynamicProperties]
class WPUPA_User {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {
        add_filter( 'get_avatar_url', array( $this, 'wpupa_get_user_avatar_url' ), 10, 3 );
    }

    /**
     * wpupa_get_user_avatar_url function.
     *
     * @access public
     * @param $url, $id_or_email, $args
     * @return
     * @since 1.0
     */
    public function wpupa_get_user_avatar_url( $url, $id_or_email, $args ) {
        $wpupa_disable_gravatar = esc_attr( get_option( 'wpupa_disable_gravatar' ) );

        $wpupa_show_avatars = esc_attr( get_option( 'wpupa_show_avatars' ) );

        $wpupa_default = esc_attr( get_option( 'wpupa_default' ) );

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
            $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) );
        } elseif ( $wpupa_disable_gravatar ) {
            $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail' ) );
        } else {
            $has_valid_url = wpupa_check_wpupa_gravatar( $id_or_email );
            if ( ! $has_valid_url ) {
                $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail' ) );
            } else {
                if ( $wpupa_default != 'wp_user_profile_avatar' && ! empty( $user_id ) ) {
                    $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) );
                }
            }
        }

        return esc_url( $url );
    }

}

new WPUPA_User();
