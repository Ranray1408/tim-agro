<?php
global $global_options;

$home_text = get_field_value($global_options, 'home_text');
$logout_text = get_field_value($global_options, 'logout_text');
?>
<header class="profile-header d-flex align-items-center">
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

        <?php
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $avatar = get_avatar_url($current_user->ID);
            $nickname = $current_user->user_nicename;
            $logout_url = wp_logout_url();
            echo '<div class="profile-header__user-info d-flex align-items-center justify-content-center">';

            echo '<figure class="profile-header__user-avatar">
                                <img src="' . esc_url($avatar) . '" alt="User Avatar">
                            </figure>';

            echo '<span class="profile-header__user-nikname">' . esc_html($nickname) . '</span>';

            echo '<button class="profile-header__logout-btn white-transparent" onclick="window.location.href=\'' . esc_url($logout_url) . '\'">' . esc_html($logout_text) . '</button>';
            echo '</div>';
        }
        ?>

    </div>
</header>
