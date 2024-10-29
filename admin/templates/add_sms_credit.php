<?php
if ( ! defined( 'ABSPATH' ) ) 
    exit; // Exit if accessed directly
?>
<h1><?php _e( 'Add SMS Credits', 'awesome-sms-for-woocommerce' ) ?></h1>
<form action="" method="post">
	<table class="form-table ">
    	<tr valign="top">
        	<th scope="row"><?php _e( 'Enter activation code', 'awesome-sms-for-woocommerce' ) ?></th>
        	<td>
        		<input type="text" name="sms_code" value="">
        		<span class="tooltip description"><?php _e( ' enter the 6 digit alphanumeric code received by email ', 'awesome-sms-for-woocommerce' ) ?></span>
        	</td>
    	</tr>
        <tr scope="row">
            <td colspan="2">
                <span class="tooltip description">
                    <?php _e( 'If you have not already purchase SMS credit, you can do so ', 'awesome-sms-for-woocommerce' ) ?>
                    <a href="https://togidata.dk/produkt-kategori/sms/" target="_blank"><?php _e( 'here.', 'awesome-sms-for-woocommerce' ) ?></a>
                </span>
            </td>
        </tr>
    </table>
    <?php submit_button( __( 'Activate','awesome-sms-for-woocommerce' ),'primary','activate' );?>
</form>

<!--/* code to display already entered sms credits */ -->
<h1><?php _e( 'Active SMS Credits', 'awesome-sms-for-woocommerce' ) ?></h1>
<?php

$active_sms_packs = array();
$active_sms_codes = get_option( 'asmsfw_active_codes' );
//print_r($active_sms_codes);
if(is_array($active_sms_codes)) {
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
            if ($wp_response['response']['code'] == 200) {
                $response = json_decode($wp_response['body']);
                if( is_object($response) ) {
                    if( array_key_exists( $response->product_id, $active_sms_packs ) ) {
                        $response->total_credit+= $active_sms_packs[$response->product_id]->total_credit;
                        $response->balance+= $active_sms_packs[$response->product_id]->balance;
                    }
                    $active_sms_packs[$response->product_id] = $response;
                }
            }
        }
    }
}
?>
<table class="wp-list-table widefat fixed striped ">
    <thead>
        <tr>
            <th><?php _e( 'SMS Product', 'awesome-sms-for-woocommerce' ) ?></th>
            <th><?php _e( 'Total Credit', 'awesome-sms-for-woocommerce' ) ?></th>
            <th><?php _e( 'Used Credit', 'awesome-sms-for-woocommerce' ) ?></th>
            <th><?php _e( 'Balance Credit', 'awesome-sms-for-woocommerce' ) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach( $active_sms_packs as $sms_product ) { 
            
            ?>
            <tr>
                <td><?php echo $sms_product->product_name ?></td>
                <td><?php echo $sms_product->total_credit ?></td>
                <td><?php echo $sms_product->total_credit - $sms_product->balance ?></td>
                <td><?php echo $sms_product->balance ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

