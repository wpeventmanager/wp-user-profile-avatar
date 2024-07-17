var FrontendAvatar = function () {

    return {

        init: function ()
        {
            jQuery('#wp-user-profile-avatar-add').off("click").on('click', FrontendAvatar.actions.chooseAvatar);
            jQuery('#wp-user-profile-avatar-remove').off("click").on('click', FrontendAvatar.actions.removeAvatar);
            jQuery('#wp-user-profile-avatar-undo').off("click").on('click', FrontendAvatar.actions.undoAvatar);
            jQuery('#wp-user-profile-avatar-update-profile').off("click").on('click', FrontendAvatar.actions.updateAvatar);
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
                        var upload = wp.media({
                            library: {
                                type: 'image'
                            },
                            title: wp_user_profile_avatar_frontend_avatar.media_box_title, 
                            multiple: false 
                        })
                        .on( 'select', function ()
                        {
                            var select = upload.state().get( 'selection' );
                            var attach = select.first().toJSON();

                            jQuery( '#wp-user-profile-avatar-preview img' ).attr( 'src', attach.url );
                            jQuery( '#wp-user-profile-avatar-thumbnail img' ).attr( 'src', attach.url );
                            jQuery( '#wpupaattachmentid' ).attr( 'value', attach.id );
                            jQuery( '#wp_user_profile_avatar_radio' ).trigger( 'click' );
                            jQuery( '#wp-user-profile-avatar-undo-button' ).show();
                        })
                        .open();
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
                        jQuery( '#wp-user-profile-avatar-preview img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
                        jQuery( '#wp-user-profile-avatar-thumbnail img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
                        jQuery( '#wpupaattachmentid' ).attr( 'value', '' );
                        jQuery( '#wpupa-url' ).attr( 'value', '' );

                        jQuery( '#wp-user-profile-avatar-remove' ).hide();
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
                        jQuery( '#wp-user-profile-avatar-preview img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
                        jQuery( '#wp-user-profile-avatar-thumbnail img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
                        jQuery( '#wpupaattachmentid' ).attr( 'value', '' );

                        jQuery( '#wp-user-profile-avatar-undo-button' ).hide();
                        jQuery('#update-user-profile-avatar').trigger('reset');
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
                                jQuery('#upload-avatar-responce').addClass(responce.class);
                                jQuery('#upload-avatar-responce').html(responce.message);

                                if (responce.class == 'wp-user-profile-avatar-success')
                                {
                                    jQuery('#wp-user-profile-avatar-preview img').attr('src', responce.avatar_original);
                                    jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', responce.avatar_thumbnail);
                                    jQuery('#update-user-profile-avatar').trigger('reset');
                                    jQuery('#wpupaattachmentid').val(response.form_wpupaattachmentid);

                                    if (response.form_wpupaattachmentid == '0') {
                                        jQuery('#wp-user-profile-avatar-remove-button').hide();
										 jQuery( '#wp-user-profile-avatar-preview img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
										jQuery( '#wp-user-profile-avatar-thumbnail img' ).attr( 'src', wp_user_profile_avatar_frontend_avatar.default_avatar );
										
                                    } else {
                                        jQuery('#wp-user-profile-avatar-remove-button').show();
                                    }
                                }
                                location.reload();
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
