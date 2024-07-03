<?php

/**
 *
 * Template name: Profile
 *
 */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}
get_header('profile');

?>


<?php
echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'programms',
    array(
        'programm_title' => 'Мої курси',
        'programm_type' => 'courses',
        'additional_class' => 'active',
    )
));

echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'programms',
    array(
        'programm_title' => 'Мої Лекції',
        'programm_type' => 'lectures',
        'additional_class' => 'lectures',
    )
));

echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'user-info'
));

echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'faq'
));

function progress_bar($blocks_passed, $blocks_count) {

    $total_width = 161;
    $block_width = 0;

    if ($blocks_count != 0) {
        $block_width = $total_width / $blocks_count;
    }

    $svg = '<svg class="progress-bar" xmlns="http://www.w3.org/2000/svg" width="161" height="5" viewBox="0 0 161 5" fill="none">
        <rect width="161" height="5" transform="matrix(1 0 0 -1 0 5)" fill="#131614" />';

    for ($i = 0; $i < $blocks_passed; $i++) {
        $x_position = $i * $block_width;
        $svg .= '<rect width="' . $block_width . '" height="5" transform="matrix(1 0 0 -1 ' . $x_position . ' 5)" fill="#53F07F" />';
    }

    $svg .= '</svg>';

    echo $svg;
}


function access_date_block($access_status, $expire_access_date, $text_access_date) {
    if (empty($access_status) || empty($expire_access_date)) return;

    $class = '';
    $text = '';

    switch ($access_status) {
        case 'access-expires':
            $class = 'access-expires';
            $text = $text_access_date[1];
            break;
        case 'access-expired':
            $class = 'access-expired';
            $text = $text_access_date[2];
            break;
        default:
            $text = $text_access_date[0];
            break;
    }

    echo '<div class="access-block ' . $class . '">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.0625 4.125V3.4375C14.0625 2.67781 14.6778 2.0625 15.4375 2.0625H16.8125C17.5722 2.0625 18.1875 2.67781 18.1875 3.4375V4.125H18.875C19.4223 4.125 19.9468 4.34225 20.3332 4.72931C20.7203 5.11569 20.9375 5.64025 20.9375 6.1875V17.875C20.9375 18.4223 20.7203 18.9468 20.3332 19.3332C19.9468 19.7203 19.4223 19.9375 18.875 19.9375C15.7675 19.9375 8.2325 19.9375 5.125 19.9375C4.57775 19.9375 4.05319 19.7203 3.66681 19.3332C3.27975 18.9468 3.0625 18.4223 3.0625 17.875V6.1875C3.0625 5.64025 3.27975 5.11569 3.66681 4.72931C4.05319 4.34225 4.57775 4.125 5.125 4.125H5.8125V3.4375C5.8125 2.67781 6.42781 2.0625 7.1875 2.0625H8.5625C9.32219 2.0625 9.9375 2.67781 9.9375 3.4375V4.125H14.0625ZM19.5625 8.9375H4.4375V17.875C4.4375 18.0572 4.50969 18.2325 4.63894 18.3611C4.7675 18.4903 4.94281 18.5625 5.125 18.5625H18.875C19.0572 18.5625 19.2325 18.4903 19.3611 18.3611C19.4903 18.2325 19.5625 18.0572 19.5625 17.875V8.9375ZM6.5 17.1875H7.875C8.2545 17.1875 8.5625 16.8795 8.5625 16.5C8.5625 16.1205 8.2545 15.8125 7.875 15.8125H6.5C6.1205 15.8125 5.8125 16.1205 5.8125 16.5C5.8125 16.8795 6.1205 17.1875 6.5 17.1875ZM11.3125 17.1875H12.6875C13.067 17.1875 13.375 16.8795 13.375 16.5C13.375 16.1205 13.067 15.8125 12.6875 15.8125H11.3125C10.933 15.8125 10.625 16.1205 10.625 16.5C10.625 16.8795 10.933 17.1875 11.3125 17.1875ZM16.125 14.4375H17.5C17.8795 14.4375 18.1875 14.1295 18.1875 13.75C18.1875 13.3705 17.8795 13.0625 17.5 13.0625H16.125C15.7455 13.0625 15.4375 13.3705 15.4375 13.75C15.4375 14.1295 15.7455 14.4375 16.125 14.4375ZM6.5 14.4375H7.875C8.2545 14.4375 8.5625 14.1295 8.5625 13.75C8.5625 13.3705 8.2545 13.0625 7.875 13.0625H6.5C6.1205 13.0625 5.8125 13.3705 5.8125 13.75C5.8125 14.1295 6.1205 14.4375 6.5 14.4375ZM11.3125 14.4375H12.6875C13.067 14.4375 13.375 14.1295 13.375 13.75C13.375 13.3705 13.067 13.0625 12.6875 13.0625H11.3125C10.933 13.0625 10.625 13.3705 10.625 13.75C10.625 14.1295 10.933 14.4375 11.3125 14.4375ZM6.5 11.6875H7.875C8.2545 11.6875 8.5625 11.3795 8.5625 11C8.5625 10.6205 8.2545 10.3125 7.875 10.3125H6.5C6.1205 10.3125 5.8125 10.6205 5.8125 11C5.8125 11.3795 6.1205 11.6875 6.5 11.6875ZM11.3125 11.6875H12.6875C13.067 11.6875 13.375 11.3795 13.375 11C13.375 10.6205 13.067 10.3125 12.6875 10.3125H11.3125C10.933 10.3125 10.625 10.6205 10.625 11C10.625 11.3795 10.933 11.6875 11.3125 11.6875ZM16.125 11.6875H17.5C17.8795 11.6875 18.1875 11.3795 18.1875 11C18.1875 10.6205 17.8795 10.3125 17.5 10.3125H16.125C15.7455 10.3125 15.4375 10.6205 15.4375 11C15.4375 11.3795 15.7455 11.6875 16.125 11.6875ZM5.8125 5.5H5.125C4.94281 5.5 4.7675 5.57219 4.63894 5.70144C4.50969 5.83 4.4375 6.00531 4.4375 6.1875V7.5625H5.8125V5.5ZM7.1875 3.4375V7.5625H8.5625V3.4375H7.1875ZM9.9375 5.5V7.5625H14.0625V5.5H9.9375ZM18.1875 7.5625H19.5625V6.1875C19.5625 6.00531 19.4903 5.83 19.3611 5.70144C19.2325 5.57219 19.0572 5.5 18.875 5.5H18.1875V7.5625ZM15.4375 3.4375V7.5625H16.8125V3.4375H15.4375Z" fill="white" />
            </svg>
            <span>' . $text . '</span><span>' . $expire_access_date . '</span>
        </div>';
}


function access_status($expire_access_date, $alert_count_days = 10) {
    $current_date = new DateTime();
    $expire_access_date = new DateTime($expire_access_date);
    $expire_access_date->modify('+2 day');

    $interval = $current_date->diff($expire_access_date);
    $days_remaining = $interval->format('%a');

    if ($current_date > $expire_access_date || $days_remaining <= 0) {
        return 'access-expired';
    } elseif ($days_remaining <= $alert_count_days) {
        return 'access-expires';
    } else {
        return 'access-valid';
    }
}


?>

<?php get_footer(); ?>
