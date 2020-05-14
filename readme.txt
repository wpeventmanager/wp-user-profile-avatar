=== WP User Profile Avatar ===

Contributors: WPEM Team,ashokdudhat,hiteshmakvana
Requires at least: 4.1
Tested up to: 5.2.2
Stable tag: 1.0
License: GNU General Public License v3.0

== Installation ==

To install this plugin, please refer to the guide here: http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation

== Changelog ==

= 1.0 =
* First release.

<?php
    // Retrieve The Post's Author ID
    $user_id = get_the_author_meta('ID');
    // Set the image size. Accepts all registered images sizes and array(int, int)
    $size = 'thumbnail';

    // Get the image URL using the author ID and image size params
    $imgURL = get_user_profile_avatar_url($user_id, $size);

    // Print the image on the page
    echo '<img src="'. $imgURL .'" alt="">';
?>