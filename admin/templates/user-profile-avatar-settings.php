<h3><?php _e('WP User Profile Avatar', 'wp-user-profile-avatar'); ?></h3>

<table class="form-table">
    <tr>
        <th>
            <label for="wp-user-profile-avatar"><?php _e('Image', 'wp-user-profile-avatar'); ?></label>
        </th>
        <td>
            <p>
                <input type="text" name="wpupa-url" id="wpupa-url" class="regular-text code" value="<?php echo $wpupa_url; ?>" placeholder="Enter Image URL">
            </p>

            <p><?php _e('OR Upload Image', 'wp-user-profile-avatar'); ?></p>

            <p id="wp-user-profile-avatar-add-button-existing">
                <button type="button" class="button" id="wp-user-profile-avatar-add"><?php _e('Choose Image'); ?></button>
                <input type="hidden" name="wpupa-attachment-id" id="wpupa-attachment-id" value="<?php echo $wpupa_attachment_id; ?>">
            </p>

            <?php
            $class_hide = 'wp-user-profile-avatar-hide';
            if (!empty($wpupa_attachment_id)) {
                $class_hide = '';
            } else if (!empty($wpupa_url)) {
                $class_hide = '';
            }
            ?>
            <div id="wp-user-profile-avatar-images-existing">
                <p id="wp-user-profile-avatar-preview">
                    <img src="<?php echo $wpupa_original; ?>" alt="">
                    <span class="description"><?php _e('Original Size', 'wp-user-profile-avatar'); ?></span>
                </p>
                <p id="wp-user-profile-avatar-thumbnail">
                    <img src="<?php echo $wpupa_thumbnail; ?>" alt="">
                    <span class="description"><?php _e('Thumbnail', 'wp-user-profile-avatar'); ?></span>
                </p>
                <p id="wp-user-profile-avatar-remove-button" class="<?php echo $class_hide; ?>">
                    <button type="button" class="button" id="wp-user-profile-avatar-remove"><?php _e('Remove Image', 'wp-user-profile-avatar'); ?></button>
                </p>
                <p id="wp-user-profile-avatar-undo-button">
                    <button type="button" class="button" id="wp-user-profile-avatar-undo"><?php _e('Undo', 'wp-user-profile-avatar'); ?></button>
                </p>
            </div>
        </td>
    </tr>

    <tr>
        <th><label for="wpupa-size"><?php _e("Avatar Size"); ?></label></th>
        <?php
        if ($wpupa_size == '' || $wpupa_size == 0) {
            $wpupa_size = get_avatar_data(get_current_user_id())['size'];
        }
        ?>
        <td>
            <input type="number" name="wpupa-size" id="wpupa-size" value="<?php echo esc_attr($wpupa_size); ?>" />
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="wpem-upload-max-file-size-field">WP Control File Size</label></th>
        <td>
            <select id="wpem-upload-max-file-size-field" name="wpem-upload-max-file-size-field">
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
                <label for="wpupa-tinymce">
                    <input name="wpupa-tinymce" type="checkbox" id="wpupa-tinymce" value="1" <?php echo checked($wpupa_tinymce, 1, true); ?> > <?php _e('Add shortcode avatar button to Visual Editor', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>

            <fieldset>
                <label for="wpupa-allow-upload">
                    <input name="wpupa-allow-upload" type="checkbox" id="wpupa-allow-upload" value="1" <?php echo checked($wpupa_allow_upload, 1, false); ?> > <?php _e('Allow Contributors &amp; Subscribers to upload avatars', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>

            <fieldset>
                <label for="wpupa-disable-gravatar">
                    <input name="wpupa-disable-gravatar" type="checkbox" id="wpupa-disable-gravatar" value="1" <?php echo checked($wpupa_disable_gravatar, 1, false); ?> > <?php _e('Disable all default gravatar and set own custom default avatar.', 'wp-user-profile-avatar'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    
</table>

