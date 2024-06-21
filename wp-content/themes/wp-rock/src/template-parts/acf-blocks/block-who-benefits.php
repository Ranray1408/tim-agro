<?php

/**
 * Block - Who benefits.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$background_image = get_field_value($fields, 'background_image');
$title = get_field_value($fields, 'title');
$subtitle = get_field_value($fields, 'subtitle');
$benefits_repeater = get_field_value($fields, 'benefits_repeater');
?>

<div class="who-benefits js-anim-activate <?php echo esc_html($class_name); ?>">
    <div class="container who-benefits__container">
        <?php
        if (!empty($background_image)) {
            echo '<img class="who-benefits__bg" src="' . $background_image . '" alt="bg">';
        }
        ?>
        <div class="who-benefits__content">
            <?php
            if (!empty($title)) {
                echo '<h2 class="who-benefits__title from-left">' . do_shortcode($title) . '</h2>';
            }
            if (!empty($subtitle)) {
                echo '<div class="who-benefits__subtitle from-left">' . do_shortcode($subtitle) . '</div>';
            }
            ?>

            <?php if (!empty($benefits_repeater)) : ?>
                <div class="who-benefits__inner from-right">
                    <?php foreach ($benefits_repeater as $item) {
                        if (!empty($item['text'])) {
                            echo '<div class="who-benefits__item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                    <circle cx="28" cy="28" r="27.5" stroke="#151A1D" />
                                    <line x1="28.2148" y1="16.9999" x2="28.2148" y2="38.4285" stroke="#151A1D" />
                                    <line x1="38.4297" y1="28.2141" x2="17.0011" y2="28.2141" stroke="#151A1D" />
                                </svg>
                                <p>' . esc_html($item['text']) . '</p>
                            </div>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
