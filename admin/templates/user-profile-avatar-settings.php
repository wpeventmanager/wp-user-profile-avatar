<h3><?php _e('WP User Profile Avatar', 'wp-user-profile-avatar'); ?></h3>

<table class="form-table">
    <tr>
        <th>
            <label for="wp_user_profile_avatar"><?php _e('Image', 'wp-user-profile-avatar'); ?></label>
        </th>
        <td>
            <p>
                <input type="text" name="wpupa_url" id="wpupa_url" class="regular-text code" value="<?php echo $wpupa_url; ?>" placeholder="Enter Image URL">
            </p>

            <p><?php _e('OR Upload Image', 'wp-user-profile-avatar'); ?></p>

            <p id="wp_user_profile_avatar_add_button_existing">
                <button type="button" class="button" id="wp_user_profile_avatar_add"><?php _e('Choose Image'); ?></button>
                <input type="hidden" name="wpupa_attachment_id" id="wpupa_attachment_id" value="<?php echo $wpupa_attachment_id; ?>">
            </p>

            <?php
            $class_hide = 'wp-user-profile-avatar-hide';
            if (!empty($wpupa_attachment_id)) {
                $class_hide = '';
            } else if (!empty($wpupa_url)) {
                $class_hide = '';
            }
            ?>
            <div id="wp_user_profile_avatar_images_existing">
                <p id="wp_user_profile_avatar_preview">
                    <img src="<?php echo $wpupa_original; ?>" alt="">
                    <span class="description"><?php _e('Original Size', 'wp-user-profile-avatar'); ?></span>
                </p>
                <p id="wp_user_profile_avatar_thumbnail">
                    <img src="<?php echo $wpupa_thumbnail; ?>" alt="">
                    <span class="description"><?php _e('Thumbnail', 'wp-user-profile-avatar'); ?></span>
                </p>
                <p id="wp_user_profile_avatar_remove_button" class="<?php echo $class_hide; ?>">
                    <button type="button" class="button" id="wp_user_profile_avatar_remove"><?php _e('Remove Image', 'wp-user-profile-avatar'); ?></button>
                </p>
                <p id="wp_user_profile_avatar_undo_button">
                    <button type="button" class="button" id="wp_user_profile_avatar_undo"><?php _e('Undo', 'wp-user-profile-avatar'); ?></button>
                </p>
            </div>
        </td>
    </tr>


    <tr>
        <th><label for="wpupa_size"><?php _e("Avatar Size"); ?></label></th>
        <?php
        if ($wpupa_size == '' || $wpupa_size == 0) {
            $wpupa_size = get_avatar_data(get_current_user_id())['size'];
        }
        ?>
        <td>
            <input type="number" name="wpupa_size" id="wpupa_size" value="<?php echo esc_attr($wpupa_size); ?>" />
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="wpem_upload_max_file_size_field">WP Control File Size</label></th>
        <td>
            <select id="wpem_upload_max_file_size_field" name="wpem_upload_max_file_size_field">
            <?php
                foreach ( $wpupa_upload_sizes as $size_wpupa ) {
                    echo '<option value="' . esc_attr($size_wpupa) . '" ' . ($size_wpupa == $wpupa_current_max_size ? 'selected' : '') . '>' . ( $size_wpupa . 'MB') . '</option>';
                } ?>
            </select>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><?php _e('Settings', 'wp-user-profile-avatar'); ?></th>
        <td>
            <fieldset>
                <label for="wpupa_tinymce">
                    <input name="wpupa_tinymce" type="checkbox" id="wpupa_tinymce" value="1" <?php echo checked($wpupa_tinymce, 1, true); ?> > <?php _e('Add shortcode avatar button to Visual Editor', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>

            <fieldset>
                <label for="wpupa_allow_upload">
                    <input name="wpupa_allow_upload" type="checkbox" id="wpupa_allow_upload" value="1" <?php echo checked($wpupa_allow_upload, 1, false); ?> > <?php _e('Allow Contributors &amp; Subscribers to upload avatars', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>

            <fieldset>
                <label for="wpupa_disable_gravatar">
                    <input name="wpupa_disable_gravatar" type="checkbox" id="wpupa_disable_gravatar" value="1" <?php echo checked($wpupa_disable_gravatar, 1, false); ?> > <?php _e('Disable all default gravatar and set own custom default avatar.', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    
</table>

