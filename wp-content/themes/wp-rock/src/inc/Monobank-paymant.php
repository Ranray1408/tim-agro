<?php
class MonobankPayment {
    private $apiUrl = "https://api.monobank.ua/api/merchant/invoice/create";
    private $apiUrlPublicKey = "https://api.monobank.ua/api/merchant/pubkey";
    private $token;
    private $cms = "Wordpress";
    private $cmsVersion;
    private $public_key;

    public function __construct($token) {
        global $wp_version;
        $this->cmsVersion = $wp_version;
        $this->token = $token;
        $this->public_key = $this->get_public_key();
    }

    public function init() {
        add_action('wp_ajax_nopriv_create_payment_action', array($this, 'create_payment_action'));
        add_action('wp_ajax_create_payment_action', array($this, 'create_payment_action'));

        $this->handle_webhook();
    }

    private function request($endpoint, $method = 'POST', $data = null) {
        $ch = curl_init();

        $headers = [
            'X-Token: ' . $this->token,
            'Content-Type: application/json',
            'X-Cms: ' . $this->cms,
            'X-Cms-Version: ' . $this->cmsVersion
        ];

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'statusCode' => $httpCode,
            'body' => json_decode($response, true)
        ];
    }

    public function createPayment($cardToken, $amount, $currency = 980, $webHookUrl = '', $redirectUrl = '', $initiationKind = 'client') {
        $webHookUrl = $webHookUrl ? $webHookUrl : get_site_url();
        $redirectUrl = $redirectUrl ? $redirectUrl : get_site_url();

        $data = [
            'cardToken' => $cardToken,
            'amount' => $amount,
            'ccy' => $currency,
            'redirectUrl' =>  $redirectUrl,
            'webHookUrl' => $webHookUrl,
            'initiationKind' => $initiationKind
        ];

        $response = $this->request($this->apiUrl, 'POST', $data);

        if ($response['statusCode'] == 200) {
            return [
                'success' => true,
                'data' => $response['body']
            ];
        } else {
            return [
                'success' => false,
                'error' => $response['body']['errText'] ?? 'Unknown error'
            ];
        }
    }

    public function create_payment_action() {
        $registration = filter_input(INPUT_POST, 'registration', FILTER_VALIDATE_INT);
        $redirect_page = filter_input(INPUT_POST, 'redirect-page', FILTER_VALIDATE_URL);
        $post_id = filter_input(INPUT_POST, 'post-id', FILTER_VALIDATE_INT);
        $user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        $params = $registration ? '?first-login=true' : '';

        $post_fields = get_fields($post_id);
        $price = get_field_value($post_fields, 'price');
        $price = $price ? $price * 100 : 0;
        $currency = 980;
        $current_url = $redirect_page ? $redirect_page : get_site_url();

        $result = $this->createPayment('1111-1111-1111-1111', $price, $currency, $current_url, $current_url . $params);

        if ($result['success']) {
            set_transient($user_email . '_payment', $result['data']['invoiceId']);
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['error']);
        }
    }

    private function handle_webhook() {
        $raw_post_data = file_get_contents('php://input');
        $webhook_data = json_decode($raw_post_data, true);

        if (isset($_SERVER['HTTP_X_SIGN'])) {

            // Check signature
            if ($this->verify_signature($raw_post_data, $_SERVER['HTTP_X_SIGN'], $this->public_key)) {
                // Log data
                error_log(print_r($webhook_data, true));

                $invoice_id = $webhook_data['invoiceId'] ?? 'unknown';

                if ($invoice_id !== 'unknown') {
                    set_transient($invoice_id, json_encode($webhook_data));
                }

                wp_send_json_success('Webhook received');
            } else {
                wp_send_json_error('Invalid signature', 403);
            }
        }
    }

    public function verify_signature($message, $signature, $publicKeyBase64) {
        // Decode public key and signature
        $publicKey = openssl_get_publickey(base64_decode($publicKeyBase64));
        $signature = base64_decode($signature);

        // Check signature
        $result = openssl_verify($message, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        // Free the src
        openssl_free_key($publicKey);

        return $result === 1;
    }

    public function check_payment() {
        if (is_user_logged_in()) {
            $user_data = wp_get_current_user();
            $user_email = $user_data->user_email;

            $user_payment = get_transient($user_email . '_payment');

            if ($user_payment) {
                $data = json_decode(get_transient($user_payment), true);
                $result = array(
                    'success' => false,
                    'text' => '',
                );

                $status = isset($data['status']) ? $data['status'] : '';

                switch ($status) {
                    case 'created':
                        $link = '<a href="https://pay.mbnk.biz/' . $user_payment . '">' . __('Сплатити', 'wp-rock') . '</a>';
                        $result['text'] = __('Рахунок створено успішно, очікується оплата. ', 'wp-rock') . $link;
                        break;
                    case 'processing':
                        $result['text'] = __('Платіж обробляється', 'wp-rock');
                        break;
                    case 'hold':
                        $result['text'] = __('Сума заблокована', 'wp-rock');
                        break;
                    case 'success':
                        $result['text'] = __('Успішна оплата', 'wp-rock');
                        $result['success'] = true;
                        delete_transient($user_payment);
                        delete_transient($user_email . '_payment');
                        break;
                    case 'failure':
                        $result['text'] = __('Неуспішна оплата', 'wp-rock');
                        delete_transient($user_payment);
                        delete_transient($user_email . '_payment');
                        break;
                    case 'reversed':
                        $result['text'] = __('Оплата повернена після успіху', 'wp-rock');
                        break;
                    case 'expired':
                        $result['text'] = __('Час дії вичерпано', 'wp-rock');
                        delete_transient($user_payment);
                        delete_transient($user_email . '_payment');
                        break;
                }

                if (empty($result['text'])) {
                    return false;
                }
                return $result;
            }
        }
        return false;
    }

    private function get_public_key() {
        $response = $this->request($this->apiUrlPublicKey, 'GET', null);

        if ($response['statusCode'] === 200) {
            return $response['body']['key'];
        }

        return $response['body']['errCode'] ?? 0;
    }
}
