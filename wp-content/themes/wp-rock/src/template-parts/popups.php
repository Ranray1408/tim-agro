<?php
//Get consultation

echo do_shortcode('[popup_box box_id="get-consultation-popup"][contact-form-7 id="1cb54c0" title="Get consultation"][/popup_box]');

echo do_shortcode('[popup_box box_id="get-access-popup"][contact-form-7 id="12624d3" title="Get access"][/popup_box]');

global $globa_options;

$forgot_password_page = get_field_value($globa_options, 'forgot_password_page');
?>


<?php

$forgot_pass_btn = !empty($forgot_password_page) ? '<a class="forgot-password" href="' . get_permalink($forgot_password_page) . '">
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
                            <span>Забули пароль?</span>
                        </a>' : '';

$login_form = '<form method="post" class="login-form js-login-form" action="' . esc_url(site_url('wp-login.php', 'login_post')) . '">
                    <h2 class="popup-title">Вхід</h2>
                    <div class="popup-subtitle body-type-2 weight500">
                        Введіть свій логін та пароль для доступу до курсів
                    </div>
                    <div class="inputs-wrapper">
                        <label class="input-label">
                            <span class="input-text body-type-5 weight400">* Логін або е-mail</span>
                            <input type="text" name="user-name-email">
                        </label>
                        <label class="input-label">
                            <span class="input-text body-type-5 weight400">* Пароль</span>
                            <input type="password" name="user-password">
                        </label>
                    </div>
                    <div class="bottom-wrapper">
                        <input type="hidden" name="redirect_to" value="' . esc_url(home_url()) . '" />
                        <input class="green-transparent" type="submit" value="Увійти">
                        ' . $forgot_pass_btn . '
                    </div>
                    <div class="js-response-container response-container"></div>
                </form>';

echo do_shortcode('[popup_box box_id="login-popup"]' . do_shortcode($login_form) . '[/popup_box]'); ?>
