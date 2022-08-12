<?php
/**
 * upload user profile shortcode
 */
if (!defined('ABSPATH'))
    exit;
?>
<div class="wp-user-profile-avatar-upload">
    <form method="post" name="update-user-profile-avatar" id="update-user-profile-avatar" class="update-user-profile-avatar" enctype="multipart/form-data">
        <table class="form-table">
            <tr>
                <td>
                    <p>
                        <input type="text" name="wpupa-url" id="wpupa-url" class="regular-text code" value="<?php echo $wpupa_url; ?>" placeholder="Enter Image URL">
                    </p>

                    <p><?php _e('OR Upload Image', 'wp-user-profile-avatar'); ?></p>

                    <p id="wp-user-profile-avatar-add-button-existing">
                        <button type="button" class="button" id="wp-user-profile-avatar-add" ><?php _e('Choose Image'); ?></button>

                        <input type="hidden" name="wpupa_attachment_id" id="wpupa_attachment_id">
                        <input type="hidden" name="user_id" id="wp-user-id" value="<?php echo $user_id; ?>">

                    </p>
                </td>
            </tr>

            <?php
            $class_hide = 'wp-user-profile-avatar-hide';
            if (!empty($wpupa_attachment_id) || !empty($wpupa_url)) {
                $class_hide = '';
            }
            ?>
            <tr id="wp-user-profile-avatar-images-existing">
                <td>
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
                </td>
            </tr>

            <tr>
                <td>
                    <button type="button" class="wpem-theme-button" id="wp-user-profile-avatar-update-profile"><?php _e('Update Profile', 'wp-user-profile-avatar'); ?></button>
                </td>
            </tr>

        </table>
    </form>

    <div id="upload-avatar-responce"></div>

</div>
