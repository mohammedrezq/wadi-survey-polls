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
            'name'                  => _x('Survey', 'Post type general name', 'wadi-survey-pro'),
            'singular_name'         => _x('Survey', 'Post type singular name', 'wadi-survey-pro'),
            'menu_name'             => _x('Survey', 'Admin Menu text', 'wadi-survey-pro'),
            'name_admin_bar'        => _x('Survey', 'Add New on Toolbar', 'wadi-survey-pro'),
            'add_new'               => __('Add Survey', 'wadi-survey-pro'),
            'add_new_item'          => __('Add New Survey', 'wadi-survey-pro'),
            'new_item'              => __('New Survey', 'wadi-survey-pro'),
            'edit_item'             => __('Edit Survey', 'wadi-survey-pro'),
            'view_item'             => __('View Survey', 'wadi-survey-pro'),
            'all_items'             => __('All Surveys', 'wadi-survey-pro'),
            'search_items'          => __('Search Survey', 'wadi-survey-pro'),
            'parent_item_colon'     => __('Parent Survey:', 'wadi-survey-pro'),
            'not_found'             => __('No Survey found.', 'wadi-survey-pro'),
            'not_found_in_trash'    => __('No Survey found in Trash.', 'wadi-survey-pro'),
            'featured_image'        => _x('Survey Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'archives'              => _x('Survey archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wadi-survey-pro'),
            'insert_into_item'      => _x('Insert into Survey', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wadi-survey-pro'),
            'uploaded_to_this_item' => _x('Uploaded to this Survey', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wadi-survey-pro'),
            'filter_items_list'     => _x('Filter Survey list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wadi-survey-pro'),
            'items_list_navigation' => _x('Survey list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wadi-survey-pro'),
            'items_list'            => _x('Survey list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wadi-survey-pro'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'survey-admin.php',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'wadi-survey-pro'),
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
            'name'                  => _x('Poll', 'Post type general name', 'wadi-survey-pro'),
            'singular_name'         => _x('Poll', 'Post type singular name', 'wadi-survey-pro'),
            'menu_name'             => _x('Poll', 'Admin Menu text', 'wadi-survey-pro'),
            'name_admin_bar'        => _x('Poll', 'Add New on Toolbar', 'wadi-survey-pro'),
            'add_new'               => __('Add Poll', 'wadi-survey-pro'),
            'add_new_item'          => __('Add New Poll', 'wadi-survey-pro'),
            'new_item'              => __('New Poll', 'wadi-survey-pro'),
            'edit_item'             => __('Edit Poll', 'wadi-survey-pro'),
            'view_item'             => __('View Poll', 'wadi-survey-pro'),
            'all_items'             => __('All Polls', 'wadi-survey-pro'),
            'search_items'          => __('Search Poll', 'wadi-survey-pro'),
            'parent_item_colon'     => __('Parent Poll:', 'wadi-survey-pro'),
            'not_found'             => __('No Poll found.', 'wadi-survey-pro'),
            'not_found_in_trash'    => __('No Poll found in Trash.', 'wadi-survey-pro'),
            'featured_image'        => _x('Poll Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wadi-survey-pro'),
            'archives'              => _x('Poll archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wadi-survey-pro'),
            'insert_into_item'      => _x('Insert into Survey', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wadi-survey-pro'),
            'uploaded_to_this_item' => _x('Uploaded to this Survey', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wadi-survey-pro'),
            'filter_items_list'     => _x('Filter Poll list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wadi-survey-pro'),
            'items_list_navigation' => _x('Poll list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wadi-survey-pro'),
            'items_list'            => _x('Poll list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wadi-survey-pro'),
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
