<?php

/**
 * user name change function Page
 */

namespace WPUPA_WpUserNameChange; 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPUPA_WpUserNameChange {
    
    public $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
       
    }
	
    /**
     * list all user function.
     *
     * @access public
     * @param
     * @return
     */
    public function wpupa_user_list() {
        $allowed_group = 'manage_options';
        if ( function_exists( 'add_submenu_page' ) ) {
            add_submenu_page( 'users.php', __( 'WP Username Change', 'wp-user-profile-avatar' ), __( 'WP Username Change ', 'wp-user-profile-avatar' ), $allowed_group, 'wpupa_username_change', 'wpupa_username_edit' );
            add_submenu_page( null, __( 'Update', 'wp-user-profile-avatar' ), __( 'Update', 'wp-user-profile-avatar' ), $allowed_group, 'wpupa_username_update', 'wpupa_user_update' );
        }
    }
     /**
     * get all users function.
     *
     * @access public
     * @param
     * @return
     */
    public function wpuser_select() {
        $records = $this->db->get_results( 'SELECT * FROM `' . $this->db->prefix . 'users`' );
        return $records;
    }
    /**
     * update user data function.
     *
     * @access public
     * @param
     * @return
     */
    public function wpuser_update( $id, $name ) {
        $result = $this->db->update(
            $this->db->prefix . 'users',
            array(
                'user_login'   => sanitize_text_field( $name ),
                'display_name' => sanitize_text_field( $name ),
            ),
            array( 'id' => $id )
        );
        return $result;
    }
}
$wpuser = new WPUPA_WpUserNameChange();
