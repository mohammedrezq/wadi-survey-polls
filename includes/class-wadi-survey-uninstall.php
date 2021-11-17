<?php

class WadiSurveyUninstall
{
    public static function uninstall()
    {
        if (! current_user_can('activate_plugins')) {
            return;
        }

        check_admin_referer('bulk-plugins');

        if (__FILE__ != WP_UNINSTALL_PLUGIN) {
            return;
        }


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

register_uninstall_hook(__FILE__, 'WadiSurveyUninstall::uninstall');

