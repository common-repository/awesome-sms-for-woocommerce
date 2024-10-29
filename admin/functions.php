<?php
if ( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

//add sms credit page code
add_action('init', 'asmsfw_add_sms_credit_code');
function asmsfw_add_sms_credit_code() {
	if (isset($_POST['activate'])) {
		//check for empty input

		if (empty($_POST['sms_code'])) {
			add_action('admin_notices', 'asmsfw_show_acivate_code_null_error');
		} else {
			//check sms code length is 6 or not
			if( strlen( trim($_POST['sms_code']) ) != 6 ) {
				add_action('admin_notices', 'asmsfw_show_acivate_code_length_error');
			}
			else {

				$code_details['sms_code'] = sanitize_text_field($_POST['sms_code']);
				
				$wp_request_url = ASMSFW_API_SERVER_URL . 'wp-json/smscp/v2/activate/';
				$wp_response = wp_remote_request(
					$wp_request_url,
					array(
						'method' => 'POST',
						'body'	=>	$code_details					
					)
				);
				
				//
				if( $wp_response && !is_wp_error($wp_response) ) {
					if ($wp_response['response']['code'] == 200) {
						$response = json_decode($wp_response['body']);
						if( $response == 'success' ) {
							//save sms codes as option
							$active_codes = get_option( 'asmsfw_active_codes' );
							if( $active_codes && array($active_codes) ) {
								$active_codes[] = sanitize_text_field($_POST['sms_code']);
							}
							else {
								$active_codes = array();
								$active_codes[] = sanitize_text_field($_POST['sms_code']);
							}
							update_option( 'asmsfw_active_codes', $active_codes );
							
							echo '<div class="notice notice-success is-dismissible"><p>' . __( 'SMS Credit activated', 'awesome-sms-for-woocommerce' ) . '</p></div>';
						}
						else if( $response == 'already_active' ) {
							echo '<div class="notice notice-error is-dismissible"><p> Error : ' . __( 'Already activated code', 'awesome-sms-for-woocommerce' ) . '</p></div>';
						}
						else if( $response == 'invalid' ) {
							echo '<div class="notice notice-error is-dismissible"><p>' . __( 'Error : Invalid code', 'awesome-sms-for-woocommerce' ) . '</p></div>';
						}
						//echo '<div class="notice notice-error is-dismissible">'.$wp_response['response']['code'].'</div>';

					}
				}
			}
		}
	}
}

function asmsfw_show_acivate_code_null_error() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php _e('Please enter activation code', 'awesome-sms-for-woocommerce')?></p>
	</div>
	<?php
}
function asmsfw_show_acivate_code_length_error() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php _e('Invalid code', 'awesome-sms-for-woocommerce')?></p>
	</div>
	<?php
}

