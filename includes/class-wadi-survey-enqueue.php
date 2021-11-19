<?php

class WadiEnqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'survey_scripts_init'));
        
        add_action('admin_enqueue_scripts', array($this, 'survey_backend_init'));

        
        add_action('admin_enqueue_scripts', array($this, 'survey_backend_init_single'));
        
        add_action('admin_enqueue_scripts', array($this, 'poll_backend_init_single'));
        
        if (ws_fs()->is_premium() || ws_fs()->is_trial()) {

            add_action('admin_enqueue_scripts', array($this, 'poll_js_csv_fn'));

            add_action('admin_enqueue_scripts', array($this, 'survey_js_csv_fn'));
            
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
        
        wp_enqueue_style('boot-style_3', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');
        wp_enqueue_style('datatables_boot-style_3', 'https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css', array(), '1.10.25', 'all');
        wp_enqueue_script('jquery_wadi', 'https://code.jquery.com/jquery-3.5.1.js', array(), '3.5.1', true);
        wp_enqueue_script('jquery_datatable_wadi', 'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js', array('jquery'), '1.10.25', true);
        wp_enqueue_script('datatable_bootstrap_wadi', 'https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js', array('jquery'), '1.10.25', true);
        wp_enqueue_style('survey_styles', plugins_url('assets/dist/main.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('survey_js_backend', plugins_url('assets/dist/main.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }

    /**
     * Script for Survey CSV
     */
    
    public function survey_js_csv_fn($hook)
    {
        if ('admin_page_single_survey' != $hook) {
            return;
        }

        if (ws_fs()->is_premium() || ws_fs()->is_trial()) {
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
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',
            array(), '4.5.2', 'all'
        );
        wp_enqueue_style(
            'datatables_boot-style_poll',
            'https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css',
            array(), '1.10.25', 'all'
        );
        wp_enqueue_script('jquery_poll_wadi', 'https://code.jquery.com/jquery-3.5.1.js', array(), '3.5.1', true);
        wp_enqueue_script('jquery_datatable_poll_wadi', 'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js', array('jquery'), '1.10.25', true);
        wp_enqueue_script('datatable_bootstrap_poll_wadi', 'https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js', array('jquery'), '1.10.25', true);
        wp_enqueue_style('poll_single_styles', plugins_url('assets/dist/poll-admin.css', realpath(__DIR__)), false, '1.0.0', 'all');
        wp_enqueue_script('poll_single_js_backend', plugins_url('assets/dist/poll-admin.js', realpath(__DIR__)), array('jquery'), '1.0.0', true);
    }


    /**
     * Script for Survey CSV
     */
    
    public function poll_js_csv_fn($hook)
    {
        if ('admin_page_single_poll' != $hook) {
            return;
        }
        if (ws_fs()->is_premium() || ws_fs()->is_trial()) {
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

new WadiEnqueue;
