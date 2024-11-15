<?php


/**
 * Plugin Name: plata by mono
 * Plugin URI: https://wordpress.org/plugins/monopay/#description
 * Description: plata by mono WooCommerce plugin enables you to easily accept payments through your Woocommerce store. <a href="https://www.monobank.ua/">https://www.monobank.ua/</a>
 * Requires at least: 6.2
 * Tested up to: 6.5.4
 * Version: 3.1.2
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 */


define('MONOGATEWAY_DIR', plugin_dir_path(__FILE__));

define('MONOGATEWAY_PATH', plugin_dir_url(__FILE__));

// Activation Hook
register_activation_hook(__FILE__, 'check_woocommerce_installed');

add_action('plugins_loaded', 'init_mono_gateway_class', 11);

add_action('plugins_loaded', 'true_load_plugin_textdomain', 11);

add_filter('woocommerce_payment_gateways', 'add_mono_gateway_class');

add_action('woocommerce_blocks_loaded', 'add_mono_gateway_block');


// Function to check if WooCommerce is installed
function check_woocommerce_installed() {
    if (!class_exists('WooCommerce')) {
        wp_die(__('<a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> is not installed. Please install <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> before activating this plugin', 'womono'));
    }
}


function true_load_plugin_textdomain() {
    $plugin_path = dirname(plugin_basename(__FILE__)) . '/languages/';
    load_plugin_textdomain('womono', false, $plugin_path);
}


function init_mono_gateway_class() {
    require_once MONOGATEWAY_DIR . 'includes/class-wc-mono-gateway.php';
}


function add_mono_gateway_class($methods) {
    $currency_code = get_woocommerce_currency();
    if ($currency_code == 'UAH') {
        $methods[] = 'WC_Gateway_Mono';
    }

    if ($currency_code == 'USD') {
        $methods[] = 'WC_Gateway_Mono';
    }

    if ($currency_code == 'EUR') {
        $methods[] = 'WC_Gateway_Mono';
    }

    return $methods;

}

function add_mono_gateway_block() {
    if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
        require_once MONOGATEWAY_DIR . 'includes/blocks/class-wc-mono-gateway-blocks.php';
        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
                $payment_method_registry->register(new WC_Gateway_Mono_Blocks_Support());
            }
        );
    }
}

function loadMonoLibrary() {
    require_once MONOGATEWAY_DIR . 'includes/classes/Api.php';
    require_once MONOGATEWAY_DIR . 'includes/classes/Order.php';
}

function plugin_abspath() {
    return trailingslashit(plugin_dir_path(__FILE__));
}

function plugin_url() {
    return untrailingslashit(plugins_url('/', __FILE__));
}

function plata_enqueue_styles() {
    if (is_checkout()) {
        wp_enqueue_style('mono-gateway-styles', MONOGATEWAY_PATH . 'assets/css/custom.css', array(), '1.0.0', 'all');
    }
}

// Set a lower priority to ensure it loads after other styles
add_action('wp_enqueue_scripts', 'plata_enqueue_styles', 999);
