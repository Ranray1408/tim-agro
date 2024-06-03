<?php

/**
 *
 * Template name: Profile
 *
 */
get_header('profile');
?>

<?php
echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'programms',
    array(
        'programm_title' => 'Мої курси',
        'programm_type' => 'courses'
    )
));

echo esc_html(get_template_part(
    'src/template-parts/tab-panel',
    'user-info'
));

?>

<?php get_footer(); ?>
