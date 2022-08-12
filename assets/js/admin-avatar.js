var AdminAvatar = function () {

    return {

        init: function ()
        {
            jQuery('#wp-user-profile-avatar-add').on('click', AdminAvatar.actions.chooseAvatar);
            jQuery('#wp-user-profile-avatar-remove').on('click', AdminAvatar.actions.removeAvatar);
            jQuery('#wp-user-profile-avatar-undo').on('click', AdminAvatar.actions.undoAvatar);

            jQuery('body').on('click', '#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap .nav-tab-wrapper a', AdminAvatar.actions.showShortcodeAvatarTab);

            jQuery('body').on('change', '#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-link-to', AdminAvatar.actions.imageLinkTo);

            jQuery('body').on('click', '#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #user-avatar-form-btn', AdminAvatar.actions.addUserAvatarShortcode);

            jQuery('body').on('click', '#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-upload-avatar #upload-avatar-form-btn', AdminAvatar.actions.addUploadAvatarShortcode);

            if (typeof (tinymce) != "undefined")
            {
                tinymce.PluginManager.add('wp_user_profile_avatar_shortcodes', function (editor, url) {
                    editor.addButton('wp_user_profile_avatar_shortcodes', {
                        title: wp_user_profile_avatar_admin_avatar.icon_title,
                        classes: 'thickbox',
                        image: wp_user_profile_avatar_admin_avatar.default_avatar,
                        icon: false,
                        onclick: function () {

                        }
                    });
                });

                setTimeout(function () {
                    jQuery('.mce-thickbox button').remove();
                    jQuery('.mce-thickbox').html('<a href="' + wp_user_profile_avatar_admin_avatar.thinkbox_ajax_url + '" class="thickbox mce-toolbar" title="' + wp_user_profile_avatar_admin_avatar.thinkbox_title + '"><img class="mce-ico" src="' + wp_user_profile_avatar_admin_avatar.default_avatar + '"></a>');
                }, 1500);
            }

        },

        actions:
                {
                    /**
                     * showShortcodeAvatarTab function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    showShortcodeAvatarTab: function (event)
                    {
                        jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap .admin-setting-left .settings-panel').hide();

                        var id = jQuery(event.target).attr('href');

                        jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap .admin-setting-left ' + id).show();
                    },

                    /**
                     * imageLinkTo function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    imageLinkTo: function (event)
                    {
                        var link_to = jQuery(event.target).val();

                        if (link_to == 'custom')
                        {
                            jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-custom-link-to').attr('type', 'text');
                        } else
                        {
                            jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-custom-link-to').attr('type', 'hidden');
                        }
                    },

                    /**
                     * addUserAvatarShortcode function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    addUserAvatarShortcode: function (event)
                    {
                        var user_id = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-user-id').val();

                        var size = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-size').val();

                        var align = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-alignment').val();

                        var link = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-link-to').val();

                        var custom_link = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-custom-link-to').val();

                        var target = '';
                        if (jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-open-new-window').prop("checked") == true)
                        {
                            target = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-open-new-window').val();
                        }

                        var caption = jQuery('body').find('#TB_window #TB_ajaxContent .wp-user-profile-avatar-shortcode-wrap #settings-user-avatar #wp-image-caption').val();

                        var user_tag = (user_id != "") ? ' user_id="' + user_id + '"' : "";

                        var size_tag = (size != "") ? ' size="' + size + '"' : "";

                        var align_tag = (align != "") ? ' align="' + align + '"' : "";

                        var link_tag = (link != "" && link != 'custom' && custom_link == "") ? ' link="' + link + '"' : "";
                        link_tag = (custom_link != "") ? ' link="' + custom_link + '"' : link_tag;

                        var target_tag = (target != "") ? ' target="' + target + '"' : "";

                        var shortcode = "<p>[user_profile_avatar" + user_tag + size_tag + align_tag + link_tag + target_tag + "] " + caption + " [/user_profile_avatar]</p>";

                        tinymce.activeEditor.insertContent(shortcode);
                        //editor.insertContent('[wdm_shortcode]');

                        jQuery('body').find('#TB_window #TB_title #TB_closeAjaxWindow #TB_closeWindowButton').trigger("click");
                    },

                    /**
                     * addUploadAvatarShortcode function.
                     *
                     * @access public
                     * @param 
                     * @return 
                     * @since 1.0
                     */
                    addUploadAvatarShortcode: function (event)
                    {
                        var shortcode = "<p>[user_profile_avatar_upload]</p>";

                        tinymce.activeEditor.insertContent(shortcode);
                        //editor.insertContent('[wdm_shortcode]');

                        jQuery('body').find('#TB_window #TB_title #TB_closeAjaxWindow #TB_closeWindowButton').trigger("click");
                    },

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
                            title: wp_user_profile_avatar_admin_avatar.media_box_title, /*Title for Media Box*/
                            multiple: false /*For limiting multiple image*/
                        })
                                .on('select', function ()
                                {
                                    var select = upload.state().get('selection');
                                    var attach = select.first().toJSON();

                                    jQuery('#wp-user-profile-avatar-preview img').attr('src', attach.url);
                                    jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', attach.url);
                                    jQuery('#wpupa_attachment_id').attr('value', attach.id);
                                    jQuery('#wp_user_profile_avatar_radio').trigger('click');
                                    jQuery('#wp-user-profile-avatar-undo-button').show();
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
                        jQuery('#wp-user-profile-avatar-preview img').attr('src', wp_user_profile_avatar_admin_avatar.default_avatar);
                        jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', wp_user_profile_avatar_admin_avatar.default_avatar);
                        jQuery('#wpupa_attachment_id').attr('value', '');
                        jQuery('#wpupa-url').attr('value', '');

                        jQuery('#wp-user-profile-avatar-remove').hide();
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
                        jQuery('#wp-user-profile-avatar-preview img').attr('src', wp_user_profile_avatar_admin_avatar.default_avatar);
                        jQuery('#wp-user-profile-avatar-thumbnail img').attr('src', wp_user_profile_avatar_admin_avatar.default_avatar);
                        jQuery('#wpupa_attachment_id').attr('value', '');

                        jQuery('#wp-user-profile-avatar-undo-button').hide();
                    },

                } /* end of action */

    }; /* enf of return */

}; /* end of class */

AdminAvatar = AdminAvatar();

jQuery(document).ready(function ($)
{
    AdminAvatar.init();
});