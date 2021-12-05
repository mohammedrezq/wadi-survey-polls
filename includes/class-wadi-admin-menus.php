<?php

class WadiSurveyAdminMenus
{

    public function __construct()
    {
        add_action('admin_menu', array($this,'wadiSurveyAdminMenuPage'));
        add_action('admin_menu', array($this, 'add_new_survey_admin_submenu'));
        add_action('admin_menu', array($this, 'register_survery_submissions_menu'));

        add_action('admin_menu', array($this, 'add_new_poll_admin_submenu'));
        add_action('admin_menu', array($this, 'register_poll_submissions_menu'));

        /**
         * Single Survey Table Cannot access directly from submenu (but you can from the survey table in survey submissions)
         */
        add_action('admin_menu', array($this, 'register_survery_submissions_menu_single'));

        add_action('admin_menu', array($this, 'register_poll_submissions_menu_single'));

    }

    /**
     * Wadi Admin Page
     */

     /**
      * Register a custom menu page.
      */
    public function wadiSurveyAdminMenuPage()
    {

        add_menu_page(
            esc_html__('Wadi Survey', 'wadi-survey'),
            esc_html__('Wadi Survey', 'wadi-survey'),
            'manage_options',
            'survey-admin.php',
            '',
            'dashicons-smiley',
            20
        );
    }


    /**
     * Add Survey Submission Submenu
     */

    public function wadi_survey_submissions_callback()
    {
        if (file_exists(plugin_dir_path(__FILE__) . 'survey-submissions.php')) {
            include_once plugin_dir_path(__FILE__) . 'survey-submissions.php';
        }
    }


    public function register_survery_submissions_menu()
    {
        add_submenu_page(
            'survey-admin.php',
            esc_html__('Survey Submissions', 'wadi-survey'),
            esc_html__('Survey Submissions', 'wadi-survey'),
            'manage_options',
            'survey_submissions',
            array($this, 'wadi_survey_submissions_callback'),
            2
        );
    }

    /**
     * Add Survey Submission Submenu
     */

    public function wadi_survey_submissions_callback_single()
    {
        if (file_exists(plugin_dir_path(__FILE__) . 'single-submissions-survey.php')) {
            include_once plugin_dir_path(__FILE__) . 'single-submissions-survey.php';
        }
    }


    public function register_survery_submissions_menu_single()
    {
        add_submenu_page(
            null,
            esc_html__('Single Survey Submissions', 'wadi-survey'),
            esc_html__('Single Survey Submissions', 'wadi-survey'),
            'manage_options',
            'single_survey',
            array($this, 'wadi_survey_submissions_callback_single'),
            100
        );
    }

    /**
     * Add New Survey Post Type
     */

    public function add_new_survey_admin_submenu()
    {
        add_submenu_page('survey-admin.php', 'Add New Survey', 'Add Survey', 'manage_options', 'post-new.php?post_type=wadi-survey', '', 1);
    }


    /**
     * Add New Poll Post Type
     */

    public function add_new_poll_admin_submenu()
    {
        add_submenu_page('survey-admin.php', 'Add New Poll', 'Add Poll', 'manage_options', 'post-new.php?post_type=wadi-poll', '', 10);
    }




    /**
     * Add Poll Submission Submenu
     */

    public function wadi_poll_submissions_callback()
    {        
        if (file_exists(plugin_dir_path(__FILE__) . 'poll-submissions.php')) {
            include_once plugin_dir_path(__FILE__) . 'poll-submissions.php';
        }
    }


    public function register_poll_submissions_menu()
    {
        add_submenu_page(
            'survey-admin.php',
            esc_html__('Poll Submissions', 'wadi-survey'),
            esc_html__('Poll Submissions', 'wadi-survey'),
            'manage_options',
            'poll_submissions',
            array($this, 'wadi_poll_submissions_callback'),
            200
        );
    }



    
    /**
     * Add Survey Submission Submenu
     */

    public function wadi_poll_submissions_callback_single()
    {
        if (file_exists(plugin_dir_path(__FILE__) . 'single-submissions-poll.php')) {
            include_once plugin_dir_path(__FILE__) . 'single-submissions-poll.php';
        }
    }


    public function register_poll_submissions_menu_single()
    {
        add_submenu_page(
            null,
            esc_html__('Single Poll Submissions', 'wadi-survey'),
            esc_html__('Single Poll Submissions', 'wadi-survey'),
            'manage_options',
            'single_poll',
            array($this, 'wadi_poll_submissions_callback_single'),
            100
        );
    }

}

new WadiSurveyAdminMenus;
