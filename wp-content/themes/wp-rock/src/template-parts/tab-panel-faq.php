<?php

$main_title = __('Мої дані', 'wp-rock');
$profile_fields = get_fields(get_the_ID());

$faq_repeater = get_field_value($profile_fields, 'faq_repeater');
?>
<div id="faq" class="profile__panel js-tab-panel">
    <div class="container">
        <?php if (!empty($faq_repeater)) : ?>
            <div class="faq js-wrock-accordion">
                <?php foreach ($faq_repeater as $item) {
                    echo '<div class="faq__item js-wrock-accordion__item">';

                    if (!empty($item['title'])) {
                        echo '<button class="faq__item-btn js-wrock-accordion__btn d-flex align-items-center justify-content-between body-type-1 weight600">
                                    ' . esc_html($item['title']) . '
                                    <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 44 44" fill="none">
                                        <circle cx="22" cy="22" r="21.5" stroke="#33B056"/>
                                        <path d="M26.4865 16.7202L25.5009 17.7058L29.4433 21.6482H11.4385V23.0562H29.4433L25.5009 26.9986L26.4865 27.9842L32.1185 22.3522L26.4865 16.7202Z" fill="white"/>
                                    </svg>
                                </button>';
                    }

                    if (!empty($item['content'])) {
                        echo '<div class="faq__item-content js-wrock-accordion__content">' . do_shortcode($item['content']) . '</div>';
                    }

                    echo '</div>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
