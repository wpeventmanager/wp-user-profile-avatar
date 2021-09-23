<?php
/**
 * user profile shortcode
 */
if (!defined('ABSPATH'))
    exit;
?>

<div class="wp-user-profile-avatar">
    <a href="<?php echo $link; ?>" target="<?php echo $target; ?>" class="wp-user-profile-avatar-link">
        <?php if(is_array($image_url)) { ?>
            <img src="<?php echo $image_url[0]; ?>" class="size-<?php echo $size; ?> <?php echo $align; ?>" width="<?php echo $image_url[1]; ?>" height="<?php echo $image_url[2]; ?>" alt="<?php echo $content; ?>" />
        <?php } else { ?>
            <img src="<?php echo $image_url; ?>" class="size-<?php echo $size; ?> <?php echo $align; ?>" alt="<?php echo $content; ?>" />
        <?php } ?>
    </a>
    <p class="caption-text <?php echo $align; ?>"><?php echo $content; ?></p>
</div>