<h3><?php _e('WP User Profile Avatar Social Settings', 'wp-user-profile-avatar'); ?></h3>
<table class="form-table">
    <tr>
        <th>
            <label for="facebook-profile">Facebook User ID(numeric)</label>
        </th>
        <td>
            <input type="text" name="fb-profile" id="fb-profile" value=" <?php echo $wp_social_fb_profile; ?>" class="regular-text" />&nbsp;
            <span><a href="http://findmyfacebookid.com/" target="_blank">Find your facebook id here</a></span>
        </td>
    <tr>
        <th>
            <label for="use-fb-profile">Use Facebook Profile as Avatar</label>
        </th>
        <td>
            <input type="checkbox" name="wp-user-social-profile" value="wp-facebook" <?php checked($wp_user_social_profile, 'wp-facebook', false) ?> >
        </td>
    </tr>
    <tr>
        <th>
            <label for="gplus-profile">Google+ id</label>
        </th>
        <td>
            <input type="text" name="gplus-profile" id="gplus-profile" value=" <?php echo $wp_social_gplus_profile; ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="use-gplus-profile">Use Google+ Profile as Avatar</label>
        </th>
        <td>
            <input type="checkbox" name="wp-user-social-profile" value="wp-gplus" <?php checked($wp_user_social_profile, 'wp-gplus', false) ?> >
        </td>
    </tr>
    <tr>
        <th>
            <label for="gplus-clear-cache">Clear Google+ Cache</label></th>
        <td>
            <input type="button" name="wp-gplus-clear" value="Clear Cache" user="<?php echo $socialprofile->ID; ?>">
            <span id="msg"></span>
        </td>
    </tr>
</table>