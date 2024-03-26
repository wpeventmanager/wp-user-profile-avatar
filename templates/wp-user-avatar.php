<?php
/**
 * user profile shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wp-user-profile-avatar">
    <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="wp-user-profile-avatar-link">
        <?php if ( is_array( $image_url ) ) { ?>
            <img src="<?php echo esc_url( $image_url[0] ); ?>" class="size-<?php echo esc_attr( $size ); ?> <?php echo esc_attr( $align ); ?>" width="<?php echo esc_attr( $image_url[1] ); ?>" height="<?php echo esc_attr( $image_url[2] ); ?>" alt="<?php echo esc_attr( $content ); ?>" />
        <?php } else { ?>
            <img src="<?php echo esc_url( $image_url ); ?>" class="size-<?php echo esc_attr( $size ); ?> <?php echo esc_attr( $align ); ?>" alt="<?php echo esc_attr( $content ); ?>" />
        <?php } ?>
    </a>
    <p class="caption-text <?php echo esc_attr( $align ); ?>"><?php echo wp_kses_post( $content ); ?></p>
</div>
