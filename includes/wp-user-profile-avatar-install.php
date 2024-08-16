<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class with Installation functions.
 */
class WPUPA_Install {

    /**
     * Install function.
     *
     * @access public static
     * @param
     * @return
     * @since 1.0
     */
    public static function install() {

        //update_option( 'avatar_default', esc_attr( 'mystery' ) );
        update_option( 'wpupa_version', esc_attr( WPUPA_VERSION ) );
    }

}
