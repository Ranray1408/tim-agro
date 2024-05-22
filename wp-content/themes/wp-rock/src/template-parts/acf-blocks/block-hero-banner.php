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
$subtitle = get_field_value($fields, 'subtitle');
$link = get_field_value($fields, 'link');

?>

<div class="hero-banner <?php echo esc_html($class_name); ?>">
    <img class="hero-banner__img-bg" src="<?php echo $background_image; ?>" alt="bg" />
    <img class="hero-banner__img-bg mob" src="<?php echo $background_image_mob; ?>" alt="bg" />
    <div class="container">
        <div class="hero-banner__content">
            <?php
            if (!empty($title)) {
                echo '<h1 class="hero-banner__title">' . do_shortcode($title) . '</h1>';
            }

            if (!empty($subtitle)) {
                echo '<div class="hero-banner__subtitle body-type-1">' . do_shortcode($subtitle) . '</div>';
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
