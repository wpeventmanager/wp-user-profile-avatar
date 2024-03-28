<?php
/**
 * Wp User List Page & Username Update Page.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \WpUserNameChange\WpUserNameChange;

function Wp_username_edit() {
    ?>
    <div class="wrap userupdater">
        <p><h1><?php esc_html_e( 'Wp Users List', 'WP_Username_change' ); ?></h1></p>
    <?php
    $wpuser  = new WpUserNameChange();
    $records = $wpuser->wpuser_select();

    if ( $records ) {
        ?>
        <table class="wp-list-table widefat fixed striped users"  cellpadding="3" cellspacing="3" width="100%">
            <thead>
                <tr>
                    <th><strong><?php esc_html_e( 'User ID', 'WP_Username_change' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'User Name', 'WP_Username_change' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Role', 'WP_Username_change' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Update', 'WP_Username_change' ); ?></strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $records as $user ) {
                    $user_info = get_userdata( $user->ID );
                    ?>
                    <tr>
                        <td><?php echo esc_attr( $user->ID ); ?></td>
                        <td><?php echo esc_attr( $user->user_login ); ?></td>
                        <td><?php echo implode( ', ', $user_info->roles ); ?></td>
                        <td><a href="<?php echo esc_url( admin_url( 'admin.php?page=Wp_username_update&update=' . $user->ID ) ); ?>">update</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    </div>
    <?php
}

function Wp_user_update() {
    if ( isset( $_REQUEST['update'] ) ) {
        $wpuser = new WpUserNameChange();
        global $wpdb;
        $id        = trim( $_REQUEST['update'] );
        $user_info = get_userdata( $id );
        $result    = $wpdb->get_results( $wpdb->prepare( "SELECT * from $wpdb->users WHERE ID = %d", $id ) );
        foreach ( $result as $user ) {
            $username = $user->user_login;
        }
        if ( ! empty( $_REQUEST['submit'] ) ) {
            $name = sanitize_user( $_POST['user_login'] );
            if ( empty( $name ) ) {
                $errorMsg = 'Error : Please do not enter  empty username.';
            } elseif ( username_exists( $name ) ) {
                $errorMsg = 'Error: This username(<i>' . esc_attr( $name ) . '</i>) is already exist.';
            } else {
                $wpuser->wpuser_update( $id, $name );
                echo '<div class="updated"><p><strong>Username Updated</strong></p></div>';
            }
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Update WP Username', 'WP_Username_change' ); ?></h1>
            <?php
            if ( isset( $errorMsg ) ) {
                echo "<div class='error'><p><strong>" . esc_attr( $errorMsg ) . '</strong></p></div>';
            }
            ?>
        </div>
        <form method="post" id="user_udate" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
            <table class="form-table">
                <tr>
                    <th><label for="olduser_login"><?php esc_html_e( 'Old Username', 'WP_Username_change' ); ?></label></th>
                    <td><strong><?php echo esc_attr( $username ); ?></strong></td>
                </tr>
                <tr>
                    <th><label for="user_login"><?php esc_html_e( 'New Username', 'WP_Username_change' ); ?></label></th>
                    <td><input type="text" name="user_login" class="regular-text" id="user_login" value="
                    <?php
                    if ( ! empty( $_POST['user_login'] ) ) {
                        echo esc_attr( $name );}
                    ?>
                    "/></td>
                </tr>
            </table>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Update WP Username">
        </form>
        <?php
    } else {
        ?>
        <script>
            window.location = '<?php echo esc_url( admin_url( 'admin.php?page=WP_Username_change' ) ); ?>'
        </script>
        <?php
    }
}
