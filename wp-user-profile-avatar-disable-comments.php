<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', 'wpupa_init_filters' );
/**
 * filter for disable comments function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_init_filters() {

    $options = get_option( 'wpupa_disable_comments_mode', false );

    if ( is_array( $options ) && isset( $options['remove_everywhere'] ) ) {

        add_action( 'template_redirect', 'wpupa_filter_admin_bar' );
        add_action( 'admin_init', 'wpupa_filter_admin_bar' );
    }
    add_action( 'wp_loaded', 'wpupa_init_wploaded_filters' );
}
/**
 * load filtered comment on frontend function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_init_wploaded_filters() {
    
    $mode = get_option( 'wpupa_disable_comments_mode', '' );
    $disabled_post_types = get_option( 'wpupa_disabled_post_types', array() );

    if ( ! empty( $disabled_post_types ) ) {
        foreach ( $disabled_post_types as $type ) {
            if ( post_type_supports( $type, 'comments' ) ) {
               
                remove_post_type_support( $type, 'comments' );
                remove_post_type_support( $type, 'trackbacks' );
            }
        }
    }

    // Filters for the admin only.
    if ( is_admin() ) {

        if ( 'remove_everywhere' === $mode ) {
            add_action( 'admin_menu', 'wpupa_filter_admin_menu' );
        }
    }
    // Filters for front end only.
    else {
        add_action( 'template_redirect', 'wpupa_check_comment_template' );

        if ( 'remove_everywhere' === $mode ) {
            add_filter( 'feed_links_show_comments_feed', '__return_false' );
        }
    }
}
/**
 * set discussion comment settings function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_discussion_settings_allowed() {
    if ( defined( 'DISABLE_COMMENTS_ALLOW_DISCUSSION_SETTINGS' ) && DISABLE_COMMENTS_ALLOW_DISCUSSION_SETTINGS == true ) {
        return true;
    }
}
/**
 * enable disable admin menu for comment function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_filter_admin_menu() {
    global $pagenow;

    if ( $pagenow == 'comment.php' || $pagenow == 'edit-comments.php' ) {
        wp_die( esc_attr__( 'Comments are closed.', 'wp-user-profile-avatar' ), '', array( 'response' => 403 ) );
    }

    remove_menu_page( 'edit-comments.php' );

    if ( ! wpupa_discussion_settings_allowed() ) {
        if ( $pagenow == 'options-discussion.php' ) {
            wp_die( esc_attr__( 'Comments are closed.', 'wp-user-profile-avatar' ), '', array( 'response' => 403 ) );
        }

        remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    }
}

/**
 * return array on which post type commets are disable function.
 *
 * @access public
 * @param $type
 * @return
 * @since 1.0
 */
function wpupa_is_post_type_disabled( $type ) {
    $disabled_post_types = get_option( 'wpupa_disabled_post_types', array() );
    return in_array( $type, $disabled_post_types );
}

/**
 * check comment template function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */

function wpupa_check_comment_template() {
    $mode = get_option( 'wpupa_disable_comments_mode', '' );
    if ( is_singular() && (  'remove_everywhere' === $mode || wpupa_is_post_type_disabled( get_post_type() ) ) ) {
        if ( ! defined( 'DISABLE_COMMENTS_REMOVE_COMMENTS_TEMPLATE' ) || DISABLE_COMMENTS_REMOVE_COMMENTS_TEMPLATE == true ) {
           
            add_filter( 'comments_template', 'wpupa_dummy_comments_template' );
        }
        // Remove comment-reply script for themes that include it indiscriminately.
        wp_deregister_script( 'comment-reply' );
    }
}

/**
 * dummy comment template function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_dummy_comments_template() {
    return dirname( __FILE__ ) . '/templates/comments-template.php';
}

/**
 * filter for admin bar function.
 *
 * @access public
 * @param 
 * @return
 * @since 1.0
 */
function wpupa_filter_admin_bar() {
    if ( is_admin_bar_showing() ) {
        // Remove comments links from admin bar.
        remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
        if ( is_multisite() ) {
            add_action( 'admin_bar_menu', 'remove_network_comment_links', 500 );
        }
    }
}

