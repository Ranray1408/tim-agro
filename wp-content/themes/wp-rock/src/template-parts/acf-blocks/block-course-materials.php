<?php

/**
 * Block - Course materials.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;

$btn_details_text = get_field_value($global_options, 'btn_details_text');
$fields = get_fields();
$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$title = get_field_value($fields, 'title');
$items_list = get_field_value($fields, 'items_list');
?>
<div class="course-materials">
    <div class="container">
        <?php
        if (!empty($title)) {
            echo '<h2 class="course-materials__title">' . esc_html($title) . '</h2>';
        }
        ?>

        <?php if (!empty($items_list)) : ?>
            <div class="course-materials__list">
                <?php foreach ($items_list as $item) : ?>
                    <div class="course-materials__list-item d-flex align-items-center flex-column">
                        <?php
                        if (!empty($item['icon'])) {
                            echo '<figure class="course-materials__item-icon d-flex justify-content-center">
                                        <img src="' . esc_url($item['icon']) . '" alt="icon">
                                    </figure>';
                        }

                        if (!empty($item['text'])) {
                            echo '<p class="course-materials__item-text d-flex">' . esc_html($item['text']) . '</p>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
