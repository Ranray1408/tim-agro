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
$args = array(
    'post_type' => 'courses',
    'post_status' => 'publish',
    'order' => 'ASC',
);

$query = new WP_Query($args);
?>
<div class="courses js-anim-activate" id="courses">
    <div class="container courses__container container-big-title reverse">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="courses__title from-left">' . esc_html($title) . '</h2>';
        }
        ?>

        <?php if ($query->have_posts()) : ?>
            <div class="courses__courses-wrapper from-right d-flex flex-column">
                <?php while ($query->have_posts()) : $query->the_post();
                    $post_fields = get_fields(get_the_ID());
                    $post_logo = get_field_value($post_fields, 'logo');
                    $post_title = get_field_value($post_fields, 'post_title');

                    $post_title = !empty($post_title) ? $post_title : get_the_title();
                ?>
                    <div class="courses__courses-item">
                        <?php
                        if (!empty($post_logo)) {
                            echo '<figure class="courses__courses-logo">
                                    <img src="' . esc_html($post_logo) . '" alt="logo">
                                </figure>';
                        }

                        echo '<p class="courses__courses-title body-type-0 ">' . do_shortcode($post_title) . '</p>';

                        ?>

                        <div class="courses__courses-info">
                            <?php
                            if (!empty(get_the_excerpt())) {
                                echo '<p class="courses__courses-excerpt body-type-2">' . get_the_excerpt() . '</p>';
                            }

                            if (!empty($btn_details_text)) {
                                echo '<a href="' . get_the_permalink() . '"
                                class="courses__courses-detaild-btn btn-with-arrow">
                                    ' . esc_html($btn_details_text) . '
                                </a>';
                            }

                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
