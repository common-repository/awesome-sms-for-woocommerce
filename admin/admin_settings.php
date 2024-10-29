<?php  
if ( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

/* Add Menu to backend "SMS Settings" */
add_action( 'admin_menu', 'asmsfw_add_settings_menu' );
function asmsfw_add_settings_menu() {
	add_menu_page( 'SMS Settings', __( 'SMS Settings', 'awesome-sms-for-woocommerce' ), 'manage_options', 'asmsfw_settings', 'asmsfw_settings_page_content','');
}
function asmsfw_settings_page_content() {
	$active_tab = '';
	if($_GET['tab']) {
		$active_tab = $_GET['tab'];
	}?>
	<div class="wrap">
		<?php 
		/* adding Tab menu and content as hook */ 
		do_action( 'asmsfw_settings_menu', $active_tab );
		do_action( 'asmsfw_settings_content', $active_tab );
		?>
	</div>
	<?php
}

/* split tab menu hook to tab menu item hook */
add_action( 'asmsfw_settings_menu', 'asmsfw_settings_menu_item' );
function asmsfw_settings_menu_item( $active_tab ) { ?>
	<h2 class="nav-tab-wrapper">
	<?php do_action( 'asmsfw_settings_new_menu_item', $active_tab ); ?>
	</h2>
<?php
}


/*Tab content pages*/
add_action( 'asmsfw_settings_content', 'asmsfw_settings_content' );
function asmsfw_settings_content( $active_tab ) {
	//load pages based on tab 
	if( empty( $active_tab ) ) {
		require ASMSFW_DIR . 'admin/templates/sms_settings.php';
	}
	else if( $active_tab == 'sms_templates' ) {
		require ASMSFW_DIR . 'admin/templates/sms_templates.php';
	}
	else if( $active_tab == 'add_sms_credit' ) {
		require ASMSFW_DIR . 'admin/templates/add_sms_credit.php';
	}
}

//===================== SMS Sending settings ======================//
/* add sms sending settings tab menu */
add_action( 'asmsfw_settings_new_menu_item', 'asmsfw_settings_sms_sending_settings_menu' );
function asmsfw_settings_sms_sending_settings_menu( $active_tab ) {	?>
	<a href="?page=asmsfw_settings" class="nav-tab <?php echo $active_tab == '' ? 'nav-tab-active' : ''; ?>">
		<?php _e( 'SMS Sending Settings', 'awesome-sms-for-woocommerce' ) ?>
	</a>
<?php
}

add_action( 'admin_init', 'asmsfw_register_sms_settings' );
function asmsfw_register_sms_settings() {
	register_setting( 'asmsfw-settings', 'asmsfw_settings_server_url' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_confirmation' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_status_change_pending' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_status_change_onhold' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_status_change_processing' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_status_change_finished' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_order_status_change_cancelled' );

	register_setting( 'asmsfw-settings', 'asmsfw_settings_admin_notification_limit' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_notification_type' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_admin_notification_phone' );
	register_setting( 'asmsfw-settings', 'asmsfw_settings_admin_notification_email' );
	
}

 
//==================== sms templates settings ====================//
/* add sms templates tab menu */
add_action( 'asmsfw_settings_new_menu_item', 'asmsfw_settings_add_sms_templates_menu' );
function asmsfw_settings_add_sms_templates_menu( $active_tab ) {	?>
	<a href="?page=asmsfw_settings&tab=sms_templates" class="nav-tab <?php echo $active_tab == 'sms_templates' ? 'nav-tab-active' : ''; ?>">
		<?php _e( 'SMS Templates', 'awesome-sms-for-woocommerce' ) ?>
	</a>
<?php
}

add_action( 'admin_init', 'asmsfw_register_templates_settings' );
function asmsfw_register_templates_settings() {
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_confirmation' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_status_change_pending' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_status_change_onhold' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_status_change_processing' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_status_change_finished' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_template_order_status_change_cancelled' );
	register_setting( 'asmsfw-templates-settings', 'asmsfw_settings_send_msg_from_order' );


}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_confirmation_template' );
function asmsfw_add_order_confirmation_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_confirmation"><?php _e( 'Order confirmation :', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_confirmation" cols="100" rows="3"><?php echo trim( get_option('asmsfw_template_order_confirmation') ) ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_status_change_pending_template' );
function asmsfw_add_order_status_change_pending_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_status_change_pending"><?php _e( 'Order status change : <br> Pending', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_status_change_pending" cols="100" rows="3"><?php echo get_option('asmsfw_template_order_status_change_pending') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_status_change_onhold_template' );
function asmsfw_add_order_status_change_onhold_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_status_change_onhold"><?php _e( 'Order status change : <br> On-hold', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_status_change_onhold" cols="100" rows="3"><?php echo get_option('asmsfw_template_order_status_change_onhold') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_status_change_processing_template' );
function asmsfw_add_order_status_change_processing_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_status_change_processing"><?php _e( 'Order status change : <br> Processing', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_status_change_processing" cols="100" rows="3"><?php echo get_option('asmsfw_template_order_status_change_processing') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_status_change_finished_template' );
function asmsfw_add_order_status_change_finished_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_status_change_finished"><?php _e( 'Order status change : <br> Finished', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_status_change_finished" cols="100" rows="3"><?php echo get_option('asmsfw_template_order_status_change_finished') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_add_order_status_change_cancelled_template' );
function asmsfw_add_order_status_change_cancelled_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_template_order_status_change_cancelled"><?php _e( 'Order status change : <br> Cancelled', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_template_order_status_change_cancelled" cols="100" rows="3"><?php echo get_option('asmsfw_template_order_status_change_cancelled') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {product}, {date}, {site_title} </p>
		</td>
	</tr>
<?php
}

add_action( 'asmsfw_sms_templates', 'asmsfw_send_sms_from_order_template' );
function asmsfw_send_sms_from_order_template() {	?>
	<tr valign="top">
		<th scrop="row"><label for="asmsfw_settings_send_msg_from_order"><?php _e( 'Custom SMS to customer via order : ', 'awesome-sms-for-woocommerce' ) ?></label></th>
		<td>
			<textarea name="asmsfw_settings_send_msg_from_order" cols="100" rows="3"><?php echo get_option('asmsfw_settings_send_msg_from_order') ?></textarea>
			<p class="description"> <?php _e( 'Tags ', 'awesome-sms-for-woocommerce' ) ?> : {name}, {email}, {phone}, {order_date}, {order_id}, {order_total}, {address}, {site_title}, {product}</p>
		</td>
	</tr>
<?php
}


/* add sms credit tab menu */
add_action( 'asmsfw_settings_new_menu_item', 'asmsfw_settings_add_sms_credit_menu' );
function asmsfw_settings_add_sms_credit_menu( $active_tab ) {	?>
	<a href="?page=asmsfw_settings&tab=add_sms_credit" class="nav-tab <?php echo $active_tab == 'add_sms_credit' ? 'nav-tab-active' : ''; ?>">
		<?php _e( 'Add SMS credit', 'awesome-sms-for-woocommerce' ) ?>
	</a>
<?php
}

?>