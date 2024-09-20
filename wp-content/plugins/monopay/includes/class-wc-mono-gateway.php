<?php

use MonoGateway\Api;
use MonoGateway\Order;


if (!class_exists('WC_Payment_Gateway')) {
    return;
}

const ORDER_STATUS_COMPLETED = 'completed';
const ORDER_STATUS_ON_HOLD = 'on-hold';
const ORDER_STATUS_REFUNDED = 'refunded';
const ORDER_STATUS_FAILED = 'failed';
const ORDER_STATUS_PROCESSING = 'processing';
const ORDER_STATUS_PENDING = 'pending';
const CURRENCY_UAH = 980;
const REFRESH_REQUEST_INTERVAL = 5;

class WC_Gateway_Mono extends WC_Payment_Gateway {
    private $token;
    private $mono_api;
    private $use_holds;
    private $destination;
    private $settings_file_path = 'plata_settings.json';
    private $redirect;

    const CURRENCY_CODE = [
        'UAH' => CURRENCY_UAH,
        'EUR' => 978,
        'USD' => 840,
    ];

    public function __construct() {
        loadMonoLibrary();
        $this->id = 'mono_gateway';
        $this->icon = '';

        $this->has_fields = false;
        $this->method_title = 'plata';
        $this->method_description = __('Accept card payments on your website via monobank payment gateway.', 'womono');

        $this->supports = ['products', 'refunds'];

        $this->init_form_fields();
        $this->init_settings();


        $logo_path = plugin_url() . '/assets/images/plata.svg';
        $this->title = __('Pay with card, Apple Pay, Google Pay', 'womono') . ' <img src="' . $logo_path . '" alt="plata"/>';

        $this->description = $this->get_option('description');
        $this->token = $this->get_option('API_KEY');
        $this->mono_api = new Api($this->token);

        $this->use_holds = $this->get_option('use_holds') == 'yes';
        $this->destination = $this->get_option('destination');
        $this->redirect = $this->get_option('redirect');

        $this->update_option('title', $this->title);
        $this->update_option('supports', $this->supports);

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_api_mono_gateway', [$this, 'webhook']);
        add_action('woocommerce_admin_order_totals_after_total', [$this, 'additional_totals_info']);

        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('woocommerce_api_mono_finalize_hold', [$this, 'admin_finalize_hold']);
        add_action('woocommerce_api_mono_cancel_hold', [$this, 'admin_cancel_hold']);
        add_action('woocommerce_api_mono_refresh', [$this, 'admin_refresh_invoice_status']);
        add_action('woocommerce_thankyou', [$this, 'post_payment_request']);
    }

    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [
                'title' => __('Enable/Disable', 'womono'),
                'type' => 'checkbox',
                'label' => __('Enable plata by mono', 'womono'),
                'default' => 'yes'
            ],
            'description' => [
                'title' => __('Description', 'womono'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('This controls the description which user sees during checkout.', 'womono'),
            ],
            'API_KEY' => [
                'title' => __('Api token', 'womono'),
                'type' => 'text',
                'description' => __('You can get your X-Token by the link: ', 'womono') . '<a href="https://web.monobank.ua/" target="blank">web.monobank.ua</a>',
                'default' => '',
            ],
            'use_holds' => [
                'title' => __('Enable holds', 'womono'),
                'type' => 'checkbox',
                'default' => 'false',
            ],
            'destination' => [
                'title' => __('Destination', 'womono'),
                'type' => 'text',
                'description' => __('Призначення платежу', 'womono'),
                'default' => '',
            ],
            'redirect' => [
                'title' => __('Redirect URL', 'womono'),
                'type' => 'text',
                'description' => __('You can do this by configuring a setting called the WordPress Address (URL) in Settings -> General.', 'womono'),
                'default' => '',
            ],
        ];
    }

    public function process_payment($order_id) {
        $order = new WC_Order($order_id);
        $basket_info = $this->get_basket_info();

        $shipping_price = $order->get_shipping_total();
        if ($shipping_price > 0) {
            $basket_info[] = [
                "name" => __('Shipping', 'womono') . ' ' . $order->get_shipping_method(),
                "qty" => 1,
                "sum" => (int)($shipping_price * 100 + 0.5),
                "icon" => '',
                "code" => 'shipping',
            ];
        }

        $mono_order = new Order();
        $mono_order->set_id($order_id);
        $mono_order->set_reference($order_id);
        $mono_order->set_destination($this->destination);
        $mono_order->set_amount((int)($order->get_total() * 100 + 0.5));
        $mono_order->set_basket_order($basket_info);

        $customer_email = $order->get_billing_email();
        if (!empty($customer_email)) {
            $mono_order->set_customer_emails(array($customer_email));
        }

        if (!empty($this->redirect)) {
            $mono_order->set_redirect_url(home_url() . $this->redirect);
        } else {
            $mono_order->set_redirect_url($order->get_checkout_order_received_url());
        }

        $mono_order->set_webhook_url(home_url() . '/?wc-api=mono_gateway');

        $this->mono_api->setOrder($mono_order);
        $payment_type = $this->use_holds ? 'hold' : 'debit';

        $currency_code = get_woocommerce_currency();
        $ccy = key_exists($currency_code, self::CURRENCY_CODE) ? self::CURRENCY_CODE[$currency_code] : CURRENCY_UAH;
        $this->update_meta($order_id, '_payment_type', $payment_type);
        $this->update_meta($order_id, '_ccy', $ccy);
        try {
            $invoice = $this->mono_api->create($payment_type, $ccy);
            if (!empty($invoice)) {
                $order->set_transaction_id($invoice['invoiceId']);
                $order->save();
            } else {
                throw new \Exception("Bad request");
            }
        } catch (\Exception $e) {
            wc_add_notice('Request error (' . $e->getMessage() . ')', 'error');
            return false;
        }
        return [
            'result' => 'success',
            'redirect' => $invoice['pageUrl'],
        ];
    }

    public function process_refund($order_id, $amount = null, $reason = '') {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        if (!$this->can_refund_order($order)) {
            return new WP_Error('error', __('Refund failed.', 'womono'));
        }

        try {
            $invoice_id = $order->get_transaction_id();
            $result = $this->mono_api->cancel([
                "invoiceId" => $invoice_id,
                "extRef" => (string)$order_id,
                "amount" => (int)($amount * 100 + 0.5),
            ]);

            if (is_wp_error($result)) {
                return new WP_Error('error', $result->get_error_message());
            }

            switch ($result['status']) {
                case 'processing':
                    wc_add_notice(__('Refund is in progress', 'womono'), 'notice');
                    return false;
                case 'success':
                    return true;
                case 'failure':
                    $order->add_order_note(
                        sprintf(__('Failed to refund %1$s', 'womono'), $amount)
                    );
                default:
                    return false;
            }
        } catch (\Exception $e) {
            wc_add_notice('Refund error (' . $e->getMessage() . ')', 'error');
            return false;
        }
    }

    public function webhook() {
        $webhook_bytes = file_get_contents('php://input');
        $x_sign = isset($_SERVER['HTTP_X_SIGN']) ? $_SERVER['HTTP_X_SIGN'] : '';
        if (!$this->verify_webhook_signature($webhook_bytes, $x_sign)) {
//            todo: return some kind of error
            return;
        }

        $invoice_webhook_request = json_decode($webhook_bytes, true);
//        ignoring 'created' and 'processing' statuses because they don't have much influence over the situation
        if ($invoice_webhook_request['status'] == 'created' || $invoice_webhook_request['status'] == 'processing') {
            return;
        }
        $order_id = (int)$invoice_webhook_request['reference'];
        $order = wc_get_order($order_id);
        $this->refresh_status($order);
    }

    public function additional_totals_info($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $meta = get_post_meta($order_id, '', true);
        $ccy = $this->get_from_meta($meta, "_ccy");
        if ($ccy == null) {
            $ccy = self::CURRENCY_CODE[get_woocommerce_currency()];
            $this->update_meta($order_id, '_ccy', $ccy);
        }
        if ($ccy == CURRENCY_UAH) {
            return;
        }
        $rate = $this->get_from_meta($meta, "_rate");
        if (!$rate) {
            return;
        }
        $amounts = $this->get_amounts($meta, $order);
        $left_to_refund = sprintf('%.2f', ($amounts["payment_amount"] - $amounts['payment_amount_refunded']) / 100);
        echo <<<END
            <script type="text/javascript">
                function updateRefundButtons() {
                    var refundValue = document.getElementById('refund_amount').value;
                    newValue = "₴0.00";
                    if (refundValue) {
                       var match = refundValue.match(/(\d+(\.\d{1,2})?)/);
                        if (!match) return; 
                        
                        var newValue = "₴" + match[0];
                    }
                    var refundButtons = document.getElementsByClassName('wc-order-refund-amount');
                    
                    for (var i = 0; i < refundButtons.length; i++) {
                        refundButtons[i].textContent = newValue;
                    }
                }
                jQuery(document).ready(function ($) {
                    var amounts = document.querySelectorAll('.wc-order-data-row span.woocommerce-Price-amount.amount');
                    amounts.forEach(function(element) {
                        var amountElement = element.getElementsByTagName("bdi").item(0);
                        if (amountElement) {
                            var match = amountElement.textContent.match(/(\d+(\.\d{1,2})?)/);                          
                            var floatAmount = parseFloat(match[0])
                            if (floatAmount) {                           
                                var amountSmallestUnits = Math.floor(floatAmount*100+0.5)
                                var uahSmallestUnits = Math.floor(amountSmallestUnits*$rate+0.5);
                                amountElement.textContent += " (₴" + (uahSmallestUnits / 100).toFixed(2) + ")";   
                            }
                        }
                    });
                    var refundButtons = document.getElementsByClassName('wc-order-refund-amount');

                    for (var i = 0; i < refundButtons.length; i++) {
                        var element = refundButtons[i];
                        var match = element.textContent.match(/(\d+(\.\d{1,2})?)/);
                        if (match) {
                            element.textContent = "₴" + match[0];
                        }
                    }
                   
                    var refundBox = document.getElementById('refund_amount');
                    if (refundBox) {       
                        refundBox.setAttribute('placeholder', '$left_to_refund');
                        refundBox.setAttribute('onInput', 'updateRefundButtons();');
                        refundBox.setAttribute('onChange', 'updateRefundButtons();');
                    }
                });
            </script>
END;
    }

    public function admin_refresh_invoice_status() {
        $ok = $this->validate_nonces('plata_refresh_nonce');
        if (!$ok) {
            return;
        }
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if (!$order_id || !current_user_can('manage_woocommerce')) {
            wp_send_json_error('Invalid request', 400);
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_send_json_error('Order not found', 404);
            return;
        }


        // Define a unique transient key for this order.
        $transient_key = 'refresh_order_' . $order_id;

        // this function might get called multiple times so we escape excessive fincalization or cancellation attempts
        // Check if this function has already been called for this order.
        if (get_transient($transient_key)) {
            // If yes, return early.
            return;
        }

        $this->refresh_status($order);
        set_transient($transient_key, true, REFRESH_REQUEST_INTERVAL);

        wp_send_json_success('Status refreshed successfully');
    }

    function refresh_status($order) {
        if (!$order) {
            return;
        }
        $invoice_id = $order->get_transaction_id();
        if (!$invoice_id) {
            return;
        }
        $status_response = $this->mono_api->getStatus($invoice_id);
        $order_status = $order->get_status();
        echo $order_status;
        if ( isset($status_response['status']) && ( $status_response['status'] == 'created' || $status_response['status'] == 'processing' ) ) {
            return;
        }
        $invoice_amount = $status_response['amount'] ?? null;
        $invoice_final_amount = (key_exists('finalAmount', $status_response)) ? $status_response['finalAmount'] : 0;
        $order_id = $order->get_id();

        switch ($status_response['status']) {
            case 'success':
                if ($order_status != ORDER_STATUS_COMPLETED) {
                    $order->payment_complete($invoice_id);
                    $order->update_status(ORDER_STATUS_COMPLETED);

                    if ($invoice_final_amount != $invoice_amount) {
                        $order->add_order_note(
                            sprintf(__('Hold finalization amount %1$s UAH', 'womono'), sprintf('%.2f', $invoice_final_amount / 100))
                        );
                    }
                    $this->update_meta($order_id, '_payment_amount', $invoice_final_amount);
                    $this->update_meta($order_id, '_payment_amount_refunded', 0);
                    $this->update_meta($order_id, '_payment_amount_final', $invoice_final_amount);
                    $ccy = get_post_meta($order_id, '_ccy', true);
                    if ($ccy && $ccy != CURRENCY_UAH) {
                        $this->update_meta($order_id, '_rate', $invoice_final_amount / (int)($order->get_total() * 100 + 0.5));
                    }
                    global $woocommerce;
                    if ($woocommerce->cart && !$woocommerce->cart->is_empty()) {
                        $woocommerce->cart->empty_cart();
                    }
                }
                break;
            case 'hold':
                if ($order_status != ORDER_STATUS_ON_HOLD) {

                    $order->update_status(ORDER_STATUS_ON_HOLD);

                    $this->update_meta($order_id, '_payment_amount', $invoice_amount);
                    $ccy = get_post_meta($order_id, '_ccy', true);
                    if ($ccy && $ccy != CURRENCY_UAH) {
                        $this->update_meta($order_id, '_rate', $invoice_amount / (int)($order->get_total() * 100 + 0.5));
                    }
                    global $woocommerce;
                    if ($woocommerce->cart && !$woocommerce->cart->is_empty()) {
                        $woocommerce->cart->empty_cart();
                    }
                }
                break;
            case 'reversed':
                if ($invoice_final_amount == 0) {
                    if ($order_status != ORDER_STATUS_REFUNDED) {
                        $order->update_status(ORDER_STATUS_REFUNDED);
                    }
                } else {
                    $payment_amount_uah = get_post_meta($order->get_id(), '_payment_amount', true);
                    if (!$payment_amount_uah) {
                        $payment_amount_uah = 0;
                    }
                    $old_payment_amount_final_uah = get_post_meta($order->get_id(), '_payment_amount_final', true);
                    if (!$old_payment_amount_final_uah) {
                        $old_payment_amount_final_uah = 0;
                    }
                    $this->update_meta($order_id, '_payment_amount_refunded', $payment_amount_uah - $invoice_final_amount);
                    $this->update_meta($order_id, '_payment_amount_final', $invoice_final_amount);
                    $refunded_amount = (int)($old_payment_amount_final_uah) - $invoice_final_amount;
                    if ($refunded_amount != 0) {
                        $order->add_order_note(
                            sprintf(__('Refunded %1$s UAH', 'womono'), sprintf('%.2f', ($refunded_amount / 100)))
                        );
                    }
                }
                break;
            case 'expired':
//               kind of a hack
                $status_response['failureReason'] = __('Invoice expired', 'womono');
            case 'failure':
                if ($order_status == ORDER_STATUS_PROCESSING || $order_status == ORDER_STATUS_PENDING) {
                    $order->update_status(ORDER_STATUS_FAILED);
                    if (key_exists('failureReason', $status_response)) {
                        $order->add_order_note(
                            sprintf(__('Payment failed, reason — %1$s', 'womono'), $status_response['failureReason'])
                        );
                    }
                }
                break;
            default:
                $order->add_order_note(
                    sprintf(__('Internal error! Got unexpected status — %1$s', 'womono'), $status_response['status'])
                );
        }
    }

    function add_meta_boxes() {
        if (isset($_GET['post'])) {
            $order_id = intval($_GET['post']);
        } else if (isset($_GET['id'])) {
            $order_id = intval($_GET['id']);
        } else {
            return;
        }
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        add_meta_box(
            'custom_refresh_payment_status',
            __('Plata payment status refresh', 'womono'),
            [$this, 'add_refresh_invoice_status_button'],
            '',
            'side',
            'high'
        );
        $order_status = $order->get_status();

        if ($order_status != ORDER_STATUS_ON_HOLD) {
//            we can finalize or cancel invoice only if it's paid
            return;
        }
        $meta = get_post_meta($order_id, '', true);
        $payment_type = $this->get_from_meta($meta, '_payment_type');
        if ($payment_type != 'hold') {
            return;
        }
        $amounts = $this->get_amounts($meta, $order);
        $payment_amount_final = $amounts['payment_amount_final'];
        $payment_amount_refunded = $amounts['payment_amount_refunded'];
        if ($payment_amount_final != 0 || $payment_amount_refunded != 0) {
//            invoice was finalized and maybe refunded
            return;
        }
        $order_status = $order->get_status();
        if ($order_status == 'refunded') {
            return;
        }
        add_meta_box(
            'custom_finalize_hold_amount',
            __('Hold Settings', 'womono'),
            [$this, 'add_hold_functionality_buttons'],
            '',
            'side',
            'high'
        );
    }

    function add_refresh_invoice_status_button($post) {
        $refresh_text = __('Request payment status update', 'womono');
        $order = wc_get_order($post->ID);
        if (!$order) {
            return;
        }
        $url = home_url() . '/?wc-api=mono_refresh';

        // Nonce for security
        $ajax_nonce = wp_create_nonce('plata_refresh_nonce');
        $nonce = sha1($ajax_nonce . $this->token);
        echo <<<END
        <a class="button button-primary" onclick="jQuery.ajax({
                 url: '$url',
                type: 'POST',
                data: {
                    'order_id': $post->ID,
                    'nonce': '$ajax_nonce',
                    'sec_nonce': '$nonce',
                },
                success: function(response) {
                    window.location.reload();
                },              
            })">$refresh_text</a>
