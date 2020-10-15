<?php
/**
 * Comment Setting page.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap">
<h1><?php _e( 'disable Comments', 'disable-comments' ); ?></h1>
<?php

global $wpdb;
$comments_count = $wpdb->get_var( "SELECT count(comment_id) from $wpdb->comments" );
if ( $comments_count <= 0 ) {?>

<p><strong><?php _e( 'No comments available for disable.', 'disable-comments' ); ?></strong></p>
</div>
<?php
	return;
}

function update_options($options) {
    
	update_option( 'disable_comments_options', $options );

}

$typeargs = array( 'public' => true );
$options = get_option( 'disable_comments_options', array() );
$modified_types = array();
$disabled_post_types = get_disabled_post_types();

if ( ! empty( $disabled_post_types ) ) {
	foreach ( $disabled_post_types as $type ) {
		// we need to know what native support was for later.
		if ( post_type_supports( $type, 'comments' ) ) {
			$modified_types[] = $type;
			remove_post_type_support( $type, 'comments' );
			remove_post_type_support( $type, 'trackbacks' );
		}
	}
}
$types = get_post_types( $typeargs, 'objects' );
// foreach ( array_keys( $types ) as $type ) {
// 	if ( ! in_array( $type, $modified_types ) && ! post_type_supports( $type, 'comments' ) ) {   // the type doesn't support comments anyway.
// //		unset( $types[ $type ] );
// 	}
// }


if ( isset( $_POST['submit'] ) && isset( $_POST['mode'] ) ) {
	check_admin_referer( 'disable-comments-admin' );

	if ( $_POST['mode'] == 'remove_everywhere' ) {
		if ( $wpdb->query( "TRUNCATE $wpdb->commentmeta" ) != false ) {
			if ( $wpdb->query( "TRUNCATE $wpdb->comments" ) != false ) {
				$wpdb->query( "UPDATE $wpdb->posts SET comment_count = 0 WHERE post_author != 0" );
				$wpdb->query( "OPTIMIZE TABLE $wpdb->commentmeta" );
				$wpdb->query( "OPTIMIZE TABLE $wpdb->comments" );
				echo "<p style='color:green'><strong>" . __( 'All comments have been disable.', 'disable-comments' ) . '</strong></p>';
			} else {
				echo "<p style='color:red'><strong>" . __( 'Internal error occured. Please try again later.', 'disable-comments' ) . '</strong></p>';
			}
		} else {
			echo "<p style='color:red'><strong>" . __( 'Internal error occured. Please try again later.', 'disable-comments' ) . '</strong></p>';
		}
	} else {
		$disabled_post_types = empty( $_POST['disabled_types'] ) ? array() : (array) $_POST['disabled_types'];
		$disabled_post_types = array_intersect( $disabled_post_types, array_keys( $types ) );

		// Extra custom post types.
		if ( ! empty( $_POST['$disabled_extra_post_types'] ) ) {
			$disabled_extra_post_types = array_filter( array_map( 'sanitize_key', explode( ',', $_POST['$disabled_extra_post_types'] ) ) );
			$disabled_extra_post_types = array_diff( $disabled_extra_post_types, array_keys( $types ) );    // Make sure we don't double up builtins.
			$disabled_post_types      = array_merge( $disabled_post_types , $disabled_extra_post_types );
		}

		if ( ! empty( $disabled_post_types ) ) {
			// Loop through post_types and remove comments/meta and set posts comment_count to 0.
			foreach ( $disabled_post_types as $disabled_post_type ) {
				$wpdb->query( "DELETE cmeta FROM $wpdb->commentmeta cmeta INNER JOIN $wpdb->comments comments ON cmeta.comment_id=comments.comment_ID INNER JOIN $wpdb->posts posts ON comments.comment_post_ID=posts.ID WHERE posts.post_type = '$disabled_post_type'" );
				$wpdb->query( "DELETE comments FROM $wpdb->comments comments INNER JOIN $wpdb->posts posts ON comments.comment_post_ID=posts.ID WHERE posts.post_type = '$disabled_post_type'" );
				$wpdb->query( "UPDATE $wpdb->posts SET comment_count = 0 WHERE post_author != 0 AND post_type = '$disabled_post_type'" );

				$post_type_object = get_post_type_object( $disabled_post_type );
				$post_type_label  = $post_type_object ? $post_type_object->labels->name : $disabled_post_type;
				echo "<p style='color:green'><strong>" . sprintf( __( 'All comments have been disable for %s.', 'disable-comments' ), $post_type_label ) . '</strong></p>';
			}

			$wpdb->query( "OPTIMIZE TABLE $wpdb->commentmeta" );
			$wpdb->query( "OPTIMIZE TABLE $wpdb->comments" );

			echo "<h4 style='color:green'><strong>" . __( 'Comment Deletion Complete', 'disable-comments' ) . '</strong></h4>';
		}
	}

	$comments_count = $wpdb->get_var( "SELECT count(comment_id) from $wpdb->comments" );
	if ( $comments_count <= 0 ) {
		?>
		<p><strong><?php _e( 'No comments available for deletion.', 'disable-comments' ); ?></strong></p>
		</div>
		<?php
		return;
	}
}
?>
<div class="wrap">
<h1><?php _ex( 'Disable Comments', 'settings page title', 'disable-comments' ); ?></h1>

<form action="" method="post" id="disable-comments">
<ul>
<li><label for="remove_everywhere"><input type="radio" id="remove_everywhere" name="mode" value="remove_everywhere" <?php checked( $options['remove_everywhere'] ); ?> /> <strong><?php _e( 'Everywhere', 'disable-comments' ); ?></strong>: <?php _e( 'Disable all comment-related controls and settings in WordPress.', 'disable-comments' ); ?></label>
	<p class="indent"><?php printf( __( '%1$s: This option is global and will affect your entire site. Use it only if you want to disable comments <em>everywhere</em>. A complete description of what this option does is <a href="%2$s" target="_blank">available here</a>.', 'disable-comments' ), '<strong style="color: #900">' . __( 'Warning', 'disable-comments' ) . '</strong>', 'https://wordpress.org/plugins/disable-comments/other_notes/' ); ?></p>
</li>
<li><label for="selected_types"><input type="radio" id="selected_types" name="mode" value="selected_types" <?php checked( ! $options['remove_everywhere'] ); ?> /> <strong><?php _e( 'On certain post types', 'disable-comments' ); ?></strong>:</label>
	<p></p>
	<ul class="indent" id="listoftypes">
		<?php
		foreach ( $types as $k => $v ) {
		echo "<li><label for='post-type-$k'><input type='checkbox' name='delete_types[]' value='$k' " . checked( $options['disabled_post_types'], true, false ) . " id='post-type-$k'> {$v->labels->name}</label></li>";}
		?>
	</ul>
	
	<p class="indent"><?php _e( 'Disabling comments will also disable trackbacks and pingbacks. All comment-related fields will also be hidden from the edit/quick-edit screens of the affected posts. These settings cannot be overridden for individual posts.', 'disable-comments' ); ?></p>
</li>
</ul>

<?php wp_nonce_field( 'disable-comments-admin' ); ?>
<h4><?php _e( 'Total Comments:', 'disable-comments' ); ?> <?php echo $comments_count; ?></h4>
<p class="submit"><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Save Changes', 'disable-comments' ); ?>"></p>
</form>
</div>
<script>
jQuery(document).ready(function($){
	function disable_comments_uihelper(){
		var indiv_bits = $("#listoftypes, #extratypes");
		if( $("#remove_everywhere").is(":checked") )
			indiv_bits.css("color", "#888").find(":input").attr("disabled", true );
		else
			indiv_bits.css("color", "#000").find(":input").attr("disabled", false );
	}

	$("#disable-comments :input").change(function(){
		$("#message").slideUp();
		disable_comments_uihelper();
	});

	disable_comments_uihelper();
});
</script>