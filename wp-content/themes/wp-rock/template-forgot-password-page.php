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

$hash_reset_password = !empty($_GET['hash_reset_password']) ? $_GET['hash_reset_password'] : null;
$transient =  $hash_reset_password ? get_transient('hash_reset_password' . $hash_reset_password) : null;

$pass_text1 = __('Введіть новий пароль', 'wp-rock');
$pass_text2 = __('Повторіть новий пароль', 'wp-rock');
?>
<div class="forgot-password">
    <div class="container">
        <div class="breadcrumbs d-flex">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
        <?php
        if (!empty($title)) {
            echo '<h2 class="forgot-password__title">' . esc_html($title) . '</h2>';
        }
        ?>
        <?php if ($transient) : ?>
            <form class="forgot-password__set-new-password-form js-set-new-password-form">
                <label class="input-wrapper">
                    <span class="body-type-5 weight600"><?php echo $pass_text1; ?></span>
                    <input class="form-input" type="password" name="password">
                </label>
                <label class="input-wrapper">
                    <span class="body-type-5 weight600"><?php echo $pass_text2; ?></span>
                    <input class="form-input" type="password" name="password-repeat">
                </label>
                <input type="hidden" name="user-email" value="<?php echo $transient; ?>">
                <input type="submit" class="green-transparent" value="Відправити">
                <div class="js-response-container forgot-password__form-response"></div>
            </form>
        <?php else :
            if (!empty($subtitle)) {
                echo '<p class="forgot-password__subtitle body-type-5 weight600">' . esc_html($subtitle) . '</p>';
            }
        ?>
            <form class="forgot-password__form js-forgot-password-form">
                <input class="form-input" type="email" name="email">
                <input type="submit" class="green-transparent">
                <div class="js-response-container forgot-password__form-response"></div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
