<?php
global $global_options;

$profile_page = get_field_value($global_options, 'profile_page');
$forgot_password_page_id = get_field_value($global_options, 'forgot_password_page');

$post_id = !empty($args['post_id']) ? $args['post_id'] : 0;
$price = !empty($args['price']) ? $args['price'] : '??';
$redirect_page = !empty($args['redirect_page']) ? $args['redirect_page'] : get_site_url();


$get_access_form = '
<div class="get-access-popup">
    <form class="get-access-form js-get-access-form" data-profile_page="' . get_permalink($profile_page) . '">
        <h2 class="popup-title">Вартість курсу <span>' . $price . '</span></h2>
        <div class="popup-subtitle body-type-2 weight500">
            ' . __('Щоб отримати доступ, заповніть форму і натисніть кнопку', 'wp-rock') . '
        </div>
        <div class="inputs-wrapper w100">
            <label class="input-label js-inner-input-wrapper">
                <span class="input-text body-type-5 weight400">* Ваше ім\'я</span>
                <input type="text" name="name" required>
            </label>
        </div>
        <div class="inputs-wrapper">
            <label class="input-label js-inner-input-wrapper">
                <span class="input-text body-type-5 weight400">* Телефон</span>
                <input type="tel" name="phone" required>
            </label>
            <label class="input-label js-inner-input-wrapper">
                <span class="input-text body-type-5 weight400">* E-mail</span>
                <input type="email" name="email" required>
            </label>
        </div>
        <div class="bottom-wrapper">
            <label class="checkbox">
                <input checked type="checkbox" required>
                <span>' . __('Надсилаючи данні, я приймаю умови Публічної оферти та Політики конфіденційності*', 'wp-rock') . '</span>
            </label>
            <input type="hidden" name="redirect-page" value="' . $redirect_page . '">
            <input type="hidden" name="registration" value="1">
            <input type="hidden" name="post-id" value="' . $post_id . '">
            <input type="hidden" name="forgot_password_page" value="' . get_permalink($forgot_password_page_id) . '">
            <input type="submit" class="green-filled" value="Оплатити">
        </div>
        <div class="js-response-container response-container"></div>
    </form>
    <button data-href="#login-popup" class="green-transparent have-account-btn js-popup-in-popup">Я вже маю акаунт</button>
</div>
';

echo do_shortcode('[popup_box box_id="get-access-popup"]' . do_shortcode($get_access_form) . '[/popup_box]');
