<div class="wrap wp-user-profile-avatar-shortcode-wrap">
    <h2 class="nav-tab-wrapper">
        <a href="#settings-user-avatar" class="nav-tab"><?php esc_html_e( 'Change User', 'wp-user-profile-avatar' ); ?></a>
        <a href="#settings-upload-avatar" class="nav-tab"><?php esc_html_e( 'Upload Avatar', 'wp-user-profile-avatar' ); ?></a>         
    </h2>

    <div class="admin-setting-left">                    
        <div class="white-background">
            <div id="settings-user-avatar" class="settings-panel">
                <form name="user-avatar-form" class="user-avatar-form">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'User Name', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <select id="wp-user-id" name="wp-user-id" class="regular-text">
                                    <option value=""><?php esc_html_e( 'Select User', 'wp-user-profile-avatar' ); ?>
                                        <?php
                                        foreach ( get_users() as $key => $user ) {
                                            echo '<option value="' . esc_attr( $user->ID ) . '">' . esc_attr( $user->display_name ) . '</option>';
                                        }
                                        ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Size', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <select id="wp-image-size" name="wp-image-size" class="regular-text">
                                    <option value="wpupavatar-default"><?php esc_html_e( 'Default', 'wp-user-profile-avatar' ); ?>
                                        <?php
                                        foreach ( get_wpupa_image_sizes() as $name => $label ) {
                                            echo '<option value="' . esc_attr( $name ) . '">' . esc_attr( $label ) . '</option>';
                                        }
                                        ?>
                                </select>
                                <p class="description"><?php esc_html_e( 'size parameter only work for uploaded avatar not with custom url.', 'wp-user-profile-avatar' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Alignment', 'wp-user-profile-avatar' ); ?></th>                                      
                            <td>
                                <select id="wp-image-alignment" name="wp-image-alignment" class="regular-text">
                                    <option value=""><?php esc_html_e( 'None', 'wp-user-profile-avatar' ); ?>
                                        <?php
                                        foreach ( get_wpupa_image_alignment() as $name => $label ) {
                                            echo '<option value="' . esc_attr( $name ) . '">' . esc_attr( $label ) . '</option>';
                                        }
                                        ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Link To', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <select id="wp-image-link-to" name="wp-image-link-to" class="regular-text">
                                        <?php
                                        foreach ( get_wpupa_image_link_to() as $name => $label ) {
                                            echo '<option value="' . esc_attr( $name ) . '">' . esc_attr( $label ) . '</option>';
                                        }
                                        ?>
                                </select>
                                <p><input type="hidden" name="wp-custom-link-to" id="wp-custom-link-to" class="regular-text" placeholder="<?php esc_html_e( 'Add custom URL', 'wp-user-profile-avatar' ); ?>"></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Open link in a new window', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <input type="checkbox" name="wp-image-open-new-window" id="wp-image-open-new-window" value="_blank" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Caption', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <input type="text" name="wp-image-caption" id="wp-image-caption" class="regular-text">
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <button type="button" class="button-primary" id="user-avatar-form-btn"><?php esc_html_e( 'Insert Shortcode', 'wp-user-profile-avatar' ); ?></button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div id="settings-upload-avatar" class="settings-panel">
                <form name="upload-avatar-form" class="upload-avatar-form">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Shortcode', 'wp-user-profile-avatar' ); ?></th>
                            <td>
                                <input type="text" name="upload-avatar-shortcode" id="upload-avatar-shortcode" value="[user_profile_avatar_upload]" class="regular-text" readonly >
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="button" class="button-primary" id="upload-avatar-form-btn"><?php esc_html_e( 'Insert Shortcode', 'wp-user-profile-avatar' ); ?></button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
