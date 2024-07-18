<?php

$access_status = !empty($args['access_status']) ? $args['access_status'] : '';
$post_id = !empty($args['post_id']) ? $args['post_id'] : '';
$programm_data = !empty($args['programm_data']) ? $args['programm_data'] : '';
global $client;

$blocks_folder = !empty($args['blocks_folder']) ? $args['blocks_folder'] : null;

$text_block_status = array(
    __('Не пройдено', 'wp-rock'),
    __('В процесі', 'wp-rock'),
    __('Пройдено', 'wp-rock')
);

$text_next_video = __('Наступне відео', 'wp-rock');

$circle_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
<g clip-path="url(#clip0_2087_36018)">
    <path d="M18 36C13.1921 36 8.67178 34.1277 5.27206 30.7279C1.87234 27.3282 0 22.8079 0 18C0 13.1921 1.87234 8.67178 5.27206 5.27206C8.67178 1.87234 13.1921 0 18 0C22.8079 0 27.3282 1.87234 30.7279 5.27206C34.1277 8.67178 36 13.1921 36 18C36 21.553 34.9456 25.0085 32.9502 27.9926C32.5187 28.6383 31.6453 28.8116 30.9996 28.3802C30.3541 27.9484 30.1805 27.075 30.6123 26.4295C32.2971 23.9093 33.1875 20.9946 33.1875 18C33.1875 9.62567 26.3743 2.8125 18 2.8125C9.62567 2.8125 2.8125 9.62567 2.8125 18C2.8125 26.3743 9.62567 33.1875 18 33.1875C20.7776 33.1875 23.4945 32.4311 25.8566 31.0004C26.5207 30.598 27.3853 30.8103 27.788 31.4747C28.1904 32.1389 27.9781 33.0038 27.3137 33.4059C24.5121 35.103 21.2915 36 18 36ZM16.4713 24.63L23.4816 20.5686C24.4078 20.0319 24.9609 19.0717 24.9609 18C24.9609 16.9283 24.4078 15.9681 23.4814 15.4314L16.4713 11.37C15.5443 10.8331 14.4366 10.8317 13.5085 11.3667C12.5785 11.9026 12.0234 12.8642 12.0234 13.9386V22.0614C12.0234 23.1358 12.5785 24.0974 13.5085 24.6333C13.9719 24.9002 14.4794 25.0334 14.987 25.0334C15.4968 25.0337 16.0068 24.8991 16.4713 24.63ZM15.0614 13.8035L22.0715 17.8649C22.0927 17.8772 22.1484 17.9094 22.1484 18C22.1484 18.0906 22.0927 18.1228 22.0715 18.1351L15.0614 22.1965C15.0392 22.2091 14.9873 22.2393 14.9131 22.1965C14.8359 22.152 14.8359 22.0886 14.8359 22.0614V13.9386C14.8359 13.9114 14.8359 13.848 14.9131 13.8035C14.9417 13.787 14.9669 13.7815 14.9884 13.7815C15.0227 13.7812 15.0477 13.7958 15.0614 13.8035Z" fill="#53F07F" />
</g>
<defs>
    <clipPath id="clip0_2087_36018">
        <rect width="36" height="36" fill="white" />
    </clipPath>
</defs>
</svg>';

$svg_pdf = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.06973 15.441C7.06973 15.0424 6.79316 14.8047 6.30519 14.8047C6.10592 14.8047 5.97098 14.8244 5.90039 14.8432V16.122C5.98397 16.1408 6.08671 16.1471 6.22833 16.1471C6.74846 16.1471 7.06973 15.8843 7.06973 15.441Z" fill="white"/>
                <path d="M10.0907 14.8179C9.87221 14.8179 9.73099 14.8367 9.64746 14.8563V17.6885C9.73104 17.7081 9.86599 17.7081 9.98799 17.7081C10.8745 17.7144 11.4527 17.2264 11.4527 16.1924C11.4589 15.2933 10.9321 14.8179 10.0907 14.8179Z" fill="white"/>
                <path d="M15.703 0H6.06363C4.65541 0 3.50927 1.14694 3.50927 2.55436V12H3.25978C2.69141 12 2.23047 12.4604 2.23047 13.0293V19.2717C2.23047 19.8405 2.69136 20.3009 3.25978 20.3009H3.50927V21.4456C3.50927 22.8546 4.65541 24 6.06363 24H19.2161C20.6235 24 21.7698 22.8545 21.7698 21.4456V6.0455L15.703 0ZM4.93078 14.1558C5.23243 14.1049 5.65644 14.0664 6.25383 14.0664C6.85754 14.0664 7.28782 14.1817 7.57693 14.4131C7.8531 14.6313 8.03947 14.9913 8.03947 15.415C8.03947 15.8386 7.89825 16.1988 7.6413 16.4427C7.30709 16.7573 6.81284 16.8986 6.23467 16.8986C6.10599 16.8986 5.99065 16.8922 5.90046 16.8797V18.4276H4.93078V14.1558ZM19.2161 22.4356H6.06363C5.51836 22.4356 5.07434 21.9916 5.07434 21.4456V20.3009H17.3352C17.9036 20.3009 18.3645 19.8405 18.3645 19.2717V13.0293C18.3645 12.4604 17.9036 12 17.3352 12H5.07434V2.55436C5.07434 2.00989 5.51841 1.56587 6.06363 1.56587L15.1178 1.55641V4.90314C15.1178 5.88068 15.9109 6.67459 16.8892 6.67459L20.1677 6.66518L20.2046 21.4455C20.2046 21.9916 19.7614 22.4356 19.2161 22.4356ZM8.66473 18.408V14.1558C9.02438 14.0986 9.49314 14.0664 9.98783 14.0664C10.81 14.0664 11.3431 14.2139 11.7608 14.5285C12.2104 14.8627 12.4928 15.3954 12.4928 16.1603C12.4928 16.9887 12.1911 17.5607 11.7733 17.9136C11.3175 18.2925 10.6236 18.4722 9.77598 18.4722C9.26834 18.4722 8.90869 18.4401 8.66473 18.408ZM15.6748 15.8905V16.6867H14.1203V18.4276H13.1376V14.0986H15.7838V14.9011H14.1203V15.8905H15.6748Z" fill="white"/>
            </svg>';

