<?php
/**
 * Author Social Profile Picture page.
 *
 * @package Author_Social_Profile_Picture
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WPUPA_Author_Social_Profile
 *
 * Handles author social profile functionality.
 */
class WPUPA_Author_Social_Profile {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'wpupa_avatar_social_profile_picture' ) );
        add_action( 'show_user_profile', array( $this, 'wpupa_user_add_extra_profile_picture_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'wpupa_user_add_extra_profile_picture_fields' ) );
        add_action( 'personal_options_update', array( $this, 'wpupa_avatar_save_extra_profile_fields' ) );
        add_action( 'edit_user_profile_update', array( $this, 'wpupa_avatar_save_extra_profile_fields' ) );
        add_action( 'wp_ajax_wp_social_avatar_gplus_clear_cache', array( $this, 'wpupa_user_social_profile_cache_clear' ) );
        add_action( 'wp_ajax_nopriv_wp_social_avatar_gplus_clear_cache', array( $this, 'wpupa_user_social_profile_cache_clear' ) );
        add_filter( 'get_avatar', array( $this, 'wpupa_user_fb_profile' ), 10, 5 );
        add_filter( 'get_avatar', array( $this, 'wpupa_user_gplus_profile' ), 10, 5 );
    }

    /**
     * Enqueue scripts for avatar social profile picture.
     */
    public function wpupa_avatar_social_profile_picture() {
        global $pagenow;

        if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) {
            wp_register_script( 'wp-avatar-social-profile-picture', WPUPA_PLUGIN_URL . '/assets/js/wp-avatar.js', array( 'jquery' ), WPUPA_VERSION, true );
        }
    }

    /**
     * Save the WP Avatar social profile settings.
     */
    public function wpupa_avatar_save_extra_profile_fields( $user_id ) {
        $current_user_id = get_current_user_id();

        if ( $current_user_id == $user_id ) :
            if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-user_' . $user_id ) ) {
                return;
            }
            // update_user_meta( $user_id, 'wp_social_fb_profile', trim( sanitize_text_field( $_POST['fb-profile'] ) ) );
            // update_user_meta( $user_id, 'wp_social_gplus_profile', trim( sanitize_text_field( $_POST['gplus-profile'] ) ) );
            // update_user_meta( $user_id, 'wp_user_social_profile', sanitize_text_field( $_POST['wp-user-social-profile'] ) );
            // update_user_meta( $user_id, 'wp_user_social_linkedin_profile', sanitize_text_field( $_POST['linkedin-profile'] ) );
			// update_user_meta( $user_id, 'wp_social_youtube_profile', sanitize_text_field( $_POST['youtube-profile'] ) );
			// update_user_meta( $user_id, 'wp_social_twitter_profile', sanitize_text_field( $_POST['twitter-profile'] ) );
			// update_user_meta( $user_id, 'wp_social_github_profile', sanitize_text_field( $_POST['github-profile'] ) );
			// update_user_meta( $user_id, 'wp_social_yahoo_profile', sanitize_text_field( $_POST['yahoo-profile'] ) );
        endif;
    }

    /**
     * Add extra profile fields for social profile picture.
     */
    public function wpupa_user_add_extra_profile_picture_fields( $socialprofile ) {
        $wp_avatar_add_social_picture = get_option( 'wp_avatar_add_social_picture', 'read' );

        if ( ! current_user_can( $wp_avatar_add_social_picture ) ) {
            return;
        }
        $wp_user_social_profile  = get_user_meta( $socialprofile->ID, 'wp_user_social_profile', true );
        $wp_social_fb_profile    = get_user_meta( $socialprofile->ID, 'wp_social_fb_profile', true );
        $wp_social_gplus_profile = get_user_meta( $socialprofile->ID, 'wp_social_gplus_profile', true );
        $wp_social_linkedin_profile = get_user_meta( $socialprofile->ID, 'wp_user_social_linkedin_profile', true ); 
		$wp_social_youtube_profile = get_user_meta( $socialprofile->ID, 'wp_social_youtube_profile', true );
		$wp_social_twitter_profile   = get_user_meta( $socialprofile->ID, 'wp_social_twitter_profile', true );
		$wp_social_github_profile    = get_user_meta( $socialprofile->ID, 'wp_social_github_profile', true );
		$wp_social_yahoo_profile     = get_user_meta( $socialprofile->ID, 'wp_social_yahoo_profile', true ); 
       ?>

       <!-- <h3><?php esc_html_e( 'WP Avatar User Role Settings', 'wp-user-profile-avatar' ); ?></h3>
        <table class="form-table">
            <tr>
                <th>
                    <label for="facebook-profile">Facebook User ID(numeric)</label>
                </th>
                <td>
                    <input type="text" name="fb-profile" id="fb-profile" value="<?php echo esc_attr( $wp_social_fb_profile ); ?>" class="regular-text" />&nbsp;
                    <span><a href="http://findmyfacebookid.com/" target="_blank">Find your facebook id here</a></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="use-fb-profile">Use Facebook Profile as Avatar</label>
                </th>
                <td>
                    <input type="checkbox" name="wp-user-social-profile" value="wp-facebook" <?php checked( $wp_user_social_profile, 'wp-facebook' ); ?> >
                </td>
            </tr>
            <tr>
                <th>
                    <label for="gplus-profile">Google+ id</label>
                </th>
                <td>
                    <input type="text" name="gplus-profile" id="gplus-profile" value="<?php echo esc_attr( $wp_social_gplus_profile ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="use-gplus-profile">Use Google+ Profile as Avatar</label>
                </th>
                <td>
                    <input type="checkbox" name="wp-user-social-profile" value="wp-gplus" <?php checked( $wp_user_social_profile, 'wp-gplus' ); ?> >
                </td>
            </tr>
            <tr>
                <th>
                    <label for="gplus-clear-cache">Clear Google+ Cache</label></th>
                <td>
                    <input type="button" name="wp-gplus-clear" value="Clear Cache" user="<?php echo esc_attr( $socialprofile->ID ); ?>">
                    <span id="msg"></span>
                </td>
            </tr>
			<tr>
				<th>
					<label for="linkedin-profile">LinkedIn Profile URL</label>
				</th>
				<td>
					<input type="text" name="linkedin-profile" id="linkedin-profile" value="<?php echo esc_attr( $wp_social_linkedin_profile ); ?>" class="regular-text" />&nbsp;
					<span><a href="https://www.linkedin.com/" target="_blank">Visit LinkedIn</a></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="use-linkedin-profile">Use LinkedIn Profile as Avatar</label>
				</th>
				<td>
					<input type="checkbox" name="wp-user-social-profile" value="wp-linkedin" <?php checked( $wp_user_social_profile, 'wp-linkedin' ); ?> >
				</td>
			</tr>
			<tr>
				<th>
					<label for="youtube-profile">YouTube Profile ID</label>
				</th>
				<td>
					<input type="text" name="youtube-profile" id="youtube-profile" value="<?php echo esc_attr( $wp_social_youtube_profile ); ?>" class="regular-text" />&nbsp;
					<span><a href="https://www.youtube.com/" target="_blank">Visit YouTube</a></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="use-youtube-profile">Use YouTube Profile as Avatar</label>
				</th>
				<td>
					<input type="checkbox" name="wp-user-social-profile" value="wp-youtube" <?php checked( $wp_user_social_profile, 'wp-youtube' ); ?> >
				</td>
			</tr>
			<tr>
				<th>
					<label for="twitter-profile">Twitter Username</label>
				</th>
				<td>
					<input type="text" name="twitter-profile" id="twitter-profile" value="<?php echo esc_attr( $wp_social_twitter_profile ); ?>" class="regular-text" />&nbsp;
					<span><a href="https://twitter.com/" target="_blank">Visit Twitter</a></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="use-twitter-profile">Use Twitter Profile as Avatar</label>
				</th>
				<td>
					<input type="checkbox" name="wp-user-social-profile" value="wp-twitter" <?php checked( $wp_user_social_profile, 'wp-twitter' ); ?> >
				</td>
			</tr>
			<tr>
				<th>
					<label for="github-profile">GitHub Username</label>
				</th>
				<td>
					<input type="text" name="github-profile" id="github-profile" value="<?php echo esc_attr( $wp_social_github_profile ); ?>" class="regular-text" />&nbsp;
					<span><a href="https://github.com/" target="_blank">Visit GitHub</a></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="use-github-profile">Use GitHub Profile as Avatar</label>
				</th>
				<td>
					<input type="checkbox" name="wp-user-social-profile" value="wp-github" <?php checked( $wp_user_social_profile, 'wp-github' ); ?> >
				</td>
			</tr>
			<tr>
				<th>
					<label for="yahoo-profile">Yahoo Profile ID</label>
				</th>
				<td>
					<input type="text" name="yahoo-profile" id="yahoo-profile" value="<?php echo esc_attr( $wp_social_yahoo_profile ); ?>" class="regular-text" />&nbsp;
					<span><a href="https://www.yahoo.com/" target="_blank">Visit Yahoo</a></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="use-yahoo-profile">Use Yahoo Profile as Avatar</label>
				</th>
				<td>
					<input type="checkbox" name="wp-user-social-profile" value="wp-yahoo" <?php checked( $wp_user_social_profile, 'wp-yahoo' ); ?> >
				</td>
			</tr>
        </table>-->
        <?php
    }

    /**
     * Clear Google+ cache for social profile.
     */
    public function wpupa_user_social_profile_cache_clear() {
        $user_id          = sanitize_text_field( $_POST['user_id'] );
        $delete_transient = delete_transient( "wp_social_avatar_gplus_{$user_id}" );

        echo esc_attr( $delete_transient );
        die();
    }

    /**
     * Filter to use Facebook profile as avatar.
     */
    public function wpupa_user_fb_profile( $avatar, $id_or_email, $size, $default ) {

        if ( is_int( $id_or_email ) ) {
            $user_id = $id_or_email;
        }

        if ( is_object( $id_or_email ) ) {
            $user_id = $id_or_email->user_id;
        }

        if ( ! is_numeric( $id_or_email ) ) {
            return $avatar;
        }
        if ( is_string( $id_or_email ) ) {
            $user = get_user_by( 'email', $id_or_email );
            if ( $user ) {
                $user_id = $user->ID;
            } else {
                $user_id = $id_or_email;
            }
        }

        $wp_user_social_profile       = get_user_meta( $user_id, 'wp_user_social_profile', true );
        $wp_social_fb_profile         = get_user_meta( $user_id, 'wp_social_fb_profile', true );
        $wp_avatar_add_social_picture = get_option( 'wp_avatar_add_social_picture', 'read' );

        if ( user_can( $user_id, $wp_avatar_add_social_picture ) ) {
            if ( 'wp-facebook' == $wp_user_social_profile && ! empty( $wp_social_fb_profile ) ) {

                $fb     = 'https://graph.facebook.com/' . $wp_social_fb_profile . '/picture?width=' . esc_attr( $size ) . '&height=' . esc_attr( $size );
                $avatar = "<img alt='facebook-profile-picture' src='" . esc_url( $fb ) . "' class='avatar avatar-" . esc_attr( $size ) . " photo' height='" . esc_attr( $size ) . "' width='" . esc_attr( $size ) . "' />";

                return $avatar;
            } else {
                return $avatar;
            }
        } else {
            return $avatar;
        }
    }

    /**
     * Filter to use Google+ profile as avatar.
     */
    public function wpupa_user_gplus_profile( $avatar, $id_or_email, $size, $default ) {

        if ( is_int( $id_or_email ) ) {
            $user_id = $id_or_email;
        }

        if ( is_object( $id_or_email ) ) {
            $user_id = $id_or_email->user_id;
        }

        if ( ! is_numeric( $id_or_email ) ) {
            return $avatar;
        }
        if ( is_string( $id_or_email ) ) {
            $user = get_user_by( 'email', $id_or_email );
            if ( $user ) {
                $user_id = $user->ID;
            } else {
                $user_id = $id_or_email;
            }
        }

        $wp_user_social_profile       = get_user_meta( $user_id, 'wp_user_social_profile', true );
        $wp_social_gplus_profile      = get_user_meta( $user_id, 'wp_social_gplus_profile', true );
        $wp_avatar_add_social_picture = get_option( 'wp_avatar_add_social_picture', 'read' );

        if ( user_can( $user_id, $wp_avatar_add_social_picture ) ) {
            if ( 'wp-gplus' == $wp_user_social_profile && ! empty( $wp_social_gplus_profile ) ) {
                if ( false === ( $gplus = get_transient( "wp_social_avatar_gplus_{$user_id}" ) ) ) {
                    $url     = 'https://www.googleapis.com/plus/v1/people/' . $wp_social_gplus_profile . '?fields=image&key=AIzaSyBrLkua-XeZh637G1T1J8DoNHK3Oqw81ao';
                    $results = wp_remote_get( $url, array( 'timeout' => -1 ) );
                    if ( ! is_wp_error( $results ) ) {
                        if ( 200 == $results['response']['code'] ) {
                            $gplusdetails = json_decode( $results['body'] );
                            $gplus        = $gplusdetails->image->url;
                            set_transient( "wp_social_avatar_gplus_{$user_id}", $gplus, 48 * HOUR_IN_SECONDS );
                            $gplus = str_replace( 'sz=50', "sz={$size}", $gplus );

                            $avatar = "<img alt='gplus-profile-picture' src='" . esc_url( $gplus ) . "' class='avatar avatar-" . esc_attr( $size ) . " photo' height='" . esc_attr( $size ) . "' width='" . esc_attr( $size ) . "' />";
                        }
                    }
                } else {
                    $gplus = str_replace( 'sz=50', "sz={$size}", $gplus );

                    $avatar = "<img alt='gplus-profile-picture' src='" . esc_url( $gplus ) . "' class='avatar avatar-" . esc_attr( $size ) . " photo' height='" . esc_attr( $size ) . "' width='" . esc_attr( $size ) . "' />";
                }
                return $avatar;
            } else {
                return $avatar;
            }
        } else {
            return $avatar;
        }
    }
}

// Initialize the class
new WPUPA_Author_Social_Profile();
