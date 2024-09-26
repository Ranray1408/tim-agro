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

// Monobank payment
// global $monobank;
global $global_options;
global $client;
global $woocommerce_actions;

//$monobank_token = get_field_value($global_options, 'monobank_token');
//$monobank = new MonobankPayment($monobank_token, $global_options);
//$monobank->init();


global $profile_functionality;
$profile_functionality = new Profile_Functionality($global_options, $client);
$profile_functionality->init();

$woocommerce_actions = new Woocommerce_actions();
$woocommerce_actions->init();

// Vimeo SDK
require get_template_directory() . '/vendor/autoload.php';

use Vimeo\Vimeo;


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

// Loading data from vimeo by saving post
function save_vimeo_data($post_id) {
    global $profile_functionality;
    global $global_options;
    global $client;

    $included_cpts = array('courses', 'lectures');
    $vimeo_blocks_folder = get_field('vimeo_blocks_folder', $post_id);
    $user_id = get_field_value($global_options, 'user_id');
    $content = get_field('content', $post_id);

    if(empty($content) && !empty($vimeo_blocks_folder)) {
        if (in_array(get_post_type($post_id), $included_cpts)) {
            $loaded_array = $profile_functionality->load_programm_content($user_id, $vimeo_blocks_folder, $client);
            $profile_functionality->set_loaded_data_to_acf($loaded_array, $post_id);
        }
    }
}

add_action('save_post', 'save_vimeo_data');
