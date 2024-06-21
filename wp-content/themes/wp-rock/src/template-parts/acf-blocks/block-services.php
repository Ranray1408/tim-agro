<?php

/**
 * Block - Services.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;

$btn_details_text = get_field_value($global_options, 'btn_details_text');

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$background_image = get_field_value($fields, 'background_image');
$big_title = get_field_value($fields, 'big_title');
$title = get_field_value($fields, 'title');

$services = get_field_value($fields, 'services');
?>
<div class="services js-anim-activate" id="services">
    <?php
    if (!empty($background_image)) {
        echo '<img class="services__bg-image" src="' . esc_url($background_image) . '" alt="bg">';
    }
    ?>
    <div class="container services__container container-big-title reverse">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="services__title from-right">' . esc_html($title) . '</h2>';
        }
        ?>

        <?php if (!empty($services)) :  ?>
            <div class="services__inner from-left">
                <?php foreach ($services as $service_id) :
                    $servies_fields = get_fields($service_id);
                    $title = get_the_title($service_id);
                    $excerpt = get_the_excerpt($service_id);
                    $link = get_the_permalink($service_id);
                    $icon = get_field_value($servies_fields, 'icon');
                ?>
                    <div class="services__item d-flex flex-column align-items-start">
                        <?php
                        if (!empty($icon)) {
                            echo '<figure class="services__icon">
                                        <img src="' . esc_url($icon) . '" alt="icon">
                                    </figure>';
                        }

                        echo '<p class="services__item-title body-type-0">' . esc_html($title) . '</p>';

                        if (!empty($excerpt)) {
                            echo '<p class="services__excerpt body-type-2">' . esc_html($excerpt) . '</p>';
                        }
                        ?>
                        <?php
                        if (!empty($btn_details_text)) {
                            echo '<a href="' . $link . '"
                            class="services__services-detaild-btn btn-with-arrow">
                                ' . esc_html($btn_details_text) . '
                            </a>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
