<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class with User Profile Settings functions.
 */
class WPUPA_Settings {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() {
        add_action( 'wp_loaded', array( $this, 'wpupa_edit_handler' ) );
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

        wp_enqueue_style( 'wp-user-profile-avatar-backend' );

        wp_enqueue_script( 'wp-user-profile-avatar-admin-avatar' );

        // options
        $wpupa_tinymce          = get_option( 'wpupa_tinymce' );
        $wpupa_allow_upload     = get_option( 'wpupa_allow_upload' );
        $wpupa_disable_gravatar = get_option( 'wpupa_disable_gravatar' );
        $wpupa_show_avatars     = get_option( 'wpupa_show_avatars' );
        $wpupa_rating           = get_option( 'wpupa_rating' );
        $wpupa_file_size        = get_option( 'wpupa_file_size' );
        $wpupa_default          = get_option( 'avatar_default' );
        $wpupa_attachment_id    = get_option( 'wpupa_attachment_id' );
        $wpupa_attachment_url = get_option('wpupa_attachment_url') ? get_option('wpupa_attachment_url') : wpupa_get_default_avatar_url(array('size' => 'admin'), array(), '');
        $wpupa_size             = get_option( 'wpupa_size' );
        $avatar_size            = get_option( 'avatar_size' );
        $wpupa_hide_post_option = get_option( 'wpupa_hide_post_option' );
        // Get the current logged-in user object
        $current_user = wp_get_current_user();

        // Retrieve the email of the logged-in user
        $user_email = $current_user->user_email;
        ?>
        <div class="wrap">
            <h2>
                <?php esc_html_e( 'WP User Profile Avatar Settings', 'wp-user-profile-avatar' ); ?>
            </h2>
            <table>
                <tr valign="top">
                    <td>
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin.php' ) ) . '?page=wp-user-profile-avatar'; ?>">
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Avatar Visibility', 'wp-user-profile-avatar' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_show_avatars">
                                                <input name="wpupa_show_avatars" type="checkbox" id="wpupa_show_avatars" value="1" <?php echo checked( $wpupa_show_avatars, 1, 0 ); ?> > <?php esc_html_e( 'Show Avatars', 'wp-user-profile-avatar' ); ?>
                                            </label>
                                            <p class="description"><?php esc_html_e( 'If it is unchecked then it will not show the user avatar at profile and frontend side.', 'wp-user-profile-avatar' ); ?></p>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Settings', 'wp-user-profile-avatar' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_tinymce">
                                                <input name="wpupa_tinymce" type="checkbox" id="wpupa_tinymce" value="1" <?php echo checked( $wpupa_tinymce, 1, 0 ); ?> > <?php esc_html_e( 'Add shortcode avatar button to Visual Editor', 'wp-user-profile-avatar' ); ?>
                                            </label>
                                        </fieldset>

                                        <fieldset>
                                            <label for="wpupa_allow_upload">
                                                <input name="wpupa_allow_upload" type="checkbox" id="wpupa_allow_upload" value="1"<?php echo checked( $wpupa_allow_upload, 1, 0 ); ?> > <?php esc_html_e( 'Allow Contributors &amp; Subscribers to upload avatars', 'wp-user-profile-avatar' ); ?>
                                            </label>
                                        </fieldset>

