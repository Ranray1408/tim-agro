<?php

/**
 * Block - Reviews.
 *
 * @package WP-rock
 * @since   4.4.0
 */

$class_name = isset($args['className']) ? ' ' . $args['className'] : '';
$fields = get_fields();
$big_title = get_field_value($fields, 'big_title');
$title = get_field_value($fields, 'title');
$slides = get_field_value($fields, 'slides');
?>
<div class="reviews">
    <div class="container reviews__container container-big-title reverse">
        <?php
        if (!empty($big_title)) {
            echo '<div class="big-title">' . esc_html($big_title) . '</div>';
        }
        ?>

        <div class="reviews__slider js-reviews-slider swiper">
            <?php
            if (!empty($title)) {
                echo ' <h2 class="reviews__title">' . esc_html($title) . '</h2>';
            }
            ?>
            <?php if (!empty($slides)) : ?>

                <div class="swiper-wrapper">
                    <?php
                    foreach ($slides as $slide) {
                        if (!empty($slide['image'])) {
                            echo '<div class="swiper-slide">
                                        <img src="' . esc_url($slide['image']) . '" alt="slide">
                                    </div>';
                        }
                    }
                    ?>
                </div>
                <div class="reviews-buton-wrapper d-inline-flex align-items-center justify-content-center">
                    <div class="reviews-button-prev">
                        <svg width="20" height="32" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8467 3.14704L6.60186 15.845L19.8467 28.5457L16.1989 31.3804L-0.000100303 15.845L16.1989 0.312416L19.8467 3.14704Z" fill="#53F07F" />
                        </svg>
                    </div>
                    <div class="reviews-button-next">
                        <svg width="20" height="32" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00292826 3.14704L13.2477 15.845L0.00292954 28.5457L3.65069 31.3804L19.8497 15.845L3.65069 0.312416L0.00292826 3.14704Z" fill="#53F07F" />
                        </svg>
                    </div>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
        </div>
    </div>
<?php endif; ?>
</div>
</div>
