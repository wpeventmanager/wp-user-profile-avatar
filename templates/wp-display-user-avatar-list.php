<?php
/**
 * Display user avatar list template
 */
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="user-avatar-list" style="text-align: <?php echo esc_attr($atts['align']); ?>;">
    <?php if (!empty($user_avatars)) {
		$avatar_counter = 0;
        foreach ($user_avatars as $user) {  ?>
            <div class="user-avatar" style="display: inline-block; margin: 10px;text-align:center;">
                <?php if (!empty($user['avatar_url'])) { ?>
                    <img src="<?php echo esc_url($user['avatar_url']); ?>" alt="<?php echo esc_attr($user['display_name']); ?>" style="border-radius: <?php echo esc_attr($atts['border_radius']); ?>px; width: <?php echo esc_attr($atts['avatar_size']); ?>px; height: <?php echo esc_attr($atts['avatar_size']); ?>px;"/><br>
                <?php } ?>
				<?php $author_page_url = get_author_posts_url($user['ID']);?>
                <?php if (!empty($user['show_name'])) { ?>
                    <span class="user-name"><a href="<?php echo $user['avatar_url'];?>"><?php echo esc_html($user['display_name']); ?></a></span>
                <?php } ?>
				
				<?php if (!empty($user['post_count'])) {
                    echo '<span class="user-postcount">('.$user['post_count'].')</span><br>';
                } else {
                    echo '<span class="user-postcount">(0)</span><br>';
                } ?>

                <?php if (!empty($user['biography'])) { ?>
                    <span class="user-biography"><?php echo esc_html($user['biography']); ?></span>
                <?php } ?>

                <?php if (!empty($user['user_link'])) {
                    switch ($user['user_link']) {
                        case 'website':
                            echo '<p class="user-link"><a href="User website URL">Visit Website</a></p>';
                            break;
                        case 'authorpage':
                            $author_page_url = get_author_posts_url($user['ID']);
                            echo '<p class="user-link"><a href="' . $author_page_url . '">View Profile</a></p>';
                            break;
                        default:
                            break;
                    }
                } ?>
            </div>
        <?php }
    } else { ?>
        <p>No avatars available</p>
    <?php } ?>
	
	<div class="pagination" style="text-align:center;">
		<?php
		
		echo paginate_links(array(
			'base' => get_pagenum_link(1) . '%_%',
			'format' => 'page/%#%/',
			'current' => max(1, get_query_var('paged', 1)),
			'total' => $total_pages,
			'prev_text' => '«',
            'next_text' => '»',
		));
		?>
		
	</div>
</div>


