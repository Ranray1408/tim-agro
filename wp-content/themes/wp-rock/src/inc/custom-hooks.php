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
function remove_lsep($html) {
    $pattern = '/\x{2028}/u';

    return preg_replace($pattern, '', $html);
}


/**
 * Remove windows LSEP from content
 *
 * @param {string} $content - Text content.
 * @return string|string[]
 */
function remove_windows_lsep_from_content($content) {
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

    // Get main tag colors from theme options
    $main_tags_colours = get_field_value($global_options, 'main_tags_colours');

    // Initialize an empty array to store CSS variable declarations
    $colours_variable = array();

    // Extract term IDs from main tag colors array
    $term_ids = array_map( function ($single_data) {
        return $single_data['title'];
    }, $main_tags_colours);

    // Get slugs for terms associated with the given term IDs
    $terms_data = get_resource_tag_slugs($term_ids);

    // Generate CSS variable declarations for each main tag color
    if ( !empty($main_tags_colours) ) {
        foreach ($main_tags_colours as $main_tags_colour) {
            $term_id    = $main_tags_colour['title'];
            $term_color = $main_tags_colour['tag_term_color'];
            $colours_variable[] = '--color-term-'.$terms_data[$term_id].':'.$term_color.';';
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
//add_action('wp_head', 'wp_rock_color_panel');


// This block will be rendered before site footer on "Single resources pages"
function add_basic_footer_block(){

    if ( is_singular('resources') ) {
        global $global_options;
        $external_data = array(
            'image' => get_field_value($global_options, 'image'),
            'title' => get_field_value($global_options, 'title'),
            'link'  => get_field_value($global_options, 'link'),
            'block_id' => 'block-id',
        );

        echo esc_html( get_template_part( 'src/template-parts/acf-blocks/block', 'lets-talk', array(
                'external_data' => $external_data
        ) ) );
    }

}
add_action('wp_rock_after_page_content', 'add_basic_footer_block', 10);
