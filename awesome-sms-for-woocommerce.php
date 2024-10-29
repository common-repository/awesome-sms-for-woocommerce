<?php
/**
 * @wordpress-plugin
 * Plugin Name:	Awesome SMS for Woocommerce
 * Plugin URI:	https://awesometogi.com/
 * Description: Send SMS messages to your customers i Denmark using this awesome plugin
 * Version:		1.0
 * Author:		AWESOME TOGI
 * Author URI:  https://awesometogi.com/
 * Text Domain: awesome-sms-for-woocommerce
 * Domain Path:  /languages
 */

if ( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

if (!defined('WPINC')) {
	die;
}

define('ASMSFW_VERSION', '1.0');
define('ASMSFW_NAME', 'Awesome SMS for Woocommerce');

define('ASMSFW_URL', plugin_dir_url(__FILE__));
define('ASMSFW_DIR', plugin_dir_path(__FILE__));

function asmsfw_actvate_plugin() {
	require ASMSFW_DIR . 'install.php';
}
register_activation_hook(__FILE__, 'asmsfw_actvate_plugin');

if( get_option('asmsfw_settings_server_url') ) {
	$server_url = esc_attr(get_option('asmsfw_settings_server_url'));
	define('ASMSFW_API_SERVER_URL', $server_url);	
}
else {
	define('ASMSFW_API_SERVER_URL', '');
}

require ASMSFW_DIR . 'init.php';


?>