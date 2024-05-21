<?php

/**
 * Custom footer template
 *
 * @package WP-rock
 */

global $global_options;

$copyright = get_field_value($global_options, 'copyright');
$logo = get_field_value($global_options, 'logo');
?>

<footer id="site-footer" class="site-footer">
    <div class="site-footer__container">
        <div class="site-footer__left">
            <a class="site-footer__logo" href="<?php echo get_site_url(); ?>">
                <?php if ($logo) : ?>
                    <img src="<?php echo $logo; ?>" alt="foter logo">
                <?php endif; ?>
            </a>
        </div>
        <?php
        wp_nav_menu([
            'menu'  => 'Footer menu',
            'echo'            => true,
            'container'       => false,
            'container_class' => 'site-footer__menu',
        ])
        ?>
    </div>
</footer>


