<?php
/**
 * Setting page.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>  
<div class="wrap">
    <h1><?php echo esc_html_x( 'Disable Comments', 'settings page title', 'wp-user-profile-avatar' ); ?></h1>
    <?php
		if ( isset( $_POST['submit'] ) && isset( $_POST['disable_comments_nonce_field'] ) && wp_verify_nonce( $_POST['disable_comments_nonce_field'], 'disable_comments_nonce' ) ) {
			
			$mode = sanitize_text_field( $_POST['mode'] );
			update_option( 'wpupa_disable_comments_mode', $mode );
			
			if ( 'selected-types' === $mode && isset( $_POST['disabled_post_types'] ) && is_array( $_POST['disabled_post_types'] ) ) {
				$disabled_post_types = array_map( 'sanitize_text_field', $_POST['disabled_post_types'] );
			} else {
				$disabled_post_types = array();
			}
			update_option( 'wpupa_disabled_post_types', $disabled_post_types );
		}

        // Handle reset action
        if ( isset( $_POST['reset'] ) && isset( $_POST['disable_comments_nonce_field'] ) && wp_verify_nonce( $_POST['disable_comments_nonce_field'], 'disable_comments_nonce' ) ) {
            delete_option( 'wpupa_disable_comments_mode' );
            delete_option( 'wpupa_disabled_post_types' );
        }

	?>
    <form action="" method="post" id="disable-comments">
        <ul>
            <li>
                <label for="remove_everywhere">
                    <input type="radio" id="remove_everywhere" name="mode" value="remove_everywhere" <?php checked( get_option('wpupa_disable_comments_mode'), 'remove_everywhere' ); ?> />
                    <strong><?php esc_html_e( 'Everywhere', 'wp-user-profile-avatar' ); ?></strong>:
                    <?php esc_html_e( 'Disable all comment-related controls and settings in WordPress.', 'wp-user-profile-avatar' ); ?>
                </label>
                <p class="indent">
                    <?php printf(
							/* translators: 1: Warning label, 2: URL link */
							esc_html__( '%1$s: This option is global and will affect your entire site. Use it only if you want to disable comments everywhere. A complete description of what this option does is %2$s.', 'wp-user-profile-avatar' ),
							'<strong style="color: #900">' . esc_html__( 'Warning', 'wp-user-profile-avatar' ) . '</strong>',
							'<a href="' . esc_url( 'https://wordpress.org/plugins/disable-comments/other_notes/' ) . '" target="_blank">' . esc_html__( 'available here', 'wp-user-profile-avatar' ) . '</a>'
						); ?>
                </p>
            </li>
            <li>
                <label for="selected-types">
                    <input type="radio" id="selected-types" name="mode" value="selected-types" <?php checked( get_option('wpupa_disable_comments_mode'), 'selected-types' ); ?> />
                    <strong><?php esc_html_e( 'On certain post types', 'wp-user-profile-avatar' ); ?></strong>:
                </label>
                <p class="indent"><?php esc_html_e( 'Disabling comments will also disable trackbacks and pingbacks. All comment-related fields will also be hidden from the edit/quick-edit screens of the affected posts. These settings cannot be overridden for individual posts.', 'wp-user-profile-avatar' ); ?></p>
                
                <ul class="indent" id="listoftypes">
                    <?php
                    $post_types = get_post_types( array( 'public' => true ), 'objects' );
                    $disabled_post_types = get_option( 'wpupa_disabled_post_types', array() );
                    foreach ( $post_types as $post_type ) {
                        ?>
                        <li>
                            <label for="post-type-<?php echo esc_attr( $post_type->name ); ?>">
                                <input type="checkbox" name="disabled_post_types[]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php checked( in_array( $post_type->name, $disabled_post_types ) ); ?> id="post-type-<?php echo esc_attr( $post_type->name ); ?>">
                                <?php echo esc_html( $post_type->labels->name ); ?>
                            </label>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
        <?php wp_nonce_field( 'disable_comments_nonce', 'disable_comments_nonce_field' ); ?>
        <p class="submit">
            <input class="button-primary" type="submit" name="submit" value="<?php esc_html_e( 'Save Changes', 'wp-user-profile-avatar' ); ?>">
            <input class="button-secondary" type="submit" name="reset" value="<?php esc_html_e( 'Reset Settings', 'wp-user-profile-avatar' ); ?>">
        </p>
    </form>
</div>