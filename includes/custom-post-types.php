<?php
// Hooking up function
add_action('init', 'create_post_type');
//add_action( 'init', 'create_tax');
//add_action( 'init', 'cp_change_post_object' );

/*
|-----------------------------------------------------------------------------------
| Add custom post types
|-----------------------------------------------------------------------------------
|
*/
function create_post_type()
{
    register_post_type(
        'post-type-name',
        array(
            'labels' => array(
                'name' => __('Post Type Names'),
                'singular_name' => __('Post Type Names')
            ),
            // 'taxonomies'  => array( 'category' ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            // 'menu_icon' =>'',
            'rewrite' => array('slug' => 'post-type-name'),
            'supports' => array('title', 'editor', 'custom-fields', 'excerpt', 'thumbnail')
        )
    );
}

/*
|-----------------------------------------------------------------------------------
| Add custom Taxonomies
|-----------------------------------------------------------------------------------
|
*/
function create_tax()
{
    register_taxonomy('post-type-category', 'post-type-name', array(
        'label' => __('Post Type Name'),
        'rewrite' => array('slug' => 'post-type-category'),
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}

/*
|-----------------------------------------------------------------------------------
| Change dashboard Posts to News
|-----------------------------------------------------------------------------------
|
*/
function cp_change_post_object()
{
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'News';
    $labels->view_item = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No News found';
    $labels->not_found_in_trash = 'No News found in Trash';
    $labels->all_items = 'All News';
    $labels->menu_name = 'News';
    $labels->name_admin_bar = 'News';
}
