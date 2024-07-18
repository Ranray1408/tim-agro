<?php

/**
 * Initial setup actions for site
 *
 * @package WP-rock
 */

/*Collect all ACF option fields to global variable. */
global $global_options;

if (function_exists('get_fields')) {
    if (function_exists('pll_current_language')) {
        // @codingStandardsIgnoreStart
        $locale         = get_locale();
        // @codingStandardsIgnoreEnd
        $global_options = get_fields('theme-general-settings_' . $locale);
    } else {
        $global_options = get_fields('theme-general-settings');
    }
}


/**
 * Main theme's class init
 */
$wp_rock = new WP_Rock();
add_action('after_setup_theme', array($wp_rock, 'px_site_setup'));

/**
 * Sanitize uploaded file name
 */
add_filter('sanitize_file_name', array($wp_rock, 'custom_sanitize_file_name'), 10, 1);


function increase_upload_size_limit($size) {
    return 30 * 1024 * 1024; // 25 MB
}
add_filter('upload_size_limit', 'increase_upload_size_limit');
add_filter('post_max_size', 'increase_upload_size_limit');


/**
 * Check field and return its value or return null.
 *
 * @param {array}  $data_arr - Array to check and return data.
 * @param {string} $key      - key that should be found in array.
 *
 * @return mixed|null
 */
function get_field_value($data_arr, $key) {
    return (isset($data_arr[$key])) ? $data_arr[$key] : null;
}

global $profile_functionality;
$profile_functionality = new profile_functionality();
$profile_functionality->init();

// Vimeo SDK
require get_template_directory() . '/vendor/autoload.php';

use Vimeo\Vimeo;

global $global_options;
$client_id = get_field_value($global_options, 'client_id');
$client_secret = get_field_value($global_options, 'client_secret');
$client_token = get_field_value($global_options, 'client_token');

global $client;
if (!empty($client_id) && !empty($client_secret) && !empty($client_token) && isHostReachable()) {
    // @intelephense-disable-line
    $client = new Vimeo($client_id, $client_secret, $client_token);
}

function isHostReachable($host = 'api.vimeo.com', $port = 443) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        fclose($connection);
        return true;
    } else {
        return false;
    }
}

function log_data($data) {
    $log_file = WP_CONTENT_DIR . '/error_logs.log';
    $log_entry = date('[Y-m-d H:i:s]') . ' ' . json_encode($data) . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

$monobank_token = get_field_value($global_options, 'monobank_token');
// Monobank payment
$monobank = new MonobankPayment($monobank_token);

global $monobank;

$monobank->init($monobank_token);
