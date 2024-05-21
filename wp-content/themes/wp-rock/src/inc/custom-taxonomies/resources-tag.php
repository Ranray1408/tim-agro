<?php
/**
 * Taxonomy events category
 *
 * @package WP-rock
 * @since 4.4.0
 */


$labels = array(
    'name' => __( 'Tag', 'wp-rock' ),
    'singular_name' => __( 'Tag', 'wp-rock' ),
    'search_items' => __( 'Search for Tags', 'wp-rock' ),
    'all_items' => __( 'All Tags', 'wp-rock' ),
    'parent_item' => __( 'Parent Tags', 'wp-rock' ),
    'parent_item_colon' => __( 'Parent Tag:', 'wp-rock' ),
    'edit_item' => __( 'Edit Tag', 'wp-rock' ),
    'update_item' => __( 'Update Tag', 'wp-rock' ),
    'add_new_item' => __( 'Add New Tag', 'wp-rock' ),
    'new_item_name' => __( 'New Tag', 'wp-rock' ),
);

register_taxonomy(
    'resource_tag',
    array( 'resources' ),
    array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'resource-tag' ),
    )
);

