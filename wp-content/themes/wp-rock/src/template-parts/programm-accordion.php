<div class="programm__accordion-item js-wrock-accordion__item <?php echo $access_status; ?>" data-programm_id="<?php echo 'programm-' . $post_id; ?>">
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
                        echo '<form class="programm__continue-access-form js-buy-programm-form">
                                                        <input type="hidden" name="user-id"
                                                            value="' . $current_user->ID . '">
                                                        <input type="hidden" name="post-id"
                                                            value="' . $post_id . '">
                                                        <input type="submit" value="' . esc_html($text_continue_access) . '"
                                                            class="continue-access-btn green-transparent">
                                                        <div class="js-response-container response-container">
                                                        </div>
                                                    </form>';
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
