<?php

class Woocommerce_actions {
    public function __construct() {
    }

    public function init() {
        add_action('wp_ajax_create_order', array($this, 'create_order_action'));
        add_action('wp_ajax_nopriv_create_order', array($this, 'create_order_action'));
    }

    public function create_order_action() {
        $product_id = filter_input(INPUT_POST, 'post-id', FILTER_VALIDATE_INT);
        $user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $user_full_name = filter_input(INPUT_POST, 'user_full_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$product_id || !$user_email || !$user_full_name || !$user_phone) {
            wp_send_json_error('Wrong or emty data');
            return;
        }

        $name_parts = explode(' ', $user_full_name, 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

        // Create order
        $order = wc_create_order();
        $order->add_product(wc_get_product($product_id), 1);
        $order->update_status('pending');
        $order->calculate_totals();

        // // Add order inforation
        // $order->set_billing_first_name($first_name);

        // if ($last_name) {
        //     $order->set_billing_last_name($last_name);
        // }

        // $order->set_billing_email($user_email);
        // $order->set_billing_phone($user_phone);

        // $user = get_user_by('email', $user_email); // Получаем пользователя по email
        // if ($user) {
        //     $order->set_customer_id($user->ID); // Устанавливаем ID пользователя для заказа
        // }

        $order->save();


        $payment_url = $order->get_checkout_payment_url();

        wp_send_json_success(array('order_data' => $payment_url));
    }
}
