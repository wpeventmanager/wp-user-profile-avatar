
<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WP_User_Profile_Avatar_Settings class.
 */

class WP_User_Profile_Avatar_Settings {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{
		add_action( 'wp_loaded', array( $this, 'edit_handler' ) );
	}
    /**
     * settings function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
	public function settings() 
	{
		wp_enqueue_media();

		wp_enqueue_style( 'wp-user-profile-avatar-backend');

		wp_enqueue_script( 'wp-user-profile-avatar-admin-avatar' );

		//options
		$wp_user_profile_avatar_tinymce = get_option('wp_user_profile_avatar_tinymce');
		$wp_user_profile_avatar_allow_upload = get_option('wp_user_profile_avatar_allow_upload');
		$wp_user_profile_avatar_disable_gravatar = get_option('wp_user_profile_avatar_disable_gravatar');
		$wp_user_profile_avatar_show_avatars = get_option('wp_user_profile_avatar_show_avatars');
		$wp_user_profile_avatar_rating = get_option('wp_user_profile_avatar_rating');
		$wp_user_profile_avatar_default = get_option('wp_user_profile_avatar_default');
		$wp_user_profile_avatar_attachment_id = get_option('wp_user_profile_avatar_attachment_id');

		$wp_user_profile_avatar_attachment_url = get_wp_user_default_avatar_url(['size' => 'admin']);
		?>

		<div class="wrap">
		  	<h2><?php _e('WP User Profile Avatar', 'wp-user-profile-avatar'); ?></h2>
		  	<table>
		  		<tr valign="top">
		  			<td>
			  			<form method="post" action="<?php echo admin_url('users.php'). '?page=wp-user-profile-avatar-settings'; ?>">

				  			<table class="form-table">

				  				<tr valign="top">
			  						<th scope="row"><?php _e('Avatar Visibility', 'wp-user-profile-avatar'); ?></th>
			  						<td>
			  							<fieldset>
							              <label for="wp_user_profile_avatar_show_avatars">
							                <input name="wp_user_profile_avatar_show_avatars" type="checkbox" id="wp_user_profile_avatar_show_avatars" value="1" <?php echo checked($wp_user_profile_avatar_show_avatars, 1, 0); ?> > <?php _e('Show Avatars', 'wp-user-profile-avatar'); ?>
							              </label>
							              <p class="description"><?php _e('If it is unchecked then it will not show the user avatar at profile and frontend side.', 'wp-user-profile-avatar'); ?></p>
							            </fieldset>
			  						</td>
			  					</tr>

			  					<tr valign="top">
			  						<th scope="row"><?php _e('Settings', 'wp-user-profile-avatar'); ?></th>
			  						<td>
				  						<fieldset>
							              <label for="wp_user_profile_avatar_tinymce">
							                <input name="wp_user_profile_avatar_tinymce" type="checkbox" id="wp_user_profile_avatar_tinymce" value="1" <?php echo checked($wp_user_profile_avatar_tinymce, 1, 0); ?> > <?php _e('Add shortcode avatar button to Visual Editor', 'wp-user-profile-avatar'); ?>
							              </label>
							            </fieldset>

							            <fieldset>
							              <label for="wp_user_profile_avatar_allow_upload">
							                <input name="wp_user_profile_avatar_allow_upload" type="checkbox" id="wp_user_profile_avatar_allow_upload" value="1"<?php echo checked($wp_user_profile_avatar_allow_upload, 1, 0); ?> > <?php _e('Allow Contributors &amp; Subscribers to upload avatars', 'wp-user-profile-avatar'); ?>
							              </label>
							            </fieldset>

							            <fieldset>
							              <label for="wp_user_profile_avatar_disable_gravatar">
							                <input name="wp_user_profile_avatar_disable_gravatar" type="checkbox" id="wp_user_profile_avatar_disable_gravatar" value="1"<?php echo checked($wp_user_profile_avatar_disable_gravatar, 1, 0); ?> > <?php _e('Disable Gravatar and use own custom avatars', 'wp-user-profile-avatar'); ?>
							              </label>
							            </fieldset>
			  						</td>
			  					</tr>

			  					

			  					<tr valign="top">
			  						<th scope="row"><?php _e('Maximum Rating', 'wp-user-profile-avatar'); ?></th>
			  						<td>
			  							<fieldset>
							              	<legend class="screen-reader-text"><?php _e('Maximum Rating','wp-user-profile-avatar'); ?></legend>
							              	<?php foreach (get_wp_user_avatar_rating() as $name => $rating) : ?>
							              		<?php $selected = ($wp_user_profile_avatar_rating == $name) ? 'checked="checked"' : ""; ?>
							              		<label><input type="radio" name="wp_user_profile_avatar_rating" value="<?php echo esc_attr( $name ); ?>" <?php echo $selected; ?> /> <?php echo $rating; ?></label><br />
							              	<?php endforeach; ?>
							            </fieldset>
			  						</td>
			  					</tr>

			  					<tr valign="top">
			  						<th scope="row"><?php _e('Default Avatar', 'wp-user-profile-avatar'); ?></th>
			  						<td class="defaultavatarpicker">
			  							<fieldset>
							              	<legend class="screen-reader-text"><?php _e('Default Avatar','wp-user-profile-avatar'); ?></legend>
							              	<?php _e('For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their e-mail address.','wp-user-profile-avatar'); ?><br />
							              	
							              	<?php $selected = ($wp_user_profile_avatar_default == 'wp_user_profile_avatar') ? 'checked="checked"' : ""; ?>
							              	<label><input type="radio" name="wp_user_profile_avatar_default" id="wp_user_profile_avatar_radio" value="wp_user_profile_avatar" <?php echo $selected; ?> /> <div id="wp-user-profile-avatar-preview"><img src="<?php echo $wp_user_profile_avatar_attachment_url; ?>" width="32" /></div> <?php _e('WP User Profile Avatar'); ?> </label><br />

							              	<?php
							              	$class_hide = 'wp-user-profile-avatar-hide';
							              	if(!empty($wp_user_profile_avatar_attachment_id))
							              	{
							              		$class_hide = '';
							              	}
							              	?>
							              	<p id="wp-user-profile-avatar-edit">
							              		<button type="button" class="button" id="wp-user-profile-avatar-add" name="wp-user-profile-avatar-add"><?php _e('Choose Image'); ?></button>
							              		<span id="wp-user-profile-avatar-remove-button" class="<?php echo $class_hide; ?>"><a href="javascript:void(0)" id="wp-user-profile-avatar-remove"><?php _e('Remove'); ?></a></span>
							              		<span id="wp-user-profile-avatar-undo-button"><a href="javascript:void(0)" id="wp-user-profile-avatar-undo"><?php _e('Undo'); ?></a></span>
							              		<input type="hidden" name="wp_user_profile_avatar_attachment_id" id="wp_user_profile_avatar_attachment_id" value="<?php echo $wp_user_profile_avatar_attachment_id; ?>">
							              	</p>

							              	<?php if(empty($wp_user_profile_avatar_disable_gravatar)) : ?>
							              	<?php foreach (get_wp_user_default_avatar() as $name => $label) : ?>
							              		<?php $avatar = get_avatar('unknown@gravatar.com', 32, $name); ?>

							              		<?php $selected = ($wp_user_profile_avatar_default == $name) ? 'checked="checked"' : ""; ?>
							              		<label><input type="radio" name="wp_user_profile_avatar_default" value="<?php echo esc_attr( $name ); ?>" <?php echo $selected; ?> /> 
							              		<?php echo preg_replace("/src='(.+?)'/", "src='\$1&amp;forcedefault=1'", $avatar); ?>
							              		<?php echo $label; ?></label><br />
							              	<?php endforeach; ?>
							              	<?php endif; ?>

							            </fieldset>
			  						</td>
			  					</tr>

			  					<tr>
			  						<td>
			  							<input type="submit" class="button button-primary" name="wp_user_profile_avatar_settings" value="<?php esc_attr_e( 'Save Changes', 'wp-user-profile-avatar' ); ?>" />
			  						</td>

			  						<td>
			  						<?php wp_nonce_field( 'user_profile_avatar_settings' ); ?>
			  						</td>
			  					</tr>

			  				</table>

			  			</form>
					</td>
		  		</tr>
		  	</table>
		</div> 	

		<?php
	}
    /**
     * edit_handler function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
	public function edit_handler() 
	{
		if ( ! empty( $_POST['wp_user_profile_avatar_settings'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'user_profile_avatar_settings' ) ) 
		{
			$user_id = get_current_user_id();

			$wp_user_profile_avatar_show_avatars  = ! empty( $_POST['wp_user_profile_avatar_show_avatars'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_show_avatars'] ) : '';


			$wp_user_profile_avatar_tinymce  = ! empty( $_POST['wp_user_profile_avatar_tinymce'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_tinymce'] ) : '';

			$wp_user_profile_avatar_allow_upload  = ! empty( $_POST['wp_user_profile_avatar_allow_upload'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_allow_upload'] ) : '';

			$wp_user_profile_avatar_disable_gravatar  = ! empty( $_POST['wp_user_profile_avatar_disable_gravatar'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_disable_gravatar'] ) : '';
			

			$wp_user_profile_avatar_rating  = ! empty( $_POST['wp_user_profile_avatar_rating'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_rating'] ) : '';

			$wp_user_profile_avatar_default  = ! empty( $_POST['wp_user_profile_avatar_default'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_default'] ) : '';

			$wp_user_profile_avatar_attachment_id  = ! empty( $_POST['wp_user_profile_avatar_attachment_id'] ) ? sanitize_text_field( $_POST['wp_user_profile_avatar_attachment_id'] ) : '';

			if($wp_user_profile_avatar_show_avatars == '')
			{
				$wp_user_profile_avatar_tinymce = '';
				$wp_user_profile_avatar_allow_upload = '';
				$wp_user_profile_avatar_disable_gravatar = '';
			}

			// options
			update_option( 'wp_user_profile_avatar_tinymce', $wp_user_profile_avatar_tinymce );
			update_option( 'wp_user_profile_avatar_allow_upload', $wp_user_profile_avatar_allow_upload );
			update_option( 'wp_user_profile_avatar_disable_gravatar', $wp_user_profile_avatar_disable_gravatar );
			update_option( 'wp_user_profile_avatar_show_avatars', $wp_user_profile_avatar_show_avatars );
			update_option( 'wp_user_profile_avatar_rating', $wp_user_profile_avatar_rating );
			update_option( 'wp_user_profile_avatar_default', $wp_user_profile_avatar_default );
			update_option( 'wp_user_profile_avatar_attachment_id', $wp_user_profile_avatar_attachment_id );

		}
	}

}

new WP_User_Profile_Avatar_Settings();