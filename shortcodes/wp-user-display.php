<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Class to define user details by using shortcode
 */
class WPUPA_User_Shortcodes {

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode( 'user_display', array( $this, 'wpupa_user_display' ) );
    }

    /**
     * display user profile avatar function
     *
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function wpupa_user_display( $atts ) {

        $atts = shortcode_atts(
			array(
                'id' => '',
                'email' => '',
				'avatar_size' => '', 
				'avatar_align' => 'center', 
			),
			$atts,
			'user_display'
		);

        // Ensure either 'id' or 'email' is provided
        if ( empty( $atts['id'] ) && empty( $atts['email'] ) ) {
            return 'Error: Please provide either a user ID or email.';
        }

        // Determine user ID
        if ( ! empty( $atts['id'] ) ) {
            $id = intval( $atts['id'] );
        } elseif ( ! empty( $atts['email'] ) ) {
            $user = get_user_by( 'email', $atts['email'] );
            $id = $user ? $user->ID : 0;
        }

        // If no user found, return an error message
        if ( $id == 0 ) {
            return 'Error: User not found.';
        }

        // Fetch user profile image
        $attachment = get_user_meta( $id, '_wpupa_attachment_id', true );
        $image_source = wp_get_attachment_image_src( $attachment );
 
        if ( $details['avatar_size'] != '' && $image_source ) {
            $size = $details['avatar_size'];
            $image_source[1] = $size;
        }

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
