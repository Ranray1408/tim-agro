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

?>

<div class="hero-banner <?php echo esc_html($class_name); ?>">
    <img class="hero-banner__img-bg" src="<?php echo $background_image; ?>" alt="bg" />
    <img class="hero-banner__img-bg mob" src="<?php echo $background_image_mob; ?>" alt="bg" />
    <?php if (is_front_page() && is_home()) : ?>
        <div class="breadcrumbs">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="hero-banner__content">
            <?php
            if (!empty($title)) {
                echo '<h1 class="hero-banner__title">' . do_shortcode($title) . '</h1>';
            }

            if (!empty($filled_subtitle)) {
                echo '<div class="hero-banner__filled-subtitle body-type-1 font-weight-400">' . do_shortcode($filled_subtitle) . '</div>';
            }

            if (!empty($subtitle)) {
                echo '<div class="hero-banner__subtitle body-type-1">' . do_shortcode($subtitle) . '</div>';
            }

            if (!empty($list)) {
                echo '<div class="hero-banner__list d-flex flex-column">';
                foreach ($list as $list_item) {
                    if(!empty($list_item['text'])) {
                        echo '<div class="hero-banner__list-item d-flex align-items-center body-type-1 font-weight-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                    <circle cx="28" cy="28" r="27.5" stroke="#53F07F"/>
                                    <line x1="28.2148" y1="16.9999" x2="28.2148" y2="38.4285" stroke="#53F07F"/>
                                    <line x1="38.4297" y1="28.2142" x2="17.0011" y2="28.2142" stroke="#53F07F"/>
                                </svg>
                                <p>' . do_shortcode($list_item['text']) . '</p>
                            </div>';
                    }
                }
                echo '</div>';
            }

            if (!empty($link['url']) && !empty($link['title'])) {
                echo '<a href="' . esc_url($link['url']) . '" class="hero-banner__choose-studies green-transparent">
                            ' . esc_html($link['title']) . '
                        </a>';
            }
            ?>
        </div>
    </div>
</div>