//======================= Send SMS from woocomerce ==============================//
// main function to send sms from woocommerce
function asmsfw_sendSMS( $order_id, $template ) {
	
	//checking for active sms codes
	$active_sms_codes = get_option( 'asmsfw_active_codes' );
	$sms_code_default = '';
	if( is_array( $active_sms_codes ) && !empty( $active_sms_codes ) ) {
		foreach( $active_sms_codes as $sms_code ) {
			if( $sms_code_default == '' ) {
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
						$sms_code_default = $sms_code;
					}
				}
			}
		}
		
		if( $sms_code_default != '' ) {

			//selecting order details
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$product_names = array();

			foreach( $order_items as $item ) {
				$product_names[] = $item['name'];
			}
			//selecting order items, date and site title
			$tag_products = implode( ', ', $product_names );
			$tag_order_date = $order->order_date;
			$tag_site_title = get_bloginfo( 'name' );
			
			//replace tags on sms template

			$post = get_post($order_id);
			$name = get_post_meta($order_id, '_billing_first_name',true).' '.get_post_meta($order_id, '_billing_last_name',true);
			$billing_address = get_post_meta($order_id,'_billing_address_index',true);
			$email = get_post_meta($order_id, '_billing_email',true);
			$order_date = get_post_meta($order_id, '_billing_email',true);
			$phone = get_post_meta($order_id, '_billing_phone',true);
			$order_date = date('Y-m-d',strtotime($post->post_date));
			$total = get_post_meta($order_id, '_order_total',true);
			$site_title = get_bloginfo('name');
			$message  = str_replace( 
				array( '{product}','{date}', '{site_title}', '{name}','{email}','{phone}','{order_date}', '{order_id}', '{order_total}','{address}' , '{product}', '{site_title}'),
				array( $tag_products, $tag_order_date, $tag_site_title, $name, $email,$phone,$order_date,'#'.$post->ID,'DKK '.$total, $billing_address,$tag_products, $site_title ), 
				$template
			);
			$order_phone = $order->billing_phone;
			//echo $message;
			//send sms details to api
			$body['phone_number'] = $order_phone;
			$body['message'] = esc_html($message);
			$body['sms_code'] = $sms_code_default;
			$codes = str_replace(' ', '', implode('-',get_option('asmsfw_active_codes')));
			$body['codes'] = $codes;
			$body['credit_notif_count'] = esc_html(get_option('asmsfw_settings_admin_notification_limit'));
			$body['credit_notif_type'] = esc_html(get_option('asmsfw_settings_notification_type'));
			$body['credit_notif_site_name'] = esc_html(get_option('blogname'));
			if($body['credit_notif_type']=='sms'){
				$body['credit_notif_to'] = get_option('asmsfw_settings_admin_notification_phone');
			}else if($body['credit_notif_type']=='email') {
				$body['credit_notif_to'] = get_option('asmsfw_settings_admin_notification_email');
			}

			$wp_request_url = ASMSFW_API_SERVER_URL . '/wp-json/smscp/v2/sendSMS/';
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
				}
			}
			print_r($response);
		}
	}
}