END;
    }

    function add_hold_functionality_buttons($post) {
        $order = wc_get_order($post->ID);
        if (!$order) {
            return;
        }

        $order_status = $order->get_status();
        if ($order_status != ORDER_STATUS_COMPLETED && $order_status != ORDER_STATUS_ON_HOLD) {
//            we can finalize or cancel invoice only if it's paid
            return;
        }
        $meta = get_post_meta($post->ID, '', true);
        $amounts = $this->get_amounts($meta, $order);

        $finalize_text = __('Finalize', 'womono');
        $cancel_hold_text = __('Cancel hold', 'womono');
        $enter_amount_text = __('Enter amount', 'womono');
        $cancel_text = __('Cancel', 'womono');
        $payment_amount = sprintf('%.2f', $amounts['payment_amount'] / 100);


        $finalize_hold_url = home_url() . '/?wc-api=mono_finalize_hold';
        $cancel_hold_url = home_url() . '/?wc-api=mono_cancel_hold';

        // Nonce for security
        $finalize_hold_nonce = wp_create_nonce('plata_finalize_hold_nonce');
        $finalize_sec_nonce = $this->create_sec_nonce($finalize_hold_nonce);
        $cancel_hold_nonce = wp_create_nonce('plata_cancel_hold_nonce');
        $cancel_hold_sec_nonce = $this->create_sec_nonce($cancel_hold_nonce);

        echo <<<END
            <div id="hold_span_actions" class="text-left">
                <a class="button button-primary"
                   href="javascript:void(0);"
                   onclick="document.getElementById('hold_span_actions').style.display='none';document.getElementById('hold_form_container').style.display='block';">
                    $finalize_text
                </a>
                
                    <a class="button button-danger" onclick="if (confirm('$cancel_hold_text')) {
                        jQuery.ajax({
                            url: '$cancel_hold_url',
                            type: 'POST',
                            data: {
                                'order_id': $post->ID,
                                'nonce': '$cancel_hold_nonce',
                                'sec_nonce': '$cancel_hold_sec_nonce',
                            },
                            success: function (response) {
                                window.location.reload();
                            },
                        })
                    }">$cancel_hold_text</a>
            </div>
            <div id="hold_form_container" style="display: none;">
                <label for="mono_amount" class="label-on-top">
                    $enter_amount_text
                </label>
                <div class="col-sm">
                    <div class="input-group">
                        <input type="text" id="mono_amount" name="finalization_amount" required="required"
                               value="$payment_amount"/>
                    </div>
                </div>
                <br/>
                <div class="text-left">
                    <button
                            type="button"
                            class="button button-secondary"
                            onclick="document.getElementById('hold_span_actions').style.display='block';document.getElementById('hold_form_container').style.display='none';">
                        $cancel_text
                    </button>                                      
                
                    <a class="button button-primary" onclick="if (confirm('$finalize_text')) {
                        jQuery.ajax({
                            url: '$finalize_hold_url',
                            type: 'POST',
                            data: {
                                'order_id': $post->ID,
                                'nonce': '$finalize_hold_nonce',
                                'sec_nonce': '$finalize_sec_nonce',
                                'finalization_amount': document.getElementById('mono_amount').value,
                            },
                            success: function (response) {
                                window.location.reload();
                            },
                        })
                    }">$finalize_text</a>
                </div>
            </div>
