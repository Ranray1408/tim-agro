<?php

/**
 *
 * Template name: Profile
 *
 */
get_header('profile');
?>

<?php
echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'programms',
    array(
        'programm_title' => 'Мої курси',
        'programm_type' => 'courses',
        'active_class' => 'active'
    )
));

echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'programms',
    array(
        'programm_title' => 'Мої Лекції',
        'programm_type' => 'lectures',
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


function video_list_html_sctructure($videos, $video_container_id, $post_id, $block_index) {
    if (!empty($videos)) :
        echo '<div class="programm__block-videos-list">';
        foreach ($videos as $key => $video) :
            $video_title = !empty($video['video_title']) ? $video['video_title'] : '';
            $video_url = !empty($video['video']['url']) ? $video['video']['url'] : '#';
            $video_id = !empty($video['video']['ID']) ? $video['video']['ID'] : '#';

            $active_class = $key === 0 ? 'playing-video' : '';

            $full_video_id = 'programm-' . $post_id  . '_block-' . $block_index . '_' . 'video-' . $video_id;

            echo '<button
                    data-video_container_id="' . $video_container_id . '"
                    data-video_url="' . $video_url . '"
                    data-video_id="' . $full_video_id . '"
                    data-video_title="' . $video_title . '"
                    data-video_duration="0"
                    data-video_stop_time="0"
                    class="programm__block-video-btn js-play-video-btn body-type-4 weight600 ' . $active_class . '">';

            if (!empty($video_title)) {
                echo '<span>' . esc_html($video_title) . '</span>';
            }

            echo '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                        <g clip-path="url(#clip0_2087_36018)">
                            <path d="M18 36C13.1921 36 8.67178 34.1277 5.27206 30.7279C1.87234 27.3282 0 22.8079 0 18C0 13.1921 1.87234 8.67178 5.27206 5.27206C8.67178 1.87234 13.1921 0 18 0C22.8079 0 27.3282 1.87234 30.7279 5.27206C34.1277 8.67178 36 13.1921 36 18C36 21.553 34.9456 25.0085 32.9502 27.9926C32.5187 28.6383 31.6453 28.8116 30.9996 28.3802C30.3541 27.9484 30.1805 27.075 30.6123 26.4295C32.2971 23.9093 33.1875 20.9946 33.1875 18C33.1875 9.62567 26.3743 2.8125 18 2.8125C9.62567 2.8125 2.8125 9.62567 2.8125 18C2.8125 26.3743 9.62567 33.1875 18 33.1875C20.7776 33.1875 23.4945 32.4311 25.8566 31.0004C26.5207 30.598 27.3853 30.8103 27.788 31.4747C28.1904 32.1389 27.9781 33.0038 27.3137 33.4059C24.5121 35.103 21.2915 36 18 36ZM16.4713 24.63L23.4816 20.5686C24.4078 20.0319 24.9609 19.0717 24.9609 18C24.9609 16.9283 24.4078 15.9681 23.4814 15.4314L16.4713 11.37C15.5443 10.8331 14.4366 10.8317 13.5085 11.3667C12.5785 11.9026 12.0234 12.8642 12.0234 13.9386V22.0614C12.0234 23.1358 12.5785 24.0974 13.5085 24.6333C13.9719 24.9002 14.4794 25.0334 14.987 25.0334C15.4968 25.0337 16.0068 24.8991 16.4713 24.63ZM15.0614 13.8035L22.0715 17.8649C22.0927 17.8772 22.1484 17.9094 22.1484 18C22.1484 18.0906 22.0927 18.1228 22.0715 18.1351L15.0614 22.1965C15.0392 22.2091 14.9873 22.2393 14.9131 22.1965C14.8359 22.152 14.8359 22.0886 14.8359 22.0614V13.9386C14.8359 13.9114 14.8359 13.848 14.9131 13.8035C14.9417 13.787 14.9669 13.7815 14.9884 13.7815C15.0227 13.7812 15.0477 13.7958 15.0614 13.8035Z" fill="#53F07F" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2087_36018">
                                <rect width="36" height="36" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>';
            echo '</button>';
        endforeach;
        echo '</div>';
    endif;
}

function block_status_text($status, $text_block_status) {
    $text = '';

    switch ($status) {
        case 'not-passed':
            $text = $text_block_status[0];
            break;
        case 'in-progress':
            $text = $text_block_status[1];
            break;
        case 'passed':
            $text = $text_block_status[2];
            break;
        default:
            $text = $text_block_status[0];
            $status = 'not-passed';
    }

    return $text;
}


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
