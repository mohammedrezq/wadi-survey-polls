<?php

class WadiPosttypes
{

    public function __construct()
    {

        add_action('init', array($this, 'cpt_init'));
        register_activation_hook(__FILE__, array($this, 'wadi_rewrite_flush'));

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
            'name_admin_bar'        => _x('Survey', 'Add New on Toolbar', 'survey'),
            'add_new'               => __('Add Survey', 'survey'),
            'add_new_item'          => __('Add New Survey', 'survey'),
            'new_item'              => __('New Survey', 'survey'),
            'edit_item'             => __('Edit Survey', 'survey'),
            'view_item'             => __('View Survey', 'survey'),
            'all_items'             => __('All Surveys', 'survey'),
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
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'survey-admin.php',
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

        /**
         * Wadi Poll Post Type (To Survey Post Type from show_in_menu)
         */
        $poll_labels = array(
            'name'                  => _x('Poll', 'Post type general name', 'survey'),
            'singular_name'         => _x('Poll', 'Post type singular name', 'survey'),
            'menu_name'             => _x('Poll', 'Admin Menu text', 'survey'),
            'name_admin_bar'        => _x('Poll', 'Add New on Toolbar', 'survey'),
            'add_new'               => __('Add Poll', 'survey'),
            'add_new_item'          => __('Add New Poll', 'survey'),
            'new_item'              => __('New Poll', 'survey'),
            'edit_item'             => __('Edit Poll', 'survey'),
            'view_item'             => __('View Poll', 'survey'),
            'all_items'             => __('All Polls', 'survey'),
            'search_items'          => __('Search Poll', 'survey'),
            'parent_item_colon'     => __('Parent Poll:', 'survey'),
            'not_found'             => __('No Poll found.', 'survey'),
            'not_found_in_trash'    => __('No Poll found in Trash.', 'survey'),
            'featured_image'        => _x('Poll Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'survey'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'survey'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'survey'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'survey'),
            'archives'              => _x('Poll archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'survey'),
            'insert_into_item'      => _x('Insert into Survey', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'survey'),
            'uploaded_to_this_item' => _x('Uploaded to this Survey', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'survey'),
            'filter_items_list'     => _x('Filter Poll list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'survey'),
            'items_list_navigation' => _x('Poll list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'survey'),
            'items_list'            => _x('Poll list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'survey'),
        );

        $poll_args = array(
            'labels'             => $poll_labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'survey-admin.php',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'wadi-poll'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'rest_base'          => 'polls',
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-exerpt-view',
            'supports'           => array('title'),
            'map_meta_cap'       => true,
        );

        register_post_type('wadi-poll', $poll_args);
    }

    /**
     * Flush rewrite rules on activation.
     */
    public function wadi_rewrite_flush()
    {
        $this->cpt_init();
        flush_rewrite_rules();
    }
}

new WadiPosttypes;
