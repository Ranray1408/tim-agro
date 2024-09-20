<?php 
# Check if class exist
if (!class_exists('MorkvaMonopayWidget')) 
{
	/**
	 * Class for add widget checkout
	 */
	class MorkvaMonopayWidget
	{
		/**
	     * @var string Api url
	     * */
	    const MRKV_MONO_API_URL = "https://api.monobank.ua/personal/checkout/order/";

	    /**
	     * @var string Order id message
	     * */
	    const MRKV_MONO_ORDER_ID = "MonoCheckout order id: ";

		/**
		 * Constructor for add buttons
		 * */
		function __construct()
		{
			if(class_exists('WC_Payment_Gateways')){
				# Get enabled data by mono gateway
	    		$wc_gateways      = new WC_Payment_Gateways();
	    		$payment_gateways = $wc_gateways->get_available_payment_gateways();

	    		if(isset($payment_gateways['morkva-monopay']) && $payment_gateways['morkva-monopay']->get_option('enabled_checkout') && $payment_gateways['morkva-monopay']->get_option('enabled_checkout') !== 'no'){
	    			# Set data gateway
	    			$monopay_gateway = $payment_gateways['morkva-monopay'];

					if(WC()->cart){
						$_SESSION['all_cart_data'] = WC()->cart->get_cart();
						$_SESSION['cart_quantity'] = WC()->cart->get_cart_contents_count();
					}

					if($monopay_gateway->get_option('display_mode_product') !== 'no'){
						if($monopay_gateway->get_option('checkout_button_type_black') !== 'no'){
							# Add checkout button to Product page
							add_action( 'woocommerce_after_add_to_cart_button', array($this, 'mrkv_add_mono_button_product_func') );
						}
						else{
							# Add checkout button to Product page
							add_action( 'woocommerce_after_add_to_cart_button', array($this, 'mrkv_add_mono_button_product_func_white') );
						}
					}
					if($monopay_gateway->get_option('display_mode_minicart') !== 'no'){
						if($monopay_gateway->get_option('checkout_button_type_black') !== 'no'){
							# Add checkout button to mini cart
							add_action('woocommerce_widget_shopping_cart_buttons', array($this, 'mrkv_add_mono_button_cart_func'));
						}
						else{
							# Add checkout button to mini cart
							add_action('woocommerce_widget_shopping_cart_buttons', array($this, 'mrkv_add_mono_button_cart_func_white'));
						}
					}
					if($monopay_gateway->get_option('display_mode_cartmain') !== 'no'){
						if($monopay_gateway->get_option('checkout_button_type_black') !== 'no'){
							# Add checkout button to mini cart
							add_action('woocommerce_after_cart_totals', array($this, 'mrkv_add_mono_button_cart_func'));
						}
						else{
							# Add checkout button to mini cart
							add_action('woocommerce_after_cart_totals', array($this, 'mrkv_add_mono_button_cart_func_white'));
						}
					}
					if($monopay_gateway->get_option('display_mode_checkout') !== 'no'){
						if($monopay_gateway->get_option('checkout_button_type_black') !== 'no'){
							# Add checkout button to mini cart
							add_action('woocommerce_review_order_before_submit', array($this, 'mrkv_add_mono_button_cart_func'));
						}
						else{
							# Add checkout button to mini cart
							add_action('woocommerce_review_order_before_submit', array($this, 'mrkv_add_mono_button_cart_func_white'));
						}
					}

					# Add ajax Monopay Checkout Product type
					add_action( 'wp_ajax_mrkv_monopay_product', array($this, 'mrkv_monopay_product_func') ); 
					add_action( 'wp_ajax_nopriv_mrkv_monopay_product', array($this, 'mrkv_monopay_product_func') );
					# Add ajax Monopay Checkout Cart type
					add_action( 'wp_ajax_mrkv_monopay_cart', array($this, 'mrkv_monopay_cart_func') ); 
					add_action( 'wp_ajax_nopriv_mrkv_monopay_cart', array($this, 'mrkv_monopay_cart_func') );
	    		}

	    		# Add all styles and scripts for widget
				add_action('wp_enqueue_scripts', array($this, 'mrkv_mono_register_scripts_styles_func'));
			}
		}

		/**
		 * Add product button
		 * */
		public function mrkv_add_mono_button_product_func(){
			# Add button
			require_once plugin_dir_path(__FILE__) . 'templates/template-monopay-product-page.php';
		}

		/**
		 * Add product button white
		 * */
		public function mrkv_add_mono_button_product_func_white(){
			# Add button
			require_once plugin_dir_path(__FILE__) . 'templates/template-monopay-product-page-white.php';
		}

		/**
		 * Add cart button
		 * */
		public function mrkv_add_mono_button_cart_func(){
			# Add button
			require_once plugin_dir_path(__FILE__) . 'templates/template-monopay-cart.php';
		}

		/**
		 * Add cart button white
		 * */
		public function mrkv_add_mono_button_cart_func_white(){
			# Add button
			require_once plugin_dir_path(__FILE__) . 'templates/template-monopay-cart-white.php';
		}

		/**
		 * Add buttons styles and scripts
		 * */
		public function mrkv_mono_register_scripts_styles_func(){
			# Add styles
			wp_enqueue_style( 'monopay-style', MORKVAMONOGATEWAY_PATH . 'assets/css/monopay-style.css' );
			# Add scripts
			wp_enqueue_script( 'monopay-script', MORKVAMONOGATEWAY_PATH . 'assets/js/monopay-script.js', NULL, NULL, true );
			# Localize script
			wp_localize_script( 'monopay-script', 'monopay_script_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}


		public function get_coupon_discount_amount_by_code($coupon_code) {

			$coupon = new WC_Coupon($coupon_code);

			// Check idf Exist
			if ($coupon->get_id()) {
				$discount_type = $coupon->get_discount_type();
				$discount_amount = $coupon->get_amount();

				return [
					'discount_type'  => $discount_type,
					'discount_amount'=> $discount_amount,
				];
			} else {
				return null;
			}
		}



		/**
		 * Create Monopay checkout by product 
		 * */
		public function mrkv_monopay_product_func(){
			# Check if post data exist
			if(isset($_POST['product'])){
				# Clear json data
				$product_data_json = stripslashes($_POST['product']);
				# Get product data
	    		$product = json_decode($product_data_json);

				if (isset($product->forcePrice)) {
					$forcePrice = $product->forcePrice;
				} else {
					$forcePrice = null;
				}

				$discount_value = 0;
				if (isset($product->discount)) {
					$discount_code = $product->discount;
					$discount_value = $this->get_coupon_discount_amount_by_code($discount_code);
				}

	    		// Get main data
	    		$product_result = array();
	    		$amount = 0;
	    		$currency = get_woocommerce_currency();
	    		$product_for_order;

	    		if($product->variation_id){
	    			$variation = wc_get_product($product->variation_id);

	    			$product_result[] = array(
	    				'id' => $product->variation_id,
	    				'name' => $variation->get_formatted_name(),
	    				'cnt' => $product->quantity,
	    				'price' => $variation->get_price()
	    			);

	    			$amount = $product->quantity * $variation->get_price();
	    			$product_for_order = $variation; 
	    		}
	    		else{
	    			$product_main = wc_get_product($product->product_id);

					$final_price = $forcePrice ?? $product_main->get_price();

					if ( !is_null($discount_value) ) {
						$final_price = $final_price - $discount_value['discount_amount'];
					}

	    			$product_result[] = array(
	    				'id' => $product->product_id,
	    				'name' => $product_main->get_title(),
	    				'cnt' => $product->quantity,
	    				'price' => $final_price
	    			);

	    			$amount = $product->quantity * ($final_price);
	    			$product_for_order = $product_main;
	    		}

				$new_order_args = array(
					'customer_id'   => get_current_user_id(),
				);

	    		# Create order
				$order = wc_create_order($new_order_args);

				$order->add_product( $product_for_order,  $product->quantity);
				$order->calculate_totals();

				# Get params
	    		$params = $this->mrkv_monopay_create_params($product_result, $amount, $product->quantity, $currency, $order);

    			# Get token by mono gateway
	    		$wc_gateways      = new WC_Payment_Gateways();
	    		$payment_gateways = $wc_gateways->get_available_payment_gateways();
	    		$mono_payment_gateway = $payment_gateways['morkva-monopay'];
	    		$monocheckout_debug = $mono_payment_gateway->get_option('monocheckout_debug');

	    		if($monocheckout_debug !== 'no'){
	    			$order->add_order_note(__('Query: ', 'mrkv-monobank-extended') . ' ' . print_r($params, 1) , $is_customer_note = 0, $added_by_user = false);
	    		}

	    		$url = $this->mrkv_monopay_create_checkout($params);

	    		$order->update_meta_data( 'mrkv_mopay_payment_method', 'morkva-monopay-checkout');
                update_post_meta( $order->get_id(), 'mrkv_mopay_payment_method', 'morkva-monopay-checkout' );
                $order->save();

	    		if(isset($url['error'])){
	    			$order->add_order_note(__('Error: ', 'mrkv-monobank-extended') . ' ' . $url['error'] , $is_customer_note = 0, $added_by_user = false);
	    			$order->update_meta_data( 'mrkv_mopay_checkout_status', 'error');
	                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status', 'error' );
	                $order->update_meta_data( 'mrkv_mopay_checkout_status_message', $url['error']);
	                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status_message', $url['error'] );
	    		}

	    		if(isset($url['order_id'])){
	    			$order->add_order_note(__('MonoCheckout order id: ', 'mrkv-monobank-extended') . ' ' . $url['order_id'] , $is_customer_note = 0, $added_by_user = false);
	    			$order->update_meta_data( '_order_mono_ref',  $url['order_id']);

	    			$order->update_meta_data( 'mrkv_mopay_checkout_status', 'sent_to_checkout');
	                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status', 'sent_to_checkout' );
	                $order->save();
	    		}

	    		if(isset($url['redirect_url'])){
					echo $url['redirect_url'];
	    		}

			}

			die;
		}

		/**
		 * Create Monopay checkout by cart 
		 * */
		public function mrkv_monopay_cart_func(){
			$items = '';
			if(isset($_SESSION['all_cart_data'])){
				$items = WC()->cart->get_cart();
			}

    		// Get main data
    		$product_result = array();

    		if(isset($_POST['product'])){
    			# Clear json data
				$product_data_json = stripslashes($_POST['product']);
				# Get product data
	    		$product = json_decode($product_data_json);
    			$amount = $product->amount;

    			$coupon = $product->coupon;
    		}
    		else{
    			$amount = $woocommerce->cart->total;
    			$coupon = '';
    		}

    		$currency = get_woocommerce_currency();
    		if(isset($_SESSION['cart_quantity'])){
    			$quantity_main = $_SESSION['cart_quantity'];
    		}
    		
    		

    		if(empty($items) || !$quantity_main){
				return false;	
			}

    		# Create order
			$order = wc_create_order();

			$product_bundles = '';

    		foreach($items as $cart_item) {
    			$product_id         = $cart_item['product_id'];
    			$variation_id       = $cart_item['variation_id'];
    			$line_total         = $cart_item['line_total'];
    			$quantity           = $cart_item['quantity'];
    			$item_price         = $line_total / $quantity;

    			if($variation_id){
    				$variation = wc_get_product($variation_id);

    				$product_result[] = array(
	    				'id' => $variation_id,
	    				'name' => $variation->get_formatted_name(),
	    				'cnt' => $quantity,
	    				'price' => $item_price
	    			);

	    			$order->add_product( $variation,  $quantity);
    			}
    			else{
    				$product_main = wc_get_product($product_id);

					$product_result[] = array(
	    				'id' => $product_id,
	    				'name' => $product_main->get_title(),
	    				'cnt' => $quantity,
	    				'price' => $item_price
	    			);

	    			$order->add_product( $product_main,  $quantity);
    			}

    			if(isset($cart_item['addons']) && is_array($cart_item['addons']))
    			{
    				$product_bundles = '<b>' . $product_main->get_title() . '</b><br>';

    				foreach($cart_item['addons'] as $addon)
    				{
						$product_bundles .= '<b>' . $addon['name'] . '</b><br> ' . $addon['value'] . '<br>';
    				}
    			}
    		}

    		if($coupon){
				$order->apply_coupon($coupon);
    		}

    		$order->calculate_totals();

    		$amount = $order->get_total();

    		# Get params
    		$params = $this->mrkv_monopay_create_params($product_result, $amount, $quantity_main, $currency, $order);

    		# Text Start script
			$script_start_text = "Script Start: " . print_r($params, 1) . "\r\n";
			# Write text to degug.log file
			file_put_contents( __DIR__ . '/debug.log', $script_start_text, FILE_APPEND );

			# Get token by mono gateway
    		$wc_gateways      = new WC_Payment_Gateways();
    		$payment_gateways = $wc_gateways->get_available_payment_gateways();
    		$mono_payment_gateway = $payment_gateways['morkva-monopay'];
    		$monocheckout_debug = $mono_payment_gateway->get_option('monocheckout_debug');

    		if($monocheckout_debug !== 'no'){
    			$order->add_order_note(__('Query: ', 'mrkv-monobank-extended') . ' ' . print_r($params, 1) , $is_customer_note = 0, $added_by_user = false);
    		}

    		
    		$url = $this->mrkv_monopay_create_checkout($params);

    		$order->update_meta_data( 'mrkv_mopay_payment_method', 'morkva-monopay-checkout');
            update_post_meta( $order->get_id(), 'mrkv_mopay_payment_method', 'morkva-monopay-checkout' );
            $order->save();

            if($product_bundles)
    		{
    			$order->add_order_note($product_bundles , $is_customer_note = 0, $added_by_user = false);
    		}

    		if(isset($url['error'])){
    			$order->add_order_note(__('Error: ', 'mrkv-monobank-extended') . ' ' . $url['error'] , $is_customer_note = 0, $added_by_user = false);
    			$order->update_meta_data( 'mrkv_mopay_checkout_status', 'error');
                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status', 'error' );
                $order->update_meta_data( 'mrkv_mopay_checkout_status_message', $url['error']);
                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status_message', $url['error'] );
                $order->save();
    		}

    		if(isset($url['order_id'])){
    			$order->add_order_note(__('MonoCheckout order id: ', 'mrkv-monobank-extended') . ' ' . $url['order_id'] , $is_customer_note = 0, $added_by_user = false);
    			$order->update_meta_data( '_order_mono_ref',  $url['order_id']);
    			$order->update_meta_data( 'mrkv_mopay_checkout_status', 'sent_to_checkout');
                update_post_meta( $order->get_id(), 'mrkv_mopay_checkout_status', 'sent_to_checkout' );
                $order->save();
    		}

    		if(isset($url['redirect_url'])){
				echo $url['redirect_url'];
    		}

			die;
		}

		/**
		 * Create params for monopay checkout request
		 * @param array Products
		 * @var string Amount
		 * @var string Quantity product
		 * @var string Currency data
		 * @param object New order
		 * 
		 * @return array Params
		 * */
		public function mrkv_monopay_create_params($products, $amount, $count, $currency, $order){

			# Create array of products
			$product_list = array();
			# Set Iso code
        	$iso_code = '';

			# Swtich currency code
	        switch($currency){
	            case 'UAH':
	                $iso_code = "980";
	            break;
	            case 'USD':
	                $iso_code = "840";
	            break;
	            case 'EUR':
	                $iso_code = "978";
	            break;
	            case 'GBP':
	                $iso_code = "826";
	            break;
	            default:
	                $iso_code = "980";
	        }

			foreach($products as $product){
				$product_list[] = array(
					'code_product' => $product['id'],
					'name' => $product['name'],
					'cnt' => $product['cnt'],
					'price' => $product['price']
				);
			}

			$web_url = get_site_url();

			$wc_gateways      = new WC_Payment_Gateways();
    		$payment_gateways = $wc_gateways->get_available_payment_gateways();
    		$mono_payment_gateway = $payment_gateways['morkva-monopay'];

    		$mono_payments = array();

    		if($mono_payment_gateway->get_option('mono_payment_methods'))
    		{
    			$mono_payments = $mono_payment_gateway->get_option('mono_payment_methods');
    		}

			$params = array(
    			'order_ref' => $order->get_id(),
    			'amount' => $amount,
    			'products' => $product_list,
    			'count' => $count,
    			'ccy' => $iso_code,
    			'payment_method_list' => $mono_payments,
    			'callback_url' => $web_url . '/?wc-api=morkva-monopay-checkout',
    			'return_url' => wc_get_checkout_url() . 'order-received/' . $order->get_id() . '/?key=' . $order->get_order_key()
    		);

    		return $params;
		}

		/**
		 * Create checkout url
		 * @param array Params
		 * */
		public function mrkv_monopay_create_checkout($params){
			# Get token by mono gateway
    		$wc_gateways      = new WC_Payment_Gateways();
    		$payment_gateways = $wc_gateways->get_available_payment_gateways();
    		$mono_payment_gateway = $payment_gateways['morkva-monopay'];
    		$mrkv_mono_token = $mono_payment_gateway->get_mrkv_mono_getToken();

    		# Set url
    		$mrkv_mono_url = self::MRKV_MONO_API_URL;

    		# Create request header
	        $mrkv_mono_headers = array(
	            'Content-type'  => 'application/json',
	            'X-Token' => $mrkv_mono_token,
	            'X-Cms' => 'morkva'
	        );

	        # Create request args
	        $mrkv_mono_args = array(
	            'method'      => 'POST',
	            'body'        => json_encode($params),
	            'headers'     => $mrkv_mono_headers,
	            'user-agent'  => 'WooCommerce/' . WC()->version,
	        );

	        # Send request
        	$mrkv_mono_request = wp_safe_remote_post($mrkv_mono_url, $mrkv_mono_args);

	        # Check request status
	        if ($mrkv_mono_request === false) 
	        {
	            # Show error
	            throw new \Exception("Connection error");
	        }

	        if(isset($mrkv_mono_request['body'])){
	        	$body_message = json_decode($mrkv_mono_request['body']);
	        	if(isset($body_message->errText)){
	        		return array(
	        			'error' => $body_message->errText
	        		);
	        	}
	        	if(isset($body_message->result)){
	        		return array(
	        			'redirect_url' => $body_message->result->redirect_url,
	        			'order_id' => $body_message->result->order_id
	        		);
	        	}
	        }

	        # Return answer
	        return $mrkv_mono_request;
		}
	}
}