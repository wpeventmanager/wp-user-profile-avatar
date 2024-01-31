<?php

class WPUPA_Shortcodes {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {

        add_shortcode('authorbox_social_info', array($this, 'authorbox_social_info'));
        add_shortcode('authorbox_social_link', array($this, 'authorbox_social_link'));
        add_shortcode('user_display', array($this, 'user_display'));
        add_shortcode('user_profile_avatar', array($this, 'user_profile_avatar'));
        add_shortcode('user_profile_avatar_upload', array($this, 'user_profile_avatar_upload'));

        add_action('wp_ajax_update_user_avatar', array($this, 'update_user_avatar'));

        add_action('wp_ajax_remove_user_avatar', array($this, 'remove_user_avatar'));

        add_action('wp_ajax_undo_user_avatar', array($this, 'undo_user_avatar'));

        add_filter('get_avatar_url', array($this, 'get_user_avatar_url'), 10, 3);
    }

    /**
     * author box social information function.
     *
     * @access public
     * @param $atts, $content
     * @return 
     * @since 1.0
     */
    public function authorbox_social_info($atts = [], $content = null) {
        global $blog_id, $post, $wpdb;

        $current_user_id = get_current_user_id();

        extract(shortcode_atts(array(
            'user_id' => esc_attr(''),
            'size' => esc_attr('thumbnail'),
            'align' => esc_attr('alignnone'),
        ), $atts));

        ob_start();

        include_once (WPUPA_PLUGIN_DIR . '/templates/wp-author-box-display.php' );

        return ob_get_clean();
    }

    /**
     * authorbox_social_link function
     * 
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function authorbox_social_link() {

        $id = get_current_user_id();

        $details = array();

        ob_start();

        include_once (WPUPA_PLUGIN_DIR . '/templates/wp-author-box-social-info.php' );

        return ob_get_clean();
    }

    /**
     * user_display function
     * 
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function user_display() {

        $id = get_current_user_id();

        $details = array(
            'first_name' => esc_attr(get_the_author_meta('first_name', $id)),
            'last_name' => esc_attr(get_the_author_meta('last_name', $id)),
            'description' => wp_kses_post(get_the_author_meta('description', $id)),
            'email' => ap_html(get_the_author_meta('email', $id)),
            'sabox_social_links' => get_the_author_meta('sabox_social_links', $id),
            'sabox-profile-image' => esc_url(get_the_author_meta('sabox-profile-image', $id)),
        );

        ob_start();

        include_once (WPUPA_PLUGIN_DIR . '/templates/wp-display-user.php' );

        return ob_get_clean();
    }

    /**
     * user_profile_avatar function.
     *
     * @access public
     * @param $atts, $content
     * @return 
     * @since 1.0
     */
    public function user_profile_avatar($atts = [], $content = null) {
        global $blog_id, $post, $wpdb;

        $current_user_id = get_current_user_id();

        extract(shortcode_atts(array(
            'user_id' => esc_attr(''),
            'size' => esc_attr('thumbnail'),
            'align' => esc_attr('alignnone'),
            'link' => esc_url('#'),
            'target' => esc_attr('_self'),
        ), $atts));

        ob_start();

        $image_url = esc_url(get_wpupa_url($current_user_id, ['size' => esc_attr($size)]));

        if ($link == 'image') {
            // Get image src
            $link = get_wpupa_url($current_user_id, ['size' => 'original']);
        } elseif ($link == 'attachment') {
            // Get attachment URL
            $link = get_attachment_link(get_the_author_meta($wpdb->get_blog_prefix(esc_attr($blog_id)) . 'user_avatar', esc_attr($user_id)));
        }

        include_once (WPUPA_PLUGIN_DIR . '/templates/wp-user-avatar.php' );

        return ob_get_clean();
    }

