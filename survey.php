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
 * Text Domain:       wqsp
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


require_once PLUGIN_PATH . 'includes/class-posttypes.php';
register_activation_hook(__FILE__, 'wadi_rewrite_flush');

/**
 * Add in custom fields.
 */
require_once PLUGIN_PATH . 'includes/class-survey-backend.php';

/**
 * Add Shortcode
 */

require_once PLUGIN_PATH . 'includes/survey-shortcode.php';

/**
 * Add Survey Database Table
 */

require_once PLUGIN_PATH . 'includes/class-survey-db.php';

/**
 * Add Survey submenu page
 */

require_once PLUGIN_PATH . 'includes/class-wadi-admin-menus.php';

/**
 * Add Single Survey Submissions Page
 */

// require_once PLUGIN_PATH . 'includes/single-submissions-survey.php';

/**
 * Enqueue Wadi Survey Scripts and Styles
 */

require_once PLUGIN_PATH . 'includes/class-wadi-survey-enqueue.php';

/**
 * Disable Gutenberg on Survey Custom Post Type 
 */
add_filter('use_block_editor_for_post_type', 'survey_disable_gutenberg', 10, 2);
function survey_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wadi-survey') return false;
    return $current_status;
}

/**
 * Add Shortcode to Survey Custom Post Type Column
 */
add_filter('manage_wadi-survey_posts_columns', 'shortcode_survey_columns_head');

function shortcode_survey_columns_head($defaults)
{
    $defaults['shortcode']  = 'Shortcode';
    return $defaults;
}

add_action('manage_wadi-survey_posts_custom_column', 'survey_shortcode_column_head_content', 10, 2);
function survey_shortcode_column_head_content($column_name, $post_ID)
{


    /**
     * Shortcode in Survey Post Type 
     * 
     * Script to copy shortcode on click
     */
    if ('shortcode' === $column_name) {
        echo '<script type="text/javascript">
        /* Get the text field */

        window.addEventListener("DOMContentLoaded", (event) => {
            function wadi_survey_copy_shortcode() {
    
                var copyText = document.querySelectorAll(".wadi_survey_shortcode");
    
                var copyTextArr = Array.from(copyText);
    
                copyTextArr.map(elem => {
                    elem.addEventListener("click", function(e){
                        e.target.setSelectionRange(0, e.target.value.length);
                        navigator.clipboard.writeText(e.target.value);
                    })
                
                })
    
            };
            wadi_survey_copy_shortcode();
        });
        
        </script>
        ';

        echo '<input class="wadi_survey_shortcode" type="text" readonly="" value="[wadi-survey id=&quot;' . $post_ID . '&quot;]">';
    }
}

/* Filter the single_template with our custom function*/
// add_filter('single_template', 'survey_post_type_template');

// function survey_post_type_template($template)
// {

//     global $post;

//     /* Checks for survey template by post type */
//     if ($post->post_type == 'wadi-survey') {
//         if (file_exists(PLUGIN_PATH . '/single-wadi-survey.php')) {
//             return PLUGIN_PATH . '/single-wadi-survey.php';
//         }
//     }

//     return $template;
// }


add_filter( 'single_template', 'override_single_template' );
function override_single_template( $single_template ){
    global $post;

    $file = PLUGIN_PATH .'/single-'. $post->post_type .'.php';

    if( file_exists( $file ) ) $single_template = $file;

    return $single_template;
}


