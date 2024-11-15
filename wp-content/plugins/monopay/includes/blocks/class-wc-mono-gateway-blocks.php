<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Mono Gateway Blocks integration
 *
 * @since 1.0.3
 */
final class WC_Gateway_Mono_Blocks_Support extends AbstractPaymentMethodType {

    /**
     * The gateway instance.
     *
     * @var WC_Gateway_Mono
     */
    private $gateway;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'mono_gateway';

    /**
     * Initializes the payment method type.
     */
    public function initialize() {
        $this->settings = get_option('woocommerce_mono_gateway_settings', []);
        $this->gateway = new WC_Gateway_Mono();
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active() {
        return $this->gateway->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $script_path = '/assets/js/frontend/blocks.js';
        $plugin_abs_path = plugin_abspath();
        $script_asset_path = $plugin_abs_path . 'assets/js/frontend/blocks.asset.php';
        $script_asset = file_exists($script_asset_path)
            ? require($script_asset_path)
            : array(
                'dependencies' => array(),
                'version' => '1.2.0'
            );
        $script_url = plugin_url() . $script_path;

        wp_register_script(
            'wc-mono-gateway-blocks',
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations('wc-mono-gateway-blocks', 'plata', $plugin_abs_path . 'languages/');
        }

        return ['wc-mono-gateway-blocks'];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data() {
        $logo_url = plugin_url() . '/assets/images/plata.svg';
        return [
            'title' => __('Pay with card, Apple Pay, Google Pay', 'womono'),
            'description' => $this->get_setting('description'),
            'supports' => array_filter($this->gateway->supports, [$this->gateway, 'supports']),
            'logo_url' => $logo_url,
        ];
    }
}