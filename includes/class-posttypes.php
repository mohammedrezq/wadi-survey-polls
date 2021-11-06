<?php

class WadiPosttypes
{

    public function __construct()
    {

        add_action('init', array($this, 'cpt_init'));
    }

    /**
     * Register a custom post type called "Survey".
     *
     * @see get_post_type_labels() for label keys.
     */
    public function cpt_init()
    {
        $labels = array(
            'name'                  => _x('Survey', 'Post type general name', 'survey'),
            'singular_name'         => _x('Survey', 'Post type singular name', 'survey'),
            'menu_name'             => _x('Survey', 'Admin Menu text', 'survey'),
            'name_admin_bar'        => _x('Surve', 'Add New on Toolbar', 'survey'),
            'add_new'               => __('Add Survey', 'survey'),
            'add_new_item'          => __('Add New Survey', 'survey'),
            'new_item'              => __('New Survey', 'survey'),
            'edit_item'             => __('Edit Survey', 'survey'),
            'view_item'             => __('View Survey', 'survey'),
            'all_items'             => __('All Survey', 'survey'),
            'search_items'          => __('Search Survey', 'survey'),
            'parent_item_colon'     => __('Parent Survey:', 'survey'),
            'not_found'             => __('No Survey found.', 'survey'),
            'not_found_in_trash'    => __('No Survey found in Trash.', 'survey'),
            'featured_image'        => _x('Survey Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'survey'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'survey'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'survey'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'survey'),
            'archives'              => _x('Survey archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'survey'),
            'insert_into_item'      => _x('Insert into Survey', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'survey'),
            'uploaded_to_this_item' => _x('Uploaded to this Survey', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'survey'),
            'filter_items_list'     => _x('Filter Survey list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'survey'),
            'items_list_navigation' => _x('Survey list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'survey'),
            'items_list'            => _x('Survey list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'survey'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'wadi-survey'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'rest_base'          => 'surveys',
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-exerpt-view',
            'supports'           => array('title'),
            'map_meta_cap'       => true,
        );

        register_post_type('wadi-survey', $args);
    }
    /**
     * Flush rewrite rules on activation.
     */
    public function survey_rewrite_flush()
    {
        $this->cpt_init();
        flush_rewrite_rules();
    }
}

new WadiPosttypes;