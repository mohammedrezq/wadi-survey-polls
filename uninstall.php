<?php
/**
 * Unistall Wadi Survey Plugin
 *
 * @since 1.0.0
 *
 */
    if (! defined('WP_UNINSTALL_PLUGIN')) {
        exit();
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
