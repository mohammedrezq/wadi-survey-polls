<?php

class WadiSurveyEnqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'survey_scripts_init'));
        
        add_action('admin_enqueue_scripts', array($this, 'survey_backend_init'));

        
        add_action('admin_enqueue_scripts', array($this, 'survey_backend_init_single'));
        
        add_action('admin_enqueue_scripts', array($this, 'poll_backend_init_single'));
        
        if (ws_fs()->is__premium_only()) {
            if (ws_fs()->is_premium()) {
                add_action('admin_enqueue_scripts', array($this, 'poll_js_csv_fn__premium_only'));

                add_action('admin_enqueue_scripts', array($this, 'survey_js_csv_fn__premium_only'));
            }
        } else {
            add_action('admin_enqueue_scripts', array($this, 'poll_tooltip_fn'));
    
            add_action('admin_enqueue_scripts', array($this, 'survey_tooltip_fn'));
        }


        add_action('admin_enqueue_scripts', array($this, 'poll_backend_init'));
        
        add_action('wp_head', array($this, 'wadi_survey'));
    }


    
    public function survey_scripts_init()
    {
        wp_enqueue_script('survey_script_front', plugins_url('assets/dist/survey.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
        
        wp_enqueue_style('survey_styles', plugins_url('assets/dist/survey.css', realpath(__DIR__)), false, '1.0.0', 'all');
        
        wp_enqueue_script('survey_script_front_multistep', plugins_url('assets/dist/multistep-survey.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
        
        wp_enqueue_style('survey_multistep_styles', plugins_url('assets/dist/multistep-survey.css', realpath(__DIR__)), false, '1.0.0', 'all');
    
        wp_localize_script('survey-js', 'my_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    
        // Poll
        wp_enqueue_script('poll_script_front', plugins_url('assets/dist/poll.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
        
        wp_enqueue_style('poll_styles', plugins_url('assets/dist/poll.css', realpath(__DIR__)), false, '1.0.0', 'all');
    }
    
    /**
     * Scripts and Styles for Survey Table
     */
    
    public function survey_backend_init($hook)
    {
        if ('wadi-survey_page_survey_submissions' != $hook) {
            return;
        }
        wp_enqueue_style('survey_styles', plugins_url('assets/dist/main.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('survey_js_backend', plugins_url('assets/dist/main.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }
    
    /**
     * Scripts and Styles for Survey Single Table
     */
    
    public function survey_backend_init_single($hook)
    {
        if ('admin_page_single_survey' != $hook) {
            return;
        }
        
        wp_enqueue_style('boot-style_3', plugins_url('assets/scripts/bootstrap.min.css', realpath(__DIR__)), array(), '5.1.3', 'all');
        wp_enqueue_style('datatables_boot-style_3', plugins_url('assets/scripts/dataTables.bootstrap5.min.css', realpath(__DIR__)), array(), '1.11.3', 'all');
        wp_enqueue_script('jquery_datatable_wadi', plugins_url('assets/scripts/jquery.dataTables.min.js', realpath(__DIR__)), array('jquery'), '1.11.3', true);
        wp_enqueue_script('datatable_bootstrap_wadi', plugins_url('assets/scripts/dataTables.bootstrap5.min.js', realpath(__DIR__)), array('jquery'), '1.11.3', true);
        wp_enqueue_style('survey_styles', plugins_url('assets/dist/main.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('survey_js_backend', plugins_url('assets/dist/main.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }

    /**
     * Script for Survey CSV
     */
    
    public function survey_js_csv_fn__premium_only($hook)
    {
        if ('admin_page_single_survey' != $hook) {
            return;
        }

        if (ws_fs()->is_premium()) {
            wp_enqueue_script('survey_js_csv', plugins_url('assets/dist/survey-csv.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
        }
    }
    
    /**
     * Scripts and Styles for Poll Single Table
     */
    
    public function poll_backend_init_single($hook)
    {
        if ('admin_page_single_poll' != $hook) {
            return;
        }
        
        wp_enqueue_style(
            'boot-style_poll',
            plugins_url('assets/scripts/bootstrap.min.css', realpath(__DIR__)),
            array(), '5.1.3', 'all'
        );
        wp_enqueue_style(
            'datatables_boot-style_poll',
            plugins_url('assets/scripts/dataTables.bootstrap5.min.css', realpath(__DIR__)),
            array(), '1.11.3', 'all'
        );
        wp_enqueue_script('jquery_datatable_poll_wadi', plugins_url('assets/scripts/jquery.dataTables.min.js', realpath(__DIR__)), array('jquery'), '1.11.3', true);
        wp_enqueue_script('datatable_bootstrap_poll_wadi', plugins_url('assets/scripts/dataTables.bootstrap5.min.js', realpath(__DIR__)), array('jquery'), '1.11.3', true);
        wp_enqueue_style('poll_single_styles', plugins_url('assets/dist/poll-admin.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('poll_single_js_backend', plugins_url('assets/dist/poll-admin.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }


    /**
     * Script for Survey CSV
     */
    
    public function poll_js_csv_fn__premium_only($hook)
    {
        if ('admin_page_single_poll' != $hook) {
            return;
        }
        if (ws_fs()->is_premium()) {
            wp_enqueue_script('poll_js_csv', plugins_url('assets/dist/poll-csv.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
        }
    }

    
    /**
     * Script for Single Poll Tooltip
     */
    
    public function poll_tooltip_fn($hook)
    {
        if ('admin_page_single_poll' != $hook) {
            return;
        }
        
        wp_enqueue_script('poll_js_tooltip', plugins_url('assets/dist/wadi-tooltip.js', realpath(__DIR__)), array('jquery'), time(), true);
        wp_enqueue_style('poll_css_tooltip', plugins_url('assets/dist/wadi-tooltip.css', realpath(__DIR__)), false, time(), 'all');

    }
    /**
     * Script for Single Poll Tooltip
     */
    
    public function survey_tooltip_fn($hook)
    {
        if ('admin_page_single_survey' != $hook) {
            return;
        }
        
        wp_enqueue_script('survey_js_tooltip', plugins_url('assets/dist/wadi-tooltip.js', realpath(__DIR__)), array('jquery'), time(), true);
        wp_enqueue_style('survey_css_tooltip', plugins_url('assets/dist/wadi-tooltip.css', realpath(__DIR__)), false, time(), 'all');
        
    }
    
    public function wadi_survey()
    {
        echo '<script type="text/javascript">
               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
             </script>';
    }


        
    /**
     * Scripts and Styles for Poll Table
     */
    
    public function poll_backend_init($hook)
    {
        if ('wadi-survey_page_poll_submissions' != $hook) {
            return;
        }
        wp_enqueue_style('poll_styles', plugins_url('assets/dist/poll-admin.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('poll_js_backend', plugins_url('assets/dist/poll-admin.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }
}

new WadiSurveyEnqueue;
