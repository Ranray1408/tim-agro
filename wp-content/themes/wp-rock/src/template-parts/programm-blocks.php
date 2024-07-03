<?php

$access_status = !empty($args['access_status']) ? $args['access_status'] : '';
$post_id = !empty($args['post_id']) ? $args['post_id'] : '';
$programm_data = !empty($args['programm_data']) ? $args['programm_data'] : '';
global $client;

$blocks_folder = !empty($args['blocks_folder']['body']['data']) ? $args['blocks_folder']['body']['data'] : null;

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

if (!empty($blocks_folder) && $access_status !== 'access-expired') : ?>
    <div class="programm__content js-wrock-accordion__content js-inner-accordion">
        <?php
        foreach ($blocks_folder as $block) :
            if ($block['type'] === 'video') continue;
            $videos = $client->request($block['folder']['uri'] . '/videos',  array(), 'GET');
            if (empty($videos['body']['data'])) continue;

            $block_title = $block['folder']['name'];
            $sanitize_block_title = sanitize_title($block['folder']['name']);

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
            $first_video = !empty($videos['body']['data'][0]) ? $videos['body']['data'][0] : null;
            $first_video_url = '#';
            $first_video_id = '#';
            $first_video_title = '...';

            if (
                !empty($first_video['player_embed_url']) &&
                !empty($first_video['name'])
            ) {
                $first_video_url = $first_video['player_embed_url'];
                $first_video_title = $first_video['name'];
            }

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
                            $video_clear_id = str_replace('/videos/', '', $first_video['uri']);
                            echo '<div
                                    data-video_id="' . $video_clear_id . '"
                                    data-video_container_id="' . $video_container_id . '"></div>';
                        }

                        ?>

                        <div class="programm__block-video-description">
                            <div class="video-name js-video-title body-type-2 weight600">
                                <?php echo $first_video_title; ?>
                            </div>
                            <button class="next-video-btn js-next-video-btn">
                                <?php echo $text_next_video; ?>
                            </button>
                        </div>
                    </div>
                    <!-- ************ Video list ************ -->
                    <?php
                    if (!empty($videos['body']['data'])) :
                        echo '<div class="programm__block-videos-list">';
                        foreach ($videos['body']['data'] as $key => $video) :
                            $video_title = !empty($video['name']) ? $video['name'] : '';
                            $video_duration = !empty($video['duration']) ? $video['duration'] : '';

                            $active_class = $key === 0 ? 'playing-video' : '';

                            $clear_video_id = str_replace('/videos/', '', $video['uri']);

                            $save_video_data = $saved_block_data->videos->{$clear_video_id} ?? '';
                            $video_pause_time = $save_video_data->videoPauseTime ?? 0;
                            $video_is_viewed = $save_video_data->isVideoViewed ?? '';

                            echo '<button
                                        id="video-btn-' . $clear_video_id . '"
                                        data-passed_blocks_count="' . $passed_blocks_count . '"
                                        data-video_container_id="' . $video_container_id . '"
                                        data-video_viewed="' . $video_is_viewed . '"
                                        data-video_id="' . $clear_video_id . '"
                                        data-video_title="' . $video_title . '"
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
