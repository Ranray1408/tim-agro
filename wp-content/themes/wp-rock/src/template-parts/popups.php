<?php
//Get consultation
global $global_options;

$get_consultation_form = get_field_value($global_options, 'get_consultation_form');

if (!empty($get_consultation_form)) {
    echo do_shortcode('[popup_box box_id="get-consultation-popup"]' . do_shortcode($get_consultation_form) . '[/popup_box]');
}

$forgot_password_resp = __('На вашу пошту було надіслано новий пароль', 'wp-rock');

$forgot_password_popup =
    '<div class="forgot-password-popup">
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
    <div class="forgot-password-popup__text body-type-2 weight600">
        ' . $forgot_password_resp . '
    </div>
</div>';

echo do_shortcode('[popup_box box_id="forgot-password-popup"]' . do_shortcode($forgot_password_popup) . '[/popup_box]');

$get_access_pay_title = __('Оплата пройшла успішно', 'wp-rock');
$get_access_pay_description = __('На вашу пошту були надіслані доступи до особистого кабінету.', 'wp-rock');

$get_access_popup_response =
    '<div class="get-access-popup-response">
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
    <h2 class="get-access-popup-response__title">
        ' . $get_access_pay_title . '
    </h2>
    <p class="get-access-popup-response__text body-type-2 weight500">
        ' . $get_access_pay_description . '
    </p>
</div>';

echo do_shortcode('[popup_box box_id="get-access-popup-response"]' . do_shortcode($get_access_popup_response) . '[/popup_box]');






global $global_options;

$forgot_password_page = get_field_value($global_options, 'forgot_password_page');

$forgor_pass_text =  __('Забули пароль?', 'wp-rock');
$forgor_pass_subtitle_text =  __('Введіть свій логін та пароль для доступу до курсів', 'wp-rock');
$forgor_pass_logint_email_text =  __('* Логін або е-mail', 'wp-rock');
$forgor_pass_password_text =  __('* Пароль', 'wp-rock');
$forgor_pass_submit_text =  __('Увійти', 'wp-rock');

$forgot_pass_btn = !empty($forgot_password_page)
    ? '<a class="forgot-password" href="' . get_permalink($forgot_password_page) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <g clip-path="url(#clip0_2087_33473)">
                                    <path d="M8.02503 3.24538e-05C3.58753 -0.0124675 -0.0124675 3.58753 3.24538e-05 8.02503C0.0125325 12.4125 3.67191 16 8.05941 16H16V8.05941C16 3.67191 12.4125 0.0125325 8.02503 3.24538e-05ZM8.71253 12.075C8.50316 12.2594 8.24378 12.3532 7.93128 12.3532C7.61878 12.3532 7.35941 12.2594 7.15003 12.075C6.94066 11.8907 6.83753 11.6532 6.83753 11.3657C6.83753 11.0782 6.94066 10.8407 7.15003 10.6563C7.35941 10.4719 7.61878 10.3782 7.93128 10.3782C8.24378 10.3782 8.50316 10.4719 8.71253 10.6563C8.92191 10.8407 9.02503 11.0782 9.02503 11.3657C9.02503 11.6532 8.91878 11.8875 8.71253 12.075ZM10.5625 6.84691C10.4344 7.11253 10.2282 7.38441 9.94378 7.65941L9.27191 8.28441C9.08128 8.46878 8.94691 8.65628 8.87503 8.85003C8.80003 9.04378 8.75941 9.28753 8.75316 9.58441H7.07816C7.07816 9.01566 7.14378 8.56566 7.27191 8.23753C7.40316 7.90316 7.61878 7.60941 7.89691 7.38128C8.18441 7.14066 8.40628 6.91878 8.55628 6.71878C8.70316 6.52816 8.78128 6.29378 8.78128 6.05628C8.78128 5.46878 8.52816 5.17191 8.02191 5.17191C7.80316 5.16566 7.59378 5.25941 7.45628 5.42816C7.31253 5.60003 7.23441 5.83128 7.22816 6.12503H5.25316C5.26253 5.34378 5.50628 4.73753 5.99066 4.30316C6.47503 3.86878 7.15316 3.65003 8.02503 3.65003C8.89378 3.65003 9.56878 3.85003 10.0469 4.25316C10.525 4.65628 10.7625 5.22503 10.7625 5.96566C10.7594 6.26878 10.6938 6.57191 10.5625 6.84691Z" fill="#08581E" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2087_33473">
                                        <rect width="16" height="16" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span>' . $forgor_pass_text . '</span>
                        </a>'
    : '';

$login_form = '<form method="post" class="login-form js-login-form" action="' . esc_url(site_url('wp-login.php', 'login_post')) . '">
                    <h2 class="popup-title">Вхід</h2>
                    <div class="popup-subtitle body-type-2 weight500">
                        ' . $forgor_pass_subtitle_text . '
                    </div>
                    <div class="inputs-wrapper">
                        <label class="input-label">
                            <span class="input-text body-type-5 weight400">
                            ' . $forgor_pass_logint_email_text . '
                            </span>
                            <input type="text" name="user-name-email">
                        </label>
                        <label class="input-label">
                            <span class="input-text body-type-5 weight400">
                            ' . $forgor_pass_password_text . '
                            </span>
                            <input type="password" name="user-password">
                        </label>
                    </div>
                    <div class="bottom-wrapper">
                        <input type="hidden" name="redirect_to" value="' . esc_url(home_url()) . '" />
                        <input class="green-transparent" type="submit" value="' . $forgor_pass_submit_text . '">
                        ' . $forgot_pass_btn . '
                    </div>
                    <div class="js-response-container response-container"></div>
                </form>';

echo do_shortcode('[popup_box box_id="login-popup"]' . do_shortcode($login_form) . '[/popup_box]');
