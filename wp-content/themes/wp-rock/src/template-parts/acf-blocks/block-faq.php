<?php

/**
 * Block - FAQ.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;

$btn_details_text = get_field_value($global_options, 'btn_details_text');

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$big_title = get_field_value($fields, 'big_title');
$title = get_field_value($fields, 'title');
$accordion = get_field_value($fields, 'accordion');
?>

<div class="block-faq">
    <div class="container container-big-title">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="block-faq__title">' . esc_html($title) . '</h2>';
        }
        ?>
        <?php if (!empty($accordion)) : ?>
            <div class="block-faq__accordion d-flex flex-column js-wrock-accordion">
                <?php foreach ($accordion as $item) {
                    if (!empty($item['button_text']) && !empty($item['content'])) {
                        echo '<div class="block-faq__accordion-item js-wrock-accordion__item">
                                    <button class="block-faq__accordion-btn d-flex justify-content-between align-items-center js-wrock-accordion__btn">
                                        ' . esc_html($item['button_text']) . '
                                        <svg xmlns="http://www.w3.org/2000/svg" width="47" height="47" viewBox="0 0 44 44" fill="none">
                                            <circle cx="22" cy="22" r="21.5" stroke="#33B056"/>
                                            <path d="M26.4865 16.7202L25.5009 17.7058L29.4433 21.6482H11.4385V23.0562H29.4433L25.5009 26.9986L26.4865 27.9842L32.1185 22.3522L26.4865 16.7202Z" fill="#151A1D"/>
                                        </svg>
                                    </button>
                                    <div class="block-faq__accordion-content js-wrock-accordion__content">
                                        ' . do_shortcode($item['content']) . '
                                    </div>
                                </div>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
