<?php

global $global_options;

$fields = get_fields();

$programm_title = !empty($args['programm_title']) ? $args['programm_title'] : '';
$programm_type = !empty($args['programm_type']) ? $args['programm_type'] : '';
$active_class = !empty($args['active_class']) ? $args['active_class'] : '';

//Texts
$main_title = __($programm_title, 'wp-rock');

$text_done = __('Виконано', 'wp-rock');
$text_from = __('з', 'wp-rock');
$text_blocks = __('блоків', 'wp-rock');

$text_continue_access = __('Продовжити доступ ', 'wp-rock');
$text_access_date = array(
    __('Доступ до', 'wp-rock'),
    __('Доступ закінчується', 'wp-rock'),
    __('Доступ закінчився', 'wp-rock'),
);

$text_next_video = __('Наступне відео', 'wp-rock');

$text_block_status = array(
    __('Не пройдено', 'wp-rock'),
    __('В процесі', 'wp-rock'),
    __('Пройдено', 'wp-rock')
);

$current_user = wp_get_current_user();

$user_fields = get_fields('user_' . $current_user->ID);
$learning_material = get_field_value($user_fields, $programm_type);
?>

<div id="<?php echo $programm_type; ?>"
    class="profile__panel js-programm js-tab-panel <?php echo $active_class; ?>"
    data-programm_type="<?php echo $programm_type; ?>"
    data-user_id="<?php echo $current_user->ID; ?>">

    <div class="programm">
        <div class="container">
            <h2 class="programm__title"><?php echo $main_title; ?></h2>
            <!-- ***************** Main accrodiont ***************** -->
            <?php if (!empty($learning_material)) : ?>
                <div class="programm__accordion js-wrock-accordion">
                    <?php
                    foreach ($learning_material as $item) :
                        if (empty($item['post_id'])) continue;

                        $post_id = $item['post_id'];
                        $post_fields = get_fields($post_id);
                        $post_logo = get_field_value($post_fields, 'logo');
                        $post_title = get_the_title($post_id);
                        $expire_access_date = !empty($item['expire_access_date']) ? $item['expire_access_date'] : '';

                        $programm = get_field_value($post_fields, 'programm');
                        $blocks_count = is_array($programm) ? count($programm) : 0;

                        $access_status = access_status($expire_access_date);

                        $programm_data = json_decode($item['programm_data']);

                        $passed_blocks_count = 0;
                        $programm_blocks = null;

                        if (!empty($programm_data->blocksPassed)) {
                            $passed_blocks_count = (int)$programm_data->blocksPassed;
                        }

                        if (!empty($programm_data->blocks)) {
                            $programm_blocks = $programm_data->blocks;
                        }
                    ?>
                        <div class="programm__accordion-item js-wrock-accordion__item">
                            <button class="programm__accordion-btn js-wrock-accordion__btn">
                                <?php
                                if (!empty($post_logo)) {
                                    echo '<img class="programm__accordion-logo"
                                    src="' . esc_url($post_logo) . '" alt="logo">';
                                }
                                ?>
                                <div class="info-wrapper">
                                    <p class="btn-title body-type-2 weight600">
                                        <?php echo $post_title; ?>
                                    </p>

                                    <div class="progress-wrapper">
                                        <div class="progress-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.65603 17.102C5.89261 16.6932 6.33491 16.4179 6.84091 16.4179C7.34691 16.4179 7.78904 16.6932 8.02579 17.102H19.8383C20.2159 17.102 20.5224 17.4086 20.5224 17.786C20.5224 18.1636 20.2159 18.4701 19.8383 18.4701H8.02579C7.78904 18.8789 7.34691 19.1543 6.84091 19.1543C6.33491 19.1543 5.89261 18.8789 5.65603 18.4701H2.0523C1.67469 18.4701 1.36816 18.1636 1.36816 17.786C1.36816 17.4086 1.67469 17.102 2.0523 17.102H5.65603ZM20.5224 4.7886C20.5224 3.6551 19.6037 2.73636 18.4702 2.73636C15.1572 2.73636 6.73354 2.73636 3.4204 2.73636C2.28707 2.73636 1.36816 3.6551 1.36816 4.7886V12.9976C1.36816 14.1309 2.28707 15.0498 3.4204 15.0498H18.4702C19.6037 15.0498 20.5224 14.1309 20.5224 12.9976V4.7886ZM19.1543 4.7886C19.1543 4.41066 18.848 4.10446 18.4702 4.10446C15.1572 4.10446 6.73354 4.10446 3.4204 4.10446C3.04263 4.10446 2.73643 4.41066 2.73643 4.7886V12.9976C2.73643 13.3753 3.04263 13.6815 3.4204 13.6815H18.4702C18.848 13.6815 19.1543 13.3753 19.1543 12.9976V4.7886ZM12.7239 9.44028C12.8962 9.31108 12.9976 9.10831 12.9976 8.89307C12.9976 8.67767 12.8962 8.47491 12.7239 8.3457L9.98757 6.29346C9.78037 6.13799 9.50307 6.11303 9.27125 6.22894C9.03943 6.34485 8.89315 6.5816 8.89315 6.84084V10.9453C8.89315 11.2044 9.03943 11.4413 9.27125 11.557C9.50307 11.673 9.78037 11.648 9.98757 11.4925L12.7239 9.44028ZM10.2613 9.57704L11.1734 8.89307L10.2613 8.20894V9.57704Z" fill="white" />
                                            </svg>
                                            <?php
                                            echo $text_done .
                                                '<span class="current">' . $passed_blocks_count . '</span>
                                        ' . $text_from . '
                                        <span class="all-programm-blocks">
                                            ' . $blocks_count . '
                                        </span>' . $text_blocks;
                                            ?>
                                        </div>
                                        <?php
                                        // Progress bar line
                                        progress_bar($passed_blocks_count, $blocks_count);

                                        // Access date block
                                        access_date_block($access_status, $expire_access_date, $text_access_date);
                                        ?>
                                    </div>
                                </div>

                                <?php if ($access_status === 'access-expired') {
                                    echo '<a class="continue-access-btn green-transparent" href="">
                                            ' . $text_continue_access . '
                                        </a>';
                                } ?>

                                <svg class="arrow-icon" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="37.5" cy="37.5" r="36.5" stroke="#53F07F" stroke-width="2" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9998 35.3334L38.0032 40.7314L32.0052 35.3334L30.6665 36.82L38.0032 43.422L45.3385 36.82L43.9998 35.3334Z" fill="white" />
                                </svg>
                            </button>
                            <!-- ***************** Blocks accrodiont ***************** -->
                            <?php if (!empty($programm) && $access_status !== 'access-expired') : ?>
                                <div class="programm__content js-wrock-accordion__content js-inner-accordion">
                                    <?php
                                    foreach ($programm as $key => $block) :
                                        $block_index = $key + 1;
                                        $block_title = $block['block_title'];
                                        $videos = $block['videos'];

                                        $block_status = 'not-passed';

                                        // Get current block from user fields info
                                        if (!empty($programm_data->blocks->{'block-' . $block_index})) {
                                            $block = $programm_data->blocks->{'block-' . $block_index};
                                            $block_status = $block->blockStatus;
                                        }

                                        // Forming ids
                                        $video_container_id = 'programm-' . $post_id . '_' . 'block-' . $block_index . '-container';
                                        $full_video_id = 'programm-' . $post_id  . '_block-' . $block_index . '_' . 'video-';

                                        // Default value
                                        $first_video_url = '#';
                                        $first_video_id = '#';
                                        $first_video_title = '...';
                                        $start_video_from = '#t=4';

                                        if (
                                            !empty($videos[0]['video']['url']) &&
                                            !empty($videos[0]['video']['ID']) &&
                                            !empty($videos[0]['video_title'])
                                        ) {
                                            $first_video_url = $videos[0]['video']['url'];
                                            $first_video_id = $full_video_id . $videos[0]['video']['ID'];
                                            $first_video_title = $videos[0]['video_title'];
                                        }

                                        // Retunr text depend on block status
                                        $block_status_text = block_status_text($block_status, $text_block_status);
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
                                                echo '<span class="block-status ' . $block_status . ' body-type-4 weight600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
                                                <circle cx="3.5" cy="3.5" r="3.5" fill="white"/>
                                            </svg>
                                            ' . $block_status_text . '
                                        </span>';
                                                ?>
                                            </button>
                                            <div class="programm__block-content js-inner-accordion__content">
                                                <div id="<?php echo $video_container_id; ?>" class="programm__block-video">
                                                    <video
                                                    data-video_id="<?php echo $first_video_id; ?>" controls
                                                    src="<?php echo $first_video_url .$start_video_from ?>"></video>

                                                    <div class="video-name js-video-title body-type-2 weight600">
                                                        <?php echo $first_video_title; ?>
                                                    </div>
                                                    <button class="next-video-btn js-next-video-btn">
                                                        <?php echo $text_next_video; ?>
                                                    </button>
                                                </div>
                                                <!-- ************ Video list ************ -->
                                                <?php video_list_html_sctructure($videos, $video_container_id, $post_id, $block_index); ?>
                                                <!-- ************ END Video list ************ -->
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <!-- ***************** END Blocks accrodiont ***************** -->
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- ***************** END Main accrodiont ***************** -->
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
