<?php
if ( ! defined( 'ABSPATH' ) ) 
    exit; // Exit if accessed directly
?> 
<h1><?php _e( 'SMS Sending Settings', 'awesome-sms-for-woocommerce' ) ?></h1>
<form method="post" action="options.php">
	<?php settings_fields( 'asmsfw-settings' ); ?>
	<?php do_settings_sections( 'asmsfw-settings' ) ?>
	<table class="form-table">

		<tr valign="top">
			<th scrop="row"><?php _e( 'SMS API Server URL', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setsurl = esc_attr(get_option('asmsfw_settings_server_url'));?>
				<label for="asmsfw_settings_server_url">
					<input type="text" name="asmsfw_settings_server_url" value="<?php echo $setsurl ?>" class="regular-text"  placeholder="<?php _e( 'Enter API Server URL here', 'awesome-sms-for-woocommerce' ) ?>"><br>
					<span class="tooltip description"><?php _e( 'This field shows the site where you purchased the SMS. You should not change the content of this field.', 'awesome-sms-for-woocommerce' ) ?></span>
				</label>
			</td>
		</tr>

		<tr valign="top">
			<th scrop="row"><?php _e( 'Send SMS to customers with an order confirmation', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setoc = esc_attr(get_option('asmsfw_settings_order_confirmation'));?>
				<label for="asmsfw_settings_order_confirmation">
					<input type="radio" name="asmsfw_settings_order_confirmation" value="true" 
						<?php echo $setoc == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_confirmation">
					<input type="radio" name="asmsfw_settings_order_confirmation" value="false"
					<?php echo $setoc == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scrop="row" colspan="2"><?php _e( 'Send SMS to customers when the order status has changed to :', 'awesome-sms-for-woocommerce' ) ?></th>
		</tr>
		<tr valign="top">
			<th scrop="row"><?php _e( 'Pending', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setosc = esc_attr(get_option('asmsfw_settings_order_status_change_pending'));?>
				<label for="asmsfw_settings_order_status_change_pending">
					<input type="radio" name="asmsfw_settings_order_status_change_pending" value="true"
					<?php echo $setosc == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_status_change_pending">
					<input type="radio" name="asmsfw_settings_order_status_change_pending" value="false"
					<?php echo $setosc == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scrop="row"><?php _e( 'On hold', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setosoh = esc_attr(get_option('asmsfw_settings_order_status_change_onhold'));?>
				<label for="asmsfw_settings_order_status_change_onhold">
					<input type="radio" name="asmsfw_settings_order_status_change_onhold" value="true"
					<?php echo $setosoh == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_status_change_onhold">
					<input type="radio" name="asmsfw_settings_order_status_change_onhold" value="false"
					<?php echo $setosoh == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scrop="row"><?php _e( 'Processing', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setosp = esc_attr(get_option('asmsfw_settings_order_status_change_processing'));?>
				<label for="asmsfw_settings_order_status_change_processing">
					<input type="radio" name="asmsfw_settings_order_status_change_processing" value="true"
					<?php echo $setosp == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_status_change_processing">
					<input type="radio" name="asmsfw_settings_order_status_change_processing" value="false"
					<?php echo $setosp == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scrop="row"><?php _e( 'Finished', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setocf = esc_attr(get_option('asmsfw_settings_order_status_change_finished'));?>
				<label for="asmsfw_settings_order_status_change_finished">
					<input type="radio" name="asmsfw_settings_order_status_change_finished" value="true"
					<?php echo $setocf == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_status_change_finished">
					<input type="radio" name="asmsfw_settings_order_status_change_finished" value="false"
					<?php echo $setocf == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scrop="row"><?php _e( 'Cancelled', 'awesome-sms-for-woocommerce' ) ?></th>
			<td>
				<?php $setocc = esc_attr(get_option('asmsfw_settings_order_status_change_cancelled'));?>
				<label for="asmsfw_settings_order_status_change_cancelled">
					<input type="radio" name="asmsfw_settings_order_status_change_cancelled" value="true"
					<?php echo $setocc == 'true' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Activate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
				<label for="asmsfw_settings_order_status_change_cancelled">
					<input type="radio" name="asmsfw_settings_order_status_change_cancelled" value="false"
					<?php echo $setocc == 'false' ? 'checked="checked"' : '' ?> >
					<?php _e( 'Deactivate', 'awesome-sms-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>

		<!-- Notification Settings -->
		<tr valign="top">
			<th scrop="row" colspan="2"><h3><?php _e( 'Notifications', 'awesome-sms-for-woocommerce' ) ?></h3></th>
		</tr>
		<tr valign="top">
			<th scrop="row" colspan="2">
				<?php _e( 'Notify admin when there is ', 'awesome-sms-for-woocommerce' ) ?>
				<input name="asmsfw_settings_admin_notification_limit" value="<?php echo get_option('asmsfw_settings_admin_notification_limit'); ?>" type="number" step="1" min="1" id="asmsfw_settings_admin_notification_limit" class="small-text">
				<?php _e( 'credit left in balance', 'awesome-sms-for-woocommerce' ) ?>
			</th>
		</tr>
		<tr valign="top">
			<th scrop="row">
				<?php _e( 'Notify admin by:', 'awesome-sms-for-woocommerce' ); ?>
			</th>
			<td>
				<table>
					<tr>
						<td><input type="radio" <?php echo get_option('asmsfw_settings_notification_type')=='sms'?'checked="checked"':''; ?> name="asmsfw_settings_notification_type" value="sms"><label><?php _e( 'SMS', 'awesome-sms-for-woocommerce' ) ?> </label></td>
						<td><input type="text" name="asmsfw_settings_admin_notification_phone"  value="<?php echo get_option('asmsfw_settings_admin_notification_phone'); ?>"  id="asmsfw_settings_admin_notification_phone"></td>
					</tr>
					<tr>
						<td><input type="radio" <?php echo get_option('asmsfw_settings_notification_type')=='email'?'checked="checked"':''; ?> name="asmsfw_settings_notification_type" value="email"><label><?php _e( 'E-mail', 'awesome-sms-for-woocommerce' ) ?> </label></td>
						<td><input type="text" name="asmsfw_settings_admin_notification_email"  value="<?php echo get_option('asmsfw_settings_admin_notification_email'); ?>"  id="asmsfw_settings_admin_notification_email"></td>
					</tr>
				</table>
			</td>
		</tr>
		

	</table>
	<?php submit_button();?>
</form>

<?php 

?>