<?php

global $global_options;
global $monobank;

$fields = get_fields();

$programm_title = !empty($args['programm_title']) ? $args['programm_title'] : '';
$programm_type = !empty($args['programm_type']) ? $args['programm_type'] : '';
$additional_class = !empty($args['additional_class']) ? $args['additional_class'] : '';

$eye_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M23.8475 11.5332C23.6331 11.2399 18.5245 4.35165 11.9999 4.35165C5.47529 4.35165 0.366469 11.2399 0.152297 11.5329C-0.0507657 11.8112 -0.0507657 12.1886 0.152297 12.4668C0.366469 12.7601 5.47529 19.6484 11.9999 19.6484C18.5245 19.6484 23.6331 12.7601 23.8475 12.467C24.0508 12.1888 24.0508 11.8112 23.8475 11.5332ZM11.9999 18.0659C7.19383 18.0659 3.03127 13.4941 1.79907 11.9995C3.02968 10.5035 7.18351 5.93406 11.9999 5.93406C16.8057 5.93406 20.968 10.5051 22.2007 12.0005C20.9701 13.4964 16.8163 18.0659 11.9999 18.0659Z" fill="#151A1D"/>
<path d="M11.9997 7.25275C9.38212 7.25275 7.25244 9.38242 7.25244 12C7.25244 14.6176 9.38212 16.7473 11.9997 16.7473C14.6173 16.7473 16.747 14.6176 16.747 12C16.747 9.38242 14.6173 7.25275 11.9997 7.25275ZM11.9997 15.1648C10.2546 15.1648 8.8349 13.7451 8.8349 12C8.8349 10.2549 10.2546 8.8352 11.9997 8.8352C13.7448 8.8352 15.1645 10.2549 15.1645 12C15.1645 13.7451 13.7449 15.1648 11.9997 15.1648Z" fill="#151A1D"/>
</svg>';

//Texts
$main_title = __($programm_title, 'wp-rock');

$current_user = wp_get_current_user();

$user_fields = get_fields('user_' . $current_user->ID);

//Information from saved user data
$user_programm = get_field_value($user_fields, $programm_type);

$payment = $monobank->check_payment();
?>

<div id="<?php echo $programm_type; ?>" class="profile__panel js-programm js-tab-panel <?php echo $additional_class; ?>" data-programm_type="<?php echo $programm_type; ?>" data-user_id="<?php echo $current_user->ID; ?>">

    <div class="programm">
        <div class="container">
            <h2 class="programm__title"><?php echo $main_title; ?></h2>
            <!-- ***************** Main accrodiont ***************** -->
            <?php if (!empty($user_programm)) : ?>
                <div class="programm__accordion js-wrock-accordion">
                    <?php
                    foreach ($user_programm as $user_programm_item) :
                        if (empty($user_programm_item['post_id'])) continue;

                        echo get_template_part('src/template-parts/programm', 'accordion', array(
                            'user_programm_item' => $user_programm_item,
                            'additional_class' => $additional_class,
                            'current_user' => $current_user,
                        ));

                        echo get_template_part('src/template-parts/pay-success-response', null, array(
                            'payment' => $payment,
                            'post_id' => $user_programm_item['post_id']
                        ));
                    endforeach;
                    ?>
                </div>
                <!-- ***************** END Main accrodiont ***************** -->
            <?php else : ?>
                <h3 class="programm__title-not-found"><?php echo __('Нічого не знайдено', 'wp-rock'); ?></h3>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php

