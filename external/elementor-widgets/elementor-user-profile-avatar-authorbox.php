<?php

namespace WPUserProfileAvatar\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Elementor Meeting Detail
 *
 * Elementor widget for meeting detail
 */
class Elementor_WPUPA_Authorbox extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'wp-user-profile-avatar-authorbox';
    }

    /**
     * Retrieve the widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'WP User Profile Avatar Authorbox', 'wp-user-profile-avatar' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve shortcode widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'fa fa-user';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return array( 'wp-user-profile-avatar-authorbox', 'code' );
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'wp-user-profile-avatar-categories' );
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_shortcode',
            array(
                'label' => __( 'Wp User Profile Avatar Authorbox', 'wp-user-profile-avatar' ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        echo '<div class="elementor-user-profile-avatar-authorbox">';
        echo do_shortcode( '[authorbox_social_info]' );
        echo do_shortcode( '[authorbox_social_link]' );
        echo '</div>';
    }

}
