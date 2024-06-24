<?php

/**
 * Block - Top page.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$background_image = get_field_value($fields, 'background_image');
$background_image_mob = get_field_value($fields, 'background_image_mob');
$title = get_field_value($fields, 'title');
$filled_subtitle = get_field_value($fields, 'filled_subtitle');
$subtitle = get_field_value($fields, 'subtitle');
$list = get_field_value($fields, 'list');
$link = get_field_value($fields, 'link');

$hide_breadcrumbs = get_field_value($fields, 'hide_breadcrumbs');

$open_popup = get_field_value($fields, 'open_popup');
$popup_activator = $open_popup ? 'js-open-popup-activator' : '';
?>

<div class="hero-banner js-anim-activate <?php echo esc_html($class_name); ?>">
    <div class="hero-banner__gradient-layer"></div>
    <img class="hero-banner__img-bg" src="<?php echo $background_image; ?>" alt="bg" />
    <div class="container hero-banner__container">
        <div class="hero-banner__content">
            <?php if (!is_front_page() && !$hide_breadcrumbs) : ?>
                <div class="breadcrumbs d-flex">
                    <?php if (function_exists('bcn_display')) {
                        bcn_display();
                    } ?>
                </div>
            <?php endif; ?>
            <?php
            if (!empty($title)) {
                echo '<h1 class="hero-banner__title from-right">' . do_shortcode($title) . '</h1>';
            }

            if (!empty($filled_subtitle)) {
                echo '<div class="hero-banner__filled-subtitle from-left green-filled-text d-inline-flex body-type-1 font-weight-400">' . do_shortcode($filled_subtitle) . '</div>';
            }

            if (!empty($subtitle)) {
                echo '<div class="hero-banner__subtitle from-right">' . do_shortcode($subtitle) . '</div>';
            }

            if (!empty($list)) {
                echo '<div class="hero-banner__list from-left d-flex flex-column">';
                foreach ($list as $list_item) {
                    if (!empty($list_item['text'])) {
                        echo '<div class="hero-banner__list-item d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                    <circle cx="28" cy="28" r="27.5" stroke="#53F07F"/>
                                    <line x1="28.2148" y1="16.9999" x2="28.2148" y2="38.4285" stroke="#53F07F"/>
                                    <line x1="38.4297" y1="28.2142" x2="17.0011" y2="28.2142" stroke="#53F07F"/>
                                </svg>
                                <p class="body-type-1 weight400">' . do_shortcode($list_item['text']) . '</p>
                            </div>';
                    }
                }
                echo '</div>';
            }

            if (!empty($link['url']) && !empty($link['title'])) {
                echo '<a href="' . esc_url($link['url']) . '" class="hero-banner__choose-studies green-transparent from-right ' . $popup_activator . '">
                            ' . esc_html($link['title']) . '
                        </a>';
            }
            ?>
        </div>
    </div>
</div>
