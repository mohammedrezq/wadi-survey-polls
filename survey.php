<?php

/**
 * Plugin Name:       Wadi Survey
 * Plugin URI:        https://www.wadiweb.com
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Wadi Web
 * Author URI:        https://www.wadiweb.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://www.wadiweb.com
 * Text Domain:       wadi-survey
 * Domain Path:       /languages
 */
/*
Wadi Survey is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Wadi Survey is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Wadi Survey. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('ws_fs')) {
    // Create a helper function for easy SDK access.
    function ws_fs()
    {
        global $ws_fs;

        if (! isset($ws_fs)) {
            // Activate multisite network integration.
            if (! defined('WP_FS__PRODUCT_9304_MULTISITE')) {
                define('WP_FS__PRODUCT_9304_MULTISITE', true);
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $ws_fs = fs_dynamic_init(array(
                'id'                  => '9304',
                'slug'                => 'wadi-survey',
                'type'                => 'plugin',
                'public_key'          => 'pk_81546581cee0f44b8175e04b18816',
                'is_premium'          => true,
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'trial'               => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                'has_affiliation'     => 'selected',
                'menu'                => array(
                    'first-path'     => 'admin.php?page=wadi-survey-account',
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'sk_3yV{a7t>G5-7Um#&@8X<$.IGB-hXU',
            ));
        }

        return $ws_fs;
    }

    // Init Freemius.
    ws_fs();
    // Signal that SDK was initiated.
    do_action('ws_fs_loaded');
}

 /**
  * Define Paths
  */

define('PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Register Task Post Type
 */


require_once PLUGIN_PATH . 'includes/class-posttypes.php';

/**
 * Add in custom fields.
 */
require_once PLUGIN_PATH . 'includes/class-wadi-backend.php';

/**
 * Add Survey Shortcode
 */

require_once PLUGIN_PATH . 'includes/survey-shortcode.php';

/**
 * Add Poll Shortcode
 */

require_once PLUGIN_PATH . 'includes/poll-shortcode.php';

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
 * Add Single Survey CSV Export
 */

require_once PLUGIN_PATH . 'includes/survey-csv.php';

/**
 * Add Single Poll CSV Export
 */

require_once PLUGIN_PATH . 'includes/poll-csv.php';

/**
 * Disable Gutenberg on Survey Custom Post Type
 */
add_filter('use_block_editor_for_post_type', 'survey_disable_gutenberg', 10, 2);
function survey_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wadi-survey') {
        return false;
    }
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



/**
 * Disable Gutenberg on Poll Custom Post Type
 */
add_filter('use_block_editor_for_post_type', 'poll_disable_gutenberg', 10, 2);
function poll_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wadi-poll') {
        return false;
    }
    return $current_status;
}


/**
 * Add Shortcode to Poll Custom Post Type Column
 */
add_filter('manage_wadi-poll_posts_columns', 'shortcode_poll_columns_head');

function shortcode_poll_columns_head($defaults)
{
    $defaults['shortcode']  = 'Shortcode';
    return $defaults;
}

add_action('manage_wadi-poll_posts_custom_column', 'poll_shortcode_column_head_content', 10, 2);
function poll_shortcode_column_head_content($column_name, $post_ID)
{


    /**
     * Shortcode in Poll Post Type
     *
     * Script to copy shortcode on click
     */
    if ('shortcode' === $column_name) {
        echo '<script type="text/javascript">
        /* Get the text field */

        window.addEventListener("DOMContentLoaded", (event) => {
            function wadi_poll_copy_shortcode() {
    
                var copyText = document.querySelectorAll(".wadi_poll_shortcode");
    
                var copyTextArr = Array.from(copyText);
    
                copyTextArr.map(elem => {
                    elem.addEventListener("click", function(e){
                        e.target.setSelectionRange(0, e.target.value.length);
                        navigator.clipboard.writeText(e.target.value);
                    })
                
                })
    
            };
            wadi_poll_copy_shortcode();
        });
        
        </script>
        ';

        echo '<input class="wadi_poll_shortcode" type="text" readonly="" value="[wadi-poll id=&quot;' . $post_ID . '&quot;]">';
    }
}

/* Filter the single_template with our custom function*/
// add_filter('single_template', 'poll_post_type_template');

// function poll_post_type_template($template)
// {

//     global $post;

//     /* Checks for survey template by post type */
//     if ($post->post_type == 'wadi-poll') {
//         if (file_exists(PLUGIN_PATH . '/single-wadi-poll.php')) {
//             return PLUGIN_PATH . '/single-wadi-poll.php';
//         }
//     }

//     return $template;
// }


add_filter('single_template', 'override_single_template');
function override_single_template($single_template)
{
    global $post;

    $file = PLUGIN_PATH .'single-'. $post->post_type .'.php';

    if (file_exists($file)) {
        $single_template = $file;
    }

    return $single_template;
}


/**
 * Wadi Survey Uninstall Wadi Survey
 *
 * @since 1.0.0
 */

register_uninstall_hook(__FILE__, 'wadi_survey_uninstall');

function wadi_survey_uninstall()
{
    global $wpdb;
    $table_wadi_survey_submissions = $wpdb->prefix . 'wadi_survey_submissions';
    $wpdb->query("DROP TABLE IF EXISTS {$table_wadi_survey_submissions}");

    $table_wadi_poll_submissions = $wpdb->prefix . 'wadi_poll_submissions';
    $wpdb->query("DROP TABLE IF EXISTS {$table_wadi_poll_submissions}");
}
