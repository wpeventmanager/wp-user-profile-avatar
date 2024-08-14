<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Class to define author box social info by using shortcode
 */
class WPUPA_Authorbox_Socialinfo_Shortcodes {

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode( 'authorbox_social_link', array( $this, 'wpupa_authorbox_social_link' ) );
    }

    /**
     * return current user's social info authorbox function
     *
     * @access public
     * @return
     * @since 1.0
     */
    public function wpupa_authorbox_social_link() {

        $id = get_current_user_id();

        $details = array();

        ob_start();

        include_once WPUPA_PLUGIN_DIR . '/includes/wp-author-box-social-info.php';

        return ob_get_clean();
    }

}

new WPUPA_Authorbox_Socialinfo_Shortcodes();
