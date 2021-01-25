<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_enqueue_scripts', 'license_enqueue_scripts' );

if(!function_exists('license_enqueue_scripts'))
{
	/**
	 * license_enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 * @since 1.2
	 */
	function license_enqueue_scripts() {
		if ( ! wp_style_is( 'wpem-updater-styles', 'enqueued' ) ) {
			wp_register_style( 'wpem-updater-styles', plugin_dir_url(__DIR__) . 'autoupdater/assets/css/backend.css' );
		}
	}
}

add_action( 'admin_menu', 'license_manage_menu', 12 );

if(!function_exists('license_manage_menu'))
{
	/**
	 * admin_menu function.
	 *
	 * @access public
	 * @return void
	 * @since 1.2
	 */
	function license_manage_menu() 
	{
		add_submenu_page(  'edit.php?post_type=event_listing', __( 'License', 'wp-event-manager-zoom' ),  __( 'License', 'wp-event-manager-zoom' ) , 'manage_options', 'license','wpem_manage_license' );
	}
}

/**
 * wpem_manage_license function.
 *
 * @access public
 * @return void
 * @since 1.2
 */
if(!function_exists('wpem_manage_license'))
{
	function wpem_manage_license() 
	{
		wp_enqueue_style('wpem-updater-styles');

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		?>
		
		<div class="wrap wpem-updater-licence-wrap">
			<h2><?php _e('License', 'wp-event-manager-zoom'); ?></h2>
			
			<div class="wpem-updater-licence">
				<?php
				foreach ($plugins as $filename => $plugin) 
				{
					if( $plugin['AuthorName'] == 'WP Event Manager' && is_plugin_active( $filename ) && !in_array( $plugin['TextDomain'], ["wp-event-manager", "wp-user-profile-avatar"] ) )
					{
						$licence_key = get_option( $plugin['TextDomain'] . '_licence_key' );
						$email = get_option( $plugin['TextDomain'] . '_email' );
						if(empty($email))
						{
							$email = get_option( 'admin_email' );
						}

						$disabled = '';
						if(!empty($licence_key))
						{
							$disabled = 'disabled';
						}
						
						include( 'templates/addon-licence.php' );
					}
				}
				?>
			</div>

			<div class="notice notice-info inline"><p><?php _e('Lost your license key?', 'wp-event-manager-zoom'); ?> <a target="_blank" href="//wp-eventmanager.com/lost-license-key/"><?php _e('Retrieve it here', 'wp-event-manager-zoom'); ?></a>.</p></div>
		</div>
		
		<?php
	}
}
