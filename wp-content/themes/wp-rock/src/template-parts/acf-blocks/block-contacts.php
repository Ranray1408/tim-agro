<?php

/**
 * Block - Courses.
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
$subtitle = get_field_value($fields, 'subtitle');
$socials = get_field_value($fields, 'socials');
$form = get_field_value($fields, 'form');
$photo = get_field_value($fields, 'photo');
?>
<div class="contacts js-anim-activate" id="contacts">
    <div class="container contacts__container d-flex container-big-title">
        <div class="contacts__form-wrapper from-left">
                <?php
                if (!empty($big_title)) {
                    echo '<div class="big-title">' . esc_html($big_title) . '</div>';
                }

                if (!empty($title)) {
                    echo ' <h2 class="contacts__title">' . esc_html($title) . '</h2>';
                }

                echo '<div class="contacts__subtitle-wrapper d-flex">';

                if (!empty($subtitle)) {
                    echo ' <p class="contacts__subtitle body-type-1">' . esc_html($subtitle) . '</p>';
                }

                if (!empty($photo)) {
                    echo ' <figure class="contacts__photo mob">
                            <img src="' . esc_url($photo) . '" alt="photo">
                        </figure>';
                }
                echo '</div>';

                if (!empty($socials)) {
                    echo '<div class="contacts__socials d-flex align-item-center">';
                    foreach ($socials as $item) {
                        if (!empty($item['icon']) && !empty($item['url'])) {
                            echo '<a href="' . esc_url($item['url']) . '" class="contacts__socials-item">
                                        <img src="' . esc_url($item['icon']) . '" alt="">
                                    </a>';
                        }
                    }
                    echo '</div>';
                }

                ?>

            <?php
            if (!empty($form)) {
                echo '<div class="contacts__form">' . do_shortcode($form) . '</div>';
            }
            ?>
        </div>
        <?php
        if (!empty($photo)) {
            echo '<figure class="contacts__photo from-right">
                    <img src="' . esc_url($photo) . '" alt="photo">
                </figure>';
        }
        ?>
    </div>
</div>
