<?php
/**
 * Add Dropcap option but keep the defaults.
 *
 * @package tiny_mce/customization
 */

add_filter( 'tiny_mce_before_init', 'my_wpeditor_formats_options' );

/**
 * Customizing WP Editor formats options.
 *
 * @param {array} $settings  -  Existing settings.
 * @return mixed
 */
function my_wpeditor_formats_options( $settings ) {
    /* Set to true to include the default settings. */
    $settings['style_formats_merge'] = true;

    $style_formats = array(
        array(
            'title' => 'body 1 (regular)',
            'block' => 'p',
            'classes' => 'body-type-1-regular',
        ),
        array(
            'title' => 'body 1 (not regular)',
            'block' => 'p',
            'classes' => 'body-type-1',
        ),
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;
}

// Callback function to insert 'styleselect' into the $buttons array
function wpb_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');
