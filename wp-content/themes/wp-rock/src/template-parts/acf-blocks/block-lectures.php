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
$logo = get_field_value($fields, 'logo');
$big_title = get_field_value($fields, 'big_title');
$title = get_field_value($fields, 'title');
$args = array(
    'post_type' => 'lectures',
    'post_status' => 'publish'
);

$query = new WP_Query($args);
?>
<div class="lectures" id="lectures">
    <div class="container lectures__container container-big-title">
        <?php
        if (!empty($logo)) {
            echo '<figure class="lectures__lectures-logo">
                    <img class="rotate-logo-anim" src="' . esc_url($logo) . '" alt="logo">
                </figure>';
        }

        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="lectures__title">' . esc_html($title) . '</h2>';
        }
        ?>

        <?php if ($query->have_posts()) : ?>
            <div class="lectures__lectures-wrapper d-flex flex-column">
                <?php while ($query->have_posts()) : $query->the_post();
                    $post_fields = get_fields(get_the_ID());
                    $post_icon = get_field_value($post_fields, 'logo');

                    if(!get_the_excerpt()) continue;
                ?>
                    <div class="lectures__lectures-item">
                        <div class="lectures__lectures-title-wrapper d-flex">
                            <?php
                            if (!empty($post_icon)) {
                                echo '<figure class="lectures__lectures-icon d-flex justify-content-center align-items-center">
                                    <img src="' . esc_html($post_icon) . '" alt="icon">
                                </figure>';
                            }

                            echo '<p class="lectures__lectures-title body-type-0 ">' . get_the_title() . '</p>';

                            ?>
                        </div>
                        <div class="lectures__lectures-info">
                            <?php
                            if (!empty(get_the_excerpt())) {
                                echo '<p class="lectures__lectures-excerpt body-type-2">' . get_the_excerpt() . '</p>';
                            }

                            if (!empty($btn_details_text)) {
                                echo '<a href="' . get_the_permalink() . '"
                                class="lectures__lectures-detaild-btn btn-with-arrow dark">
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
