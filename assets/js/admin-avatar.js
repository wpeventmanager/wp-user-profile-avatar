var AvatarAdmin = function () {

    return {

	    init: function() 
        {
        	jQuery( '#wpem-add' ).on('click', AvatarAdmin.actions.chooseAvatar);
            jQuery( '#wpem-remove' ).on('click', AvatarAdmin.actions.removeAvatar);
            jQuery( '#wpem-undo' ).on('click', AvatarAdmin.actions.undoAvatar);
        },

	    actions:
	    {
	    	chooseAvatar: function (event) 
	    	{
                var upload = wp.media({
                    library: {
                        type: 'image'
                    },
                    title: wpem_admin_avatar.media_box_title, /*Title for Media Box*/
                    multiple: false /*For limiting multiple image*/
                })
                .on('select', function ()
                {
                    var select = upload.state().get('selection');
                    var attach = select.first().toJSON();

                    jQuery('#wpem-preview img').attr('src', attach.url);
                    jQuery('#wpem-thumbnail img').attr('src', attach.url);
                    jQuery('#wp_user_profile_avatar_attachment_id').attr('value', attach.id);
                    jQuery('#wp_user_profile_avatar_radio').trigger('click');
                    jQuery('#wpem-undo-button').show();
                })
                .open();
	        },

            removeAvatar: function (event) 
            {
                jQuery('#wpem-preview img').attr('src', wpem_admin_avatar.default_avatar);
                jQuery('#wpem-thumbnail img').attr('src', wpem_admin_avatar.default_avatar);
                jQuery('#wp_user_profile_avatar_attachment_id').attr('value', '');

                jQuery('#wpem-remove').hide();
            },

            undoAvatar: function (event) 
            {
                jQuery('#wpem-preview img').attr('src', wpem_admin_avatar.default_avatar);
                jQuery('#wpem-thumbnail img').attr('src', wpem_admin_avatar.default_avatar);
                jQuery('#wp_user_profile_avatar_attachment_id').attr('value', '');

                jQuery('#wpem-undo-button').hide();
            },
		
		} /* end of action */

    }; /* enf of return */

}; /* end of class */

AvatarAdmin = AvatarAdmin();

jQuery(document).ready(function($) 
{
   AvatarAdmin.init();
});
