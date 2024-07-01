<?php
/**
 * Initial setup actions for site
 *
 * @package WP-rock
 */

/*Collect all ACF option fields to global variable. */
global $global_options;

if ( function_exists( 'get_fields' ) ) {
    if ( function_exists( 'pll_current_language' ) ) {
        // @codingStandardsIgnoreStart
        $locale         = get_locale();
        // @codingStandardsIgnoreEnd
        $global_options = get_fields( 'theme-general-settings_' . $locale );
    } else {
        $global_options = get_fields( 'theme-general-settings' );
    }
}


/**
 * Main theme's class init
 */
$wp_rock = new WP_Rock();
add_action( 'after_setup_theme', array( $wp_rock, 'px_site_setup' ) );

/**
 * Sanitize uploaded file name
 */
add_filter( 'sanitize_file_name', array( $wp_rock, 'custom_sanitize_file_name' ), 10, 1 );


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
function get_field_value( $data_arr, $key ) {
    return ( isset( $data_arr[ $key ] ) ) ? $data_arr[ $key ] : null;
}

$profile_functionality = new profile_functionality();
$profile_functionality->init();

// Vimeo SDK
require get_template_directory() . '/vendor/autoload.php';
use Vimeo\Vimeo;

$client_id = 'c8b5f4ae254bfdc534542558fde0d89ae99b76ae';
$client_secret = 'TlpYbxGzrOQEjfkA+Uc8HaZBb5e0nqTScCrZe83An9ILVa72qlAYkS5KPAdyAzneMmzYQ4vSeSx5dTwJk2PUfZ4MXCfqLZuaPjYYaYcu+VUAKe3kiE8HjWrFY3qjZPzm';
$client_token = 'f9644f60392275c14ccb5e927369e4b3';

// @intelephense-disable-line
global $client;
$client = new Vimeo($client_id, $client_secret, $client_token);
