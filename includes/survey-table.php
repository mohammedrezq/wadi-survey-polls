<?php

/**
 * Add Survey Submission Submenu
 */

function wadi_survey_submissions_callback()
{
    if (file_exists(plugin_dir_path(__FILE__) . 'survey-submissions.php')) {
        require_once plugin_dir_path(__FILE__) . 'survey-submissions.php';
    }

}


function register_survery_submissions_menu() {
    add_submenu_page(
        'edit.php?post_type=wadi-survey',
        esc_html__('Survey Submissions', 'survey'),
        esc_html__('Survey Submissions', 'survey'),
        'manage_options',
        'survey_submissions',
        'wadi_survey_submissions_callback',
        10
        );
}
add_action( 'admin_menu', 'register_survery_submissions_menu');

/**
 * Add Survey Submission Submenu
 */

function wadi_survey_submissions_callback_single()
{
    if (file_exists(plugin_dir_path(__FILE__) . 'single-submissions-survey.php')) {
        require_once plugin_dir_path(__FILE__) . 'single-submissions-survey.php';
    }

}


function register_survery_submissions_menu_single() {
    add_submenu_page(
        null,
        esc_html__('Single Survey Submissions', 'survey'),
        esc_html__('Single Survey Submissions', 'survey'),
        'manage_options',
        'single_survey',
        'wadi_survey_submissions_callback_single',
        10
        );
}
add_action( 'admin_menu', 'register_survery_submissions_menu_single');