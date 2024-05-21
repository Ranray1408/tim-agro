<?php

/**
 * General template for 404 page
 *
 * @package WP-rock
 * @since 4.4.0
 */

get_header();
global $global_options;

$page_404_background_image = get_field_value($global_options, 'page_404_background_image');
$page_404_big_title = get_field_value($global_options, 'page_404_big_title');
$page_404_title = get_field_value($global_options, 'page_404_title');
$page_404_description = get_field_value($global_options, 'page_404_description');
$page_404_link_text = get_field_value($global_options, 'page_404_link_text');

do_action('wp_rock_before_page_content');
?>
<section class="section-404" style="background-image: url(<?php echo $page_404_background_image; ?>);">
    <div class="custom-container">
        <div class="section-404__content">

            <?php
            if (!empty($page_404_big_title)) {
                echo '<h1 class="section-404__big-title">' . do_shortcode($page_404_big_title) . '</h1>';
            }
            if (!empty($page_404_title)) {
                echo '<h2 class="section-404__title">' . do_shortcode($page_404_title) . '</h2>';
            }
            if (!empty($page_404_description)) {
                echo '<p class="section-404__description">' . do_shortcode($page_404_description) . '</p>';
            }

            if (!empty($page_404_link_text)) {
                echo '<a class="section-404__link" href="' . esc_attr(get_home_url()) . '">
                        ' . $page_404_link_text . '
                    </a>';
            }
            ?>
        </div>
    </div>
</section>

<?php do_action('wp_rock_after_page_content'); ?>
<?php wp_footer(); ?>
