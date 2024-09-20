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

$user_full_name = wp_get_current_user() ? wp_get_current_user()->user_firstname . ' ' . wp_get_current_user()->user_lastname : '';

$green_class = $show_link ? 'green-svg' : '';


$socials = get_field_value($fields, 'socials');
$phone = get_field_value($fields, 'phone');
$cta_button = get_field_value($fields, 'cta_button');
$product_id = get_field_value($fields, 'post_id');
$product = wc_get_product($product_id);
$price     = $product->get_regular_price();
$popup_id = is_user_logged_in() ? '#' : '#login-popup';

$link_class = $show_link ? 'shown-link' : '';

$attached_post = get_field('attached_post', $product_id);

$user_programms_array = get_field(get_post_type($attached_post), 'user_' . get_current_user_id());
$user_phone = get_field('user_phone', 'user_' . get_current_user_id());



$is_user_have_programm = $profile_functionality->is_user_have_programm($user_programms_array, $attached_post);

$user_email = is_user_logged_in() ? wp_get_current_user()->user_email : null;
$is_programm_access_expire = $profile_functionality->is_programm_access_expire(get_current_user_id(), $attached_post);

$product = wc_get_product($product_id);
$regular_price = $product->get_regular_price();
?>
<div class="price-cta js-anim-activate <?php echo $link_class; ?>">
    <div class="container d-flex flex-column">
        <?php
        if (!empty($title)) {
            echo '<h3 class="price-cta__title from-right">' . esc_html($title) . '</h3>';
        }
        ?>
        <div
            class="price-cta__wrapper from-left d-flex justify-content-center align-items-center <?php echo $green_class; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path
                    d="M0.333333 3C0.333333 4.47276 1.52724 5.66667 3 5.66667C4.47276 5.66667 5.66667 4.47276 5.66667 3C5.66667 1.52724 4.47276 0.333333 3 0.333333C1.52724 0.333333 0.333333 1.52724 0.333333 3ZM3 3.5L562 3.49995L562 2.49995L3 2.5L3 3.5Z"
                    fill="white" fill-opacity="0.5" />
            </svg>
            <?php
            if ($show_link && !empty($phone)) {
                $clear_phone = preg_replace('/\D+/', '', $phone);
                echo '<a href="tel:' . esc_html($clear_phone) . '" class="price-cta__phone from-right">
                            ' . esc_html($phone) . '
                        </a>';
            } elseif (!empty($price) && !$show_link) {
                echo '<p class="price-cta__price">' . esc_html($price) . ' '.get_woocommerce_currency().'</p>';
            }
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path
                    d="M556.333 2.99995C556.333 4.47271 557.527 5.66662 559 5.66662C560.473 5.66662 561.667 4.47271 561.667 2.99995C561.667 1.52719 560.473 0.333284 559 0.333284C557.527 0.333285 556.333 1.52719 556.333 2.99995ZM4.37114e-08 3.5L559 3.49995L559 2.49995L-4.37114e-08 2.5L4.37114e-08 3.5Z"
                    fill="white" fill-opacity="0.5" />
            </svg>
        </div>
        <?php



        if (is_user_logged_in()) {
            // If user logged in and have program
            if (!empty($cta_button) && $is_user_have_programm !== false) {
                if ($is_programm_access_expire) {
                    // If user have programm and programm access expire
                    echo '<a href="#continue-access-popup" class="price-cta__cta-button green-transparent from-left js-open-popup-activator">
                    ' . esc_html($cta_button) . '
                    </a>';
                } else {
                    // If user have programm access not expire
                    echo '<a href="#have-access-popup" class="price-cta__cta-button green-transparent from-left js-open-popup-activator">
                                ' . esc_html($cta_button) . '
                            </a>';
                }
            } else {
                // If user loggedin and don't have programm
                if (!empty($cta_button)) {
                    if (get_post_type($attached_post) === 'courses') {
                        echo '<a href="#buy-programm-popup" class="price-cta__cta-button green-transparent from-left js-open-popup-activator">
                                ' . esc_html($cta_button) . '
                            </a>';
                    } else {
                        echo '<form class="price-cta__buy-programm-form js-buy-programm-form">
                                <input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
                                <input type="hidden" name="email" value="' . $user_email . '">
                                <input type="hidden" name="phone" value="' . $user_phone . '">
                                <input type="hidden" name="user_full_name" value="' . $user_full_name . '">
                                <input type="hidden" name="post-id" value="' . $product_id . '">
                                <input type="hidden" name="amount" value="' . $regular_price . '">
                                <input type="submit" value="' . esc_html($cta_button) . '"
                                class="price-cta__cta-button green-transparent from-left">
                                <div class="js-response-container response-container"></div>
                                </form>';
                    }
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
echo get_template_part('src/template-parts/get-access-popup', null, array(
    'redirect_page' => get_the_permalink(),
    'price' => $regular_price,
    'product_id' => $product_id
));

// echo get_template_part('src/template-parts/pay-success-response', null, array(
//     'payment' => $payment,
//     'post_id' => $post_id
// ));

echo get_template_part('src/template-parts/continue-access-popup', null, array(
    'post_id' => get_the_ID(),
    'product_id' => $product_id,
));

echo get_template_part('src/template-parts/have-access-popup', null, array());

echo get_template_part('src/template-parts/buy-programm-popup', null, array(
    'post_id' => $product_id,
    'price' => $regular_price,
    'user_email' => $user_email
));
?>
