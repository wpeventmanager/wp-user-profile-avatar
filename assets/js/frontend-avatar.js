var FrontendAvatar = function () {

    return {

        init: function ()
        {
            jQuery('#wp-user-profile-avatar-add').on('click', FrontendAvatar.actions.chooseAvatar);
            jQuery('#wp-user-profile-avatar-remove').on('click', FrontendAvatar.actions.removeAvatar);
            jQuery('#wp-user-profile-avatar-undo').on('click', FrontendAvatar.actions.undoAvatar);
            jQuery('#wp-user-profile-avatar-update-profile').on('click', FrontendAvatar.actions.updateAvatar);
        },

        actions:
                {
                    /**
                     * chooseAvatar function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    chooseAvatar: function (event)
                    {
                        var file_frame;
                        event.preventDefault();
                        // if the file_frame has already been created, just reuse it
                        if (file_frame) {
                            file_frame.open();
                            return;
                        }

                        file_frame = wp.media.frames.file_frame = wp.media({
                            title: jQuery(this).data('uploader_title'),
                            button: {
                                text: jQuery(this).data('uploader_button_text'),
                            },
                            multiple: false // set this to true for multiple file selection
                        });
                        file_frame.on('select', function () {
                            attachment = file_frame.state().get('selection').first().toJSON();
                            console.log(attachment);
                            // do something with the file here
                            jQuery('#wpupaattachmentid').attr('value', attachment.id);
                            jQuery('#wp-user-profile-avatar-preview img').attr('src', attachment.sizes['full']['url']);
                            jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', attachment.sizes['thumbnail']['url']);
                        });
                        file_frame.open();
                    },
                    /**
                     * removeAvatar function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    removeAvatar: function (event)
                    {
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-error');
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-success');
                        jQuery('#upload-avatar-responce').html('');

                        var form_data = jQuery('.update-user-profile-avatar').serialize();

                        var fd = new FormData();
                        fd.append("action", 'remove_user_avatar');
                        fd.append("form_data", form_data);
                        fd.append("security", wp_user_profile_avatar_frontend_avatar.wp_user_profile_avatar_security);

                        jQuery.ajax({
                            url: wp_user_profile_avatar_frontend_avatar.ajax_url,
                            type: 'post',
                            dataType: 'JSON',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (responce)
                            {
                                jQuery('#upload-avatar-responce').addClass(responce.class);
                                jQuery('#upload-avatar-responce').html(responce.message);

                                if (responce.class == 'wp-user-profile-avatar-success')
                                {
                                    jQuery('#wp-user-profile-avatar-preview img').attr('src', responce.avatar_original);
                                    jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', responce.avatar_thumbnail);

                                    jQuery('.update-user-profile-avatar #wpupa-url').val('');
                                    jQuery('.update-user-profile-avatar #wpupaattachmentid').val('');
                                    jQuery('#wp-user-profile-avatar-remove-button').hide();
                                }
                            }
                        });
                    },

                    /**
                     * undoAvatar function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    undoAvatar: function (event)
                    {
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-error');
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-success');
                        jQuery('#upload-avatar-responce').html('');

                        var form_data = jQuery('.update-user-profile-avatar').serialize();

                        var fd = new FormData();
                        fd.append("action", 'undo_user_avatar');
                        fd.append("form_data", form_data);
                        fd.append("security", wp_user_profile_avatar_frontend_avatar.wp_user_profile_avatar_security);

                        jQuery.ajax({
                            url: wp_user_profile_avatar_frontend_avatar.ajax_url,
                            type: 'post',
                            dataType: 'JSON',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (responce)
                            {
                                jQuery('#upload-avatar-responce').addClass(responce.class);
                                jQuery('#upload-avatar-responce').html(responce.message);

                                if (responce.class == 'wp-user-profile-avatar-success')
                                {
                                    jQuery('#wp-user-profile-avatar-preview img').attr('src', responce.avatar_original);
                                    jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', responce.avatar_thumbnail);

                                    jQuery('#wp-user-profile-avatar-undo-button').hide();
                                }
                            }
                        });
                    },

                    /**
                     * updateAvatar function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    updateAvatar: function (event)
                    {
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-error');
                        jQuery('#upload-avatar-responce').removeClass('wp-user-profile-avatar-success');
                        jQuery('#upload-avatar-responce').html('');

                        var form_data = jQuery('.update-user-profile-avatar').serialize();

                        var fd = new FormData();
                        fd.append("user-avatar", jQuery('#wp-user-profile-avatar-add'));
                        fd.append("action", 'update_user_avatar');
                        fd.append("form_data", form_data);
                        fd.append("security", wp_user_profile_avatar_frontend_avatar.wp_user_profile_avatar_security);

                        jQuery.ajax({
                            url: wp_user_profile_avatar_frontend_avatar.ajax_url,
                            type: 'post',
                            dataType: 'JSON',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (responce)
                            {
                                console.log(responce);
                                jQuery('#upload-avatar-responce').addClass(responce.class);
                                jQuery('#upload-avatar-responce').html(responce.message);

                                if (responce.class == 'wp-user-profile-avatar-success')
                                {
                                    jQuery('#wp-user-profile-avatar-preview img').attr('src', responce.avatar_original);
                                    jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', responce.avatar_thumbnail);
                                    jQuery('#update-user-profile-avatar').trigger('reset');
                                    jQuery('#wp-user-profile-avatar-undo-button').show();
                                }
                            }
                        });

                    },

                } /* end of action */

    }; /* enf of return */

}; /* end of class */

FrontendAvatar = FrontendAvatar();

jQuery(document).ready(function ($)
{
    FrontendAvatar.init();
});
