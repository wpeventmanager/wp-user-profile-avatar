<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WPUPA_Admin class.
 */
class WPUPA_Admin {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {

        include_once( 'wp-user-profile-avatar-settings.php' );
        $this->settings_page = new WPUPA_Settings();

        $wpupa_tinymce = get_option('wpupa_tinymce');
        if ($wpupa_tinymce) {
            add_action('init', array($this, 'wpupa_add_buttons'));
        }

        include_once( 'templates/wp-username-change.php' );

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        add_action('show_user_profile', array($this, 'wpupa_add_fields'));
        add_action('edit_user_profile', array($this, 'wpupa_add_fields'));

        add_action('personal_options_update', array($this, 'wpupa_save_fields'));
        add_action('edit_user_profile_update', array($this, 'wpupa_save_fields'));

        add_action('admin_init', array($this, 'allow_contributor_subscriber_uploads'));

        add_action('init', array($this, 'thickbox_model_init'));
        add_action('wp_ajax_thickbox_model_view', array($this, 'thickbox_model_view'));
        add_action('wp_ajax_nopriv_thickbox_model_view', array($this, 'thickbox_model_view'));

        add_action('admin_init', array($this, 'init_size'));

    }

    /**
     * admin_enqueue_scripts function.
     * enqueue style and script for admin
     * @access public
     * @param 
     * @return 
     * @since 1.0.0
     */
    public function admin_enqueue_scripts() {
        global $pagenow;

        wp_register_style('wp-user-profile-avatar-backend', WPUPA_PLUGIN_URL . '/assets/css/backend.min.css');

        wp_register_script('wp-user-profile-avatar-admin-avatar', WPUPA_PLUGIN_URL . '/assets/js/admin-avatar.min.js', array('jquery'), WPUPA_VERSION, true);

        wp_localize_script('wp-user-profile-avatar-admin-avatar', 'wp_user_profile_avatar_admin_avatar', array(
            'thinkbox_ajax_url' => admin_url('admin-ajax.php') . '?height=600&width=770&action=thickbox_model_view',
            'thinkbox_title' => '',
            'icon_title' => __('WP User Profile Avatar', 'wp-user-profile-avatar'),
            'wp_user_profile_avatar_security' => wp_create_nonce("_nonce_user_profile_avatar_security"),
            'media_box_title' => __('Choose Image: Default Avatar', 'wp-user-profile-avatar'),
            'default_avatar' => WPUPA_PLUGIN_URL . '/assets/images/wp-user-thumbnail.png',
                )
        );

        wp_enqueue_style('wp-user-profile-avatar-backend');
        wp_enqueue_script('wp-user-profile-avatar-admin-avatar');
    }

    /**
     * wpupa_add_fields function.
     *
     * @access public
     * @param $user
     * @return 
     * @since 1.0
     */
    public function wpupa_add_fields($user) {
        wp_enqueue_media();

        wp_enqueue_style('wp-user-profile-avatar-backend');

        wp_enqueue_script('wp-user-profile-avatar-admin-avatar');

        $user_id = get_current_user_id();

        $wpupa_original = get_wpupa_url($user->ID, ['size' => 'original']);
        $wpupa_thumbnail = get_wpupa_url($user->ID, ['size' => 'thumbnail']);

        $wpupa_attachment_id = get_user_meta($user->ID, '_wpupa_attachment_id', true);
        $wpupa_url = get_user_meta($user->ID, '_wpupa_url', true);

        $wpupa_file_size = get_user_meta($user->ID, 'wpupa_file_size', true);
        $wpupa_size = get_user_meta($user->ID, 'wpupa_size', true);
        $wpupa_tinymce = get_option('wpupa_tinymce');
        $wpupa_allow_upload = get_option('wpupa_allow_upload');
        $wpupa_disable_gravatar = get_option('wpupa_disable_gravatar');

        // Custom uplaod file size
        $wpupa_max_size = get_option('wpem_max_file_size');
        if ( ! $wpupa_max_size ) {
            $wpupa_max_size = 64 * 1024 * 1024;
        }
        $wpupa_max_size = $wpupa_max_size / 1024 / 1024;
        $wpupa_upload_sizes = array( 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024 ); 
        $wpupa_current_max_size = self::wpupa_get_closest($wpupa_max_size, $wpupa_upload_sizes);

        include('templates/user-profile-avatar-settings.php');

    }

