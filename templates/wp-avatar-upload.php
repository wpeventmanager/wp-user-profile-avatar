<?php
/**
 * upload user profile shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wp-user-profile-avatar-upload">
    <form method="post" name="update-user-profile-avatar" id="update-user-profile-avatar" class="update-user-profile-avatar" enctype="multipart/form-data">
        <table class="form-table">
            <tr>
                <td>
                    <p>
                        <input type="text" name="wpupa-url" id="wpupa-url" class="regular-text code" value="<?php echo esc_url( $wpupa_url ); ?>" placeholder="Enter Image URL">
                    </p>

                    <p><?php esc_html_e( 'OR Upload Image', 'wp-user-profile-avatar' ); ?></p>

                    <p id="wp-user-profile-avatar-add-button-existing">
                        <button type="button" class="button" id="wp-user-profile-avatar-add" ><?php esc_html_e( 'Choose Image' ); ?></button>

                        <input type="hidden" name="wpupaattachmentid" id="wpupaattachmentid">
                        <input type="hidden" name="user_id" id="wp-user-id" value="<?php echo esc_attr( $user_id ); ?>">

                    </p>
                </td>
            </tr>

            <?php
            // $class_hide = 'wp-user-profile-avatar-hide';
            if ( ! empty( $wpupaattachmentid ) || ! empty( $wpupa_url ) ) {
                $class_hide = '';
            }
            ?>
            <tr id="wp-user-profile-avatar-images-existing">
                <td>
                    <p id="wp-user-profile-avatar-preview">
                        <img src="<?php echo ( $wpupa_url ) ? esc_url( $wpupa_url ) : esc_url( $wpupa_original ); ?>" alt="">
                        <span class="description"><?php esc_html_e( 'Original Size', 'wp-user-profile-avatar' ); ?></span>
                    </p>
                    <p id="wp-user-profile-avatar-thumbnail">
                        <img src="<?php echo ( $wpupa_url ) ? esc_url( $wpupa_url ) : esc_url( $wpupa_thumbnail ); ?>" alt="">
                        <span class="description"><?php esc_html_e( 'Thumbnail', 'wp-user-profile-avatar' ); ?></span>
                    </p>
                    <p id="wp-user-profile-avatar-remove-button" class="<?php // echo esc_attr($class_hide); ?>">
                        <button type="button" class="button" id="wp-user-profile-avatar-remove"><?php esc_html_e( 'Remove Image', 'wp-user-profile-avatar' ); ?></button>
                    </p>
                    <p id="wp-user-profile-avatar-undo-button">
                        <button type="button" class="button" id="wp-user-profile-avatar-undo"><?php esc_html_e( 'Undo', 'wp-user-profile-avatar' ); ?></button>
                    </p>
                </td>
            </tr>

            <tr>
                <td>
                    <button type="button" class="wpem-theme-button" id="wp-user-profile-avatar-update-profile"><?php esc_html_e( 'Update Profile', 'wp-user-profile-avatar' ); ?></button>
                </td>
            </tr>

        </table>
    </form>

    <div id="upload-avatar-responce"></div>

</div>
