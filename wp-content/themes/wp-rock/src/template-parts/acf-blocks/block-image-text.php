<?php

/**
 * Block - Image text.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$title = get_field_value($fields, 'title');
$content = get_field_value($fields, 'content');
$image = get_field_value($fields, 'image');
?>
<div class="image-text js-anim-activate">
    <div class="container image-text__container">
        <div class="image-text__inner-wrapper d-flex">
            <?php
            if (!empty($image)) {
                echo '<figure class="image-text__image from-left">
                            <img src="' . esc_url($image) . '" alt="image">
                        </figure>';
            }
            ?>
            <div class="image-text__content-wrapper from-right">
                <?php
                if (!empty($title)) {
                    echo '<h2 class="image-text__title">' . esc_html($title) . '</h2>';
                }

                if (!empty($content)) {
                    echo '<div class="image-text__content">' . do_shortcode($content) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
