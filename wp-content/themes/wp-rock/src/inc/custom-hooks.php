<?php

/**
 * Custom hooks
 *
 * @package WP-rock/custom-hooks
 */

/**
 * Remove windows LSEP from content
 *
 * @param { string } $html - Text content.
 *
 * @return array|string|string[]
 */
function remove_lsep($html): array|string {
    $pattern = '/\x{2028}/u';

    return preg_replace($pattern, '', $html);
}


/**
 * Remove windows LSEP from content
 *
 * @param {string} $content - Text content.
 * @return string|string[]
 */
function remove_windows_lsep_from_content($content): array|string {
    return str_replace("\r\n", '', $content);
}
add_filter('the_content', 'remove_windows_lsep_from_content');



/**
 * Change display type for language switcher in Frontend
 */
add_filter(
    'pll_the_languages_args',
    function ($args) {
        $args['display_names_as'] = 'slug';
        return $args;
    }
);



/**
 * Remove tag <p> и <br> in plugin contact form.
 */
add_filter('wpcf7_autop_or_not', '__return_false');


/**
 * Output CSS styles for custom tag colors in the WordPress theme.
 * This function retrieves tag colors from theme options and generates CSS variables.
 *
 * @return void
 */
function wp_rock_color_panel(): void {
    global $global_options;

    // Get main colours set from theme options
    $colours_set = get_field_value($global_options, 'colours_set');

    // Initialize an empty array to store CSS variable declarations
    $colours_variable = array();

    // Generate CSS variable declarations for each main tag color
    if (!empty($colours_set)) {
        foreach ($colours_set as $single_color) {
            $color_type = $single_color['name_of_color_type'];
            $color_code = $single_color['color_code'];
            $colours_variable[] = '--color-type-' . $color_type . ':' . $color_code . ';';
        }
    }

    // Output CSS styles with the generated CSS variables
?>
    <style>
        :root {
            <?php echo implode('', $colours_variable); ?>
        }
    </style>
<?php
}
add_action('wp_head', 'wp_rock_color_panel');








// Hande chage order status
add_action('woocommerce_order_status_completed', 'add_program_on_order_complete', 10, 1);

function add_program_on_order_complete($order_id) {
	global $profile_functionality;

	$order = wc_get_order($order_id);

	if (!$order) {
		return;
	}

	$user_id = $order->get_user_id();
	$access_period = get_post_meta($order->get_id(), 'access_period', true);

	foreach ($order->get_items() as $item_id => $item) {
		$product_id = $item['product_id'];

		$product_fields = get_fields($product_id);
		$programm_id = get_field_value($product_fields, 'attached_post');

		if ($programm_id) {
			$profile_functionality->add_update_user_programm($programm_id, $user_id, $access_period);
			break;
		}
	}
}


// Filling additionals order fields
$cookie_data = isset($_COOKIE['creating_order']) ? stripslashes($_COOKIE['creating_order']) : '';

add_action('woocommerce_new_order', function($order_id) use ($cookie_data) {
	if ($cookie_data) {
		$cookie_data = json_decode($cookie_data);
		// Get fields form cookie
		$user_email = $cookie_data->userEmail ?? null;
		$user_phone = $cookie_data->userPhone ?? null;
		$user_registration = $cookie_data->userRegistration ?? false;
		$continue_period = $cookie_data->continuePeriod ?? 90;
	}
	else {
		error_log('$cookie_data = nodata');
		$user_email = null;
		$user_phone = null;
		$user_registration = false;
		$continue_period = 90;
	}

	// Add order inforation
	$order = wc_get_order($order_id);

	$order->set_billing_email($user_email);
	$order->set_billing_phone($user_phone);

	$user = get_user_by('email', $user_email);
	if ($user) {
		$order->set_customer_id($user->ID);
	}

	update_post_meta($order_id, 'user_just_registered', $user_registration);
	update_post_meta($order_id, 'access_period', $continue_period);

	$order->save();
}, 90, 1);


// Display fields in woocommercer order
add_action('woocommerce_admin_order_data_after_order_details', 'display_custom_order_fields');

function display_custom_order_fields($order) {
	$user_just_registered = get_post_meta($order->get_id(), 'user_just_registered', true);
	$access_period = get_post_meta($order->get_id(), 'access_period', true);

	echo '<p class="form-field form-field-wide wc-customer-user"><strong>' . __('User Just Registered', 'wp-rock') . ':</strong> ' . ($user_just_registered ? 'Yes' : 'No') . '</p>';
	echo '<p class="form-field form-field-wide wc-customer-user"><strong>' . __('Access Period', 'wp-rock') . ':</strong> ' . esc_html($access_period) . ' days</p>';
}



add_action('template_redirect', 'custom_redirect_after_thankyou');

function custom_redirect_after_thankyou() {
	// Check that this is the "Order Received" order page
	if (is_wc_endpoint_url('order-received')) {
		// Get the order ID from the URL
		$order_id = isset($_GET['key']) ? wc_get_order_id_by_order_key($_GET['key']) : 0;

		if ($order_id) {
			wp_redirect(home_url('/profile-page?order='.$order_id));
			exit;
		}
	}
}


function order_statuses_for_payment_complete($statuses) {
	$statuses[] = 'processing';
    return $statuses;
}
add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', 'order_statuses_for_payment_complete', 30, 1 );