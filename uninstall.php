<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

$options = array(
    'wpupa_version',
    'wpupa_show_avatars',
    'wpupa_attachment_id',
);

foreach ($options as $option) {
    delete_option($option);
}

$users = get_users();

foreach ($users as $user) {
    delete_user_meta($user->ID, '_wpupa_attachment_id');
    delete_user_meta($user->ID, '_wpupa_default');
    delete_user_meta($user->ID, '_wpupa_url');
    delete_user_meta($user->ID, 'wpupa_tinymce');
    delete_user_meta($user->ID, 'wpupa_file_size');
    delete_user_meta($user->ID, 'wpupa_size');
    delete_user_meta($user->ID, 'wpupa_allow_upload');
    delete_user_meta($user->ID, 'wpupa_disable_gravatar');
}