    /**
     * user_profile_avatar_upload function.
     *
     * @access public
     * @param $atts, $content
     * @return 
     * @since 1.0
     */
    public function user_profile_avatar_upload($atts = [], $content = null) {
        extract(shortcode_atts(array(
                        ), $atts));

        ob_start();

        if (!is_user_logged_in()) {
            ?>
            <h5><strong style="color:red;"><?php echo __('ERROR: ', 'wp-user-profile-avatar'); ?></strong> 
                <?php printf('You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', wp_login_url()); ?> 
            </h5>
            <?php
            return false;
        }

        $wpupa_allow_upload = esc_attr(get_option('wpupa_allow_upload'));

        $user_id = get_current_user_id();
        $user = new WP_User($user_id);
        $user_data = get_userdata($user_id);

        if (in_array('contributor', $user_data->roles)) {
            if (empty($wpupa_allow_upload)) {
                ?>
                <h5><strong style="color:red;"><?php echo __('ERROR: ', 'wp-user-profile-avatar'); ?></strong> 
                    <?php printf('You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', wp_login_url()); ?> 
                </h5>
                <?php
                return false;
            }
        }

        if (in_array('subscriber', $user_data->roles)) {
            if (empty($wpupa_allow_upload)) {
                ?>
                <h5><strong style="color:red;"><?php echo __('ERROR: ', 'wp-user-profile-avatar'); ?></strong> 
                    <?php printf('You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', wp_login_url()); ?> 
                </h5>
                <?php
                return false;
            }
        }

        wp_enqueue_script('wp-user-profile-avatar-frontend-avatar');

        $wpupa_original = esc_url(get_wpupa_url($user_id, ['size' => 'original']));
        $wpupa_thumbnail = esc_url(get_wpupa_url($user_id, ['size' => 'thumbnail']));

        $wpupaattachmentid = esc_attr(get_user_meta($user_id, '_wpupaattachmentid', true));
        $wpupa_url = esc_url(get_user_meta($user_id, '_wpupa-url', true));

        include_once (WPUPA_PLUGIN_DIR . '/templates/wp-avatar-upload.php' );

        return ob_get_clean();
    }

    /**
     * update_user_avatar function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function update_user_avatar() {
        check_ajax_referer('_nonce_user_profile_avatar_security', 'security');

        parse_str($_POST['form_data'], $form_data);

        //sanitize each of the values of form data
        $form_wpupa_url = esc_url_raw($form_data['wpupa-url']);
        $form_wpupaattachmentid = absint($form_data['wpupaattachmentid']);
        $user_id = absint($form_data['user_id']);


        $file = $_FILES['user-avatar'];

        if (isset($file) && !empty($file)) {

            $post_id = 0;

            //sanitize each of the values of file data
            $file_name = sanitize_file_name($file['name']);
            $file_type = sanitize_text_field($file['type']);
            $file_tmp_name = sanitize_text_field($file['tmp_name']);
            $file_size = absint($file['size']);

            // Upload file
            $overrides = array('test_form' => false);
            $uploaded_file = $this->handle_upload($file, $overrides);

            $attachment = array(
                'post_title' => $file_name,
                'post_content' => '',
                'post_type' => 'attachment',
                'post_parent' => null, // populated after inserting post
                'post_mime_type' => $file_type,
                'guid' => $uploaded_file['url']
            );

            $attachment['post_parent'] = $post_id;
            $attach_id = wp_insert_attachment($attachment, $uploaded_file['file'], $post_id);
            $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_file['file']);

            if (isset($user_id, $attach_id)) {
                $result = wp_update_attachment_metadata($attach_id, $attach_data);
                update_user_meta($user_id, '_wpupaattachmentid', $attach_id);
            }
        } else {
            if (isset($user_id, $form_wpupaattachmentid))
                update_user_meta($user_id, '_wpupaattachmentid', $form_wpupaattachmentid);
        }

        if (isset($user_id, $form_wpupa_url))
            update_user_meta($user_id, '_wpupa-url', $form_wpupa_url);

        if (!empty($form_wpupaattachmentid) || $form_wpupa_url) {
            update_user_meta($user_id, '_wpupa_default', 'wp_user_profile_avatar');
        } else {
            update_user_meta($user_id, '_wpupa_default', '');
        }

        $wpupaattachmentid = get_user_meta($user_id, '_wpupaattachmentid', true);
        $wpupa_url = get_user_meta($user_id, '_wpupa-url', true);
     
        if (empty($wpupaattachmentid) && empty($wpupa_url)) {
            $wpupa_original = '';
            $wpupa_thumbnail = '';
            $message = __('Error! Select Image', 'wp-user-profile-avatar');
            $class = 'wp-user-profile-avatar-error';
        } else {
            $wpupa_original = get_wpupa_url($user_id, ['size' => 'original']);
            $wpupa_thumbnail = get_wpupa_url($user_id, ['size' => 'thumbnail']);
            $message = __('Successfully Updated Avatar', 'wp-user-profile-avatar');
            $class = 'wp-user-profile-avatar-success';
        }

        echo json_encode(['avatar_original' => $wpupa_original, 'avatar_thumbnail' => $wpupa_thumbnail, 'message' => $message, 'class' => $class]);

        wp_die();
    }

    /**
     * remove_user_avatar function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    function remove_user_avatar() {
        check_ajax_referer('_nonce_user_profile_avatar_security', 'security');

        parse_str($_POST['form_data'], $form_data);

        //sanitize each of the values of form data
        $wpupa_url = esc_url_raw($form_data['wpupa-url']);
        $wpupaattachmentid = absint($form_data['wpupaattachmentid']);
        $user_id = absint($form_data['user_id']);

        if (isset($user_id)) {
            update_user_meta($user_id, '_wpupaattachmentid', '');
            update_user_meta($user_id, '_wpupa-url', '');
            update_user_meta($user_id, '_wpupa_default', '');

            //delete also attachment
            wp_delete_attachment($wpupaattachmentid, true);
        }

        $wpupa_original = get_wpupa_url($user_id, ['size' => 'original']);
        $wpupa_thumbnail = get_wpupa_url($user_id, ['size' => 'thumbnail']);

        $message = __('Successfully Removed Avatar', 'wp-user-profile-avatar');
        $class = 'wp-user-profile-avatar-success';
        
        echo json_encode(['avatar_original' => $wpupa_original, 'avatar_thumbnail' => $wpupa_thumbnail, 'message' => $message, 'class' => $class]);

        wp_die();
    }

    /**
     * undo_user_avatar function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    function undo_user_avatar() {
        check_ajax_referer('_nonce_user_profile_avatar_security', 'security');

        parse_str($_POST['form_data'], $form_data);

        //sanitize each of the values of form data
        $wpupa_url = esc_url_raw($form_data['wpupa-url']);
        $wpupaattachmentid = absint($form_data['wpupaattachmentid']);
        $user_id = absint($form_data['user_id']);

        if (isset($user_id)) {
            update_user_meta($user_id, '_wpupaattachmentid', '');
            update_user_meta($user_id, '_wpupa-url', '');
            update_user_meta($user_id, '_wpupa_default', '');
        }

        

        $wpupa_original = get_wpupa_url($user_id, ['size' => 'original']);
        $wpupa_thumbnail = get_wpupa_url($user_id, ['size' => 'thumbnail']);

        $message = __('Successfully Undo Avatar', 'wp-user-profile-avatar');
        $class = 'wp-user-profile-avatar-success';

        echo json_encode(['avatar_original' => $wpupa_original, 'avatar_thumbnail' => $wpupa_thumbnail, 'message' => $message, 'class' => $class]);

        wp_die();
    }

    /**
     * handle_upload function.
     *
     * @access public
     * @param $file_handler, $overrides
     * @return 
     * @since 1.0
     */
    function handle_upload($file_handler, $overrides) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $upload = wp_handle_upload($file_handler, $overrides);

