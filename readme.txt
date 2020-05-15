=== WP User Profile Avatar ===

Contributors: wpeventmanager,ashokdudhat,hiteshmakvana
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=55FRYATTFLA5N
Tags: avatar, user profile, gravatar,custom profile photo, custom profile picture, profile photo, profile picture, author image, author photo
Requires at least: 4.1
Tested up to: 5.2.2
Stable tag: 1.0
Requires PHP: 5.4
License: GNU General Public License v3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html

    
== Description ==


WordPress avatar or User Profile Avatar you can easily change by WP User Profile Avatar. WP User Profile Avatar allows you to use any photos uploaded into your Media Library or use photo url as an avatar instead of using Gravatar.


**Plugin Features**

* You can choose an image from Media Library.
* You can upload avatar image from your computer.
* You can decide visibility to show avatar or not.
* Display avatar & upload avatar from frontend side shortcode for Visual Editor.
* Allow anyone *(Contributors & Subscribers)* can upload avatar.
* Disable Gravatar and use own custom avatars
* You can rate avatar as G, PG, R, X based on your appropriateness
* Allow change default avatar.

= Be a contributor =

If you want to contribute, go to our [WP User Profile Avatar GitHub Repository](https://github.com/wpeventmanager/wp-user-profile-avatar) and see where you can help.

You can also add a new language via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/wp-user-profile-avatar). We've built a short guide explaining [how to translate and localize the plugin](https://www.wp-eventmanager.com/documentation/translating-wp-event-manager/).

Thanks to all of our contributors.

= Connect With US =

To stay in touch and get latest update about WP User Profile Avatar's further releases and features, you can connect with us via:

- [Facebook](https://www.facebook.com/wpeventmanager/)
- [Twitter](https://twitter.com/wp_eventmanager)
- [Google Plus](https://plus.google.com/u/0/b/107105224603939407328/107105224603939407328)
- [Linkedin](https://www.linkedin.com/company/wp-event-manager)
- [Pinterest](https://www.pinterest.com/wpeventmanager/)
- [Youtube](https://www.youtube.com/channel/UCnfYxg-fegS_n9MaPNU61bg).


== Installation ==



= Automatic installation =


Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.


In the search field type "WP User Profile Avatar" and click Search Plugins. Once you've found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by clicking _Install Now_.


= Manual installation =


The manual installation method involves downloading the plugin and uploading it to your web server via your favorite FTP application.


* Download the plugin file to your computer and unzip it

* Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.

* Activate the plugin from the Plugins menu within the WordPress admin.


== Frequently Asked Questions ==


= How I can set new user profile avatar? =

Go Admin Dashboard -> Users -> All Users --> Select any user profile you would like to edit. 
Find "WP User Profile Avatar" section, You can give new avatar url path or you can upload avatar using media library.
Upate User.

= How I can display new user profile avatar at frontend side? =

You can show user profile avatar two ways.

1. Use the below shortcode and pass parameters based on your need.

[user_profile_avatar]  --> It will show default avatar of the plugin.
[user_profile_avatar user_id="9"] --> It will selected user avatar.

You can also set other parameters like size, align, link and target in this shortcode.

2. Using the function get_user_profile_avatar_url.

You will need to place below code in each area of your theme where you wish to add and retrieve your theme’s custom avatar image. 

<?php
    // Get The Post's Author ID
    $authorID = get_the_author_meta('ID');
    $authorname = get_the_author_meta('display_name', $authorID);
    // Set the image size. Accepts all registered images sizes and array(int, int)
    $size = 'thumbnail';
    $imgURL='';

    // Get the image URL using the author ID and image size params
    if (function_exists('get_user_profile_avatar_url')) 
        $imgURL = get_user_profile_avatar_url($authorID, $size);

    // display image on the page
    echo '<img src="'. $imgURL .'" alt="'. $authorname .'">';
?>

= How to allow Contributors & Subscribers can upload avatar? =

Go Admin Dashboard -> Users -> Profile Avatar Settings 
Find "Allow Contributors & Subscribers to upload avatars", check it.
Save Changes.


== Changelog ==

= 1.0 =

* First stable release.

