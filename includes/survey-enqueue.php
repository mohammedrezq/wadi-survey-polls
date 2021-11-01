<?php
add_action('wp_enqueue_scripts', 'survey_scripts_init');

function survey_scripts_init()
{
    wp_enqueue_script('survey_script_front', plugins_url('assets/dist/survey.js', realpath(__DIR__)),  array('jquery'), time(), true);
    // wp_enqueue_script('survey_styles', plugins_url('assets/dist/main.js', realpath(__DIR__)),  array('jquery'), time(), true);

    wp_localize_script('survey-js', 'my_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}

/**
 * Scripts and Styles for Survey Table
 */

function survey_backend_init($hook)
{
    if ('survey_page_survey_submissions' != $hook) {
        return;
    }
    wp_enqueue_style('survey_styles', plugins_url('assets/dist/main.css', realpath(__DIR__)), false, time(), 'all');
    wp_enqueue_script('survey_js_backend', plugins_url('assets/dist/main.js', realpath(__DIR__)), array('jquery'), time(), true);
}
add_action('admin_enqueue_scripts', 'survey_backend_init');

/**
 * Scripts and Styles for Survey Single Table
 */

function survey_backend_init_single($hook)
{
    if ('survey_page_single_survey' != $hook) {
        return;
    }
    
    wp_enqueue_style(
        'boot-style_3',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',
        array(),
    );
    wp_enqueue_style(
        'datatables_boot-style_3',
        'https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css',
        array(),
    );
    wp_enqueue_script( 'jquery_kotw', 'https://code.jquery.com/jquery-3.5.1.js', array(), '3.5.1', true );
    wp_enqueue_script( 'jquery_datatable_kotw', 'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js', array('jquery_kotw'), '1.10.25', true );
    wp_enqueue_script( 'datatable_bootstrap_kotw', 'https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js', array('jquery_kotw'), '1.10.25', true );
    wp_enqueue_style('survey_styles', plugins_url('assets/dist/main.css', realpath(__DIR__)), false, time(), 'all');
    wp_enqueue_script('survey_js_backend', plugins_url('assets/dist/main.js', realpath(__DIR__)), array('jquery'), time(), true);
}
add_action('admin_enqueue_scripts', 'survey_backend_init_single');

add_action('wp_head', 'wadi_survey');

function wadi_survey()
{

    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// function kotw_enqueue_bootstrap_selectively($hook)
// {
//     if ('survey_page_survey_submissions' != $hook) {
//         return;
//     }
//     wp_enqueue_style(
//         'boot-style',
//         'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',
//         array(),
//     );
// }
// add_action('admin_enqueue_scripts', 'kotw_enqueue_bootstrap_selectively');