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
  * Define Paths
  */

define('PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Register Task Post Type
 */


require_once plugin_dir_path(__FILE__) . 'includes/posttypes.php';
register_activation_hook(__FILE__, 'survey_rewrite_flush');

/**
 * Add in custom fields.
 */
require_once plugin_dir_path(__FILE__) . 'includes/carbon-functions.php';

/**
 * Add Shortcode
 */

require_once plugin_dir_path(__FILE__) . 'includes/survey-shortcode.php';

/**
 * Disable Gutenberg on Survey Custom Post Type 
 */
add_filter('use_block_editor_for_post_type', 'survey_disable_gutenberg', 10, 2);
function survey_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'survey') return false;
    return $current_status;
}

/**
 * Add Shortcode to Survey Custom Post Type Column
 */
add_filter('manage_survey_posts_columns', 'shortcode_survey_columns_head');

function shortcode_survey_columns_head($defaults)
{
    $defaults['shortcode']  = 'Shortcode';
    return $defaults;
}

add_action('manage_survey_posts_custom_column', 'survey_shortcode_column_head_content', 10, 2);
function survey_shortcode_column_head_content($column_name, $post_ID)
{
    if ('shortcode' === $column_name) {
        echo '[survey id="' . $post_ID . '"]';
    }
}

/* Filter the single_template with our custom function*/
add_filter('single_template', 'survey_post_type_template');

function survey_post_type_template($template)
{

    global $post;

    /* Checks for survey template by post type */
    if ($post->post_type == 'survey') {
        if (file_exists(PLUGIN_PATH . 'single-survey.php')) {
            return PLUGIN_PATH . 'single-survey.php';
        }
    }

    return $template;
}



