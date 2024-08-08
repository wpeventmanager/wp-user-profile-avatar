<?php
/**
 * Tools page.
 *
 * @package Disable_Comments
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html_x( 'Delete Comments', 'settings page title', 'wp-user-profile-avatar' ); ?></h1>
    <?php
    if ( isset( $_POST['submit'] ) && isset( $_POST['delete_comments_nonce_field'] ) && wp_verify_nonce( $_POST['delete_comments_nonce_field'], 'delete_comments_nonce' ) ) {
        
        $mode = sanitize_text_field( $_POST['mode'] );
        update_option( 'wpupa_delete_comments_mode', $mode );

        if ( 'delete_everywhere' === $mode ) {

            $deleted_count = wpupa_delete_comments_everywhere();
            echo '<div class="notice notice-success"><p>';
			printf(
				/* translators: %d: Number of deleted comments */
				esc_html__( '%d comments have been deleted from your site.', 'wp-user-profile-avatar' ),
				intval( $deleted_count )
			);
			echo '</p></div>';
            update_option( 'wpupa_selected_post_types', array() );
            
        } elseif ( 'selected-post-types' === $mode ) {
           
            if ( isset( $_POST['selected_post_types'] ) && is_array( $_POST['selected_post_types'] ) && ! empty( $_POST['selected_post_types'] ) ) {
                
                $selected_post_types = array_map( 'sanitize_text_field', $_POST['selected_post_types'] );
                update_option( 'wpupa_selected_post_types', $selected_post_types ); // Save selected post types
                $deleted_count = wpupa_delete_comments_by_post_types( $selected_post_types );
                echo '<div class="notice notice-success"><p>';
				printf(
					/* translators: %d: Number of deleted comments */
					esc_html__( '%d comments have been deleted from selected post types.', 'wp-user-profile-avatar' ),
					intval( $deleted_count )
				);
				echo '</p></div>';
            
            } else {
                update_option( 'wpupa_selected_post_types', array() );
            }
        }
    }

    // Handle reset action
    if ( isset( $_POST['reset'] ) && isset( $_POST['delete_comments_nonce_field'] ) && wp_verify_nonce( $_POST['delete_comments_nonce_field'], 'delete_comments_nonce' ) ) {
        delete_option( 'wpupa_delete_comments_mode' );
        delete_option( 'wpupa_selected_post_types' );
    }

    ?>
    <form action="" method="post" id="delete-comments">
        <ul>
            <li>
                <label for="delete_everywhere">
                    <input type="radio" id="delete_everywhere" name="mode" value="delete_everywhere" <?php checked( get_option('wpupa_delete_comments_mode'), 'delete_everywhere' ); ?> />
                    <strong><?php esc_html_e( 'Everywhere', 'wp-user-profile-avatar' ); ?></strong>:
                    <?php esc_html_e( 'Delete all comments across your entire site.', 'wp-user-profile-avatar' ); ?>
                </label>
                <p class="indent">
                    <?php printf( esc_html__( '%1$s: This option is global and will affect your entire site. Use it only if you want to delete comments everywhere.', 'wp-user-profile-avatar' ), '<strong style="color: #900">' . esc_html__( 'Warning', 'wp-user-profile-avatar' ) . '</strong>' ); ?>
                </p>
            </li>
            <li>
                <label for="selected-post-types">
                    <input type="radio" id="selected-post-types" name="mode" value="selected-post-types" <?php checked( get_option('wpupa_delete_comments_mode'), 'selected-post-types' ); ?> />
                    <strong><?php esc_html_e( 'On certain post types', 'wp-user-profile-avatar' ); ?></strong>:
                </label>
                <p class="indent"><?php esc_html_e( 'Deleting comments will permanently remove them from selected post types.', 'wp-user-profile-avatar' ); ?></p>
                
                <ul class="indent" id="list-of-post-types">
                    <?php
                    $post_types = get_post_types( array( 'public' => true ), 'objects' );
                    $selected_post_types = get_option( 'wpupa_selected_post_types', array() );
                    foreach ( $post_types as $post_type ) {
                        ?>
                        <li>
                            <label for="post-type-<?php echo esc_attr( $post_type->name ); ?>">
                                <input type="checkbox" name="selected_post_types[]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php checked( in_array( $post_type->name, $selected_post_types ) ); ?> id="post-type-<?php echo esc_attr( $post_type->name ); ?>">
                                <?php echo esc_html( $post_type->labels->name ); ?>
                            </label>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
        <?php wp_nonce_field( 'delete_comments_nonce', 'delete_comments_nonce_field' ); ?>
        <p class="submit">
            <input class="button-primary" type="submit" name="submit" value="<?php esc_html_e( 'Save Changes', 'wp-user-profile-avatar' ); ?>">
            <input class="button-secondary" type="submit" name="reset" value="<?php esc_html_e( 'Reset Settings', 'wp-user-profile-avatar' ); ?>">
        </p>
    </form>
</div>
