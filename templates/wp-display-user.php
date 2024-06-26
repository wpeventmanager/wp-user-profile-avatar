<?php
/**
 * User display shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="author-details" style="text-align: <?php echo esc_attr( $details['avatar_align'] ); ?>;">
    <p class="caption-text"><?php echo isset( $details['first_name'] ) ? esc_attr( $details['first_name'] ) : ''; ?></p>
    <p class="caption-text"><?php echo isset( $details['last_name'] ) ? esc_attr( $details['last_name'] ) : ''; ?></p>
    <p class="caption-text"><?php echo isset( $details['description'] ) ? esc_attr( $details['description'] ) : ''; ?></p>
    <p class="caption-text"><?php echo isset( $details['email'] ) ? esc_attr( $details['email'] ) : ''; ?></p>
    <?php
    if ( ! empty( $details['sabox_social_links'] ) ) {
        foreach ( $details['sabox_social_links'] as $name => $link ) {
            ?>
            <p class="caption-text"><?php echo esc_attr( $name ); ?>:<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_attr( $link ); ?></a></p>
                <?php
        }
    }

    $attachment = get_user_meta(get_current_user_id(),'_wpupa_attachment_id',true);
	$image_source = wp_get_attachment_image_src($attachment);
		
    if($details['avatar_size'] != ''){
        $size = $details['avatar_size'];
        $image_source[1] = $size;
    }	

    //if ( '' != esc_attr( $details['sabox-profile-image'] ) ) {
        ?>
        <img src="<?php echo $image_source[0]; ?>" width="<?php echo $image_source[1]; ?>" />
    <?php //} ?>

</div>
