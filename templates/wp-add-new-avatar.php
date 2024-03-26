<?php

/**
 * Add New Default Avatar  page.
 *
 * @package Add New Default Avatar page.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php

$Add_New_User = new Add_New_User();

class Add_New_User {

    public function admin_init() {
        register_setting( 'discussion', 'Add_New_User', array( $this, 'validate' ) );
        add_settings_field( 'Add_New_User', __( 'Add New Default Avatar', 'anda' ), array( $this, 'field_html' ), 'discussion', 'avatars', $args = array() );
    }

    public function field_html() {
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

    function validate( $input ) {
        foreach ( $input as $k => $v ) {
            $input[ $k ]['name'] = esc_attr( $v['name'] );
            $input[ $k ]['url']  = esc_url( $v['url'] );
            if ( empty( $v['name'] ) && empty( $v['url'] ) ) {
                unset( $input[ $k ] );
            }
        }
        return $input;
    }

    function avatar_defaults( $avatar_defaults ) {
        $opts = get_option( 'Add_New_User', false );
        if ( $opts ) {
            foreach ( $opts as $k => $v ) {
                $av                     = html_entity_decode( $v['url'] );
                $avatar_defaults[ $av ] = $v['name'];
            }
        }
        return $avatar_defaults;
    }

    public function update_default_avatar( $avatar, $id_or_email, $size, $default = '' ) {

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
            remove_filter( 'update_default_avatar', array( $this, 'update_default_avatar' ), 88, 5 );
            return $avatar;
        }
        $avatar = str_replace( '%size%', $size, $avatar );
        $avatar = str_replace( urlencode( '%size%' ), $size, $avatar );
        return $avatar;
    }

    public function __construct() {
        add_filter( 'admin_init', array( $this, 'admin_init' ) );
        add_filter( 'avatar_defaults', array( $this, 'avatar_defaults' ) );
        add_filter( 'update_default_avatar', array( $this, 'update_default_avatar' ), 10, 5 );
    }

}
