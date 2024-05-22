<?php

/**
 * Block - About person.
 *
 * @package WP-rock
 * @since   4.4.0
 */
global $global_options;

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$big_title = get_field_value($fields, 'big_title');
$photo = get_field_value($fields, 'photo');
$title = get_field_value($fields, 'title');
$repeater = get_field_value($fields, 'repeater');
$socials_title = get_field_value($fields, 'socials_title');
$socials = get_field_value($fields, 'socials');
$pupils_count = get_field_value($fields, 'pupils_count');
$pupils_title = get_field_value($fields, 'pupils_title');

$logo = get_field_value($global_options, 'logo');
?>
<div class="about-person">
    <div class="container about-person__container container-big-title">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }

        if (!empty($title)) {
            echo ' <h2 class="about-person__title mob">' . esc_html($title) . '</h2>';
        }

        if (!empty($photo)) {
            echo '<figure class="about-person__photo">
                    <img src="' . esc_url($photo) . '" alt="photo">
                    <img class="logo" src="' . esc_url($logo) . '" alt="">
                </figure>';
        }
        ?>
        <div class="about-person__content">
            <?php
            if (!empty($title)) {
                echo ' <h2 class="about-person__title">' . esc_html($title) . '</h2>';
            }
            ?>
            <?php if (!empty($repeater)) : ?>
                <div class="about-person__repeater">
                    <?php foreach ($repeater as $item) : ?>
                        <?php
                        if (!empty($item['icon']) && !empty($item['text'])) {

                            echo '<div class="about-person__repeater-item">
                                    <img class="about-person__repeater-item-icon" src="' . $item['icon'] . '" alt="icon">
                                    <p class="about-person__repeater-item-text body-type-4 weight400">
                                        ' . $item['text'] .
                                '</p>';

                            if (!empty($item['link']['url']) && !empty($item['link']['title'])) {
                                echo '<a href="' . $item['link']['url'] . '"
                                        class="about-person__repeater-item-link">
                                                    ' . $item['link']['title'] . '
                                                </a>';
                            }
                            echo '</div>';
                        }
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="about-person__bottom-wrapper">
                <?php if (!empty($socials)) : ?>
                    <div class="about-person__socials">
                        <?php
                        if (!empty($socials_title)) {
                            echo '<div class="about-person__socials-title body-type-1">
                                    ' . esc_html($socials_title) .
                                '</div>';
                        }
                        echo '<div class="about-person__socials-inner">';
                        foreach ($socials as $item) {
                            if (!empty($item['icon']) && !empty($item['link'])) {
                                echo '<a href="' . $item['link'] . '" class="about-person__social-item">
                                <img src="' . $item['icon'] . '" alt="icon">
                                </a>';
                            }
                        }
                        echo '</div>';
                        ?>
                    </div>
                <?php endif; ?>
                <div class="about-person__pupils-wrtapper">
                    <?php
                    if (!empty($pupils_count)) {
                        echo '<div class="about-person__pupils-count">' . esc_html($pupils_count) . '</div>';
                    }

                    if (!empty($pupils_title)) {
                        echo '<div class="about-person__pupils-text body-type-4 weight400">
                                ' . do_shortcode($pupils_title) .
                            '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
