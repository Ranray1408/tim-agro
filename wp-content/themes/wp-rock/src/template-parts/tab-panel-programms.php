<?php

global $global_options;

$fields = get_fields();

$programm_title = !empty($args['programm_title']) ? $args['programm_title'] : '';
$programm_type = !empty($args['programm_type']) ? $args['programm_type'] : '';
$additional_class = !empty($args['additional_class']) ? $args['additional_class'] : '';

$monitor_svg = ' <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.65603 17.102C5.89261 16.6932 6.33491 16.4179 6.84091 16.4179C7.34691 16.4179 7.78904 16.6932 8.02579 17.102H19.8383C20.2159 17.102 20.5224 17.4086 20.5224 17.786C20.5224 18.1636 20.2159 18.4701 19.8383 18.4701H8.02579C7.78904 18.8789 7.34691 19.1543 6.84091 19.1543C6.33491 19.1543 5.89261 18.8789 5.65603 18.4701H2.0523C1.67469 18.4701 1.36816 18.1636 1.36816 17.786C1.36816 17.4086 1.67469 17.102 2.0523 17.102H5.65603ZM20.5224 4.7886C20.5224 3.6551 19.6037 2.73636 18.4702 2.73636C15.1572 2.73636 6.73354 2.73636 3.4204 2.73636C2.28707 2.73636 1.36816 3.6551 1.36816 4.7886V12.9976C1.36816 14.1309 2.28707 15.0498 3.4204 15.0498H18.4702C19.6037 15.0498 20.5224 14.1309 20.5224 12.9976V4.7886ZM19.1543 4.7886C19.1543 4.41066 18.848 4.10446 18.4702 4.10446C15.1572 4.10446 6.73354 4.10446 3.4204 4.10446C3.04263 4.10446 2.73643 4.41066 2.73643 4.7886V12.9976C2.73643 13.3753 3.04263 13.6815 3.4204 13.6815H18.4702C18.848 13.6815 19.1543 13.3753 19.1543 12.9976V4.7886ZM12.7239 9.44028C12.8962 9.31108 12.9976 9.10831 12.9976 8.89307C12.9976 8.67767 12.8962 8.47491 12.7239 8.3457L9.98757 6.29346C9.78037 6.13799 9.50307 6.11303 9.27125 6.22894C9.03943 6.34485 8.89315 6.5816 8.89315 6.84084V10.9453C8.89315 11.2044 9.03943 11.4413 9.27125 11.557C9.50307 11.673 9.78037 11.648 9.98757 11.4925L12.7239 9.44028ZM10.2613 9.57704L11.1734 8.89307L10.2613 8.20894V9.57704Z" fill="white" />
</svg>';

$eye_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M23.8475 11.5332C23.6331 11.2399 18.5245 4.35165 11.9999 4.35165C5.47529 4.35165 0.366469 11.2399 0.152297 11.5329C-0.0507657 11.8112 -0.0507657 12.1886 0.152297 12.4668C0.366469 12.7601 5.47529 19.6484 11.9999 19.6484C18.5245 19.6484 23.6331 12.7601 23.8475 12.467C24.0508 12.1888 24.0508 11.8112 23.8475 11.5332ZM11.9999 18.0659C7.19383 18.0659 3.03127 13.4941 1.79907 11.9995C3.02968 10.5035 7.18351 5.93406 11.9999 5.93406C16.8057 5.93406 20.968 10.5051 22.2007 12.0005C20.9701 13.4964 16.8163 18.0659 11.9999 18.0659Z" fill="#151A1D"/>
<path d="M11.9997 7.25275C9.38212 7.25275 7.25244 9.38242 7.25244 12C7.25244 14.6176 9.38212 16.7473 11.9997 16.7473C14.6173 16.7473 16.747 14.6176 16.747 12C16.747 9.38242 14.6173 7.25275 11.9997 7.25275ZM11.9997 15.1648C10.2546 15.1648 8.8349 13.7451 8.8349 12C8.8349 10.2549 10.2546 8.8352 11.9997 8.8352C13.7448 8.8352 15.1645 10.2549 15.1645 12C15.1645 13.7451 13.7449 15.1648 11.9997 15.1648Z" fill="#151A1D"/>
</svg>';

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

$current_user = wp_get_current_user();

$user_fields = get_fields('user_' . $current_user->ID);
$learning_material = get_field_value($user_fields, $programm_type);
?>

<div id="<?php echo $programm_type; ?>" class="profile__panel js-programm js-tab-panel <?php echo $additional_class; ?>" data-programm_type="<?php echo $programm_type; ?>" data-user_id="<?php echo $current_user->ID; ?>">

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
                        <div class="programm__accordion-item js-wrock-accordion__item"
                            data-programm_id="<?php echo 'programm-' . $post_id; ?>">
                            <button class="programm__accordion-btn js-wrock-accordion__btn">
                                <?php
                                if (!empty($post_logo)) {
                                    echo '<img class="programm__accordion-logo"
                                    src="' . esc_url($post_logo) . '" alt="logo">';
                                }
                                ?>
                                <div class="programm__accordion-btn-info-wrapper">
                                    <p class="btn-title body-type-2 weight600">
                                        <?php echo $post_title; ?>
                                    </p>

                                    <div class="progress-info">
                                        <div class="progress-wrapper">
                                            <div class="progress-info-inner d-flex align-items-center">
                                                <div class="passed-block body-type-5 weight600">
                                                    <?php
                                                    if (str_contains($additional_class, 'lectures')) {
                                                        echo $eye_svg;
                                                    } else {
                                                        echo $monitor_svg;
                                                    }

                                                    echo $text_done .
                                                        '<span class="passed-blocks-span">
                                                            ' . $passed_blocks_count . '
                                                        </span>
                                                        ' . $text_from . '
                                                        <span class="all-programm-blocks">
                                                            ' . $blocks_count . '
                                                        </span>'
                                                        . $text_blocks;
                                                    ?>
                                                </div>
                                                <?php
                                                // Progress bar line
                                                progress_bar($passed_blocks_count, $blocks_count); ?>
                                            </div>
                                        </div>
                                        <div class="access-wrapper d-flex">
                                            <?php
                                            // Access date block
                                            access_date_block($access_status, $expire_access_date, $text_access_date);
                                            if ($access_status === 'access-expired') {
                                                echo '<a class="continue-access-btn green-transparent" href="">
                                                    ' . $text_continue_access . '
                                                </a>';
                                            } ?>
                                        </div>
                                    </div>
                                </div>

                                <svg class="arrow-icon" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="37.5" cy="37.5" r="36.5" stroke="#53F07F" stroke-width="2" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9998 35.3334L38.0032 40.7314L32.0052 35.3334L30.6665 36.82L38.0032 43.422L45.3385 36.82L43.9998 35.3334Z" fill="white" />
                                </svg>
                            </button>
                            <!-- ***************** Blocks accrodiont ***************** -->
                            <?php
                            echo esc_html(
                                get_template_part('src/template-parts/programm', 'blocks', array(
                                    'programm' => $programm,
                                    'access_status' => $access_status,
                                    'post_id' => $post_id,
                                    'programm_data' => $programm_data,
                                ))
                            );
                            ?>
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
