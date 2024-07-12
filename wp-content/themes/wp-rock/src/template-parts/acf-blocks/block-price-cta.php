<?php

/**
 * Block - Price CTA.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;
global $monobank;

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

$user_email = is_user_logged_in() ? wp_get_current_user()->user_email : null;

$popup_id = is_user_logged_in() ? '#' : '#login-popup';

$link_class = $show_link ? 'shown-link' : '';

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
        if (!empty($cta_button) && !is_user_logged_in()) {
            echo '<button
                        data-href="#get-access-popup"
                        data-price="' . $price . '"
                        data-post_id="' . $post_id . '"
                        class="price-cta__cta-button green-transparent from-left js-open-popup-activator js-get-access">
                        ' . esc_html($cta_button) . '
                    </button>';
        } else {
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
        <div class="bottom-wrapper">
            <label class="checkbox">
                <input checked type="checkbox" required>
                <span>' . __('Надсилаючи данні, я приймаю умови Публічної оферти та Політики конфіденційності*', 'wp-rock') . '</span>
            </label>
            <input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
            <input type="submit" class="green-transparent" value="Оплатити">
            <input type="hidden" name="registration" value="1">
            <input type="hidden" name="post-id" value="' . $post_id . '">
            <input type="hidden" name="forgot_password_page" value="' . get_permalink($forgot_password_page_id) . '">
        </div>
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
?>