                                        <fieldset>
                                            <label for="wpupa_disable_gravatar">
                                                <input name="wpupa_disable_gravatar" type="checkbox" id="wpupa_disable_gravatar" value="1"<?php echo checked( $wpupa_disable_gravatar, 1, 0 ); ?> > <?php esc_html_e( 'Disable all default gravatar and set own custom default avatar.', 'wp-user-profile-avatar' ); ?>
                                            </label>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Avatar Rating', 'wp-user-profile-avatar' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <legend class="screen-reader-text"><?php esc_html_e( 'Avatar Rating', 'wp-user-profile-avatar' ); ?></legend>
                                            <?php foreach ( wpupa_get_rating() as $name => $rating ) : ?>
                                                <?php $selected = ( $wpupa_rating == $name ) ? 'checked="checked"' : ''; ?>
                                                <label><input type="radio" name="wpupa_rating" value="<?php echo esc_attr( $name ); ?>" <?php echo esc_attr( $selected ); ?> /> <?php echo esc_attr( $rating ); ?></label><br />
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
                                            <?php foreach ( wpupa_get_file_size() as $name => $size ) { ?>
                                                <?php $selected = ( $wpupa_file_size == $name ) ? 'selected="selected"' : ''; ?>
                                                <option value="<?php echo esc_attr( $name ); ?>" <?php echo esc_attr( $selected ); ?> /><?php echo esc_attr( $name == 1024 ? '1GB' : $size ); ?></option>
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
                                            <option value=""><?php echo esc_html_e( 'Select Avatar Size', 'wp-user-profile-avatar' ); ?></option>
                                            <?php
                                            foreach ( wpupa_get_image_sizes() as $name => $avarat_key ) {

                                                ?>
                                                <?php
                                                $avatar_size_selected = ( $avatar_size == $name ) ? 'selected="selected"' : '';
                                                ?>
                                                <option value="<?php echo esc_attr( $name ); ?>" <?php echo esc_attr( $avatar_size_selected ); ?> /><?php echo esc_attr( $avarat_key ); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Selecting avatar size here will not work with user profile avatar shortcode size parameters. [user_profile_avatar size="original"]', 'wp-user-profile-avatar' ); ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Hide Bio Info Box Avatar', 'wp-user-profile-avatar' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="wpupa_hide_post_option">
                                                <input name="wpupa_hide_post_option" type="checkbox" id="wpupa_hide_post_option" value="1"<?php echo checked( $wpupa_hide_post_option, 1, 0 ); ?> > <?php esc_html_e( 'Turn off the author bio info box', 'wp-user-profile-avatar' ); ?>
                                            </label>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Default Avatar', 'wp-user-profile-avatar' ); ?></th>
                                    <td class="defaultavatarpicker">
                                        <fieldset>
                                            <legend class="screen-reader-text"><?php esc_html_e( 'Default Avatar', 'wp-user-profile-avatar' ); ?></legend>
                                            <?php esc_html_e( 'For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their e-mail address.', 'wp-user-profile-avatar' ); ?><br />

                                            <?php $selected = ( $wpupa_default == 'wp_user_profile_avatar' ) ? 'checked="checked"' : ''; ?>
                                            <label>
                                                <input type="radio" name="avatar_default" id="wp_user_profile_avatar_radio" value="wp_user_profile_avatar" <?php echo esc_attr( $selected ); ?> />
                                                <div id="wp_user_profile_avatar_preview">
                                                    <img src="<?php echo esc_url( $wpupa_attachment_url ); ?>" width="32" />
                                                </div> 
                                                <?php esc_html_e( 'WP User Profile Avatar', 'wp-user-profile-avatar' ); ?> 
                                            </label>
                                            <br />

                                            <?php
                                            $class_hide = 'wp-user-profile-avatar-hide';
                                            if ( ! empty( $wpupa_attachment_id ) ) {
                                                $class_hide = '';
                                            }
                                            ?>
                                            <p id="wp-user-profile-avatar-edit">
                                                <button type="button" class="button" id="wp-user-profile-avatar-add" name="wp-user-profile-avatar-add"><?php esc_html_e( 'Choose Image', 'wp-user-profile-avatar' ); ?></button>
                                                <span id="wp-user-profile-avatar-remove-button" class="<?php echo esc_attr( $class_hide ); ?>"><a href="javascript:void(0)" id="wp-user-profile-avatar-remove"><?php esc_html_e( 'Remove', 'wp-user-profile-avatar' ); ?></a></span>
                                                <span id="wp-user-profile-avatar-undo-button"><a href="javascript:void(0)" id="wp-user-profile-avatar-undo"><?php esc_html_e( 'Undo', 'wp-user-profile-avatar' ); ?></a></span>
                                                <input type="hidden" name="wpupaattachmentid" id="wpupaattachmentid" value="<?php echo esc_attr( $wpupa_attachment_id ); ?>">
                                            </p>
 
                                            <?php
                                            if ( empty( $wpupa_disable_gravatar ) ) :
                                                foreach ( wpupa_get_default_avatar() as $name => $label ) :
                                                    $selected = ( $wpupa_default == $name ) ? 'checked="checked"' : ''; ?>
                                                    <label><input type="radio" name="avatar_default" value="<?php echo esc_attr( $name ); ?>" <?php echo esc_attr( $selected ); ?> />                                  
                                                       <?php echo get_avatar( $user_email, 32, $name );
                                                        echo esc_attr( $label ); ?>
                                                    </label><br />
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>

                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="submit" class="button button-primary" name="wp_user_profile_avatar_settings" value="<?php esc_attr_e( 'Save Changes', 'wp-user-profile-avatar' ); ?>" />
                                    </td>

                                    <td>
                                        <?php wp_nonce_field( 'user_profile_avatar_settings' ); ?>
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
     * edit fields handler function.
     *
     * @access public
     * @param
     * @return
     * @since 1.0
     */
    public function wpupa_edit_handler() {
        if ( ! empty( $_POST['wp_user_profile_avatar_settings'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_wpnonce'] ) ), 'user_profile_avatar_settings' ) ) {
            $user_id = get_current_user_id();

            $wpupa_show_avatars = ! empty( $_POST['wpupa_show_avatars'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_show_avatars'] ) ) : '';

            $wpupa_tinymce = ! empty( $_POST['wpupa_tinymce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_tinymce'] ) ) : '';

            $wpupa_allow_upload = ! empty( $_POST['wpupa_allow_upload'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_allow_upload'] ) ) : '';

            $wpupa_disable_gravatar = ! empty( $_POST['wpupa_disable_gravatar'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_disable_gravatar'] ) ) : '';

            $wpupa_rating = ! empty( $_POST['wpupa_rating'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_rating'] ) ) : '';

            $wpupa_file_size = ! empty( $_POST['wpupa_file_size'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_file_size'] ) ) : '';

            $wpupa_default = ! empty( $_POST['avatar_default'] ) ? sanitize_text_field( wp_unslash( $_POST['avatar_default'] ) ) : '';

           if ( ! empty( $_POST['wpupaattachmentid'] ) ) {
                $wpupa_attachment_id = sanitize_text_field( $_POST['wpupaattachmentid'] );
                $wpupa_attachment_url = esc_url( wp_get_attachment_url( $wpupa_attachment_id ) );
            } else {
                $wpupa_attachment_id = '';
                $wpupa_attachment_url = esc_url( WPUPA_PLUGIN_URL . '/assets/images/wp-user-thumbnail.png' );
            }

            $wpupa_size = ! empty( $_POST['wpupa_size'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_size'] ) ) : '';

            $avatar_size = ! empty( $_POST['avatar_size'] ) ? sanitize_text_field( wp_unslash( $_POST['avatar_size'] ) ) : '';

            $wpupa_hide_post_option = ! empty( $_POST['wpupa_hide_post_option'] ) ? sanitize_text_field( wp_unslash( $_POST['wpupa_hide_post_option'] ) ) : '';

            if ( $wpupa_show_avatars == '' ) {
                $wpupa_tinymce          = '';
                $wpupa_allow_upload     = '';
                $wpupa_disable_gravatar = '';
            }

            if ( $wpupa_disable_gravatar ) {
                $wpupa_default = 'wp_user_profile_avatar';
            }

            // options
            update_option( 'wpupa_tinymce', $wpupa_tinymce );
            update_option( 'wpupa_allow_upload', $wpupa_allow_upload );
            update_option( 'wpupa_disable_gravatar', $wpupa_disable_gravatar );
            update_option( 'wpupa_show_avatars', $wpupa_show_avatars );
            update_option( 'wpupa_rating', $wpupa_rating );
            update_option( 'wpupa_file_size', $wpupa_file_size );
            update_option( 'avatar_default', $wpupa_default );
            update_option( 'wpupa_attachment_id', $wpupa_attachment_id );
            update_option( 'wpupa_attachment_url', $wpupa_attachment_url); 
            update_option( 'wpupa_size', $wpupa_size );
            update_option( 'avatar_size', $avatar_size );
            update_option( 'wpupa_hide_post_option', $wpupa_hide_post_option );
        }
    }
}

new WPUPA_Settings();
