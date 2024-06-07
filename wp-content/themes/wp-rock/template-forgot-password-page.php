<?php

/**
 *
 * Template name: Forgot password page
 *
 */

get_header();
$fields = get_fields(get_the_ID());

$title = get_field_value($fields, 'title');
$subtitle = get_field_value($fields, 'subtitle');
?>
<div class="forgot-password">
    <div class="container">
        <div class="breadcrumbs d-flex">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
        <?php
        if(!empty($title)) {
            echo '<h2 class="forgot-password__title">'.esc_html($title).'</h2>';
        }
        if(!empty($subtitle)) {
            echo '<p class="forgot-password__subtitle body-type-5 weight600">'.esc_html($subtitle).'</p>';
        }
        ?>
        <form class="forgot-password__form js-forgot-password-form">
            <input class="form-input" type="email" name="email">
            <input type="submit" class="green-transparent">
            <div class="js-response-container forgot-password__form-response"></div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
