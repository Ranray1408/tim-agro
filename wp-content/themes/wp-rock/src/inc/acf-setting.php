<?php
/**
 * Create Theme General Settings
 *
 * @package acf/settings
 */

if ( function_exists( 'acf_add_options_page' ) ) {

    $parent = acf_add_options_page(
        array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug'  => 'theme-general-settings',
            'post_id'    => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
        )
    );
}


if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_664d8e445fe19',
        'title' => 'Colours settings',
        'fields' => array(
            array(
                'key' => 'field_664d8e4450bf9',
                'label' => 'Colours set',
                'name' => 'colours_set',
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'table',
                'pagination' => 0,
                'min' => 0,
                'max' => 0,
                'collapsed' => '',
                'button_label' => 'Add Row',
                'rows_per_page' => 20,
                'sub_fields' => array(
                    array(
                        'key' => 'field_664d8eac50bfa',
                        'label' => 'Name of color type',
                        'name' => 'name_of_color_type',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => 'Використовуйте це поле для завдання назви типу кольору елементів сайту',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_664d8e4450bf9',
                    ),
                    array(
                        'key' => 'field_664d8f0f50bfb',
                        'label' => 'Color code',
                        'name' => 'color_code',
                        'aria-label' => '',
                        'type' => 'color_picker',
                        'instructions' => 'Виберіть потрібний колір для потрібного типу кольору елементу',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'enable_opacity' => 0,
                        'return_format' => 'string',
                        'parent_repeater' => 'field_664d8e4450bf9',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-general-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ));

endif;


/**
 * Register the styles (CSS) for the ACF blocks (acf_register_block_type()) in head section instead of body or footer
 */
add_action( 'wp_enqueue_scripts', 'register_acf_block_styles' );
add_action( 'admin_enqueue_scripts', 'register_acf_block_styles' );

function register_acf_block_styles() {

    $wrock_blocks  = new WP_Rock_Blocks();
    $custom_blocks = $wrock_blocks->blocks;

    if ( !empty($custom_blocks) ) {

        foreach (array_keys($custom_blocks) as $key) {
            if( has_block( 'acf/'.$key ) ) {
                $style_file = ASSETS_CSS . $key . '.css';
                wp_enqueue_style( 'acf-block-'.$key, $style_file, array(), wp_get_theme()->get( 'Version' ) );
            }
        }
    }
}

/**
 * Adding "preconnect" rel attribute to <link> tag for ACF styles to improve performance
 * @param $html
 * @param $handle
 * @param $href
 * @param $media
 *
 * @return array|mixed|string|string[]
 */
function add_preconnect_rel_attribute($html, $handle, $href, $media) {

    $wrock_blocks  = new WP_Rock_Blocks();
    $custom_blocks = $wrock_blocks->blocks;

    if ( !empty($custom_blocks) ) {

        foreach (array_keys($custom_blocks) as $key) {
            if ($handle === 'acf-block-'.$key) {
                // Adding attribute rel="preconnect"
                $html = str_replace('stylesheet', 'preconnect', $html);
            }
        }
    }

    return $html;
}
//add_filter('style_loader_tag', 'add_preconnect_rel_attribute', 90, 4);
