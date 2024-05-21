<?php
/**
 * ACF fields processing for adding different analytics like Google, Facebook etc., in admin dashboard
 *
 * @package WP-rock/analytics-fields
 */

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_662226a068db1',
        'title' => 'Analytics settings',
        'fields' => array(
            array(
                'key' => 'field_662226a1bf356',
                'label' => 'Codes for HEAD section',
                'name' => 'codes_for_head_section',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'rows' => '',
                'placeholder' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_662226c9bf357',
                'label' => 'Codes for AFTER BODY OPEN TAG section',
                'name' => 'codes_for_after_body_open_tag_section',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'rows' => '',
                'placeholder' => '',
                'new_lines' => '',
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


if ( function_exists( 'get_field' ) ) {

    add_action( 'wp_rock_before_close_head_tag', 'custom_analytics_1', 100 );

    /**
     * Render content from Theme settings (should be filled in admin dashboard).
     *
     * @return void
     */
    function custom_analytics_1(): void {
        echo do_shortcode( get_field( 'codes_for_head_section', 'theme-general-settings' ) );
    }

    add_action( 'wp_rock_after_open_body_tag', 'custom_analytics_2', 100 );

    /**
     * Render content from Theme settings (should be filled in admin dashboard).
     *
     * @return void
     */
    function custom_analytics_2(): void {
        echo do_shortcode( get_field( 'codes_for_after_body_open_tag_section', 'theme-general-settings' ) );
    }
}
