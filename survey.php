<?php

/**
 * Plugin Name:       Survey
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mohammed Rezq
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       survey
 * Domain Path:       /languages
 */
/*
Survey is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Survey is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Survey. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */


/**
 * Register Task Post Type
 */


define('PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once plugin_dir_path(__FILE__) . 'includes/posttypes.php';
register_activation_hook(__FILE__, 'survey_rewrite_flush');

/**
 * Register Task Logger Role
 */

// require_once plugin_dir_path(__FILE__) . 'includes/roles.php';
// register_activation_hook(__FILE__, 'taskbook_register_role');
// register_deactivation_hook(__FILE__, 'taskbook_remove_role');

/**
 * Add in CMB2 for fun new fields.
 */
// require_once plugin_dir_path(__FILE__) . 'includes/survey-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/carbon-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/survey-shortcode.php';






add_filter('use_block_editor_for_post_type', 'survey_disable_gutenberg', 10, 2);
function survey_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'survey') return false;
    return $current_status;
}


add_filter('manage_survey_posts_columns', 'wpso_custom_columns_head');
function wpso_custom_columns_head($defaults)
{
    $defaults['shortcode']  = 'Shortcode';
    return $defaults;
}

add_action('manage_survey_posts_custom_column', 'wpso_custom_columns_content', 10, 2);
function wpso_custom_columns_content($column_name, $post_ID)
{
    if ('shortcode' === $column_name) {
        echo '[survey id="' . $post_ID . '"]';
    }
}
// add_filter('maange_survey_posts_columns', 'wpso_custom_columns_head');
// function wpso_custom_columns_head($defaults) {
//     $defaults['shortcode']  = 'Shortcode';
//     return $defaults;
// }

// add_action('maange_survey_posts_custom_column', 'wpso_custom_columns_content', 10, 2);
// function wpso_custom_columns_content( $column_name, $post_ID ) {
//     if ( 'shortcode' === $column_name ) {
//         echo '[survey id="' . $post_ID . '"]';
//     }
// }

/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single)
{

    global $post;

    /* Checks for single template by post type */
    if ($post->post_type == 'survey') {
        if (file_exists(PLUGIN_PATH . 'single-survey.php')) {
            return PLUGIN_PATH . 'single-survey.php';
        }
    }

    return $single;
}



