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
                echo "Запрос выполнен успешно: " . print_r($response, true);
            } catch (VimeoRequestException $e) {
                // Обработка ошибки запроса
                error_log('Ошибка запроса к Vimeo API: ' . $e->getMessage());
                echo 'Ошибка запроса. Пожалуйста, попробуйте позже.';
            }

        } catch (Exception $e) {
            // Обработка ошибки создания клиента
            error_log('Ошибка при создании клиента Vimeo: ' . $e->getMessage());
            echo 'Ошибка при создании клиента. Пожалуйста, попробуйте позже.';
        }
    } else {
        // Обработка случая, когда хост недоступен
        echo 'Сеть недоступна. Пожалуйста, проверьте ваше соединение.';
    }
} else {
    echo 'Некоторые параметры для подключения пусты.';
}
