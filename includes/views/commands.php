<?php
/**
 * @global array $commands
 * @global string $command_key
 * @global int $post_id
 */
$servers = \WooMinecraft\WooCommerce\get_servers();
?>
<div class="woo_minecraft">
	<p class="title"><?php esc_html_e( 'WooMinecraft Commands', 'woominecraft' ); ?></p>
	<table class="woominecraft commands" cellpadding="5px">
		<thead>
			<tr>
				<th class="command">
					<?php esc_html_e( 'Command', 'woominecraft' ); ?>
					<?php echo wc_help_tip( esc_html__( 'The player name will be put in place of {player}.  You also do not have to use a slash to start the command.', 'woominecraft' ) ); ?>
				</th>
				<th class="server">
					<?php esc_html_e( 'Server', 'woominecraft' ); ?>
					<?php echo wc_help_tip( esc_html__( 'Servers are managed under WooCommerce General Settings', 'woominecraft' ) ); ?>
				</th>
				<th class="buttons"><input type="button" class="button button-small button-primary wmc_add_server" value="<?php esc_html_e( 'Add Command', 'woominecraft' ); ?>" /></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! isset( $commands ) || empty( $commands ) ) : ?>
			<tr class="row">
				<td><input type="text" name="wmc_commands[<?php echo esc_attr( $command_key ); ?>][post_<?php echo (int) $post_id; ?>][0][command]" class="widefat" placeholder="<?php echo esc_attr_x( 'give {player} apple 1', 'Sample Command', 'woominecraft' ); ?>" /> </td>
				<td>
					<select name="wmc_commands[<?php echo esc_attr( $command_key ); ?>][post_<?php echo (int) $post_id; ?>][0][server]" >
					<?php
					foreach ( $servers as $server ) {
						printf( '<option value="%s">%s</option>', esc_attr( $server['key'] ), esc_html( $server['name'] ) );
					}
					?>
					</select>
				</td>
				<td><input type="button" class="button button-small wmc_delete_server" value="<?php esc_html_e( 'Delete Command', 'woominecraft' ); ?>" /></td>
			</tr>
			<?php else : ?>
				<?php $offset = 0; foreach ( $commands as $server_key => $command ) : ?>
					<?php foreach ( $command as $player_command ) : ?>
					<tr class="row">
						<td><input type="text" name="wmc_commands[<?php echo esc_attr( $command_key ); ?>][post_<?php echo (int) $post_id; ?>][<?php echo (int) $offset; ?>][command]" class="widefat" placeholder="<?php esc_html_e( 'give {player} apple 1', 'woominecraft' ); ?>" value="<?php echo esc_attr( $player_command ); ?>" /> </td>
						<td>
							<select name="wmc_commands[<?php echo esc_attr( $command_key ); ?>][post_<?php echo (int) $post_id; ?>][<?php echo (int) $offset; ?>][server]" >
								<?php
								foreach ( $servers as $server ) {
									printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $server['key'] ), selected( $server['key'], $server_key, false ), esc_html( $server['name'] ) );
								}
								?>
							</select>
						</td>
						<td><input type="button" class="button button-small wmc_delete_server" value="<?php esc_html_e( 'Delete Command', 'woominecraft' ); ?>" /></td>
					</tr>
					<?php
						$offset++;
						endforeach; // END Foreach
					?>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<?php if ( isset( $commands ) && ! empty( $commands ) ) : 
		$is_autocomplete = get_post_meta( $post_id, '_wmc_autocomplete', true ) === 'yes';
	?>
	<p class="form-field wmc_autocomplete_field">
		<label for="wmc_autocomplete_<?php echo (int) $post_id; ?>">
			<input type="checkbox" id="wmc_autocomplete_<?php echo (int) $post_id; ?>" name="wmc_autocomplete[<?php echo esc_attr( $command_key ); ?>][post_<?php echo (int) $post_id; ?>]" value="yes" <?php checked( $is_autocomplete, true ); ?> />
			<strong><?php esc_html_e( 'Auto-Complete Order Status', 'woominecraft' ); ?></strong>
		</label>
		<span class="description" style="display:block; margin-top:4px;"><?php esc_html_e( 'If checked, orders containing this product will automatically be marked as completed when payment is received (assuming all other items in the cart also auto-complete or do not require processing).', 'woominecraft' ); ?></span>
	</p>
	<?php endif; ?>
</div>
