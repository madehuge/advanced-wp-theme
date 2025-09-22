<?php
// Register Custom Post Type for Hero Slides
function custom_register_hero_slide_cpt() {
    $labels = [
        'name'               => __( 'Hero Slides', 'custom' ),
        'singular_name'      => __( 'Hero Slide', 'custom' ),
        'add_new'            => __( 'Add New Slide', 'custom' ),
        'add_new_item'       => __( 'Add New Hero Slide', 'custom' ),
        'edit_item'          => __( 'Edit Hero Slide', 'custom' ),
        'new_item'           => __( 'New Hero Slide', 'custom' ),
        'view_item'          => __( 'View Hero Slide', 'custom' ),
        'search_items'       => __( 'Search Hero Slides', 'custom' ),
        'not_found'          => __( 'No Hero Slides found', 'custom' ),
        'not_found_in_trash' => __( 'No Hero Slides found in Trash', 'custom' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,                  // Not publicly accessible
        'show_ui'            => true,                   // Show in WP Admin
        'show_in_menu'       => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-format-image',  // Media icon
        'supports'           => ['title', 'editor', 'thumbnail'],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,                   // Better permission handling
        'has_archive'        => false,                  // Disable archive
        'rewrite'            => false,                  // No public URL
    ];

    register_post_type( 'hero_slide', $args );
}
add_action( 'init', 'custom_register_hero_slide_cpt' );




// Register Custom Post Type: Resources
function custom_register_resources_cpt() {
    $labels = [
        'name'                  => __( 'Resources', 'custom' ),
        'singular_name'         => __( 'Resource', 'custom' ),
        'add_new'               => __( 'Add New Resource', 'custom' ),
        'add_new_item'          => __( 'Add New Resource', 'custom' ),
        'edit_item'             => __( 'Edit Resource', 'custom' ),
        'new_item'              => __( 'New Resource', 'custom' ),
        'view_item'             => __( 'View Resource', 'custom' ),
        'search_items'          => __( 'Search Resources', 'custom' ),
        'not_found'             => __( 'No Resources found', 'custom' ),
        'not_found_in_trash'    => __( 'No Resources found in Trash', 'custom' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'resources'],
    ];

    register_post_type( 'resource', $args );
}
add_action( 'init', 'custom_register_resources_cpt' );


// Register Custom Taxonomy: Resource Categories
function custom_register_resource_taxonomy() {
    $labels = [
        'name'              => __( 'Resource Categories', 'custom' ),
        'singular_name'     => __( 'Resource Category', 'custom' ),
        'search_items'      => __( 'Search Categories', 'custom' ),
        'all_items'         => __( 'All Categories', 'custom' ),
        'edit_item'         => __( 'Edit Category', 'custom' ),
        'update_item'       => __( 'Update Category', 'custom' ),
        'add_new_item'      => __( 'Add New Category', 'custom' ),
        'new_item_name'     => __( 'New Category Name', 'custom' ),
        'menu_name'         => __( 'Categories', 'custom' ),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => true,   // Like categories (not tags)
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'resource-category'],
    ];

    register_taxonomy( 'resource_category', ['resource'], $args );
}
add_action( 'init', 'custom_register_resource_taxonomy' );

// Register Custom Taxonomy: Resource Types (Blogs, Videos, Webinars)
function custom_register_resource_type_taxonomy() {
    $labels = [
        'name'              => __( 'Resource Types', 'custom' ),
        'singular_name'     => __( 'Resource Type', 'custom' ),
        'search_items'      => __( 'Search Types', 'custom' ),
        'all_items'         => __( 'All Types', 'custom' ),
        'edit_item'         => __( 'Edit Type', 'custom' ),
        'update_item'       => __( 'Update Type', 'custom' ),
        'add_new_item'      => __( 'Add New Type', 'custom' ),
        'new_item_name'     => __( 'New Type Name', 'custom' ),
        'menu_name'         => __( 'Types', 'custom' ),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => false,   // Like tags (not categories)
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'resource-type'],
        'show_in_rest'      => true,
    ];

    register_taxonomy( 'resource_type', ['post', 'resource'], $args );
}
add_action( 'init', 'custom_register_resource_type_taxonomy' );

// Register Custom Post Type: News
function custom_register_news_cpt() {
    $labels = [
        'name'                  => __( 'News', 'custom' ),
        'singular_name'         => __( 'News Article', 'custom' ),
        'add_new'               => __( 'Add New News', 'custom' ),
        'add_new_item'          => __( 'Add New News Article', 'custom' ),
        'edit_item'             => __( 'Edit News Article', 'custom' ),
        'new_item'              => __( 'New News Article', 'custom' ),
        'view_item'             => __( 'View News Article', 'custom' ),
        'search_items'          => __( 'Search News', 'custom' ),
        'not_found'             => __( 'No News found', 'custom' ),
        'not_found_in_trash'    => __( 'No News found in Trash', 'custom' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-megaphone',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'news'],
        'show_in_rest'       => true,
    ];

    register_post_type( 'news', $args );
}
add_action( 'init', 'custom_register_news_cpt' );

// Register Custom Taxonomy: News Categories
function custom_register_news_taxonomy() {
    $labels = [
        'name'              => __( 'News Categories', 'custom' ),
        'singular_name'     => __( 'News Category', 'custom' ),
        'search_items'      => __( 'Search Categories', 'custom' ),
        'all_items'         => __( 'All Categories', 'custom' ),
        'edit_item'         => __( 'Edit Category', 'custom' ),
        'update_item'       => __( 'Update Category', 'custom' ),
        'add_new_item'      => __( 'Add New Category', 'custom' ),
        'new_item_name'     => __( 'New Category Name', 'custom' ),
        'menu_name'         => __( 'Categories', 'custom' ),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => true,   // Like categories (not tags)
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'news-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy( 'news_cat', ['news'], $args );
}
add_action( 'init', 'custom_register_news_taxonomy' );