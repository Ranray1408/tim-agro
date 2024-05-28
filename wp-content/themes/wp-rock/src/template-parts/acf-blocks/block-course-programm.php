<?php

/**
 * Block - Course programm.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$fields = get_fields();
$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$big_title = get_field_value($fields, 'big_title');
$title = get_field_value($fields, 'title');
$blocks_list = get_field_value($fields, 'blocks_list');
?>
<div class="course-programm">
    <div class="container course-programm__container container-big-title reverse">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="course-programm__title">' . esc_html($title) . '</h2>';
        }
        ?>
        <?php if (!empty($blocks_list)) : ?>
            <div class="course-programm__inner-wrap">
                <?php
                foreach ($blocks_list as $item) {
                    if (!empty($item['title']) && !empty($item['content'])) {
                        echo '<div class="course-programm__block">
                                <p class="course-programm__block-title body-type-0">
                                    ' . esc_html($item['title']) . '
                                </p>
                                <div class="course-programm__block-content">
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