    /**
     * wpupa_save_fields function.
     *
     * @access public
     * @param $user_id
     * @return 
     * @since 1.0
     */
    public function wpupa_save_fields($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            
            if (isset($_POST['wpupa_url'])) {
                $wpupa_url = esc_url_raw($_POST['wpupa_url']);
            }
            if (isset($_POST['wpupa_attachment_id'])) {
                $wpupa_attachment_id = absint($_POST['wpupa_attachment_id']);
            }

            if (isset($_POST['wpupa_file_size'])) {
                $wpupa_file_size = esc_attr($_POST['wpupa_file_size']);
                update_user_meta($user_id, 'wpupa_file_size', $wpupa_file_size);
            }

            if (isset($_POST['wpupa_size'])) {
                $wpupa_size = absint($_POST['wpupa_size']);
                update_user_meta($user_id, 'wpupa_size', $wpupa_size);
            }
            

            if (isset($wpupa_url, $wpupa_attachment_id)) {
                update_user_meta($user_id, '_wpupa_attachment_id', $wpupa_attachment_id);
                update_user_meta($user_id, '_wpupa_url', $wpupa_url);
            }
            
            $wpupa_tinymce = !empty($_POST['wpupa_tinymce']) ? sanitize_text_field($_POST['wpupa_tinymce']) : '';

            $wpupa_allow_upload = !empty($_POST['wpupa_allow_upload']) ? sanitize_text_field($_POST['wpupa_allow_upload']) : '';

            $wpupa_disable_gravatar = !empty($_POST['wpupa_disable_gravatar']) ? sanitize_text_field($_POST['wpupa_disable_gravatar']) : '';
            
            update_option('wpupa_tinymce', $wpupa_tinymce);
            update_option('wpupa_allow_upload', $wpupa_allow_upload);
            update_option('wpupa_disable_gravatar', $wpupa_disable_gravatar);
            
            if (!empty($wpupa_attachment_id) || !empty($wpupa_url)) {
                update_user_meta($user_id, '_wpupa_default', 'wp_user_profile_avatar');
            } else {
                update_user_meta($user_id, '_wpupa_default', '');
            }
        } else {
            status_header('403');
            die();
        }
    }



    /**
     * wpupa_add_buttons function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function wpupa_add_buttons() {
        // Add only in Rich Editor mode
        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array($this, 'wpupa_add_tinymce_plugin'));
            add_filter('mce_buttons', array($this, 'wpupa_register_button'));
        }
    }

    /**
     * wpupa_register_button function.
     *
     * @access public
     * @param $buttons
     * @return 
     * @since 1.0
     */
    public function wpupa_register_button($buttons) {
        array_push($buttons, 'separator', 'wp_user_profile_avatar_shortcodes');
        return $buttons;
    }

    /**
     * wpupa_add_tinymce_plugin function.
     *
     * @access public
     * @param $plugins
     * @return 
     * @since 1.0
     */
    public function wpupa_add_tinymce_plugin($plugins) {
        $plugins['wp_user_profile_avatar_shortcodes'] = WPUPA_PLUGIN_URL . '/assets/js/admin-avatar.min.js';
        return $plugins;
    }

    /**
     * thickbox_model_init function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function thickbox_model_init() {
        add_thickbox();
    }

    /**
     * thickbox_model_view function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function thickbox_model_view() {
        include_once (WPUPA_PLUGIN_DIR . '/admin/templates/shortcode-popup.php' );

        wp_die();
    }

    /**
     * allow_contributor_uploads function.
     * `
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function allow_contributor_subscriber_uploads() {
        $contributor = get_role('contributor');
        $subscriber = get_role('subscriber');

        $wpupa_allow_upload = get_option('wpupa_allow_upload');

        if (!empty($contributor)) {
            if ($wpupa_allow_upload) {
                $contributor->add_cap('upload_files');
            } else {
                $contributor->remove_cap('upload_files');
            }
        }

        if (!empty($subscriber)) {
            if ($wpupa_allow_upload) {
                $subscriber->add_cap('upload_files');
            } else {
                $subscriber->remove_cap('upload_files');
            }
        }
        
    }

    /**
     * Get closest value from array
     *
     */
    function wpupa_get_closest( $wpupa_search, $wpupa_arr ) {
        $closest = null;
        foreach ( $wpupa_arr as $wpupa_item ) {
            if ( $wpupa_closest === null || abs($wpupa_search - $wpupa_closest) > abs($wpupa_item - $wpupa_search) ) {
                $wpupa_closest = $wpupa_item;
            }
        }
        return $wpupa_closest;
    }

    function init_size() { 
        if ( isset($_POST['wpem_upload_max_file_size_field']) ) {
            $wpupa_max_size = (int) $_POST['wpem_upload_max_file_size_field'] * 1024 * 1024;
            update_option('wpem_max_file_size', $wpupa_max_size);
            wp_safe_redirect(admin_url('upload.php?page=wpem_upload_max_file_size&max-size-updated=true'));
        }
        add_filter('upload_size_limit', array($this, 'wpem_upload_max_increase_upload' ));
    }

    /**
     * Increase max_file_size
     *
     */
    function wpem_upload_max_increase_upload() {
        $wpupa_max_size = (int)get_option('wpem_max_file_size');
        
        if ( ! $wpupa_max_size ) {
            $wpupa_max_size = 64 * 1024 * 1024;
        }

        return $wpupa_max_size;
    }
}

new WPUPA_Admin();