END;
    }

    function admin_finalize_hold() {
        $ok = $this->validate_nonces('plata_finalize_hold_nonce');
        if (!$ok) {
            return;
        }
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if (!$order_id || !current_user_can('manage_woocommerce')) {
            wp_send_json_error('Invalid request', 400);
            return;
        }
        if (!$order_id) {
            return;
        }
        // Define a unique transient key for this order.
        $transient_key = 'finalize_or_cancel_hold_' . $order_id;

        // this function might get called multiple times so we escape excessive finalization or cancellation attempts
        // Check if this function has already been called for this order.
        if (get_transient($transient_key)) {
            // If yes, return early.
            return;
        }
        set_transient($transient_key, true, 180);

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $order_status = $order->get_status();
        if ($order_status != ORDER_STATUS_ON_HOLD) {
            return;
        }
        $invoice_id = $order->get_transaction_id();
        if (!$invoice_id) {
            return;
        }

        $finalization_amount = floatval($_POST['finalization_amount']);
        try {
            $result = $this->mono_api->finalizeHold([
                "invoiceId" => $invoice_id,
                "amount" => (int)($finalization_amount * 100 + 0.5),
            ]);

            if (is_wp_error($result)) {
                return new WP_Error('error', $result->get_error_message());
            }
            if (key_exists('errText', $result)) {
                $order->add_order_note(__('Failed to finalize invoice: ', 'womono') . $result['errText']);
            }
        } catch (\Exception $e) {
            $order->add_order_note(__('Hold cancellation error: ', 'womono') . $e->getMessage());
            return;
        }
    }

    function admin_cancel_hold() {
        $ok = $this->validate_nonces('plata_cancel_hold_nonce');
        if (!$ok) {
            return;
        }
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if (!$order_id || !current_user_can('manage_woocommerce')) {
            wp_send_json_error('Invalid request', 400);
            return;
        }
        if (!$order_id) {
            return;
        }
        // Define a unique transient key for this order.
        $transient_key = 'finalize_or_cancel_hold_' . $order_id;

        // this function might get called multiple times so we escape excessive fincalization or cancellation attempts
        // Check if this function has already been called for this order.
        if (get_transient($transient_key)) {
            // If yes, return early.
            return;
        }
        set_transient($transient_key, true, 180);

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $order_status = $order->get_status();
        if ($order_status != ORDER_STATUS_ON_HOLD) {
            return;
        }
        $invoice_id = $order->get_transaction_id();
        if (!$invoice_id) {
            return;
        }
        try {
            $result = $this->mono_api->cancel([
                "invoiceId" => $invoice_id,
                "extRef" => (string)$order_id,
            ]);

            if (is_wp_error($result)) {
                return new WP_Error('error', $result->get_error_message());
            }
            if (key_exists('errText', $result)) {
                $order->add_order_note(__('Hold cancellation error: ', 'womono') . $result['errText']);
            }
        } catch (\Exception $e) {
            $order->add_order_note(__('Hold cancellation error: ', 'womono') . $e->getMessage());
            return;
        }
    }

    public function can_refund_order($order) {
        $has_api_creds = $this->get_option('API_KEY');
        return $order && $order->get_transaction_id() && $has_api_creds;
    }

    function verify_webhook_signature($data, $x_sign_b64) {
        $pubKeyBase64 = $this->get_pubkey();
        $signature = base64_decode($x_sign_b64);
        $publicKey = openssl_get_publickey(base64_decode($pubKeyBase64));

        $result = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return $result === 1;
    }

    public function get_pubkey() {
        $pubkey_data = $this->read_settings_from_file($this->settings_file_path);
        if (isset($pubkey_data['key'])) {
            return $pubkey_data['key'];
        }
        $response_decoded = $this->mono_api->getPubkey();

        $this->write_settings_to_file($this->settings_file_path, $response_decoded);
        return $response_decoded['key'];
    }

    function read_settings_from_file($file_path) {
        $settings = [];

        // Check if the file exists
        if (file_exists($file_path)) {
            // Read the file contents
            $file_contents = file_get_contents($file_path);

            // Parse the contents into an associative array (assuming JSON format)
            $settings = json_decode($file_contents, true);
        }

        return $settings;
    }

    function write_settings_to_file($file_path, $settings) {
        // Convert the settings array to a JSON string
        $file_contents = json_encode($settings, JSON_PRETTY_PRINT);

        // Write the contents to the file
        file_put_contents($file_path, $file_contents);
    }

    function get_from_meta($meta, $key) {
        foreach ($meta as $item_key => $item_value) {
            if ($item_key == $key && !empty($item_value)) {
                return $item_value[0];
            }
        }
        return null;
    }

    function get_amounts($meta, $order) {
        $payment_amount = $this->get_from_meta($meta, "_payment_amount");
        $payment_amount_refunded = $this->get_from_meta($meta, "_payment_amount_refunded");
        $payment_amount_final = $this->get_from_meta($meta, "_payment_amount_final");
        if ($payment_amount !== null) {
            return [
                'payment_amount' => $payment_amount,
                'payment_amount_refunded' => $payment_amount_refunded,
                'payment_amount_final' => $payment_amount_final
            ];
        }
        $invoice_status = $this->mono_api->getStatus($order->get_transaction_id());
        $order_id = $order->get_id();
        switch ($invoice_status['status']) {
            case 'success':
                $payment_amount = $invoice_status['finalAmount'];
                $payment_amount_refunded = 0;
                $payment_amount_final = $invoice_status['finalAmount'];
                break;
            case 'hold':
                $payment_amount = $invoice_status['amount'];
                $payment_amount_refunded = 0;
                $payment_amount_final = 0;
                $this->update_meta($order_id, '_payment_type', 'hold');
                break;
            case 'reversed':
                $amount_refunded = 0;
                foreach ($invoice_status['cancelList'] as $cancel_item) {
                    if ($cancel_item['status'] == 'success') {
                        $amount_refunded += $cancel_item['amount'];
                    }
                }
                $payment_amount = $invoice_status['finalAmount'] + $amount_refunded;
                $payment_amount_refunded = $amount_refunded;
                $payment_amount_final = $invoice_status['finalAmount'];
                break;
            default:
                return [];
        }
        $this->update_meta($order_id, '_payment_amount', $payment_amount);
        $this->update_meta($order_id, '_payment_amount_refunded', $payment_amount_refunded);
        $this->update_meta($order_id, '_payment_amount_final', $payment_amount_final);

        return [
            'payment_amount' => $payment_amount,
            'payment_amount_refunded' => $payment_amount_refunded,
            'payment_amount_final' => $payment_amount_final
        ];
    }

    function post_payment_request($order_id) {
        if (!$order_id) {
            return;
        }
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $order_status = $order->get_status();
        if ($order_status == ORDER_STATUS_PROCESSING || $order_status == ORDER_STATUS_PENDING) {
            $this->refresh_status($order);
        }

        $order_status = $order->get_status();
        if ($order_status == ORDER_STATUS_FAILED) {
            wc_add_notice(__('Payment failed', 'womono'), 'error');
            wp_redirect(wc_get_checkout_url());
            exit();
        }
    }

