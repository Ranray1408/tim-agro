
<?php
global $global_options;

$profile_page = get_field_value($global_options, 'profile_page');
$profile_page_url = !empty($profile_page) ? get_the_permalink($profile_page) : get_site_url();


echo do_shortcode(
    '[popup_box box_id="have-access-popup"]
        <div class="pay-success-response">
            <h2 class="popup-title">
                ' . __('Ви вже купили цей курс', 'wp-rock') . '
            </h2>
            <a href="' . $profile_page_url . '" class="popup-button green-transparent">
                ' . __('Подивитися в особистому кабінеті ', 'wp-rock') . '
            </a>
        </div>
        [/popup_box]'
);
