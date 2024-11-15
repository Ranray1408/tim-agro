<?php
// Vimeo SDK
require get_template_directory() . '/vendor/autoload.php';

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoRequestException;
use Exception;

// Проверка доступности хоста
function isHostReachable($host = 'api.vimeo.com', $port = 443) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        fclose($connection);
        return true;
    } else {
        return false;
    }
}

global $global_options;
$client_id = get_field_value($global_options, 'client_id');
$client_secret = get_field_value($global_options, 'client_secret');
$client_token = get_field_value($global_options, 'client_token');

global $client;

if (!empty($client_id) && !empty($client_secret) && !empty($client_token)) {
    if (isHostReachable()) {
        try {
            $client = new Vimeo($client_id, $client_secret, $client_token);

            try {
                $response = $client->request('/me', [], 'GET');
                echo "Request completed successfully: " . print_r($response, true);
            } catch (VimeoRequestException $e) {
                error_log('Vimeo API request error: ' . $e->getMessage());
                echo 'Request error. Please try again later.';
            }

        } catch (Exception $e) {
            error_log('Error creating Vimeo client: ' . $e->getMessage());
            echo 'Error creating client. Please try again later.';
        }
    } else {
        echo 'Network is unavailable. Please check your connection.';
    }
} else {
    echo 'Some connection parameters are empty.';
}
