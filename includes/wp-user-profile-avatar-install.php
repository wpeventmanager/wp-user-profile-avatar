<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WPUPA_Install class.
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

        update_option( 'wpupa_default', esc_attr( 'mystery' ) );
        update_option( 'wpupa_version', esc_attr( WPUPA_VERSION ) );
    }

}