//send sms with order confirmation
add_action( 'woocommerce_thankyou', 'asmsfw_send_sms_order_confirmation', 10, 1 );
function  asmsfw_send_sms_order_confirmation( $order_id ) {
	if( get_option( 'asmsfw_settings_order_confirmation' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_confirmation' );
		asmsfw_sendSMS( $order_id, $template );
	}
}

//send sms on order status change - pending
add_action( 'woocommerce_order_status_pending', 'asmsfw_send_sms_order_status_change_pending', 10, 1 );
function  asmsfw_send_sms_order_status_change_pending( $order_id ) {
	//echo get_option( 'asmsfw_template_order_status_change_pending' ) ;
	if( get_option( 'asmsfw_settings_order_status_change_pending' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_status_change_pending' );
		asmsfw_sendSMS( $order_id, $template );
	}
}

//send sms on order status change - onhold
add_action( 'woocommerce_order_status_on-hold', 'asmsfw_send_sms_order_status_change_onhold', 10, 1 );
function  asmsfw_send_sms_order_status_change_onhold( $order_id ) {
	//echo get_option( 'woocommerce_order_status_on-hold' ) ;
	if( get_option( 'asmsfw_settings_order_status_change_onhold' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_status_change_onhold' );
		asmsfw_sendSMS( $order_id, $template );
	}
}

//send sms on order status change - processing
add_action( 'woocommerce_order_status_processing', 'asmsfw_send_sms_order_status_change_processing', 10, 1 );
function  asmsfw_send_sms_order_status_change_processing( $order_id ) {
	//echo get_option( 'asmsfw_send_sms_order_status_change_processing' ) ;
	if( get_option( 'asmsfw_settings_order_status_change_processing' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_status_change_processing' );
		asmsfw_sendSMS( $order_id, $template );
	}
}

//send sms on order status change - onhold
add_action( 'woocommerce_order_status_completed', 'asmsfw_send_sms_order_status_change_finished', 10, 1 );
function  asmsfw_send_sms_order_status_change_finished( $order_id ) {
	//echo get_option( 'woocommerce_order_status_on-hold' ) ;
	if( get_option( 'asmsfw_settings_order_status_change_finished' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_status_change_finished' );
		asmsfw_sendSMS( $order_id, $template );
	}
}

//send sms on order status change - onhold
add_action( 'woocommerce_order_status_cancelled', 'asmsfw_send_sms_order_status_change_cancelled', 10, 1 );
function  asmsfw_send_sms_order_status_change_cancelled( $order_id ) {
	//echo get_option( 'woocommerce_order_status_on-hold' ) ;
	if( get_option( 'asmsfw_settings_order_status_change_cancelled' ) == 'true' ) {
		$template = get_option( 'asmsfw_template_order_status_change_cancelled' );
		asmsfw_sendSMS( $order_id, $template );
	}
}




// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'order_sms_add_meta_boxes' );
if ( ! function_exists( 'order_sms_add_meta_boxes' ) )
{
	function order_sms_add_meta_boxes()
	{
		add_meta_box( 'order_sms_add_meta_field', __('Send SMS to Customer','awesome-sms-for-woocommerce'), 'asmsfw_send_sms_to_customer_meta', 'shop_order', 'side', 'low' );
	}
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'asmsfw_send_sms_to_customer_meta' ) )
{
	function asmsfw_send_sms_to_customer_meta()
	{
		global $post;
		$order = new WC_Order($post->ID);
		$template = get_option('asmsfw_settings_send_msg_from_order');
		$name = get_post_meta($post->ID, '_billing_first_name',true).' '.get_post_meta($post->ID, '_billing_last_name',true);
		$billing_address = get_post_meta($post->ID,'_billing_address_index',true);
		$email = get_post_meta($post->ID, '_billing_email',true);
		$order_date = get_post_meta($post->ID, '_billing_email',true);
		$phone = get_post_meta($post->ID, '_billing_phone',true);
		$order_date = date('Y-m-d',strtotime($post->post_date));
		$total = get_post_meta($post->ID, '_order_total',true);

		$site_title = get_bloginfo('name');

		$items = $order->get_items();
		$i=1;
		$product = '';
		foreach ( $items as $item ) {
			if(count($items) >1) {
				if($i == count($items) ) {
					$product .= ' and '.$item['name']; 
				} else {
					$product .= $item['name'].', '; 
				}	
			} else {
				$product .= $item['name']; 
			}
			$i++;
		}

		$site_title = get_bloginfo('name');

		$template = esc_html((str_replace(array('{name}','{email}','{phone}','{order_date}', '{order_id}', '{order_total}','{address}' , '{product}', '{site_title}'), array($name,$email,$phone,$order_date,'#'.$post->ID,'DKK '.$total, $billing_address,$product, $site_title),$template)));

		echo '<p style="border-bottom:solid 1px #eee;padding-bottom:13px;">';
		$messages = get_post_meta($post->ID,'order_sms_history',true);
		$msg_html = '';
		if(!empty($messages)) {
			$messages = array_reverse($messages);

			foreach($messages as $row) { 
				if($row['key']!='')
					$msg_html.='<li rel="100" key="'.$row['key'].'" class="note system-note"><div class="note_content"><p>'.$row['content'].'</p></div><p class="meta"><abbr class="exact-date" title="'.date('d M Y H:i:s A',strtotime($row['date'])).'">added on '.date('d M Y H:i:s A',strtotime($row['date'])).'</abbr><a href="#" class="delete_item" id="'.$row['key'].'">Delete</a></p></li>';
			}
		}
		echo '<p style="border-bottom:solid 1px #eee;padding-bottom:13px;">
		<textarea name="customer_sms_content" id="customer_sms_content" rows="6">' . $template . ' </textarea></p>';
		echo '<p><input type="button" name="send_sms" class="send_sms button" value="'.__('Send SMS','awesome-sms-for-woocommerce').'" class="button "></p>';
		echo '<b>Tags : {name}, {email}, {phone}, {order_date}, {order_id}, {order_total}, {address}, {site_title}, {product}</b>';
		echo '<div class="message_history"><ul class="order_notes">'.$msg_html.'</ul></div>';
		?>
		<script>
			jQuery(document).ready(function () {
				jQuery(document).on('click','.send_sms',function () {
					var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
					var data = {
						'action': 'send_order_sms',
						'order_id': <?php echo $post->ID ?>,
						'content': jQuery('#customer_sms_content').val(),
						'to': '<?php echo $phone; ?>'
					};
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('.message_history ul').prepend(response);
					});
				});
				jQuery(document).on('click','.delete_item',function (e) {
					e.preventDefault();
					if(confirm("Are you sure..?")) {
						delId= jQuery(this).attr('id');
						var data = {
							'action': 'delete_order_sms',
							'order_id': <?php echo $post->ID ?>,
							'key': delId
						};
						jQuery.post(ajaxurl, data, function(response) {

							jQuery("[key="+delId+"]").fadeOut(300);
						});
					}
				});
				
			});
		</script>
		<?php
	}
}

add_action( 'wp_ajax_delete_order_sms', 'delete_order_sms' );
add_action( 'wp_ajax_nopriv_delete_order_sms', 'delete_order_sms' );
function delete_order_sms() {
	$history = get_post_meta( $_POST['order_id'], 'order_sms_history', true);
	foreach($history as $key=>$row) {
		if($row['key']==$_POST['key']) {
			unset($history[$key]);
		}
	}
	update_post_meta( $_POST['order_id'], 'order_sms_history', $history);
	echo 1;
	exit;
}

add_action( 'wp_ajax_send_order_sms', 'send_order_sms' );
add_action( 'wp_ajax_nopriv_send_order_sms', 'send_order_sms' );
function send_order_sms() {
	$post = get_post($_POST['order_id']);
	$order = new WC_Order($post->ID);
	$template = sanitize_text_field($_POST['content']);
	$name = esc_html(get_post_meta($post->ID, '_billing_first_name',true).' '.get_post_meta($post->ID, '_billing_last_name',true));
	$billing_address = esc_html(get_post_meta($post->ID,'_billing_address_index',true));
	$email = esc_html(get_post_meta($post->ID, '_billing_email',true));
	$order_date = esc_html(get_post_meta($post->ID, '_billing_email',true));
	$phone = esc_html(get_post_meta($post->ID, '_billing_phone',true));
	$order_date = esc_html(date('Y-m-d',strtotime($post->post_date)));
	$total = esc_html(get_post_meta($post->ID, '_order_total',true));

	$items = $order->get_items();
	$i=1;
	$product = '';
	foreach ( $items as $item ) {
		if(count($items) >1) {
			if($i == count($items) ) {
				$product .= ' and '.$item['name']; 
			} else {
				$product .= $item['name'].', '; 
			}	
		} else {
			$product .= $item['name']; 
		}
		$i++;
	}

	$site_title = get_bloginfo('name');


//	$template = strip_tags(str_replace(array('{name}','{email}','{phone}','{order_date}', '{order_id}', '{order_total}'), array($name,$email,$phone,$order_date,'#'.$post->ID,'DKK '.$total),$template));
	$template = (str_replace(array('{name}','{email}','{phone}','{order_date}', '{order_id}', '{order_total}','{address}' , '{product}', '{site_title}'), array($name,$email,$phone,$order_date,'#'.$post->ID,'DKK '.$total, $billing_address,$product, $site_title),$template));
	$key = time();
	$messages = array();
	$msg = get_post_meta($post->ID,'order_sms_history',true);
	if(!empty($msg) && is_array($msg)) {
		$messages[] = $msg;
	}
	$messages[] = array('content'=>esc_html($template), 'date'=>date('Y-m-d H:i:s'), 'key'=>$key);
	update_post_meta($post->ID,'order_sms_history',$messages);
	echo '<li rel="100" class="note system-note" key="'.$key.'"><div class="note_content"><p>'.$template.'</p></div><p class="meta"><abbr class="exact-date" title="'.date('Y-m-d H:i:s').'">added on '.date('d M Y H:i:s A').'</abbr><a href="#" class="delete_item" id="'.$key.'">Delete</a></p> <hr/></li>';
	$template = esc_html(get_option( 'asmsfw_template_order_confirmation' ));
	asmsfw_sendSMS($post->ID, $template );
	exit;
}
// Save the data of the Meta field
add_action( 'save_post', 'asmsfw_save_wc_order_other_fields', 10, 1 );
if ( ! function_exists( 'asmsfw_save_wc_order_other_fields' ) )
{

	function asmsfw_save_wc_order_other_fields( $post_id ) {

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
		if ( ! isset( $_POST[ 'mv_other_meta_field_nonce' ] ) ) {
			return $post_id;
		}
		$nonce = sanitize_text_field($_REQUEST[ 'mv_other_meta_field_nonce' ]);

        //Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce ) ) {
			return $post_id;
		}

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

        // Check the user's permissions.
		if ( 'page' == $_POST[ 'post_type' ] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}
        // --- Its safe for us to save the data ! --- //
 
	}
}


?>