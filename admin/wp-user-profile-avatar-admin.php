
<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WPEM_User_Profile_Avatar_Admin class.
 */

class WPEM_User_Profile_Avatar_Admin {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{
		include_once( 'wp-user-profile-avatar-settings.php' );

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'show_user_profile', array( $this, 'wp_user_profile_avatar_add_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'wp_user_profile_avatar_add_fields' ) );

		add_action( 'personal_options_update', array( $this, 'wp_user_profile_avatar_save_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'wp_user_profile_avatar_save_fields' ) );

		$this->settings_page = new WPEM_User_Profile_Avatar_Settings();
	}

	public function admin_menu() {

		add_submenu_page( 'users.php', __( 'Profile Avatar Settings', 'wp-user-profile-avatar' ), __( 'Profile Avatar Settings', 'wp-user-profile-avatar' ), 'manage_options', 'wpem-profile-avatar-settings', array( $this->settings_page, 'settings' ) );
	}

	/**
	 * admin_enqueue_scripts function.
	 *
	 * @access public
	 * @param 
	 * @return 
	 * @since 1.0
	 */
	public function admin_enqueue_scripts() 
	{
		wp_register_style( 'wp-user-profile-avatar-backend', WP_USER_PROFILE_AVATAR_PLUGIN_URL . '/assets/css/backend.css' );

		wp_register_script( 'wp-user-profile-avatar-admin-avatar', WP_USER_PROFILE_AVATAR_PLUGIN_URL . '/assets/js/admin-avatar.js', array( 'jquery' ), WP_USER_PROFILE_AVATAR_VERSION, true);
		
		wp_localize_script( 'wp-user-profile-avatar-admin-avatar', 'wpem_admin_avatar', array( 
								'media_box_title' => __( 'Choose Image: Default Avatar', 'wp-user-profile-avatar'),
								'default_avatar' => WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-thumbnail.png',
							)
						);
	}

	public function wp_user_profile_avatar_add_fields( $user ) 
	{
		wp_enqueue_media();

		wp_enqueue_style( 'wp-user-profile-avatar-backend');

		wp_enqueue_script( 'wp-user-profile-avatar-admin-avatar' );

		$user_id = $user->ID;

		$wp_user_profile_avatar_original = wpem_get_user_avatar_url($user_id, ['size' => 'original']);
		$wp_user_profile_avatar_thumbnail = wpem_get_user_avatar_url($user_id, ['size' => 'thumbnail']);

		$wp_user_profile_avatar_attachment_id = get_user_meta($user_id, 'wp_user_profile_avatar_attachment_id', true);
		$wp_user_profile_avatar_url = get_user_meta($user_id, 'wp_user_profile_avatar_url', true);

		?>
		<h3><?php _e('WP User Profile Avatar', 'wp-user-profile-avatar'); ?></h3>
		
		<table class="form-table">
			<tr>
				<th>
					<label for="wp_user_profile_avatar"><?php _e('Image', 'wp-user-profile-avatar'); ?></label>
				</th>
				<td>
					<p">
						<input type="text" name="wp_user_profile_avatar_url" class="regular-text code" value="<?php echo $wp_user_profile_avatar_url; ?>" placeholder="Enter Image URL">
					</p>

					<p">
						<?php _e('OR Upload Image', 'wp-user-profile-avatar'); ?>
					</p>

					<p id="wpem-add-button-existing">
						<button type="button" class="button" id="wpem-add"><?php _e('Choose Image'); ?></button>
						<input type="hidden" name="wp_user_profile_avatar_attachment_id" id="wp_user_profile_avatar_attachment_id" value="<?php echo $wp_user_profile_avatar_attachment_id; ?>">
					</p>

					<?php
	              	$class_hide = 'wpem-hide';
	              	if(!empty($wp_user_profile_avatar_attachment_id))
	              	{
	              		$class_hide = '';
	              	}
	              	?>
					<div id="wpem-images-existing">
				      	<p id="wpem-preview">
				        	<img src="<?php echo $wp_user_profile_avatar_original; ?>" alt="">
				        	<span class="description"><?php _e('Original Size', 'wp-user-profile-avatar'); ?></span>
				      	</p>
				      	<p id="wpem-thumbnail">
				        	<img src="<?php echo $wp_user_profile_avatar_thumbnail; ?>" alt="">
				        	<span class="description"><?php _e('Thumbnail', 'wp-user-profile-avatar'); ?></span>
				      	</p>
				      	<p id="wpem-remove-button" class="<?php echo $class_hide; ?>">
					        <button type="button" class="button" id="wpem-remove"><?php _e('Remove Image', 'wp-user-profile-avatar'); ?></button>
				        </p>
				      	<p id="wpem-undo-button">
				      		<button type="button" class="button" id="wpem-undo"><?php _e('Undo', 'wp-user-profile-avatar'); ?></button>
				      	</p>
				    </div>
				</td>
			</tr>
		</table>
		<?php
	}

	public function wp_user_profile_avatar_save_fields( $user_id ) 
	{
		if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	
		update_usermeta( $user_id, 'wp_user_profile_avatar_attachment_id', $_POST['wp_user_profile_avatar_attachment_id'] );
		update_usermeta( $user_id, 'wp_user_profile_avatar_url', $_POST['wp_user_profile_avatar_url'] );
	}
			
}

new WPEM_User_Profile_Avatar_Admin();