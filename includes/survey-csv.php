<?php


function export_survey_results_to_csv() {
    
    // not empty
    
    global $wpdb;
    $wpdb_table = $wpdb->prefix . 'wadi_survey_submissions';
    
    $surveyID = $_POST['paramId'];
    
    $survey_query = $wpdb->prepare("SELECT
    *
    FROM
    $wpdb_table WHERE survey_id=$surveyID");
        
    
    $query_results = $wpdb->get_results($survey_query, ARRAY_A);


    $survey_ids = $wpdb->prepare("SELECT
        DISTINCT user_id, survey_id
        FROM
        $wpdb_table WHERE survey_id=$surveyID");

    $query_survey_ids = $wpdb->get_results($survey_ids, ARRAY_A);


    $headers = array("User ID", "Survey");

    $lead_array = array();

    foreach ($query_results[0] as $survey_item) {

        $surveyQArr=str_replace('\\','', $survey_item);

        
        $v_new=json_decode($surveyQArr,true);

            foreach ($v_new as $key => $item) {
                array_push($headers, $item['name']);
            }
            
    }

    $answers_query = $wpdb->prepare("SELECT
    questions_answers, user_id
    FROM
    $wpdb_table WHERE survey_id=$surveyID");

    $answers_query_results = $wpdb->get_results($answers_query, ARRAY_A);

    $query_results = $wpdb->get_results($survey_query, ARRAY_A);

    foreach ($query_survey_ids as $single_result) {
        $user_data = get_userdata($single_result['user_id']);
        $surveyId = $single_result['survey_id'];
        $SurveyPermalink = get_edit_post_link($surveyId);
        
    }

    
    // Filling CSV File

    $output_handle = @fopen( 'php://output', 'w' ); //phpcs:ignore

    $first = true;

    fputcsv( $output_handle, $headers );

    $first = false;

    foreach ($answers_query_results as $survey_item) { 
        
        $user_data = get_userdata($survey_item['user_id']);
        $lead_array['user'] =  $user_data->display_name;
        $lead_array['survey'] = get_the_title($surveyId);
        $lead_array['answers'] = array();
        $surveyAnswersArr=str_replace('\\','', $survey_item);

        foreach ($surveyAnswersArr as $arr) {

            $v_new=json_decode($arr,true);

            

            // $lead_array['name'] = $v_new;
            foreach ($v_new as $key => $item) {
                array_push($lead_array['answers'], $item['value']);
            }
        }

 

        $surveysQuestions[] = array(
            'User' => $lead_array['user'],
            'Survey' => $lead_array['survey'],
            'Answers' => $lead_array['answers'],
        );
        
        // echo '<pre>';
        // print_r($surveysQuestions['User']);
        // echo '</pre>';
        
    }

    function array_flatten($array = null) {
        $result = array();
    
        if (!is_array($array)) {
            $array = func_get_args();
        }
    
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
    
        return $result;
    }
    foreach ( $surveysQuestions as $row ) {

        if(!isset($row['User'])) {
            $row['User'] = 'Visitor';
        }
        


        $lead_array = (array) $row; // Cast the Object to an array
        $nArr = array_flatten($lead_array);
        // Add row to file
        fputcsv( $output_handle, $nArr );
    }

    fclose( $output_handle ); //phpcs:ignore
    die();

}
add_action('wp_ajax_export_survey_results_to_csv', 'export_survey_results_to_csv');