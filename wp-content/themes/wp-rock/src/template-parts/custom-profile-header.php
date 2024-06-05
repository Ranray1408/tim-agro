<?php
global $global_options;

$home_text = get_field_value($global_options, 'home_text');
$logout_text = get_field_value($global_options, 'logout_text');
$logo = get_field_value($global_options, 'logo');

$svg_no_photo = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
<defs>
</defs>
<g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
	<path d="M 45 88 c -11.049 0 -21.18 -2.003 -29.021 -8.634 C 6.212 71.105 0 58.764 0 45 C 0 20.187 20.187 0 45 0 c 24.813 0 45 20.187 45 45 c 0 13.765 -6.212 26.105 -15.979 34.366 C 66.181 85.998 56.049 88 45 88 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(214,214,214); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 45 60.71 c -11.479 0 -20.818 -9.339 -20.818 -20.817 c 0 -11.479 9.339 -20.818 20.818 -20.818 c 11.479 0 20.817 9.339 20.817 20.818 C 65.817 51.371 56.479 60.71 45 60.71 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(165,164,164); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 45 90 c -10.613 0 -20.922 -3.773 -29.028 -10.625 c -0.648 -0.548 -0.88 -1.444 -0.579 -2.237 C 20.034 64.919 31.933 56.71 45 56.71 s 24.966 8.209 29.607 20.428 c 0.301 0.793 0.069 1.689 -0.579 2.237 C 65.922 86.227 55.613 90 45 90 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(165,164,164); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
</g>
</svg>';
?>
<header class="profile-header site-header d-flex align-items-center js-site-header">
    <div class="container profile-header__container d-flex align-items-center justify-content-between">
        <a href="<?php echo get_home_url(); ?>" class="profile-header__home-btn d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <g clip-path="url(#clip0_2087_35727)">
                    <path d="M19.6643 9.10436L16.6332 6.07326V2.57183C16.6332 1.93873 16.1201 1.42564 15.4861 1.42564C14.8535 1.42564 14.3405 1.93873 14.3405 2.57183V3.7806L12.0836 1.52363C10.9678 0.408402 9.02771 0.41038 7.91442 1.52565L0.335548 9.10436C-0.111849 9.55265 -0.111849 10.278 0.335548 10.7256C0.783148 11.1738 1.50992 11.1738 1.95736 10.7256L9.5355 3.14669C9.78238 2.90111 10.2175 2.90111 10.4631 3.14596L18.0425 10.7256C18.2673 10.9497 18.5603 11.0612 18.8532 11.0612C19.1468 11.0612 19.4403 10.9496 19.6643 10.7256C20.1119 10.278 20.1119 9.55269 19.6643 9.10436Z" fill="#53F07F" />
                    <path d="M10.3981 5.32243C10.1779 5.10236 9.82136 5.10236 9.60182 5.32243L2.93543 11.9868C2.83018 12.0921 2.77051 12.2357 2.77051 12.3856V17.2464C2.77051 18.387 3.69534 19.3118 4.83592 19.3118H8.13646V14.2003H11.8627V19.3118H15.1633C16.3038 19.3118 17.2287 18.387 17.2287 17.2464V12.3856C17.2287 12.2357 17.1695 12.0921 17.0637 11.9868L10.3981 5.32243Z" fill="#53F07F" />
                </g>
                <defs>
                    <clipPath id="clip0_2087_35727">
                        <rect width="20" height="20" fill="white" />
                    </clipPath>
                </defs>
            </svg>
            <span><?php echo esc_html($home_text); ?></span>
        </a>

        <?php if ($logo) : ?>
            <a class="profile-header__logo" href="<?php echo get_site_url(); ?>">
                <img src="<?php echo $logo; ?>" alt="header logo" />
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


        <?php
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $avatar = get_field('user_avatar', 'user_' . $current_user->ID);
            $nickname = $current_user->display_name;
            $logout_url = wp_logout_url();

            echo '<div class="profile-header__user-info d-flex align-items-center justify-content-center">';

            echo '<div class="profile-header__user-info-inner">';

            if ($avatar) {
                echo '<figure class="profile-header__user-avatar">
                                <img src="' . esc_url($avatar) . '" alt="User Avatar">
                            </figure>';
            } else {
                echo '<figure class="profile-header__user-avatar">
                                ' . $svg_no_photo . '
                            </figure>';
            }

            echo '<span class="profile-header__user-nikname">' . esc_html($nickname) . '</span>';

            echo '</div>';

            echo '<div class="profile-header__user-info-inner-btn">';


            echo ' <button data-role="mobile-menu" class="site-header__hamburger js-site-header-hamburger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                            <path d="M26.045 3.95454H3.9541V6.40909H26.045V3.95454Z" fill="#25292C" />
                            <path d="M26.045 23.5909H3.9541V26.0455H26.045V23.5909Z" fill="#25292C" />
                            <path d="M26.045 13.7727H3.9541V16.2273H26.045V13.7727Z" fill="#25292C" />
                        </svg>
                    </button>';

            echo '<button class="profile-header__logout-btn white-transparent" onclick="window.location.href=\'' . esc_url($logout_url) . '\'">' . esc_html($logout_text) . '</button>';

            echo '</div>';

            echo '</div>';
        }
        ?>

    </div>
</header>
