<?php

/**
 * Add New Default Avatar page.
 *
 * @package Add_New_Default_Avatar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WPUPA_Add_New_User
 *
 * Handles adding new default avatars and related functionality.
 */
class WPUPA_Add_New_User {

    /**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'admin_init', array( $this, 'wpupa_admin_init' ) );
        add_filter( 'avatar_defaults', array( $this, 'wpupa_avatar_defaults' ) );
        add_filter( 'update_default_avatar', array( $this, 'wpupa_update_default_avatar' ), 10, 5 );
    }

    /**
     * Initialize admin settings for adding new default avatar.
     */
    public function wpupa_admin_init() {
        register_setting( 'discussion', 'Add_New_User', array( $this, 'wpupa_validate' ) );
        add_settings_field( 'Add_New_User', __( 'Add New Default Avatar', 'wp-user-profile-avatar' ), array( $this, 'wpupa_field_html' ), 'discussion', 'avatars', $args = array() );
    }

    /**
     * HTML output for the settings field to add new default avatar.
     */
    public function wpupa_field_html() {
        $value = get_option(
            'Add_New_User',
            array(
                array(
                    'name' => 'New Avatar',
                    'url'  => 'url',
                ),
            )
        );

        foreach ( $value as $k => $v ) {
            extract( $v );
            echo '<p>';
            echo "<input type='text' name='Add_New_User[" . esc_attr( $k ) . "][name]' value='" . esc_attr( $name ) . "' size='15' />";
            echo "<input type='text' name='Add_New_User[" . esc_attr( $k ) . "][url]' value='" . esc_attr( $url ) . "' size='35' />";
            echo '</p>';
        }

        $add_value = uniqid();
        echo '<p id="Add_New_User">';
        echo "<input type='text' name='Add_New_User[" . esc_attr( $add_value ) . "][name]' value='' size='15' />";
        echo "<input type='text' name='Add_New_User[" . esc_attr( $add_value ) . "][url]' value='' size='35' />";
        echo '</p>';
    }

    /**
     * Validate and sanitize input for new default avatar settings.
     *
     * @param array $input Input values.
     * @return array Sanitized input values.
     */
    public function wpupa_validate( $input ) {
        foreach ( $input as $k => $v ) {
            $input[ $k ]['name'] = esc_attr( $v['name'] );
            $input[ $k ]['url']  = esc_url( $v['url'] );
            if ( empty( $v['name'] ) && empty( $v['url'] ) ) {
                unset( $input[ $k ] );
            }
        }
        return $input;
    }

    /**
     * Add new default avatars to the avatar defaults list.
     *
     * @param array $avatar_defaults Existing avatar defaults.
     * @return array Modified avatar defaults.
     */
    public function wpupa_avatar_defaults( $avatar_defaults ) {
        $opts = get_option( 'Add_New_User', false );
        if ( $opts ) {
            foreach ( $opts as $k => $v ) {
                $av                     = html_entity_decode( $v['url'] );
                $avatar_defaults[ $av ] = $v['name'];
            }
        }
        return $avatar_defaults;
    }

    /**
     * Update default avatar URL based on user's email.
     *
     * @param string $avatar Default avatar HTML.
     * @param int|string|object $id_or_email The user identifier (ID, email, or object).
     * @param int $size Avatar size.
     * @param string $default Default avatar URL.
     * @return string Updated avatar HTML.
     */
    public function wpupa_update_default_avatar( $avatar, $id_or_email, $size, $default = '' ) {

        if ( is_numeric( $id_or_email ) ) {
            $email   = get_userdata( $id_or_email )->user_email;
            $user_id = (int) $id_or_email;
        } elseif ( is_object( $id_or_email ) ) {
            $email   = $id_or_email->comment_author_email;
            $user_id = (int) $id_or_email->user_id;
        } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
            $email   = $id_or_email;
            $user_id = $user->ID;
        }
        if ( isset( $user_id['local_avatar'] ) ) {
            $local_avatars = get_user_meta( $user_id, 'local_avatar', true );
        }
        if ( ! empty( $local_avatars ) && ( isset( $GLOBALS['hook_suffix'] ) && $GLOBALS['hook_suffix'] != 'options-discussion.php' ) ) {
            remove_filter( 'update_default_avatar', array( $this, 'wpupa_update_default_avatar' ), 88, 5 );
            return $avatar;
        }
        $avatar = str_replace( '%size%', $size, $avatar );
        $avatar = str_replace( urlencode( '%size%' ), $size, $avatar );
        return $avatar;
    }

}

// Instantiate the class
$Add_New_User = new WPUPA_Add_New_User();
