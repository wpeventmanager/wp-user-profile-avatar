<?php
/**
 * user profile shortcode
 */
if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wp-user-profile-avatar">
	<a href="<?php echo $link; ?>" target="<?php echo $target; ?>" class="wp-user-profile-avatar-link">
		<img src="<?php echo $image_url; ?>" class="size-<?php echo $size; ?> <?php echo $align; ?>" >
	</a>
	<p class="caption-text <?php echo $align; ?>"><?php echo $content; ?></p>
</div>