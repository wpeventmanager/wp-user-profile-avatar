<?php

/**
 * Author Box Social link page.
 *
 * @package Author Box Social link page.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php

function add_user_social_contact_info($user_contact) {
    $user_contact['facebook'] = __('Facebook URL');
    $user_contact['skype'] = __('Skype');
    $user_contact['twitter'] = __('Twitter');
    $user_contact['youtube'] = __('Youtube Channel');
    $user_contact['linkedin'] = __('LinkedIn');
    $user_contact['googleplus'] = __('Google +');
    $user_contact['pinterest'] = __('Pinterest');
    $user_contact['instagram'] = __('Instagram');
    $user_contact['github'] = __('Github profile');
    return $user_contact;
}

add_filter('user_contactmethods', 'add_user_social_contact_info');

function wp_fontawesome_styles() {
    wp_register_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', '', '4.4.0', 'all');
    wp_enqueue_style('fontawesome');
}

add_action('wp_enqueue_scripts', 'wp_fontawesome_styles');

function wp_author_social_info_box($content) {

    global $post;

    if (is_single() && isset($post->post_author)) {

// Get author's display name
        $display_name = esc_attr(get_the_author_meta('first_name', $post->post_author));

// If display name is not available then use nickname
        if (empty($display_name))
            $display_name = esc_attr(get_the_author_meta('nickname', $post->post_author));

// Get author's biographical description
        $user_description = wp_kses_post(get_the_author_meta('user_description', $post->post_author));

// Get author's email
        $user_email = esc_html(get_the_author_meta('email', $post->post_author));

// Get author's Facebook
        $user_facebook = esc_url(get_the_author_meta('facebook', $post->post_author));

// Get author's Skype
        $user_skype = esc_url(get_the_author_meta('skype', $post->post_author));

// Get author's Twitter
        $user_twitter = esc_url(get_the_author_meta('twitter', $post->post_author));

// Get author's LinkedIn 
        $user_linkedin = esc_url(get_the_author_meta('linkedin', $post->post_author));

// Get author's Youtube
        $user_youtube = esc_url(get_the_author_meta('youtube', $post->post_author));

// Get author's Google+
        $user_googleplus = esc_url(get_the_author_meta('googleplus', $post->post_author));

// Get author's Pinterest
        $user_pinterest = esc_url(get_the_author_meta('pinterest', $post->post_author));

// Get author's Instagram
        $user_instagram = esc_url(get_the_author_meta('instagram', $post->post_author));

// Get author's Github
        $user_github = esc_url(get_the_author_meta('github', $post->post_author));


// Get link to the author  page
        $user_posts = esc_url(get_author_posts_url(get_the_author_meta('ID', $post->post_author)));
        if (!empty($display_name))
            $author_details = '<div class="author-flex">';
        $author_details .= '<div class="author-image">' . get_avatar(get_the_author_meta('ID'), 90) . '</div>';
        $author_details .= '<div class="author-right-content">';
        $author_details .= '<div class="author-name"><strong>' . get_the_author_meta('display_name') . '</strong></div>';
        $author_details .= '<p class="author-bio">' . get_the_author_meta('description') . '</p>';
        $author_details .= '</div> </div>';
        $author_details .= '<div class="author-social">';

// Display author Email link
        $author_details .= ' <a href="' . esc_url( 'mailto:' . $user_email ) . '" target="_blank" rel="nofollow" title="E-mail" class="tooltip"><i class="fa fa-envelope-square fa-2x"></i> </a>';

// Display author Facebook link
        if (!empty($user_facebook)) {
            $author_details .= ' <a href="' . esc_url($user_facebook) . '" target="_blank" rel="nofollow" title="Facebook" class="tooltip"><i class="fa fa-facebook-official fa-2x"></i> </a>';
        } else {
           
        }

// Display author Skype link
        if (!empty($user_skype)) {
            $author_details .= ' <a href="' . esc_url($user_skype) . '" target="_blank" rel="nofollow" title="Username paaljoachim Skype" class="tooltip"><i class="fa fa-skype fa-2x"></i> </a>';
        } else {
           
        }

// Display author Twitter link
        if (!empty($user_twitter)) {
            $author_details .= ' <a href="' . esc_url($user_twitter) . '" target="_blank" rel="nofollow" title="Twitter" class="tooltip"><i class="fa fa-twitter-square fa-2x"></i> </a>';
        } else {
            
        }

// Display author LinkedIn link
        if (!empty($user_linkedin)) {
            $author_details .= ' <a href="' . esc_url($user_linkedin) . '" target="_blank" rel="nofollow" title="LinkedIn" class="tooltip"><i class="fa fa-linkedin-square fa-2x"></i> </a>';
        } else {
           
        }

// Display author Youtube link
        if (!empty($user_youtube)) {
            $author_details .= ' <a href="' . esc_url($user_youtube) . '" target="_blank" rel="nofollow" title="Youtube" class="tooltip"><i class="fa fa-youtube-square fa-2x"></i> </a>';
        } else {
            
        }

// Display author Google + link
        if (!empty($user_googleplus)) {
            $author_details .= ' <a href="' . esc_url($user_googleplus) . '" target="_blank" rel="nofollow" title="Google+" class="tooltip"><i class="fa fa-google-plus-square fa-2x"></i> </a>';
        } else {
           
        }

// Display author Pinterest link
        if (!empty($user_pinterest)) {
            $author_details .= ' <a href="' . esc_url($user_pinterest) . '" target="_blank" rel="nofollow" title="Pinterest" class="tooltip"><i class="fa fa-pinterest-square fa-2x"></i> </a>';
        } else {
            
        }
// Display author instagram link
        if (!empty($user_instagram)) {
            $author_details .= ' <a href="' . esc_url($user_instagram ). '" target="_blank" rel="nofollow" title="instagram" class="tooltip"><i class="fa fa-instagram fa-2x"></i> </a>';
        } else {
            
        }

// Display author Github link
        if (!empty($user_github)) {
            $author_details .= ' <a href="' . esc_url($user_github ). '" target="_blank" rel="nofollow" title="Github" class="tooltip"><i class="fa fa-github-square fa-2x"></i> </a>';
        } else {
            
        }

        $author_details .= '</div>';

        $wpupa_hide_post_option = get_option('wpupa_hide_post_option');
        if($wpupa_hide_post_option == ''){
            $content = $content . '<footer class="author-bio-section" >' . wp_kses_post($author_details) . '</footer>';
        }
    }
    return $content;
}

add_action('the_content', 'wp_author_social_info_box');
remove_filter('pre_user_description', 'wp_filter_kses');