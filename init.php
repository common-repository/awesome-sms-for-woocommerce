<?php 
if ( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

add_action( 'plugins_loaded', 'asmsfw_load_textdomain' );
function asmsfw_load_textdomain() {
  load_plugin_textdomain( 'awesome-sms-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

//checking sms credit count
add_action( 'admin_notices', 'asmsfw_check_sms_credit_count' );
function asmsfw_check_sms_credit_count() {
	$active_sms_codes = get_option( 'asmsfw_active_codes' );
	$sms_credit_balance = 0;
	if( empty($active_sms_codes) ) {
		asmsfw_notice_sms_credit_empty();
	}
	else if( is_array( $active_sms_codes ) ) {
		foreach( $active_sms_codes as $sms_code ) {
		    $body['user_active_code'] = $sms_code;
		    $wp_request_url = ASMSFW_API_SERVER_URL . '/wp-json/smscp/v2/smsCreditDetails/';
		    $wp_response = wp_remote_request(
		        $wp_request_url,
		        array(
		            'method' => 'POST', 
		            'body'   => $body        
		        )
		    );
		    $response = array();
		    if( $wp_response && !is_wp_error($wp_response) ) {
	            $response = json_decode($wp_response['body']);
	            if( $response->balance > 0 ) {
	            	$sms_credit_balance+= $response->balance;
	            }
	        }
		}

		if( $sms_credit_balance <= 0 ) {
			asmsfw_notice_sms_credit_empty();
		}
	}
	
}

function asmsfw_notice_sms_credit_empty() {
	echo '<div class="notice notice-warning is-dismissible"><p>';
	_e( 'No SMS credit available!', 'awesome-sms-for-woocommerce' );
	echo '</p></div>';
}

require ASMSFW_DIR . 'admin/init.php'; 
?>