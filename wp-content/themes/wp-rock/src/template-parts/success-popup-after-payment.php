<?php
global $profile_functionality;

$pay_success_title = __('Оплата пройшла успішно', 'wp-rock');
$user_just_registered = false;
$result = array(
	'success' => false,
	'text' => ''
);

$order_id = $order_id ?? $args['order_id'];

$order = wc_get_order($order_id);

if (!$order) {
	return;
}

$user_id = $order->get_user_id();

$user_just_registered = get_post_meta($order->get_id(), 'user_just_registered', true);
$access_period = get_post_meta($order->get_id(), 'access_period', true);
$redirect_page = get_post_meta($order->get_id(), 'redirect_page', true);

foreach ($order->get_items() as $item_id => $item) {
	$product_id = $item['product_id'];

	$product_fields = get_fields($product_id);
	$programm_id = get_field_value($product_fields, 'attached_post');

	if ($programm_id) {

		$result = $profile_functionality->add_update_user_programm($programm_id, $user_id, $access_period, true);

		break;
	}
}

/*if (!$result['success']) {
	error_log('Помилка додавання програми для користувача: ' . $result['text']);
	return;
}*/

if ($user_just_registered) {
	$result['text'] = __('На вашу пошту були надіслані доступи до особистого кабінету.', 'wp-rock');
}

echo do_shortcode(
	'[popup_box box_id="pay-success-response" box_class=""]
<div class="pay-success-response">
	<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
		<g clip-path="url(#clip0_2087_37696)">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M25 0C11.2109 0 0 11.2109 0 25C0 38.7891 11.2109 50 25 50C38.7891 50 50 38.7891 50 25C50 11.2109 38.7891 0 25 0Z" fill="#33B056"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M37.0898 16.5723C37.6953 17.1777 37.6953 18.1738 37.0898 18.7793L22.4414 33.4277C22.1387 33.7305 21.7383 33.8867 21.3379 33.8867C20.9375 33.8867 20.5371 33.7305 20.2344 33.4277L12.9102 26.1035C12.3047 25.498 12.3047 24.502 12.9102 23.8965C13.5156 23.291 14.5117 23.291 15.1172 23.8965L21.3379 30.1172L34.8828 16.5723C35.4883 15.957 36.4844 15.957 37.0898 16.5723Z" fill="#F2F3EB"/>
		</g>
		<defs>
			<clipPath id="clip0_2087_37696">
				<rect width="50" height="50" fill="white"/>
			</clipPath>
		</defs>
	</svg>
	<h2 class="pay-success-response__title">
		' . $pay_success_title . '
	</h2>
	<p class="pay-success-response__text js-pay-success-response-text body-type-2 weight500">
		' . $result['text'] . '
	</p>
</div>
[/popup_box]'
);

?>
<script>
    ( () =>{
        const date = new Date();
        date.setTime(date.getTime() + 60 * 60 * 1000);
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `creating_order='';${expires};path=/`;
	})()
</script>
