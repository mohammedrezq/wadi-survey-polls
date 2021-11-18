<?php

/**
 * Plugin Name:       Wadi Survey Pro
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

if (function_exists('ws_fs')) {
    ws_fs()->set_basename(true, __FILE__);
} else {
    if ( ! function_exists( 'ws_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ws_fs() {
            global $ws_fs;
    
            if ( ! isset( $ws_fs ) ) {
                // Activate multisite network integration.
                if ( ! defined( 'WP_FS__PRODUCT_9304_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_9304_MULTISITE', true );
                }
    
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';
    
                $ws_fs = fs_dynamic_init( array(
                    'id'                  => '9304',
                    'slug'                => 'wadi-survey',
                    'premium_slug'        => 'wadi-survey-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_81546581cee0f44b8175e04b18816',
                    'is_premium'          => false,
                    'premium_suffix'      => 'pro',
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
                        'first-path'     => 'plugins.php',
                        'contact'        => true,
                        'support'        => true,
                        'account'        => true
                    ),
                    'navigation'        => 'menu',
                    // Set the SDK to work in a sandbox mode (for development & testing).
                    // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                    'secret_key'          => 'sk_BRsA~Q8yZ&kRLoo)9x8P-CqCbj4fe',
                ) );
            }
    
            return $ws_fs;
        }
    
        // Init Freemius.
        ws_fs();
        // Signal that SDK was initiated.
        do_action( 'ws_fs_loaded' );
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
     * Enqueue Wadi Survey Scripts and Styles
     */

    require_once PLUGIN_PATH . 'includes/class-wadi-survey-enqueue.php';



    /**
     * Add Single Survey CSV Export
     */

    require_once PLUGIN_PATH . 'includes/survey-csv.php';


    /**
     * Wadi Survey Uninstall
     */

    // require_once PLUGIN_PATH . 'includes/class-wadi-survey-uninstall.php';

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
    
        // Not like register_uninstall_hook(), you do NOT have to use a static function.
        ws_fs()->add_action('after_uninstall', 'wadi_survey_uninstall_fn');
        function wadi_survey_uninstall_fn() {
            global $wpdb;
            $tableArray = [
                $wpdb->prefix . 'wadi_survey_submissions',
                $wpdb->prefix . 'wadi_poll_submissions'
        
           ];
        
            foreach ($tableArray as $tablename) {
                $wpdb->query("DROP TABLE IF EXISTS $tablename");
            }
          
            // Delete all Wadi Survey Post on install
            $surveyPosts= get_posts(array('post_type'=>'wadi-survey','numberposts'=>-1));
                foreach ($surveyPosts as $surveyPost) {
                    wp_delete_post($surveyPost->ID, true);
                }
            $pollPosts= get_posts(array('post_type'=>'wadi-poll','numberposts'=>-1));
                foreach ($pollPosts as $pollPost) {
                    wp_delete_post($pollPost->ID, true);
                }
        }
}
