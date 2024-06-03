<?php
$current_user = wp_get_current_user();
$avatar = get_avatar_url($current_user->ID);
$nickname = $current_user->user_nicename;
$email = $current_user->user_email;

$user_phone = get_field('user_phone', 'user_' . $current_user->ID);

$main_title = __('Мої дані', 'wp-rock');
$text_load_avatar = __('Завантажити фото', 'wp-rock');
$text_input_name = __('Ваше ім\'я', 'wp-rock');
$text_input_email = __('E-mail', 'wp-rock');
$text_input_phone = __('Телефон', 'wp-rock');
$text_submit_btn = __('Зберегти', 'wp-rock');


$edit_btn = '<button class="edit-btn js-edit-btn">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g opacity="0.5" clip-path="url(#clip0_2087_34641)">
        <path d="M22.3512 14.1679C21.8452 14.1679 21.4351 14.578 21.4351 15.084V22.1679H1.83206V2.56491H8.91605C9.42202 2.56491 9.83208 2.15485 9.83208 1.64888C9.83208 1.14291 9.42202 0.732849 8.91605 0.732849H0.916031C0.410062 0.732849 0 1.14291 0 1.64888V23.084C0 23.5899 0.410109 24 0.916031 24H22.3511C22.8571 24 23.2672 23.5899 23.2672 23.084V15.084C23.2672 14.578 22.8571 14.1679 22.3512 14.1679Z" fill="white" />
        <path d="M23.7318 3.53559L20.4649 0.268406C20.2933 0.0964687 20.0603 0 19.8173 0C19.5743 0 19.3416 0.0964687 19.1697 0.268406L8.36048 11.0775C8.24934 11.1884 8.16905 11.3258 8.1263 11.4766L6.84384 16.0263C6.75408 16.3454 6.84356 16.688 7.07803 16.9225C7.25208 17.0965 7.48598 17.1909 7.72566 17.1909C7.80839 17.1909 7.89206 17.1796 7.97391 17.1567L12.5235 15.8742C12.6747 15.8315 12.8121 15.7508 12.9229 15.64L23.7318 4.83089C24.0896 4.47328 24.0896 3.89311 23.7318 3.53559ZM11.7968 14.1756L9.05086 14.9493L9.82491 12.204L19.8173 2.21161L21.7889 4.18322L11.7968 14.1756Z" fill="white" />
        <path d="M9.6851 11.0457L8.38965 12.3412L11.6563 15.6079L12.9518 14.3124L9.6851 11.0457Z" fill="white" />
    </g>
    <defs>
        <clipPath id="clip0_2087_34641">
            <rect width="24" height="24" fill="white" />
        </clipPath>
    </defs>
    </svg>
</button>';

?>
<div id="user-info" class="profile__panel-user-info active js-user-info-form">
    <div class="container">
        <h2 class="profile__panel-user-info-title"><?php echo $main_title; ?></h2>
        <form class="profile__panel-user-info-form">

            <div class="profile__panel-user-info-form-avatar-wrapper">
                <?php
                echo '<figure class="profile__panel-user-info-form-avatar">
                        <img src="' . esc_url($avatar) . '" alt="User Avatar">
                    </figure>';
                ?>
                <button class="load-new-avatar">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2087_34676)">
                            <path d="M0.563298 7.57931C0.874408 7.57931 1.1266 7.32712 1.1266 7.01601V2.39696C1.1266 1.60832 1.88704 1.12956 2.67568 1.12956H7.29473C7.60584 1.12956 7.85803 0.877368 7.85803 0.566258C7.85803 0.255148 7.60584 0.00296021 7.29473 0.00296021H2.67568C1.26741 0.00296021 0 0.988744 0 2.39696V7.01601C0 7.32707 0.252187 7.57931 0.563298 7.57931Z" fill="#53F07F" />
                            <path d="M17.8344 0.00295153C17.7574 -0.000983843 17.6802 -0.000983843 17.6032 0.00295153H12.9842C12.6731 0.00295153 12.4209 0.255139 12.4209 0.566249C12.4209 0.877359 12.6731 1.12955 12.9842 1.12955H17.6032C18.2547 1.08108 18.8222 1.56993 18.8707 2.22148C18.875 2.27992 18.875 2.33857 18.8707 2.39701V7.01606C18.8707 7.32717 19.1228 7.57936 19.4339 7.57936C19.7451 7.57936 19.9972 7.32717 19.9972 7.01606V2.39695C20.0611 1.1386 19.0928 0.0667801 17.8344 0.00295153Z" fill="#53F07F" />
                            <path d="M19.4339 12.142C19.1228 12.142 18.8707 12.3942 18.8707 12.7053V17.3243C18.8707 18.113 18.3918 18.8734 17.6032 18.8734H12.9842C12.6731 18.8734 12.4209 19.1256 12.4209 19.4367C12.4209 19.7478 12.6731 20 12.9842 20H17.6032C19.0115 20 19.9972 18.7326 19.9972 17.3243V12.7053C19.9972 12.3942 19.7451 12.142 19.4339 12.142Z" fill="#53F07F" />
                            <path d="M7.29467 18.8734H2.67568C1.8383 18.8311 1.16891 18.1617 1.1266 17.3243V12.7053C1.1266 12.3942 0.874408 12.142 0.563298 12.142C0.252187 12.142 0 12.3942 0 12.7053V17.3243C0.0440438 18.7834 1.21657 19.9559 2.67568 20H7.29473C7.60584 20 7.85803 19.7478 7.85803 19.4367C7.85803 19.1256 7.60578 18.8734 7.29467 18.8734Z" fill="#53F07F" />
                            <path d="M13.9415 9.86065C13.9415 7.76073 12.2391 6.05838 10.1392 6.05838C8.03926 6.05838 6.33691 7.76073 6.33691 9.86065C6.33691 11.9606 8.03926 13.6629 10.1392 13.6629C12.2327 13.6476 13.9261 11.9542 13.9415 9.86065ZM10.1392 12.5363C8.66153 12.5363 7.46356 11.3384 7.46356 9.86065C7.46356 8.38295 8.66148 7.18498 10.1392 7.18498C11.6169 7.18498 12.8149 8.38289 12.8149 9.86065C12.7996 11.332 11.6106 12.5211 10.1392 12.5363Z" fill="#53F07F" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2087_34676">
                                <rect width="20" height="20" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span><?php echo $text_load_avatar; ?></span>
                    <input type="file">
                </button>
            </div>

            <div class="profile__panel-user-info-form-input-wrapper">
                <span><?php echo $text_input_name; ?></span>
                <div class="inner-input-wrapper js-inner-input-wrapper">
                    <input type="hidden" name="user_id">
                    <input readonly type="text" name="nickname" value="<?php echo $nickname; ?>">
                    <?php echo $edit_btn; ?>
                </div>
            </div>
            <div class="profile__panel-user-info-form-input-wrapper">
                <span><?php echo $text_input_email; ?></span>
                <div class="inner-input-wrapper js-inner-input-wrapper">
                    <input readonly type="text" name="email" value="<?php echo $email; ?>">
                    <?php echo $edit_btn; ?>
                </div>
            </div>
            <div class="profile__panel-user-info-form-input-wrapper">
                <span><?php echo $text_input_phone; ?></span>
                <div class="inner-input-wrapper js-inner-input-wrapper">
                    <input readonly type="text" name="phone" value="<?php echo $user_phone; ?>">
                    <?php echo $edit_btn; ?>
                </div>
            </div>
            <input class="green-transparent profile__panel-user-info-form-submit" value="<?php echo $text_submit_btn; ?>" type="submit">
        </form>
    </div>
</div>
