<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$options = array(
    'wpupa_version',
    'wpupa_show_avatars',
    'wpupaattachmentid',
);

foreach ( $options as $option ) {
    delete_option( $option );
}

$users = get_users();

foreach ( $users as $user ) {
    delete_user_meta( $user->ID, '_wpupaattachmentid' );
    delete_user_meta( $user->ID, '_wpupa_default' );
    delete_user_meta( $user->ID, '_wpupa-url' );
    delete_user_meta( $user->ID, 'wpupa-tinymce' );
    delete_user_meta( $user->ID, 'wpupa_file_size' );
    delete_user_meta( $user->ID, 'wpupa-size' );
    delete_user_meta( $user->ID, 'wpupa-allow-upload' );
    delete_user_meta( $user->ID, 'wpupa-disable-gravatar' );
}
