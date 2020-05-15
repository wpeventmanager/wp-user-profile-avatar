
<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/**
 * WP_User_Profile_Avatar_Admin class.
 */

class WP_User_Profile_Avatar_Admin {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() 
	{
		include_once( 'wp-user-profile-avatar-settings.php' );

		$wp_user_profile_avatar_tinymce = get_option('wp_user_profile_avatar_tinymce');
	    if($wp_user_profile_avatar_tinymce) 
	    {	
	      	add_action('init', array( $this, 'wp_user_profile_avatar_add_buttons'));
	    }

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'show_user_profile', array( $this, 'wp_user_profile_avatar_add_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'wp_user_profile_avatar_add_fields' ) );

		add_action( 'personal_options_update', array( $this, 'wp_user_profile_avatar_save_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'wp_user_profile_avatar_save_fields' ) );

		$this->settings_page = new WP_User_Profile_Avatar_Settings();

		add_action('init', array( $this, 'thickbox_model_init'));
		add_action('wp_ajax_thickbox_model_view', array( $this, 'thickbox_model_view'));
		add_action('wp_ajax_nopriv_thickbox_model_view', array( $this, 'thickbox_model_view'));
	}

    /**
     * admin_menu function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
	public function admin_menu() {

		add_submenu_page( 'users.php', __( 'Profile Avatar Settings', 'wp-user-profile-avatar' ), __( 'Profile Avatar Settings', 'wp-user-profile-avatar' ), 'manage_options', 'wp-user-profile-avatar-settings', array( $this->settings_page, 'settings' ) );
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
		
		wp_localize_script( 'wp-user-profile-avatar-admin-avatar', 'wp_user_profile_avatar_admin_avatar', array( 
								'thinkbox_ajax_url' 	 => admin_url( 'admin-ajax.php' ) . '?height=600&width=770&action=thickbox_model_view',
								'icon_title' 	 =>  __( 'WP User Profile Avatar', 'wp-user-profile-avatar'),
								'wp_user_profile_avatar_security'  => wp_create_nonce( "_nonce_user_profile_avatar_security" ),
								'media_box_title' => __( 'Choose Image: Default Avatar', 'wp-user-profile-avatar'),
								'default_avatar' => WP_USER_PROFILE_AVATAR_PLUGIN_URL.'/assets/images/wp-user-thumbnail.png',
							)
						);

		wp_enqueue_style( 'wp-user-profile-avatar-backend' );
		wp_enqueue_script( 'wp-user-profile-avatar-admin-avatar' );
	}

    /**
     * wp_user_profile_avatar_add_fields function.
     *
     * @access public
     * @param $user
     * @return 
     * @since 1.0
     */
	public function wp_user_profile_avatar_add_fields( $user ) 
	{
		wp_enqueue_media();

		wp_enqueue_style( 'wp-user-profile-avatar-backend');

		wp_enqueue_script( 'wp-user-profile-avatar-admin-avatar' );

		$user_id = $user->ID;

		$wp_user_profile_avatar_original = get_wp_user_profile_avatar_url($user_id, ['size' => 'original']);
		$wp_user_profile_avatar_thumbnail = get_wp_user_profile_avatar_url($user_id, ['size' => 'thumbnail']);

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
					<p>
						<input type="text" name="wp_user_profile_avatar_url" id="wp_user_profile_avatar_url" class="regular-text code" value="<?php echo $wp_user_profile_avatar_url; ?>" placeholder="Enter Image URL">
					</p>

					<p><?php _e('OR Upload Image', 'wp-user-profile-avatar'); ?></p>

					<p id="wp-user-profile-avatar-add-button-existing">
						<button type="button" class="button" id="wp-user-profile-avatar-add"><?php _e('Choose Image'); ?></button>
						<input type="hidden" name="wp_user_profile_avatar_attachment_id" id="wp_user_profile_avatar_attachment_id" value="<?php echo $wp_user_profile_avatar_attachment_id; ?>">
					</p>

					<?php
	              	$class_hide = 'wp-user-profile-avatar-hide';
	              	if(!empty($wp_user_profile_avatar_attachment_id))
	              	{
	              		$class_hide = '';
	              	}
	              	else if(!empty($wp_user_profile_avatar_url))
	              	{
	              		$class_hide = '';
	              	}

	              	?>
					<div id="wp-user-profile-avatar-images-existing">
				      	<p id="wp-user-profile-avatar-preview">
				        	<img src="<?php echo $wp_user_profile_avatar_original; ?>" alt="">
				        	<span class="description"><?php _e('Original Size', 'wp-user-profile-avatar'); ?></span>
				      	</p>
				      	<p id="wp-user-profile-avatar-thumbnail">
				        	<img src="<?php echo $wp_user_profile_avatar_thumbnail; ?>" alt="">
				        	<span class="description"><?php _e('Thumbnail', 'wp-user-profile-avatar'); ?></span>
				      	</p>
				      	<p id="wp-user-profile-avatar-remove-button" class="<?php echo $class_hide; ?>">
					        <button type="button" class="button" id="wp-user-profile-avatar-remove"><?php _e('Remove Image', 'wp-user-profile-avatar'); ?></button>
				        </p>
				      	<p id="wp-user-profile-avatar-undo-button">
				      		<button type="button" class="button" id="wp-user-profile-avatar-undo"><?php _e('Undo', 'wp-user-profile-avatar'); ?></button>
				      	</p>
				    </div>
				</td>
			</tr>
		</table>
		<?php
	}

    /**
     * wp_user_profile_avatar_save_fields function.
     *
     * @access public
     * @param $user_id
     * @return 
     * @since 1.0
     */
	public function wp_user_profile_avatar_save_fields( $user_id ) 
	{
		if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
		
		update_user_meta( $user_id, 'wp_user_profile_avatar_attachment_id', $_POST['wp_user_profile_avatar_attachment_id'] );
		update_user_meta( $user_id, 'wp_user_profile_avatar_url', $_POST['wp_user_profile_avatar_url'] );

		if( !empty($_POST['wp_user_profile_avatar_attachment_id']) || !empty($_POST['wp_user_profile_avatar_url']) )
		{
			update_user_meta( $user_id, 'wp_user_profile_avatar_default', 'wp_user_profile_avatar' );
		}
		else
		{
			update_user_meta( $user_id, 'wp_user_profile_avatar_default', '' );
		}
		
	}
	
	/**
     * wp_user_profile_avatar_add_buttons function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function wp_user_profile_avatar_add_buttons() 
    {
        // Add only in Rich Editor mode
        if(get_user_option('rich_editing') == 'true') 
        {
            add_filter('mce_external_plugins', array( $this, 'wp_user_profile_avatar_add_tinymce_plugin'));
            add_filter('mce_buttons', array( $this, 'wp_user_profile_avatar_register_button'));
        }
    }

    /**
     * wp_user_profile_avatar_register_button function.
     *
     * @access public
     * @param $buttons
     * @return 
     * @since 1.0
     */
    public function wp_user_profile_avatar_register_button($buttons) 
    {
        array_push($buttons, 'separator', 'wp_user_profile_avatar_shortcodes');
        return $buttons;
    }

    /**
     * wp_user_profile_avatar_add_tinymce_plugin function.
     *
     * @access public
     * @param $plugins
     * @return 
     * @since 1.0
     */
    public function wp_user_profile_avatar_add_tinymce_plugin($plugins) 
    {
        $plugins['wp_user_profile_avatar_shortcodes'] = WP_USER_PROFILE_AVATAR_PLUGIN_URL . '/assets/js/admin-avatar.js';
        return $plugins;
    }

    /**
     * thickbox_model_init function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
    public function thickbox_model_init()
	{
	    add_thickbox();
	}

	/**
     * thickbox_model_view function.
     *
     * @access public
     * @param 
     * @return 
     * @since 1.0
     */
	public  function thickbox_model_view()
	{
		include_once (WP_USER_PROFILE_AVATAR_PLUGIN_DIR . '\admin\templates\shortcode-popup.php' );

		wp_die();
	}


}

new WP_User_Profile_Avatar_Admin();



