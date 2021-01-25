<div class="licence-row">
	<div class="plugin-info"><?php echo $plugin['Title']; ?>
		<div class="plugin-author">
			<a target="_blank" href="//wp-eventmanager.com/"><?php echo $plugin['Author']; ?></a>				
		</div>
	</div>

	<div class="plugin-licence">
		<form method="post">
			<label for="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_licence_key"><?php _e('License', 'wp-event-manager-zoom'); ?>
				<input <?php echo $disabled; ?> type="text" id="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_licence_key" name="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_licence_key" placeholder="XXXX-XXXX-XXXX-XXXX" value="<?php echo esc_attr( $licence_key ); ?>">
			</label>

			<label for="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_email"><?php _e('Email', 'wp-event-manager-zoom'); ?>
				<input <?php echo $disabled; ?> type="email" id="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_email" name="<?php echo esc_attr( $plugin['TextDomain'] ); ?>_email" placeholder="<?php _e('Email address', 'wp-event-manager-zoom'); ?>" value="<?php echo esc_attr( $email ); ?>">
			</label>

			<?php if(!empty($licence_key) ) : ?>
				<a href="<?php echo remove_query_arg( array( 'deactivated_licence', 'activated_licence' ), add_query_arg( $plugin['TextDomain'] . '_deactivate_licence', 1 ) ) ?>" class="button"><?php _e('Deactivate License', 'wp-event-manager-zoom'); ?></a>
			<?php else : ?>
				<input type="submit" class="button" name="submit" value="<?php _e('Activate License', 'wp-event-manager-zoom'); ?>">
			<?php endif ; ?>
		</form>
	</div>
</div>