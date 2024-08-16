<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WPUPA_Shortcodes {

    /**
     * Constructor - get the plugin hooked in and ready
     */
    public function __construct() { 

        add_shortcode( 'authorbox_social_info', array( $this, 'wpupa_authorbox_social_info' ) );
        add_shortcode( 'authorbox_social_link', array( $this, 'wpupa_authorbox_social_link' ) );
        add_shortcode( 'user_display', array( $this, 'wpupa_user_display' ) );
        add_shortcode( 'user_profile_avatar', array( $this, 'wpupa_user_profile_avatar' ) );
        add_shortcode( 'user_profile_avatar_upload', array( $this, 'wpupa_user_profile_avatar_upload' ) );
        add_shortcode( 'all_user_avatars', array( $this, 'wpupa_all_user_avatars' ) );

        add_action( 'wp_ajax_update_user_avatar', array( $this, 'wpupa_update_user_avatar' ) );

        add_action( 'wp_ajax_remove_user_avatar', array( $this, 'wpupa_remove_user_avatar' ) );

        add_action( 'wp_ajax_undo_user_avatar', array( $this, 'wpupa_undo_user_avatar' ) );

        add_filter( 'get_avatar_url', array( $this, 'wpupa_get_user_avatar_url' ), 10, 3 );
    }

    /**
     * author box social information function.
     *
     * @access public
     * @param $atts, $content
     * @return
     * @since 1.0
     */
    public function wpupa_authorbox_social_info( $atts = array(), $content = null ) {
        global $blog_id, $post, $wpdb;

        $current_user_id = get_current_user_id();

        extract(
            shortcode_atts(
                array(
                    'user_id' => esc_attr( '' ),
                    'size'    => esc_attr( 'thumbnail' ),
                    'align'   => esc_attr( 'alignnone' ),
                ),
                $atts
            )
        );

        ob_start();

        include_once WPUPA_PLUGIN_DIR . '/includes/wp-author-box-display.php';

        return ob_get_clean();
    }

    /**
     * wpupa_authorbox_social_link function
     *
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function wpupa_authorbox_social_link() {

        $id = get_current_user_id();

        $details = array();

        ob_start();

        include_once WPUPA_PLUGIN_DIR . '/templates/wp-author-box-social-info.php';

        return ob_get_clean();
    }

    /**
     * wpupa_user_display function
     *
     * @access public
     * @param $atts
     * @return
     * @since 1.0
     */
    public function wpupa_user_display() {

        $id = get_current_user_id();

        $details = array(
            'first_name'          => esc_attr( get_the_author_meta( 'first_name', $id ) ),
            'last_name'           => esc_attr( get_the_author_meta( 'last_name', $id ) ),
            'description'         => wp_kses_post( get_the_author_meta( 'description', $id ) ),
            'email'               => ap_html( get_the_author_meta( 'email', $id ) ),
            'sabox_social_links'  => get_the_author_meta( 'sabox_social_links', $id ),
            'sabox-profile-image' => esc_url( get_the_author_meta( 'sabox-profile-image', $id ) ),
        );

        ob_start();

        include_once WPUPA_PLUGIN_DIR . '/templates/wp-display-user.php';

        return ob_get_clean();
    }

    /**
     * wpupa_all_user_avatars function
     *
     * @access public
     * @param $atts
     * @return
     *
     */
	public function wpupa_all_user_avatars( $atts ) {
		$atts = shortcode_atts( array(
			'roles'				=> '',
			'avatar_size'		=> 100,
			'border_radius' 	=> 0,
			'align' 			=> '',
			'limit' 			=> '',
			'max_bio_length'	=> -1,
			'min_post_count'	=> 0,
			'page_size' 		=> 10,
			'order' 			=> 'ID',
			'sort_direction'	=> 'asc',
			'render_as_list'	=> 'false',
			'hiddenusers' 		=> '',
			'link_to_authorpage'=> '',
			'user_link' 		=> '',
			'show_name' 		=> '',
			'show_biography' 	=> '',
			'show_postcount' 	=> '',
			'blogs' 			=> '', 
		), $atts, 'all_user_avatars');
		
		$roles = !empty( $atts['roles'] ) ? array_map( 'trim', explode( ',', $atts['roles'] ) ) : array();

		$hidden_users = !empty( $atts['hiddenusers'] ) ? array_map( 'trim', explode( ',', $atts['hiddenusers'] ) ) : array();

		$order = !empty( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'ID';
		$sort_direction = in_array( strtolower( $atts['sort_direction'] ), array( 'desc', 'descending' ) ) ? 'DESC' : 'ASC';

		$paged = max( 1, get_query_var( 'paged', 1 ) );
		$offset = ( $paged - 1 ) * $atts['page_size'];

		$user_avatars = array();

		// Check if blogs attribute is specified for WPMU mode
		if ( !empty( $atts['blogs'] ) && is_multisite() ) {
			$blog_ids = array_map( 'trim', explode( ',', $atts['blogs'] ) );

			foreach ( $blog_ids as $blog_id ) {
				
				switch_to_blog( $blog_id );

				$args = array(
					'role__in'		=> $roles,
					'exclude' 		=> $hidden_users,
					'fields' 		=> array( 'ID', 'display_name', 'description' ),
					'number' 		=> $atts['limit'], 
					'orderby' 		=> $order,
					'order'			=> $sort_direction,
					'count_total'	=> false,
				);

				$user_query = new WP_User_Query( $args );
				$users = $user_query->get_results();
				
				if ( !empty( $atts['min_post_count'] ) ) {
					$min_post_count = ( int ) $atts['min_post_count'];
					if ( $min_post_count > 0 ) {
						$users = array_filter( $users, function( $user ) use ( $min_post_count ) {
							return count_user_posts( $user->ID ) >= $min_post_count;
						});
					}
				}

				foreach ( $users as $user ) {
					$avatar_url = get_avatar_url( $user->ID, array( 'size' => $atts['avatar_size'] ) );
					$user_data = array( 
						'display_name' 	=> esc_html( $user->display_name ),
						'avatar_url' 	=> esc_url( $avatar_url ),
						'ID' 			=> $user->ID,
					);

					if ( !empty( $atts['show_name'] ) && $atts['show_name'] === 'true' ) {
						$user_data['show_name'] = true;
					}

					if ( !empty( $atts['show_biography'] ) && $atts['show_biography'] === 'true' ) {
						if ( $atts['max_bio_length'] > 0 ) {
							
							$user_data['biography'] = substr(get_the_author_meta( 'description', $user->ID ), 0, $atts['max_bio_length']);
						} else {
							$user_data['biography'] = get_the_author_meta( 'description', $user->ID );
						}
					}

					if ( !empty($atts['show_postcount'] ) && $atts['show_postcount'] === 'true' ) {
						$user_data['post_count'] = count_user_posts( $user->ID );
					}

					if ( !empty($atts['user_link'] ) ) {
						$user_data['user_link'] = sanitize_key($atts['user_link']);
					}
					if ( ! empty( $atts['align'] ) ) {
						$this->userlist->align = esc_attr( $atts['align'] );
					}
					
					$user_avatars[] = $user_data;
				}

				// Restore the original blog context
				restore_current_blog();
			}
		} else {
			// No specific blogs provided
			$args = array(
				'role__in'	=> $roles,
				'exclude'	=> $hidden_users,
				'fields' 	=> array( 'ID', 'display_name', 'description' ),
				'number' 	=> $atts['limit'], 
				'orderby' 	=> $order,
				'order' 	=> $sort_direction,
				'count_total'=> false,
			);

			$user_query = new WP_User_Query( $args );
			$users = $user_query->get_results();
			
			if ( !empty( $atts['min_post_count'] ) ) {
				$min_post_count = ( int ) $atts['min_post_count'];
				if ( $min_post_count > 0 ) {
					$users = array_filter( $users, function( $user ) use ( $min_post_count ) {
						return count_user_posts( $user->ID ) >= $min_post_count;
					} );
				}
			}

			$total_users = count( $users );
			$total_pages = ceil( $total_users / $atts['page_size'] );
			$users = array_slice( $users, $offset, $atts['page_size'] );

			foreach ( $users as $user ) {
				$avatar_url = get_avatar_url( $user->ID, array( 'size' => $atts['avatar_size'] ) );
				$user_data = array(
					'display_name'	=> esc_html( $user->display_name ),
					'avatar_url'	=> esc_url( $avatar_url ),
					'ID' 			=> $user->ID,
				);

				if ( !empty( $atts['show_name'] ) && $atts['show_name'] === 'true' ) {
					$user_data['show_name'] = true;
				}

				if ( !empty( $atts['show_biography'] ) && $atts['show_biography'] === 'true' ) {
					if ( $atts['max_bio_length'] > 0 ) {
						
						$user_data['biography'] = substr(get_the_author_meta( 'description', $user->ID ), 0, $atts['max_bio_length']);
					} else {
						$user_data['biography'] = get_the_author_meta( 'description', $user->ID );
					}
				}

				if ( !empty($atts['show_postcount'] ) && $atts['show_postcount'] === 'true' ) {
					$user_data['post_count'] = count_user_posts( $user->ID );
				}

				if ( !empty( $atts['user_link'] ) ) {
					$user_data['user_link'] = sanitize_key( $atts['user_link'] );
				}
				
				$user_avatars[] = $user_data;
			}
		}

		ob_start();

		include_once WPUPA_PLUGIN_DIR . '/templates/wp-display-user-avatar-list.php';

		return ob_get_clean();
	}
    /**
     * wpupa_user_profile_avatar function.
     *
     * @access public
     * @param $atts, $content
     * @return
     * @since 1.0
     */
    public function wpupa_user_profile_avatar( $atts = array(), $content = null ) {
        global $blog_id, $post, $wpdb;

        $current_user_id = get_current_user_id();

        $admin_avatar_size = get_option( 'avatar_size' );

        extract(
            shortcode_atts(
                array(
                    'user_id' => esc_attr( '' ),
                    'size'    => esc_attr( 'thumbnail' ),
                    'align'   => esc_attr( 'alignnone' ),
                    'link'    => esc_attr( '#' ),
                    'target'  => esc_attr( '_self' ),
                    'url'     => esc_attr( '' ),
                ),
                $atts
            )
        );

        $user_id = !empty( $user_id ) ? esc_attr( $user_id ) : $current_user_id;
        $size = !empty( $atts['size'] ) ? sanitize_text_field( $atts['size'] ) : sanitize_text_field( $admin_avatar_size );
        $align = sanitize_text_field( $atts['align'] );
        $link =  !empty($atts['link']) ? sanitize_text_field($atts['link']) : '#' ;
        $target = !empty($atts['target']) ? sanitize_text_field($atts['target']) : '_self';
		$url = !empty($atts['url']) ?  sanitize_text_field($atts['url'])  : '';

        if( !empty( $atts['size'] ) ){
			$size = esc_attr( $atts['size'] );
		}else{
			$size = esc_attr( $admin_avatar_size );
		}

        if ($link === 'custom' && empty($url)) {
			return '<div class="error-message" style="color:red;">Error: You must provide a URL when using the "custom" link option. Please include the "url" attribute in your shortcode.</div>';
		}

        ob_start();
            
        $image_url = esc_url( wpupa_get_url( $user_id, array( 'size' => esc_attr( $size ) ) ) );
        if ( $link == 'image' ) {
            // Get image src
            $link = esc_url( wpupa_get_url( $user_id, array( 'size' => 'original' ) ) );
        } elseif ( $link == 'attachment' ) {
            // Get attachment URL
            $link = esc_url( get_attachment_link( get_the_author_meta( $wpdb->get_blog_prefix( esc_attr( $blog_id ) ) . 'user_avatar', esc_attr( $user_id ) ) ) );
        } elseif ( $link == 'custom' ){
            $link = $url;
       } elseif ( $link == 'none' ){
           $link = '#';
       }

        include_once WPUPA_PLUGIN_DIR . '/templates/wp-user-avatar.php';

        return ob_get_clean();
    }
    /**
     * wpupa_user_profile_avatar_upload function.
     *
     * @access public
     * @param $atts, $content
     * @return
     * @since 1.0
     */
    public function wpupa_user_profile_avatar_upload( $atts = array(), $content = null ) {
        extract(
            shortcode_atts(
                array(),
                $atts
            )
        );

        ob_start();

        if ( ! is_user_logged_in() ) { ?>
            <h5><strong style="color:red;"><?php esc_html_e( 'ERROR: ', 'wp-user-profile-avatar' ); ?></strong> 
                <?php printf( 'You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', esc_url( wp_login_url() ) ); ?> 
            </h5>
            <?php
            return false;
        }

        $wpupa_allow_upload = esc_attr( get_option( 'wpupa_allow_upload' ) );

        $user_id   = get_current_user_id();
        $user      = new WP_User( $user_id );
        $user_data = get_userdata( $user_id );

        if ( in_array( 'contributor', $user_data->roles ) ) {
            if ( empty( $wpupa_allow_upload ) ) {
                ?>
                <h5><strong style="color:red;"><?php esc_html_e( 'ERROR: ', 'wp-user-profile-avatar' ); ?></strong> 
                    <?php printf( 'You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', esc_url( wp_login_url() ) ); ?> 
                </h5>
                <?php
                return false;
            }
        }

        if ( in_array( 'subscriber', $user_data->roles ) ) {
            if ( empty( $wpupa_allow_upload ) ) {
                ?>
                <h5><strong style="color:red;"><?php esc_html_e( 'ERROR: ', 'wp-user-profile-avatar' ); ?></strong> 
                    <?php printf( 'You do not have enough priviledge to access this page. Please <a href="%s"><b>login</b></a> to continue.', esc_url( wp_login_url() ) ); ?> 
                </h5>
                <?php
                return false;
            }
        }

        wp_enqueue_script( 'wp-user-profile-avatar-frontend-avatar' );

        $wpupa_original  = esc_url( wpupa_get_url( $user_id, array( 'size' => 'original' ) ) );
        $wpupa_thumbnail = esc_url( wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) ) );

        $wpupaattachmentid = esc_attr( get_user_meta( $user_id, '_wpupa_attachment_id', true ) );
        $wpupa_url         = esc_url( get_user_meta( $user_id, '_wpupa_url', true ) );

        include_once WPUPA_PLUGIN_DIR . '/templates/wp-avatar-upload.php';

        return ob_get_clean();
    }

    /**
     * wpupa_update_user_avatar function.
     *
     * @access public
     * @param
     * @return
     * @since 1.0
     */
    public function wpupa_update_user_avatar() {
        check_ajax_referer( '_nonce_user_profile_avatar_security', 'security' );

        parse_str( $_POST['form_data'], $form_data );

        // sanitize each of the values of form data
        $form_wpupa_url         = sanitize_url( $form_data['wpupa-url'] );
        $form_wpupaattachmentid = absint( $form_data['wpupaattachmentid'] );
        $user_id                = absint( $form_data['user_id'] );
        $current_user_id        = get_current_user_id();

        if ( $current_user_id == $user_id ) :
            $file = isset( $_FILES['user-avatar'] );

            if ( isset( $file ) && ! empty( $file ) ) {

                $post_id = 0;

                // sanitize each of the values of file data
                $file_name     = sanitize_file_name( $file['name'] );
                $file_type     = sanitize_text_field( $file['type'] );
                $file_tmp_name = sanitize_text_field( $file['tmp_name'] );
                $file_size     = absint( $file['size'] );

                // Upload file
                $overrides     = array( 'test_form' => false );
                $uploaded_file = $this->wpupa_handle_upload( $file, $overrides );

                $attachment = array(
                    'post_title'     => $file_name,
                    'post_content'   => '',
                    'post_type'      => 'attachment',
                    'post_parent'    => null, // populated after inserting post
                    'post_mime_type' => $file_type,
                    'guid'           => $uploaded_file['url'],
                );

                $attachment['post_parent'] = $post_id;
                $attach_id                 = wp_insert_attachment( $attachment, $uploaded_file['file'], $post_id );
                $attach_data               = wp_generate_attachment_metadata( $attach_id, $uploaded_file['file'] );

                if ( isset( $user_id, $attach_id ) ) {
                    $result = wp_update_attachment_metadata( $attach_id, $attach_data );
                    update_user_meta( $user_id, '_wpupa_attachment_id', $attach_id );
                }
            } else {
                if ( isset( $user_id, $form_wpupaattachmentid ) ) {
                    update_user_meta( $user_id, '_wpupa_attachment_id', $form_wpupaattachmentid );
                }
            }

            if ( isset( $user_id, $form_wpupa_url ) ) {
                update_user_meta( $user_id, '_wpupa_url', $form_wpupa_url );
            }

            if ( ! empty( $form_wpupaattachmentid ) || $form_wpupa_url ) {
                update_user_meta( $user_id, '_wpupa_default', 'wp_user_profile_avatar' );
            } else {
                update_user_meta( $user_id, '_wpupa_default', '' );
            }

            $wpupa_original  = wpupa_get_url( $user_id, array( 'size' => 'original' ) );
            $wpupa_thumbnail = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) );
            $message         = __( 'Successfully Updated Avatar', 'wp-user-profile-avatar' );
            $class           = 'wp-user-profile-avatar-success';            

            echo wp_json_encode(
                array(
                    'avatar_original'  => $wpupa_original,
                    'avatar_thumbnail' => $wpupa_thumbnail,
                    'message'          => esc_attr( $message ),
                    'class'            => esc_attr( $class ),
                    'form_wpupaattachmentid' => esc_attr( $form_wpupaattachmentid ),
                )
            );
        else :
            $message = __( 'Permission Denied', 'wp-user-profile-avatar' );
            $class   = 'wp-user-profile-avatar-errors';
            echo wp_json_encode(
                array(
                    'message' => $message,
                    'class'   => $class,
                )
            );
        endif;
        wp_die();
    }

    /**
     * wpupa_remove_user_avatar function.
     *
     * @access public
     * @param
     * @return
     * @since 1.0
     */
    public function wpupa_remove_user_avatar() {
        check_ajax_referer( '_nonce_user_profile_avatar_security', 'security' );

        parse_str( sanitize_text_field($_POST['form_data']), $form_data );

        // sanitize each of the values of form data
        $wpupa_url         = esc_url_raw( $form_data['wpupa-url'] );
        $wpupaattachmentid = absint( $form_data['wpupaattachmentid'] );
        $user_id           = absint( $form_data['user_id'] );
        $current_user_id   = get_current_user_id();

        if ( $current_user_id == $user_id ) :
            if ( isset( $user_id ) ) { 
                update_user_meta( $user_id, '_wpupa_attachment_id', '' );
                update_user_meta( $user_id, '_wpupa_url', '' );
                update_user_meta( $user_id, '_wpupa_default', '' );

                // delete also attachment
                wp_delete_attachment( $wpupaattachmentid, true );
            }

            $wpupa_original  = wpupa_get_url( $user_id, array( 'size' => 'original' ) );
            $wpupa_thumbnail = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) );

            $message = __( 'Successfully Removed Avatar', 'wp-user-profile-avatar' );
            $class   = 'wp-user-profile-avatar-success';

            echo wp_json_encode(
                array(
                    'avatar_original'  => $wpupa_original,
                    'avatar_thumbnail' => $wpupa_thumbnail,
                    'message'          => esc_attr( $message ),
                    'class'            => esc_attr( $class ),
                )
            );
        else :
            $message = __( 'Permission Denied', 'wp-user-profile-avatar' );
            $class   = 'wp-user-profile-avatar-errors';
            echo wp_json_encode(
                array(
                    'message' => esc_attr( $message ),
                    'class'   => esc_attr( $class ),
                )
            );
        endif;
        wp_die();
    }

    /**
     * wpupa_undo_user_avatar function.
     *
     * @access public
     * @param
     * @return
     * @since 1.0
     */
    public function wpupa_undo_user_avatar() {
        check_ajax_referer( '_nonce_user_profile_avatar_security', 'security' );

        parse_str( sanitize_text_field($_POST['form_data']), $form_data );

        // sanitize each of the values of form data
        $wpupa_url         = esc_url_raw( $form_data['wpupa-url'] );
        $wpupaattachmentid = absint( $form_data['wpupaattachmentid'] );
        $user_id           = absint( $form_data['user_id'] );
        $current_user_id   = get_current_user_id();

        if ( $current_user_id == $user_id ) :
            if ( isset( $user_id ) ) {
                update_user_meta( $user_id, '_wpupa_attachment_id', '' );
                update_user_meta( $user_id, '_wpupa_url', '' );
                update_user_meta( $user_id, '_wpupa_default', '' );
            }

            $wpupa_original  = wpupa_get_url( $user_id, array( 'size' => 'original' ) );
            $wpupa_thumbnail = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) );

            $message = __( 'Successfully Undo Avatar', 'wp-user-profile-avatar' );
            $class   = 'wp-user-profile-avatar-success';

            echo wp_json_encode(
                array(
                    'avatar_original'  => $wpupa_original,
                    'avatar_thumbnail' => $wpupa_thumbnail,
                    'message'          => esc_attr( $message ),
                    'class'            => esc_attr( $class ),
                )
            );
        else :
            $message = __( 'Permission Denied', 'wp-user-profile-avatar' );
            $class   = 'wp-user-profile-avatar-errors';
            echo wp_json_encode(
                array(
                    'message' => esc_attr( $message ),
                    'class'   => esc_attr( $class ),
                )
            );
        endif;
        wp_die();
    }
    /**
     * wpupa_handle_upload function.
     *
     * @access public
     * @param $file_handler, $overrides
     * @return
     * @since 1.0
     */
    public function wpupa_handle_upload( $file_handler, $overrides ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        $upload = wp_handle_upload( $file_handler, $overrides );
        return $upload;
    }
    /**
     * wpupa_get_user_avatar_url function.
     *
     * @access public
     * @param $url, $id_or_email, $args
     * @return
     * @since 1.0
     */
    public function wpupa_get_user_avatar_url( $url, $id_or_email, $args ) {
        $wpupa_disable_gravatar = get_option( 'wpupa_disable_gravatar' );

        $wpupa_show_avatars = get_option( 'wpupa_show_avatars' );

        $wpupa_default = get_option( 'avatar_default' );
       
        if ( ! $wpupa_show_avatars ) {
            return false;
        }
        $user_id = null;
        if ( is_admin() ) {
            $screen = get_current_screen();
        }else{
            $screen = array();
        }
        if ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->comment_author_email ) ) { 
                $user_id = $id_or_email->user_id;
            }
        } else {
            if ( is_email( $id_or_email )) {
                if($screen->base !== 'options-discussion' && ($screen->base !== 'admin.php' && isset($_GET['page']) && $_GET['page'] !== 'wp-user-profile-avatar')){
                    $user = get_user_by( 'email', $id_or_email );
                    if ( $user ) {
                        $user_id = $user->ID;
                    }
                }
            } else {
                $user_id = $id_or_email;
            }
        }
 
        // First checking custom avatar.
        if ( wpupa_check_wpupa_url( $user_id ) ) {
            $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) , $args, $url);
        } elseif ( $wpupa_disable_gravatar ) {
            $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail', $args, $url ) );
        } else {
            $has_valid_url = wpupa_check_wpupa_gravatar( $id_or_email );
            if ( ! $has_valid_url ) {
                if ( is_object( $screen ) && property_exists( $screen, 'base' ) ) {
                    if($screen->base !== 'profile' && $wpupa_default !== 'wp_user_profile_avatar' ){
                        return $url;
                    }
                }
                $url = wpupa_get_default_avatar_url( array( 'size' => 'thumbnail' ), $args, $url );
            } else {
                if ( $wpupa_default != 'wp_user_profile_avatar' && ! empty( $user_id ) ) {
                    $url = wpupa_get_url( $user_id, array( 'size' => 'thumbnail' ) , $args, $url);
                }
            }
        }
        
        return $url;
    }
}
new WPUPA_Shortcodes();
