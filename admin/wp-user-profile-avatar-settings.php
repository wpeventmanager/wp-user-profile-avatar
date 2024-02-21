<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WPUPA_Settings class.
 */
class WPUPA_Settings {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {
        add_action('wp_loaded', array($this, 'edit_handler'));
    }

    /**
     * settings function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function settings() {
        wp_enqueue_media();

        wp_enqueue_style('wp-user-profile-avatar-backend');

        wp_enqueue_script('wp-user-profile-avatar-admin-avatar');

        //options
        $wpupa_tinymce = get_option('wpupa_tinymce');
        $wpupa_allow_upload = get_option('wpupa_allow_upload');
        $wpupa_disable_gravatar = get_option('wpupa_disable_gravatar');
        $wpupa_show_avatars = get_option('wpupa_show_avatars');
        $wpupa_rating = get_option('wpupa_rating');
        $wpupa_file_size = get_option('wpupa_file_size');
        $wpupa_default = get_option('wpupa_default');
        $wpupa_attachment_id = get_option('wpupaattachmentid');
        $wpupa_attachment_url = get_wpupa_default_avatar_url(['size' => 'admin']);
        $wpupa_size = get_option('wpupa_size');
        $avatar_size = get_option('avatar_size');
        $wpupa_hide_post_option = get_option('wpupa_hide_post_option');
        ?>
        <div class="wrap">
            <h2>
                <?php _e('WP User Profile Avatar Settings', 'wp-user-profile-avatar'); ?>
            </h2>
            <table>
                <tr valign="top">
                    <td>
                        <form method="post" action="<?php echo esc_url(admin_url('admin.php')). '?page=wp-user-profile-avatar'; ?>">

                            <table class="form-table">

                                <tr valign="top">
                                    <th scope="row"><?php _e('Avatar Visibility', 'wp-user-profile-avatar'); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_show_avatars">
                                                <input name="wpupa_show_avatars" type="checkbox" id="wpupa_show_avatars" value="1" <?php echo checked($wpupa_show_avatars, 1, 0); ?> > <?php _e('Show Avatars', 'wp-user-profile-avatar'); ?>
                                            </label>
                                            <p class="description"><?php _e('If it is unchecked then it will not show the user avatar at profile and frontend side.', 'wp-user-profile-avatar'); ?></p>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('Settings', 'wp-user-profile-avatar'); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_tinymce">
                                                <input name="wpupa_tinymce" type="checkbox" id="wpupa_tinymce" value="1" <?php echo checked($wpupa_tinymce, 1, 0); ?> > <?php _e('Add shortcode avatar button to Visual Editor', 'wp-user-profile-avatar'); ?>
                                            </label>
                                        </fieldset>

                                        <fieldset>
                                            <label for="wpupa_allow_upload">
                                                <input name="wpupa_allow_upload" type="checkbox" id="wpupa_allow_upload" value="1"<?php echo checked($wpupa_allow_upload, 1, 0); ?> > <?php _e('Allow Contributors &amp; Subscribers to upload avatars', 'wp-user-profile-avatar'); ?>
                                            </label>
                                        </fieldset>

                                        <fieldset>
                                            <label for="wpupa_disable_gravatar">
                                                <input name="wpupa_disable_gravatar" type="checkbox" id="wpupa_disable_gravatar" value="1"<?php echo checked($wpupa_disable_gravatar, 1, 0); ?> > <?php _e('Disable all default gravatar and set own custom default avatar.', 'wp-user-profile-avatar'); ?>
                                            </label>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('Avatar Rating', 'wp-user-profile-avatar'); ?></th>
                                    <td>
                                        <fieldset>
                                            <legend class="screen-reader-text"><?php _e('Avatar Rating', 'wp-user-profile-avatar'); ?></legend>
                                            <?php foreach (get_wpupa_rating() as $name => $rating) : ?>
                                                <?php $selected = ($wpupa_rating == $name) ? 'checked="checked"' : ""; ?>
                                                <label><input type="radio" name="wpupa_rating" value="<?php echo esc_attr($name); ?>" <?php echo $selected; ?> /> <?php echo esc_attr($rating); ?></label><br />
                                            <?php endforeach; ?>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="wpupa_file_size">Avatar Max File Size</label>
                                    </th>
                                    <td>
                                        <select id="wpupa_file_size" name="wpupa_file_size">
                                            <?php foreach (get_wpupa_file_size() as $name => $size) { ?>
                                                <?php $selected = ($wpupa_file_size == $name) ? 'selected="selected"' : ""; ?>
                                                <option value="<?php echo esc_attr($name); ?>" <?php echo $selected; ?> /><?php echo esc_attr($name == 1024 ? '1GB' : $size ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="wpupa_file_size">Avatar Size</label>
                                    </th>
                                    <td>
                                        <select id="avatar_size" name="avatar_size">
                                            <option value=""><?php echo _e('Select Avatar Size', 'wp-user-profile-avatar'); ?></option>
                                            <?php foreach (get_wpupa_image_sizes() as $name => $avarat_key) { 

                                                ?>
                                                <?php $avatar_size_selected = ($avatar_size == $name) ? 'selected="selected"' : "";
                                                ?>
                                                <option value="<?php echo esc_attr($name); ?>" <?php echo $avatar_size_selected; ?> /><?php echo esc_attr($avarat_key); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="description"><?php _e('Selecting avatar size here will not work with user profile avatar shortcode size parameters. [user_profile_avatar size="original"]'); ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Hide Bio Info Box Avatar', 'wp-user-profile-avatar'); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_hide_post_option">
                                                <input name="wpupa_hide_post_option" type="checkbox" id="wpupa_hide_post_option" value="1"<?php echo checked($wpupa_hide_post_option, 1, 0); ?> > <?php _e('Turn off the author bio info box', 'wp-user-profile-avatar'); ?>
                                            </label>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('Default Avatar', 'wp-user-profile-avatar'); ?></th>
                                    <td class="defaultavatarpicker">
                                        <fieldset>
                                            <legend class="screen-reader-text"><?php _e('Default Avatar', 'wp-user-profile-avatar'); ?></legend>
                                            <?php _e('For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their e-mail address.', 'wp-user-profile-avatar'); ?><br />

                                            <?php $selected = ($wpupa_default == 'wp_user_profile_avatar') ? 'checked="checked"' : ""; ?>
                                            <label>
                                                <input type="radio" name="wpupa_default" id="wp_user_profile_avatar_radio" value="wp_user_profile_avatar" <?php echo $selected; ?> />
                                                <div id="wp_user_profile_avatar_preview">
                                                    <img src="<?php echo esc_url($wpupa_attachment_url); ?>" width="32" />
                                                </div> 
                                                <?php _e('WP User Profile Avatar'); ?> 
                                            </label>
                                            <br />

                                            <?php
                                            $class_hide = 'wp-user-profile-avatar-hide';
                                            if (!empty($wpupa_attachment_id)) {
                                                $class_hide = '';
                                            } ?>
                                            <p id="wp-user-profile-avatar-edit">
                                                <button type="button" class="button" id="wp-user-profile-avatar-add" name="wp-user-profile-avatar-add"><?php _e('Choose Image'); ?></button>
                                                <span id="wp-user-profile-avatar-remove-button" class="<?php echo esc_attr($class_hide); ?>"><a href="javascript:void(0)" id="wp-user-profile-avatar-remove"><?php _e('Remove'); ?></a></span>
                                                <span id="wp-user-profile-avatar-undo-button"><a href="javascript:void(0)" id="wp-user-profile-avatar-undo"><?php _e('Undo'); ?></a></span>
                                                <input type="hidden" name="wpupaattachmentid" id="wpupaattachmentid" value="<?php echo esc_attr($wpupa_attachment_id); ?>">
                                            </p>

                                            <?php if (empty($wpupa_disable_gravatar)) : 
                                                foreach (get_wpupa_default_avatar() as $name => $label) :
                                                    $avatar = get_avatar('', 32, $name); 
                                                    $avatar_url = get_wpupa_selected_avatar_url($name);
                                                       
                                                    $selected = ($wpupa_default == $name) ? 'checked="checked"' : ""; ?>
                                                    <label><input type="radio" name="wpupa_default" value="<?php echo esc_attr($name); ?>" <?php echo $selected; ?> /> 
                                                        <img alt='' src='<?php echo $avatar_url;?>' srcset='<?php echo $avatar_url;?>' class='avatar avatar-32 photo avatar-default' height='32' width='32' loading='lazy' decoding='async'/>
 
                                                        <?php echo esc_attr($label); ?>
                                                    </label><br />
                                                <?php endforeach; 
                                            endif; ?>

                                        </fieldset>
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        <input type="submit" class="button button-primary" name="wp_user_profile_avatar_settings" value="<?php esc_attr_e('Save Changes', 'wp-user-profile-avatar'); ?>" />
                                    </td>

                                    <td>
                                        <?php wp_nonce_field('user_profile_avatar_settings'); ?>
                                    </td>
                                </tr>

                            </table>

                        </form>
                    </td>
                </tr>
            </table>
        </div> 	

        <?php
    }

    /**
     * edit_handler function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function edit_handler() {
        if (!empty($_POST['wp_user_profile_avatar_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'user_profile_avatar_settings')) {
            $user_id = get_current_user_id();

            $wpupa_show_avatars = !empty($_POST['wpupa_show_avatars']) ? sanitize_text_field($_POST['wpupa_show_avatars']) : '';


            $wpupa_tinymce = !empty($_POST['wpupa_tinymce']) ? sanitize_text_field($_POST['wpupa_tinymce']) : '';

            $wpupa_allow_upload = !empty($_POST['wpupa_allow_upload']) ? sanitize_text_field($_POST['wpupa_allow_upload']) : '';

            $wpupa_disable_gravatar = !empty($_POST['wpupa_disable_gravatar']) ? sanitize_text_field($_POST['wpupa_disable_gravatar']) : '';


            $wpupa_rating = !empty($_POST['wpupa_rating']) ? sanitize_text_field($_POST['wpupa_rating']) : '';

            $wpupa_file_size = !empty($_POST['wpupa_file_size']) ? sanitize_text_field($_POST['wpupa_file_size']) : '';

            $wpupa_default = !empty($_POST['wpupa_default']) ? sanitize_text_field($_POST['wpupa_default']) : '';

            $wpupa_attachment_id = !empty($_POST['wpupaattachmentid']) ? sanitize_text_field($_POST['wpupaattachmentid']) : '';

            $wpupa_size = !empty($_POST['wpupa_size']) ? sanitize_text_field($_POST['wpupa_size']) : '';

            $avatar_size = !empty($_POST['avatar_size']) ? sanitize_text_field($_POST['avatar_size']) : '';

            $wpupa_hide_post_option = !empty($_POST['wpupa_hide_post_option']) ? sanitize_text_field($_POST['wpupa_hide_post_option']) : '';
            

            if ($wpupa_show_avatars == '') {
                $wpupa_tinymce = '';
                $wpupa_allow_upload = '';
                $wpupa_disable_gravatar = '';
            }

            if ($wpupa_disable_gravatar) {
                $wpupa_default = 'wp_user_profile_avatar';
            }

            // options
            update_option('wpupa_tinymce', $wpupa_tinymce);
            update_option('wpupa_allow_upload', $wpupa_allow_upload);
            update_option('wpupa_disable_gravatar', $wpupa_disable_gravatar);
            update_option('wpupa_show_avatars', $wpupa_show_avatars);
            update_option('wpupa_rating', $wpupa_rating);
            update_option('wpupa_file_size', $wpupa_file_size);
            update_option('wpupa_default', $wpupa_default);
            update_option('wpupaattachmentid', $wpupa_attachment_id);
            update_option('wpupa_size', $wpupa_size);
            update_option('avatar_size', $avatar_size);
            update_option('wpupa_hide_post_option', $wpupa_hide_post_option);
        }
    }

}

new WPUPA_Settings();
