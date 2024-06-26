<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Class to define user details by using shortcode
 */
#[AllowDynamicProperties]
class WPUPA_User_Shortcodes {

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode( 'user_display', array( $this, 'user_display' ) );
    }

    /**
     * user_display function
     *
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function user_display( $atts ) {

        $atts = shortcode_atts(
			array(
				'avatar_size' => '', 
				'avatar_align' => 'left', 
			),
			$atts,
			'user_display'
		);

        $id = get_current_user_id();

        $details = array(
            'first_name'          => esc_attr( get_the_author_meta( 'first_name', $id ) ),
            'last_name'           => esc_attr( get_the_author_meta( 'last_name', $id ) ),
            'description'         => wp_kses_post( get_the_author_meta( 'description', $id ) ),
            'email'               => esc_html( get_the_author_meta( 'email', $id ) ),
            'sabox_social_links'  => get_the_author_meta( 'sabox_social_links', $id ),
            'sabox-profile-image' => esc_url( get_the_author_meta( 'sabox-profile-image', $id ) ),
            'avatar_size'         => $atts['avatar_size'],
			'avatar_align'        => esc_attr( $atts['avatar_align'] ),
        );

        ob_start();

        include_once WPUPA_PLUGIN_DIR . '/templates/wp-display-user.php';

        return ob_get_clean();
    }

}

new WPUPA_User_Shortcodes();
