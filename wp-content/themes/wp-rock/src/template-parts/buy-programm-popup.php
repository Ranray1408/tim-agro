<?php
global $global_options;

$post_id = !empty($args['post_id']) ? $args['post_id'] : 0;
$price = !empty($args['price']) ? $args['price'] : '??';
$user_email = !empty($args['user_email']) ? $args['user_email'] : '??';

if(empty($post_id)) return;

$svg_promo = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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

$html = '<div class="get-access-popup">
            <form class="get-access-form js-buy-programm-form">
                <h2 class="popup-title">Вартість курсу <span>' . $price . ' '.get_woocommerce_currency().'</span></h2>
                <div class="popup-subtitle body-type-2 weight500">
                    ' . __('Якщо у вас є промокод, введіть його, щоб отримати знижку', 'wp-rock') . '
                </div>
                <div class="inputs-wrapper promocode">
                    <label class="input-label">
                        ' . $svg_promo . '
                        <span class="input-text body-type-5 weight400">Введіть промокод</span>
                        <input type="text" name="promocode">
                    </label>
                </div>
                <div class="bottom-wrapper">
                    <label class="checkbox">
                        <input checked type="checkbox" required>
                        <span>' . __('Надсилаючи данні, я приймаю умови Публічної оферти та Політики конфіденційності*', 'wp-rock') . '</span>
                    </label>
                    <input type="hidden" name="email" value="' . $user_email . '">
                    <input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
                    <input type="hidden" name="amount" value="' . $price . '">
                    <input type="hidden" name="post-id" value="' . $post_id . '">
                    <input type="submit" class="green-transparent" value="Оплатити">
                </div>
                <div class="js-response-container response-container"></div>
            </form>
</div>';
echo do_shortcode('[popup_box box_id="buy-programm-popup"]' . $html . '[/popup_box]');
