<?php

/**
 * Custom hooks
 *
 * @package WP-rock/custom-hooks
 */

/**
 * Remove windows LSEP from content
 *
 * @param { string } $html - Text content.
 *
 * @return array|string|string[]
 */
function remove_lsep($html): array|string
{
    $pattern = '/\x{2028}/u';

    return preg_replace($pattern, '', $html);
}


/**
 * Remove windows LSEP from content
 *
 * @param {string} $content - Text content.
 * @return string|string[]
 */
function remove_windows_lsep_from_content($content): array|string
{
    return str_replace("\r\n", '', $content);
}
add_filter('the_content', 'remove_windows_lsep_from_content');



/**
 * Change display type for language switcher in Frontend
 */
add_filter(
    'pll_the_languages_args',
    function ($args) {
        $args['display_names_as'] = 'slug';
        return $args;
    }
);



/**
 * Remove tag <p> и <br> in plugin contact form.
 */
add_filter('wpcf7_autop_or_not', '__return_false');


/**
 * Output CSS styles for custom tag colors in the WordPress theme.
 * This function retrieves tag colors from theme options and generates CSS variables.
 *
 * @return void
 */
function wp_rock_color_panel(): void
{
    global $global_options;

    // Get main colours set from theme options
    $colours_set = get_field_value($global_options, 'colours_set');

    // Initialize an empty array to store CSS variable declarations
    $colours_variable = array();

    // Generate CSS variable declarations for each main tag color
    if ( !empty($colours_set) ) {
        foreach ($colours_set as $single_color) {
            $color_type = $single_color['name_of_color_type'];
            $color_code = $single_color['color_code'];
            $colours_variable[] = '--color-type-'.$color_type.':'.$color_code.';';
        }
    }

    // Output CSS styles with the generated CSS variables
    ?>
    <style>
        :root {
        <?php echo implode('', $colours_variable); ?>
        }
    </style>
    <?php
}
add_action('wp_head', 'wp_rock_color_panel');



/*
primary-white : #fff
primary-1 : #53F07F
dark-1 : #151A1D
 */