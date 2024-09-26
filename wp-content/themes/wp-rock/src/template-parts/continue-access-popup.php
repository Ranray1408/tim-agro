<?php
global $global_options;

$post_id = !empty($args['post_id']) ? $args['post_id'] : 0;
$product_id = !empty($args['product_id']) ? $args['product_id'] : null;

if(empty($post_id)) return;

$post_fields = get_fields($post_id);
$continue_checkboxes = get_field_value($post_fields, 'continue_checkboxes');

echo '<div style="display: none;" class="continue-checkboxes-cehck">';
var_dump($continue_checkboxes);
echo '</div>';

$continue_access_title = __('Продовжити курс', 'wp-rock');
$continue_access_description = __('Виберіть термін продовження', 'wp-rock');


$user_email = is_user_logged_in() ? wp_get_current_user()->user_email : null;

$html = '<form class="continue-access-popup__form js-buy-programm-form">';

$html .= '<h2 class="continue-access-popup__title">' . $continue_access_title . '</h2>';

$html .= '<p class="continue-access-popup__text body-type-2 weight500">
                ' . $continue_access_description . '
            </p>';

$html .= '<input type="hidden" name="redirect-page" value="' . get_the_permalink() . '">
        <input type="hidden" name="email" value="' . $user_email . '">
        <input type="hidden" name="post-id" value="' . $product_id . '">';

$html .= '<div class="continue-access-popup__checks-wrapper">';

if (!empty($continue_checkboxes)) {
    foreach ($continue_checkboxes as $key => $checkbox) {
        if (!empty($checkbox['text']) && !empty($checkbox['price'])) {
            $checked = $key === 0 ? 'checked' : '';

			$valuecheck = $checkbox['period'].'|'.$checkbox['price'];
			$result = preg_replace("/[^\d|]/", "", $valuecheck);

            $html .= '<label class="continue-access-popup__checkbox">
                        <input ' . $checked . ' value="' . $result . '" required name="period-amount" type="radio">
                        <div class="continue-access-popup__checkbox-inner">
                            <span class="body-type-2 weight500">' . $checkbox['text'] . '</span>
                            <span class="body-type-2 weight800">' . $checkbox['price'] . '</span>
                        </div>
                    </label>';
        }
    }
}

$html .= '</div>';

$html .= '<input type="submit" class="continue-access-popup__submit green-transparent" value="' . __('Оплатити', 'wp-rock') . '" />';

$html .= '</form>';

echo do_shortcode('[popup_box box_id="continue-access-popup"]' . $html . '[/popup_box]');
