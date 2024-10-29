<?php 

if ( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly


//sms settings 
if( !get_option( 'asmsfw_settings_order_confirmation' ) ) {
	update_option( 'asmsfw_settings_order_confirmation', 'true' );
}
if( !get_option( 'asmsfw_settings_order_status_change_pending' ) ) {
	update_option( 'asmsfw_settings_order_status_change_pending', 'false' );
}
if( !get_option( 'asmsfw_settings_order_status_change_onhold' ) ) {
	update_option( 'asmsfw_settings_order_status_change_onhold', 'false' );
}
if( !get_option( 'asmsfw_settings_order_status_change_processing' ) ) {
	update_option( 'asmsfw_settings_order_status_change_processing', 'true' );
}
if( !get_option( 'asmsfw_settings_order_status_change_finished' ) ) {
	update_option( 'asmsfw_settings_order_status_change_finished', 'true' );
}
if( !get_option( 'asmsfw_settings_order_status_change_cancelled' ) ) {
	update_option( 'asmsfw_settings_order_status_change_cancelled', 'false' );
}
//sms templates
if( !get_option( 'asmsfw_template_order_confirmation' ) ) {
	$data = "Tak for din ordre. Vi går i gang med at behandle ordren og giver dig besked, når varen er sendt. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_confirmation', $data );
}
if( !get_option( 'asmsfw_template_order_status_change_pending' ) ) {
	$data = "Din ordrestatus er sat til at afvente. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_status_change_pending', $data );
}
if( !get_option( 'asmsfw_template_order_status_change_onhold' ) ) {
	$data = "Din ordre er sat på hold. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_status_change_onhold', $data );
}
if( !get_option( 'asmsfw_template_order_status_change_processing' ) ) {
	$data = "Vi er nu i gang med at behandle din ordre. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_status_change_processing', $data );
}
if( !get_option( 'asmsfw_template_order_status_change_finished' ) ) {
	$data = "Din ordre er nu færdigbehandlet og på vej med posten. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_status_change_finished', $data );
}
if( !get_option( 'asmsfw_template_order_status_change_cancelled' ) ) {
	$data = "Din ordre er annulleret. Med venlig hilsen {site_title}";
	update_option( 'asmsfw_template_order_status_change_cancelled', $data );
}

if( !get_option( 'asmsfw_settings_send_msg_from_order' ) ) {
	$data = "Hej {name}, din ordre {order_id} er blevet accepteret";
	update_option( 'asmsfw_settings_send_msg_from_order', $data );
}

//option for sms codes
if( !get_option( 'asmsfw_active_codes' ) ) {
	update_option( 'asmsfw_active_codes', '' );
} 

 
/*
Notifications Start
*/

if( !get_option( 'asmsfw_settings_admin_notification_limit' ) ) {
	update_option( 'asmsfw_settings_admin_notification_limit', 10 );
}

if( !get_option( 'asmsfw_settings_notification_type' ) ) {
	update_option( 'asmsfw_settings_notification_type', 'email' );
}

if( !get_option( 'asmsfw_settings_admin_notification_email' ) ) {
	update_option( 'asmsfw_settings_admin_notification_email', get_option('admin_email') );
}

/*
Notifications End
*/


//option for server url
update_option( 'asmsfw_settings_server_url', 'https://togidata.dk/index.php/' );
?>