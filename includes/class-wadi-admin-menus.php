<?php

class WadiAdminMenus
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_survery_submissions_menu'));
        add_action('admin_menu', array($this, 'register_survery_submissions_menu_single'));
    }

    /**
     * Add Survey Submission Submenu
     */

    public function wadi_survey_submissions_callback()
    {
        if (file_exists(plugin_dir_path(__FILE__) . 'survey-submissions.php')) {
            require_once plugin_dir_path(__FILE__) . 'survey-submissions.php';
        }
    }


    public function register_survery_submissions_menu()
    {
        add_submenu_page(
            'edit.php?post_type=wadi-survey',
            esc_html__('Survey Submissions', 'survey'),
            esc_html__('Survey Submissions', 'survey'),
            'manage_options',
            'survey_submissions',
            array($this, 'wadi_survey_submissions_callback'),
            10
        );
    }

    /**
     * Add Survey Submission Submenu
     */

    public function wadi_survey_submissions_callback_single()
    {
        if (file_exists(plugin_dir_path(__FILE__) . 'single-submissions-survey.php')) {
            require_once plugin_dir_path(__FILE__) . 'single-submissions-survey.php';
        }
    }


    public function register_survery_submissions_menu_single()
    {
        add_submenu_page(
            null,
            esc_html__('Single Survey Submissions', 'survey'),
            esc_html__('Single Survey Submissions', 'survey'),
            'manage_options',
            'single_survey',
            array($this, 'wadi_survey_submissions_callback_single'),
            10
        );
    }
}

new WadiAdminMenus;
