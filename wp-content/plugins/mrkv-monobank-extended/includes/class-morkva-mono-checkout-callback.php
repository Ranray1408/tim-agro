<?php
# Check if class exist
if (!class_exists('MrkvMonoCheckoutCallback'))
{
	/**
	 * Class for getting callback mono checkout
	 */
	class MrkvMonoCheckoutCallback
	{
		/**
		 * Constructor for save data by mono checkout
		 * */
		function __construct()
		{
			# Create callback
			add_action('woocommerce_api_morkva-monopay-checkout', array($this, 'mrkv_mono_checkout_callback_success'));
		}

		/**
		 * Add data order by mono checkout
		 * */
		public function mrkv_mono_checkout_callback_success(){
			# Get content
	        $mrkv_mono_callback_json = @file_get_contents('php://input');

	        # Get callback data
	        $mrkv_mono_callback = json_decode($mrkv_mono_callback_json, true);

	        # Change Timezone
			date_default_timezone_set("Europe/Kiev");
			# Get current datetime
			$date_now = date("Y-m-d h:i:sa");

			# Text Start script
			$script_start_text = "" . $date_now . "\r\n";
			$script_start_text = "Get data: " . print_r($mrkv_mono_callback, 1) . "\r\n";
			# Write text to degug.log file
			file_put_contents( __DIR__ . '/debug.log', $script_start_text, FILE_APPEND );

	        # Check callback data
	        if($mrkv_mono_callback){
	        	if(isset($mrkv_mono_callback['invoiceId'])){
	        		# Get order object
	        		$order = wc_get_orders( array(
					    'limit'        => 1, 
					    'orderby'      => 'date',
					    'order'        => 'DESC',
					    'meta_key'     => '_order_mono_ref', 
					    'meta_compare' => '==',
					    'meta_value'   => $mrkv_mono_callback['invoiceId'],
					));
	        		# Get order
					$order_main = $order[0];
					# Add data to order
					$order_main->add_order_note(print_r($mrkv_mono_callback, 1) , $is_customer_note = 0, $added_by_user = false);

					$wc_gateways      = new WC_Payment_Gateways();
		    		$payment_gateways = $wc_gateways->get_available_payment_gateways();

					if(isset($mrkv_mono_callback['paymentInfo']['paymentMethod'])){
						$order_main->set_payment_method($payment_gateways['morkva-monopay']);
					}

					if(isset($mrkv_mono_callback['status']))
					{
						$order_main->update_meta_data( 'mrkv_mopay_checkout_status', $mrkv_mono_callback['status']);
		                update_post_meta( $order_main->get_id(), 'mrkv_mopay_checkout_status', $mrkv_mono_callback['status'] );
		                $order_main->save();
					}

					if(isset($mrkv_mono_callback['status']) && $mrkv_mono_callback['status'] == 'success'){
						# Set payment complete
						$order_main->payment_complete($mrkv_mono_callback['paymentInfo']['tranId']);
					}

					# Save order data
					$order_main->save();	

					# Send email
					# Get the WC_Email_New_Order object
					$email_new_order = WC()->mailer()->get_emails()['WC_Email_New_Order'];

					# Sending the new Order email notification for an $order_id (order ID)
					$email_new_order->trigger( $order_main->get_id() );
	        	}
        	}
		}
	}
}