// moved it to separate function because in new woocommerce versions meta is taken from wp_wc_orders_meta
// whereas in older versions it's taken from wp_postmeta
// so there was an idea to update both tables, but for now just changed meta retrieval directly from wp_postmeta
    function update_meta($order_id, $key, $value) {
        update_post_meta($order_id, $key, $value);
    }

    function create_sec_nonce($ajax_nonce) {
        return sha1($ajax_nonce . $this->token);
    }

    function validate_nonces($action) {
        if (!isset($_POST['nonce']) || !isset($_POST['sec_nonce'])) {
            wp_send_json_error('Invalid request', 400);
            return false;
        }
        check_ajax_referer($action, 'nonce');
        $expected_nonce = $this->create_sec_nonce($_POST['nonce']);
        if ($expected_nonce != $_POST['sec_nonce']) {
            wp_send_json_error('Invalid request', 400);
            return false;
        }
        return true;
    }

    function get_basket_info() {
        if (!WC()->session) {
            WC()->initialize_session();
        }

        $basket_info = [];
        if (!isset(WC()->cart)) {
            return $basket_info;
        }

        $cart_info = WC()->cart->get_cart();
        foreach ($cart_info as $cart_item) {
            // Get the product object
            $product = $cart_item['data'];

            // Get product image
            $image_elem = $product->get_image();
            $image = [];
            preg_match_all('/src="(.+)" class/', $image_elem, $image);


            // Get SKU or use product ID
            $sku = (string)$product->get_sku();
            $code = empty($sku) ? (string)$product->get_id() : $sku;

//            line_total does not reflect the price of the single product which we need in sum parameter
            $qty = (float)$cart_item['quantity'];
            $line_total = (int)((float)$cart_item['line_total'] * 100 + 0.5);
            $sum = (int)((float)$product->get_price() * 100 + 0.5);
            if ($qty * $sum != $line_total) {
                $sum = $line_total;
                $qty = 1;
            }

            $basket_info[] = [
                "name" => $product->get_name(),
                "qty" => $qty,
                "sum" => $sum,
                "icon" => $image[1][0],
                "code" => $code,
            ];
        }

        return $basket_info;
    }
}
