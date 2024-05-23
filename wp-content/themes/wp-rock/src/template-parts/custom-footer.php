<?php

/**
 * Custom footer template
 *
 * @package WP-rock
 */

global $global_options;

$copyright = get_field_value($global_options, 'copyright');
$logo = get_field_value($global_options, 'logo');
$social_links = get_field_value($global_options, 'social_links');
?>

<footer id="site-footer" class="site-footer">
    <div class="container site-footer__container">
        <?php if ($logo) : ?>
            <a class="site-footer__logo" href="<?php echo get_site_url(); ?>">
                <img src="<?php echo $logo; ?>" alt="foter logo" />
            </a>
        <?php endif; ?>

        <div class="site-footer__menu-wrapper">
            <?php
            wp_nav_menu([
                'menu'       => 'Footer Main menu',
                'echo'       => true,
                'container'  => false,
                'menu_class' => 'site-footer__menu',
            ]);

            if (!empty($copyright)) {
                echo '<p class="site-footer__ps">' . do_shortcode($copyright) . '</p>';
            }
            ?>
        </div>

        <div class="site-footer__social-wrapper">
            <?php
            if (!empty($social_links)) {
                echo '<div class="site-footer__socials">';
                foreach ($social_links as $item) {
                    if (!empty($item['icon']) && !empty($item['link'])) {
                        echo '<a href="' . esc_url($item['link']) . '" class="site-footer__socials-item">
                                <img src="' . $item['icon'] . '" alt="icon" />
                            </a>';
                    }
                }
                echo '</div>';
            }
            ?>

            <?php
            wp_nav_menu([
                'menu'       => 'Footer menu',
                'echo'       => true,
                'container'  => false,
                'menu_class' => 'site-footer__policy-menu',
            ])
            ?>

            <?php
            if (!empty($copyright)) {
                echo '<p class="site-footer__ps body-type-6 mob">' . do_shortcode($copyright) . '</p>';
            }
            ?>
        </div>
    </div>
</footer>