        return $upload;
    }

    /**
     * get_user_avatar_url function.
     *
     * @access public
     * @param $url, $id_or_email, $args
     * @return 
     * @since 1.0
     */
    public function get_user_avatar_url($url, $id_or_email, $args) {

        $wpupa_disable_gravatar = get_option('wpupa_disable_gravatar');

        $wpupa_show_avatars = get_option('wpupa_show_avatars');

        $wpupa_default = get_option('wpupa_default');

        if (!$wpupa_show_avatars) {
            return false;
        }

        $user_id = null;
        if (is_object($id_or_email)) {
            if (!empty($id_or_email->comment_author_email)) {
                $user_id = $id_or_email->user_id;
            }
        } else {
            if (is_email($id_or_email)) {
                $user = get_user_by('email', $id_or_email);
                if ($user) {
                    $user_id = $user->ID;
                }
            } else {
                $user_id = $id_or_email;
            }
        }

        // First checking custom avatar.
        if (check_wpupa_url($user_id)) {
            $url = get_wpupa_url($user_id, ['size' => 'thumbnail']);
        } else if ($wpupa_disable_gravatar) {
            $url = get_wpupa_default_avatar_url(['size' => 'thumbnail']);
        } else {
            $has_valid_url = check_wpupa_gravatar($id_or_email);
            if (!$has_valid_url) {
                $url = get_wpupa_default_avatar_url(['size' => 'thumbnail']);
            } else {
                if ($wpupa_default != 'wp_user_profile_avatar' && !empty($user_id)) {
                    $url = get_wpupa_url($user_id, ['size' => 'thumbnail']);
                }
            }
        }

        return $url;
    }

}

new WPUPA_Shortcodes();
