<?php

/**
 * Block - Price CTA.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;
global $monobank;
global $profile_functionality;

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$title = get_field_value($fields, 'title');
$show_link = get_field_value($fields, 'show_link');

$profile_page = get_field_value($global_options, 'profile_page');
$forgot_password_page_id = get_field_value($global_options, 'forgot_password_page');

$green_class = $show_link ? 'green-svg' : '';

$price = get_field_value($fields, 'price');
$socials = get_field_value($fields, 'socials');
$phone = get_field_value($fields, 'phone');
$cta_button = get_field_value($fields, 'cta_button');
$post_id = get_field_value($fields, 'post_id');

$popup_id = is_user_logged_in() ? '#' : '#login-popup';

$link_class = $show_link ? 'shown-link' : '';

$user_programms_array = get_field(get_post_type($post_id), 'user_' . get_current_user_id());

$is_user_have_programm = $profile_functionality->is_user_have_programm($user_programms_array, $post_id);

$user_email = is_user_logged_in() ? wp_get_current_user()->user_email : null;
?>
<div class="price-cta js-anim-activate <?php echo $link_class; ?>">
    <div class="container d-flex flex-column">
        <?php
        if (!empty($title)) {
            echo '<h3 class="price-cta__title from-right">' . esc_html($title) . '</h3>';
        }
        ?>
        <div class="price-cta__wrapper from-left d-flex justify-content-center align-items-center <?php echo $green_class; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path d="M0.333333 3C0.333333 4.47276 1.52724 5.66667 3 5.66667C4.47276 5.66667 5.66667 4.47276 5.66667 3C5.66667 1.52724 4.47276 0.333333 3 0.333333C1.52724 0.333333 0.333333 1.52724 0.333333 3ZM3 3.5L562 3.49995L562 2.49995L3 2.5L3 3.5Z" fill="white" fill-opacity="0.5" />
            </svg>
            <?php
            if ($show_link && !empty($phone)) {
                $clear_phone = preg_replace('/\D+/', '', $phone);
                echo '<a href="tel:' . esc_html($clear_phone) . '" class="price-cta__phone from-right">
                            ' . esc_html($phone) . '
                        </a>';
            } elseif (!empty($price) && !$show_link) {
                echo '<p class="price-cta__price">' . esc_html($price) . '</p>';
            }
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path d="M556.333 2.99995C556.333 4.47271 557.527 5.66662 559 5.66662C560.473 5.66662 561.667 4.47271 561.667 2.99995C561.667 1.52719 560.473 0.333284 559 0.333284C557.527 0.333285 556.333 1.52719 556.333 2.99995ZM4.37114e-08 3.5L559 3.49995L559 2.49995L-4.37114e-08 2.5L4.37114e-08 3.5Z" fill="white" fill-opacity="0.5" />
            </svg>
        </div>
        <?php
        if (is_user_logged_in()) {
            // If user loggedin and have programm
            if (!empty($cta_button) && $is_user_have_programm !== false) {
                echo '<a href="#continue-access-popup" class="price-cta__cta-button green-transparent from-left js-open-popup-activator">
                            ' . esc_html($cta_button) . '
                        </a>';
            } else {
                // If user loggedin and don't have programm
                if (!empty($cta_button)) {
                    echo '<form class="price-cta__buy-programm-form js-buy-programm-form">
                            <input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
                            <input type="hidden" name="email" value="' . $user_email . '">
                            <input type="hidden" name="post-id" value="' . $post_id . '">
                            <input type="submit" value="' . esc_html($cta_button) . '"
                            class="price-cta__cta-button green-transparent from-left">
                            <div class="js-response-container response-container"></div>
                            </form>';
                }
            }
        } else {
            // If user don't loggedin
            echo '<a href="#get-access-popup" class="price-cta__cta-button green-transparent from-left js-open-popup-activator">
                        ' . esc_html($cta_button) . '
                    </a>';
        }

        if (!empty($socials)) {
            echo '<div class="price-cta__socials d-flex align-items-center from-left">';
            foreach ($socials as $item) {
                echo '<a href="' . esc_url($item['link']) . '" class="price-cta__socials-item d-flex align-items-center justify-content-center">
                                <img src="' . esc_url($item['icon']) . '" alt="phone">
                            </a>';
            }
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php

$svg = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_3621_634)">
<path d="M15.2649 8.00056L15.9668 6.16969C16.0438 5.9677 15.9838 5.73872 15.8148 5.60173L14.2929 4.36781L13.985 2.43094C13.951 2.21696 13.783 2.04997 13.569 2.01597L11.6321 1.70799L10.3992 0.185095C10.2632 0.0161068 10.0292 -0.0438891 9.83225 0.0331056L8.00037 0.736058L6.1695 0.0341055C5.96651 -0.0438891 5.73952 0.0181066 5.60253 0.186095L4.36862 1.70899L2.43175 2.01697C2.21877 2.05097 2.05078 2.21896 2.01678 2.43194L1.7088 4.36881L0.185905 5.60273C0.017916 5.73872 -0.0430798 5.9677 0.0339149 6.16969L0.735867 8.00056L0.0339149 9.83144C-0.0440797 10.0334 0.017916 10.2624 0.185905 10.3984L1.7088 11.6313L2.01678 13.5682C2.05078 13.7822 2.21777 13.9502 2.43175 13.9842L4.36862 14.2921L5.60253 15.814C5.73952 15.984 5.96851 16.044 6.1705 15.966L8.00037 15.2651L9.83125 15.967C9.88924 15.989 9.94924 16 10.0102 16C10.1572 16 10.3022 15.935 10.3992 15.814L11.6321 14.2921L13.569 13.9842C13.783 13.9502 13.951 13.7822 13.985 13.5682L14.2929 11.6313L15.8148 10.3984C15.9838 10.2614 16.0438 10.0334 15.9668 9.83144L15.2649 8.00056Z" fill="#F44336"/>
<path d="M6.50026 7.00126C5.67332 7.00126 5.00037 6.32831 5.00037 5.50136C5.00037 4.67442 5.67332 4.00146 6.50026 4.00146C7.32721 4.00146 8.00016 4.67442 8.00016 5.50136C8.00016 6.32831 7.32721 7.00126 6.50026 7.00126ZM6.50026 5.0014C6.22428 5.0014 6.0003 5.22538 6.0003 5.50136C6.0003 5.77734 6.22428 6.00133 6.50026 6.00133C6.77624 6.00133 7.00023 5.77734 7.00023 5.50136C7.00023 5.22538 6.77624 5.0014 6.50026 5.0014Z" fill="#FAFAFA"/>
<path d="M9.50008 12.0003C8.67314 12.0003 8.00018 11.3273 8.00018 10.5004C8.00018 9.67344 8.67314 9.00049 9.50008 9.00049C10.327 9.00049 11 9.67344 11 10.5004C11 11.3273 10.327 12.0003 9.50008 12.0003ZM9.50008 10.0004C9.2251 10.0004 9.00011 10.2254 9.00011 10.5004C9.00011 10.7754 9.2251 11.0004 9.50008 11.0004C9.77506 11.0004 10 10.7754 10 10.5004C10 10.2254 9.77506 10.0004 9.50008 10.0004Z" fill="#FAFAFA"/>
<path d="M5.5005 12.0005C5.3995 12.0005 5.29851 11.9705 5.21052 11.9075C4.98553 11.7465 4.93354 11.4345 5.09452 11.2095L10.0942 4.21003C10.2552 3.98504 10.5672 3.93305 10.7921 4.09404C11.0171 4.25403 11.0681 4.567 10.9081 4.79099L5.90847 11.7905C5.80948 11.9275 5.65649 12.0005 5.5005 12.0005Z" fill="#FAFAFA"/>
</g>
<defs>
<clipPath id="clip0_3621_634">
<rect width="16" height="16" fill="white"/>
</clipPath>
</defs>
</svg>';

$get_access_form = '
<div class="get-access-popup">
    <form class="get-access-form js-get-access-form" data-profile_page="' . get_permalink($profile_page) . '">
        <h2 class="popup-title">Вартість курсу <span>' . $price . '</span></h2>
        <div class="popup-subtitle body-type-2 weight500">
            ' . __('Щоб отримати доступ, заповніть форму і натисніть кнопку', 'wp-rock') . '
        </div>
        <div class="inputs-wrapper w100">
            <label class="input-label">
                <span class="input-text body-type-5 weight400">* Ваше ім\'я</span>
                <input type="text" name="name" required>
            </label>
        </div>
        <div class="inputs-wrapper">
            <label class="input-label">
                <span class="input-text body-type-5 weight400">* Телефон</span>
                <input type="tel" name="phone" required>
            </label>
            <label class="input-label">
                <span class="input-text body-type-5 weight400">* E-mail</span>
                <input type="email" name="email" required>
            </label>
        </div>
        <div class="inputs-wrapper promocode">
            <label class="input-label">
                ' . $svg . '
                <span class="input-text body-type-5 weight400">Введіть промокод</span>
                <input type="text" name="promocode">
            </label>
        </div>
        <div class="bottom-wrapper">
            <label class="checkbox">
                <input checked type="checkbox" required>
                <span>' . __('Надсилаючи данні, я приймаю умови Публічної оферти та Політики конфіденційності*', 'wp-rock') . '</span>
            </label>
            <input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
            <input type="hidden" name="registration" value="1">
            <input type="hidden" name="post-id" value="' . $post_id . '">
            <input type="hidden" name="forgot_password_page" value="' . get_permalink($forgot_password_page_id) . '">
            <input type="submit" class="green-transparent" value="Оплатити">
        </div>
        <div class="js-response-container response-container"></div>
    </form>
    <button data-href="#login-popup" class="green-transparent have-account-btn js-popup-in-popup">Я вже маю акаунт</button>
</div>
';

echo do_shortcode('[popup_box box_id="get-access-popup"]' . do_shortcode($get_access_form) . '[/popup_box]');

$payment = $monobank->check_payment();

echo get_template_part('src/template-parts/pay-success-response', null, array(
    'payment' => $payment,
    'post_id' => $post_id
));

echo get_template_part('src/template-parts/continue-access-popup', null, array(
    'post_id' => get_the_ID()
));
?>