if (!empty($blocks_folder) && $access_status !== 'access-expired') : ?>
    <div class="programm__content js-wrock-accordion__content js-inner-accordion">
        <?php
        foreach ($blocks_folder as $block) :
            $videos = [];

            if (!empty($block['videos'])) {
                foreach ($block['videos'] as $video) {
                    $response = $client->request('/videos/' . $video['video_id'], [], 'GET');
                    $videos[] = array(
                        'video_id' => $video['video_id'],
                        'name' => $response['body']['name'],
                        'files' => $video['files'],
                        'duration' => $response['body']['duration'],
                    );
                }
            }

            if (empty($videos)) continue;

            $block_title = $block['block_title'];
            $sanitize_block_title = sanitize_title($block_title);

            $block_status = 'not-passed';
            $passed_blocks_count = 0;

            //Data information about block that saved in user fields
            $saved_block_data = $programm_data->blocks->{$sanitize_block_title} ?? '';

            if (!empty($programm_data->blocksPassed)) {
                $passed_blocks_count = $programm_data->blocksPassed;
            }

            // Get current block from user fields info
            if (!empty($saved_block_data)) {
                $block_status = $saved_block_data->blockStatus;
            }

            // Forming ids
            $video_container_id = 'programm-' . $post_id . '_' . $sanitize_block_title;

            //Check if we have saved video data
            $saved_video_data = null;

            // Default value for playing first video in list
            $first_video = !empty($videos[0]) ? $videos[0] : null;
            $first_video_title = !empty($first_video['name']) ? do_shortcode($first_video['name']) : '...';
        ?>
            <div data-block_id="<?php echo $video_container_id; ?>" data-block_status="<?php echo $block_status ?>" class="programm__block js-programm-block js-inner-accordion__content">

                <button class="programm__block-btn js-inner-accordion__btn">
                    <?php
                    if (!empty($block_title)) {
                        echo '<span class="body-type-0">
                        ' . esc_html($block_title) . '
                        </span>';
                    }

                    // Block status
                    echo '<span class="block-status js-block-status ' . $block_status . ' body-type-4 weight600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
                                    <circle cx="3.5" cy="3.5" r="3.5" fill="white"/>
                                </svg>
                                <span class="not-passed text">' . $text_block_status[0] . '</span>
                                <span class="in-progress text">' . $text_block_status[1] . '</span>
                                <span class="passed text">' . $text_block_status[2] . '</span>
                            </span>';
                    ?>
                </button>
                <div class="programm__block-content js-inner-accordion__content">
                    <div class="programm__block-video-wrapper js-block-video">

                        <?php
                        if ($first_video) {
                            echo '<div data-video_id="' . $first_video['video_id'] . '"
                                    data-video_container_id="' . $video_container_id . '"></div>';
                        }

                        ?>

                        <div class="programm__block-video-description">
                            <div class="video-name js-video-title body-type-2 weight600">
                                <?php echo $first_video_title; ?>
                            </div>
                            <?php if (!empty($first_video['files'])) {
                                echo '<div class="video-embed-files js-video-embed-files">';
                                foreach ($first_video['files'] as $file) {
                                    echo '<a target="_blank" class="green-transparent" href="' . $file['file'] . '">
                                            ' . $svg_pdf . '
                                            <span>' . $file['file_name'] . '</span>
                                        </a>';
                                }
                                echo '</div>';
                            } ?>
                            <button class="next-video-btn js-next-video-btn">
                                <?php echo $text_next_video; ?>
                            </button>
                        </div>
                    </div>
                    <!-- ************ Video list ************ -->
                    <?php
                    if (!empty($videos)) :
                        echo '<div class="programm__block-videos-list">';
                        foreach ($videos as $key => $video) :

                            $video_title = !empty($video['name']) ? $video['name'] : '';
                            $video_duration = !empty($video['duration']) ? $video['duration'] : '';

                            $active_class = $key === 0 ? 'playing-video' : '';

                            $video_id = $video['video_id'];
                            $save_video_data = $saved_block_data->videos->{$video_id} ?? '';
                            $video_pause_time = $save_video_data->videoPauseTime ?? 0;
                            $video_is_viewed = $save_video_data->isVideoViewed ?? '';

                            echo '<button
                                        id="video-btn-' . $video_id . '"
                                        data-video_files="' . htmlspecialchars(json_encode($video['files'])) . '"
                                        data-passed_blocks_count="' . $passed_blocks_count . '"
                                        data-video_container_id="' . $video_container_id . '"
                                        data-video_viewed="' . $video_is_viewed . '"
                                        data-video_id="' . $video_id . '"
                                        data-video_title="' . htmlspecialchars($video_title) . '"
                                        data-video_pause_time="' . $video_pause_time . '"
                                        class="programm__block-video-btn js-play-video-btn body-type-4 weight600 ' . $active_class . '">';

                            if (!empty($video_title)) {
                                echo '<span>' . esc_html($video_title) . '</span>';
                            }

                            echo $circle_svg;
                            echo '</button>';
                        endforeach;
                        echo '</div>';
                    endif; ?>
                    <!-- ************ END Video list ************ -->
                </div>
            </div>
        <?php endforeach;
        ?>
    </div>
<?php endif; ?>
