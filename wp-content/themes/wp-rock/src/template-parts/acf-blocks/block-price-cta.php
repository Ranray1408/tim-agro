<?php

/**
 * Block - Price CTA.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$title = get_field_value($fields, 'title');
$show_link = get_field_value($fields, 'show_link');

$green_class = $show_link ? 'green-svg' : '';

$mid_text = get_field_value($fields, 'mid_text');
$socials = get_field_value($fields, 'socials');
$phone = get_field_value($fields, 'phone');
$cta_button = get_field_value($fields, 'cta_button');
?>
<div class="price-cta">
    <div class="container d-flex flex-column">
        <?php
        if (!empty($title)) {
            echo '<h3 class="price-cta__title">' . esc_html($title) . '</h3>';
        }
        ?>
        <div class="price-cta__wrapper d-flex justify-content-center align-items-center <?php echo $green_class; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path d="M0.333333 3C0.333333 4.47276 1.52724 5.66667 3 5.66667C4.47276 5.66667 5.66667 4.47276 5.66667 3C5.66667 1.52724 4.47276 0.333333 3 0.333333C1.52724 0.333333 0.333333 1.52724 0.333333 3ZM3 3.5L562 3.49995L562 2.49995L3 2.5L3 3.5Z" fill="white" fill-opacity="0.5" />
            </svg>
            <?php
            if ($show_link && !empty($phone)) {
                $clear_phone = preg_replace('/\D+/', '', $phone);
                echo '<a href="tel:' . esc_html($clear_phone) . '" class="price-cta__phone">
                            ' . esc_html($phone) . '
                        </a>';
            } elseif (!empty($mid_text) && !$show_link) {
                echo '<p class="price-cta__mid-text">' . esc_html($mid_text) . '</p>';
            }
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="562" height="6" viewBox="0 0 562 6" fill="none">
                <path d="M556.333 2.99995C556.333 4.47271 557.527 5.66662 559 5.66662C560.473 5.66662 561.667 4.47271 561.667 2.99995C561.667 1.52719 560.473 0.333284 559 0.333284C557.527 0.333285 556.333 1.52719 556.333 2.99995ZM4.37114e-08 3.5L559 3.49995L559 2.49995L-4.37114e-08 2.5L4.37114e-08 3.5Z" fill="white" fill-opacity="0.5" />
            </svg>
        </div>
        <?php
        if (!empty($cta_button['url']) && !empty($cta_button['title'])) {
            echo '<a href="' . esc_url($cta_button['url']) . '" class="price-cta__cta-button green-transparent">
                        ' . esc_html($cta_button['title']) . '
                    </a>';
        }
        ?>
        <div class="price-cta__socials d-flex align-items-center">
            <?php
            if (!empty($socials)) {
                foreach ($socials as $item) {
                    echo '<a href="' . esc_url($item['link']) . '" class="price-cta__socials-item d-flex align-items-center justify-content-center">
                                <img src="' . esc_url($item['icon']) . '" alt="phone">
                            </a>';
                }
            }
            ?>
        </div>
    </div>
</div>
