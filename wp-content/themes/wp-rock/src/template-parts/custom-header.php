<?php

/**
 * Custom header template
 *
 * @package WP-rock
 */

global $global_options;

$logo = get_field_value($global_options, 'logo');
?>

<header id="site-header" class="site-header js-site-header">
    <div class="container site-header__container">
        <?php if ($logo) : ?>
            <a class="site-header__logo" href="<?php echo get_site_url(); ?>">
                <img src="<?php echo $logo; ?>" alt="header logo">
            </a>
        <?php endif; ?>

        <nav class="site-header__menu-wrapper">
            <?php
            wp_nav_menu([
                'menu' => 'Main menu',
                'echo' => true,
                'container' => false,
                'menu_class' => 'site-header__menu',
            ]);
            ?>
        </nav>

        <button class="site-header__login-btn green-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <g clip-path="url(#clip0_2087_27075)">
                    <path d="M15.364 11.636C14.3837 10.6558 13.217 9.93013 11.9439 9.49085C13.3074 8.55179 14.2031 6.9802 14.2031 5.20312C14.2031 2.33413 11.869 0 9 0C6.131 0 3.79688 2.33413 3.79688 5.20312C3.79688 6.9802 4.69262 8.55179 6.05609 9.49085C4.78308 9.93013 3.61631 10.6558 2.63605 11.636C0.936176 13.3359 0 15.596 0 18H1.40625C1.40625 13.8128 4.81279 10.4062 9 10.4062C13.1872 10.4062 16.5938 13.8128 16.5938 18H18C18 15.596 17.0638 13.3359 15.364 11.636ZM9 9C6.90641 9 5.20312 7.29675 5.20312 5.20312C5.20312 3.1095 6.90641 1.40625 9 1.40625C11.0936 1.40625 12.7969 3.1095 12.7969 5.20312C12.7969 7.29675 11.0936 9 9 9Z" fill="white" />
                </g>
                <defs>
                    <clipPath id="clip0_2087_27075">
                        <rect width="18" height="18" fill="white" />
                    </clipPath>
                </defs>
            </svg>
            <span>Вхід</span>
        </button>

        <button data-role="mobile-menu" data class="site-header__hamburger">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path d="M26.045 3.95454H3.9541V6.40909H26.045V3.95454Z" fill="#25292C" />
                <path d="M26.045 23.5909H3.9541V26.0455H26.045V23.5909Z" fill="#25292C" />
                <path d="M26.045 13.7727H3.9541V16.2273H26.045V13.7727Z" fill="#25292C" />
            </svg>
        </button>
    </div>
</header>
