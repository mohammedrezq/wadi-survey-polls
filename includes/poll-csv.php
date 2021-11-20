<?php
 if (! defined('ABSPATH')) {
     exit;
 }
 /**
  * Only Premium users can download Poll CSV
  */
if ( ws_fs()->is_premium() ) {
    /**
     * Check if String contains substring // https://stackoverflow.com/questions/66519169/call-to-undefined-function-str-contains-php
     */
    if (!function_exists('str_contains')) {
        function str_contains(string $haystack, string $needle): bool
        {
            return '' === $needle || false !== strpos($haystack, $needle);
        }
    }
    function export_poll_results_to_csv()
    {
    
    // not empty
    
        global $wpdb;
        $wpdb_table = $wpdb->prefix . 'wadi_poll_submissions';
    
        $pollID = $_POST['paramPollId'];
    
        $poll_query = $wpdb->prepare("SELECT
    *
    FROM
    $wpdb_table WHERE poll_id=$pollID");
        
    
        $query_results = $wpdb->get_results($poll_query, ARRAY_A);


        $poll_ids = $wpdb->prepare("SELECT
        DISTINCT user_id, poll_id
        FROM
        $wpdb_table WHERE poll_id=$pollID");

        $query_poll_ids = $wpdb->get_results($poll_ids, ARRAY_A);


        $headers = array("User ID", "poll");

        $lead_array = array();

        foreach ($query_results[0] as $poll_item) {
            $pollQArr=str_replace('\\', '', $poll_item);

        
            $v_new=json_decode($pollQArr, true);

            foreach ($v_new as $key => $item) {
                array_push($headers, $item['name']);
            }
        }

        $answers_query = $wpdb->prepare("SELECT
    questions_answers, user_id
    FROM
    $wpdb_table WHERE poll_id=$pollID");

        $answers_query_results = $wpdb->get_results($answers_query, ARRAY_A);

        $query_results = $wpdb->get_results($poll_query, ARRAY_A);

        foreach ($query_poll_ids as $single_result) {
            $user_data = get_userdata($single_result['user_id']);
            $pollId = $single_result['poll_id'];
            $pollPermalink = get_edit_post_link($pollId);
        }

        // Filling CSV File

        $output_handle = @fopen('php://output', 'w'); //phpcs:ignore

        $first = true;

        fputcsv($output_handle, $headers);

        $first = false;

        foreach ($answers_query_results as $poll_item) {
            $user_data = get_userdata($poll_item['user_id']);
            $lead_array['user'] =  $user_data->display_name;
            $lead_array['poll'] = get_the_title($pollId);
            $lead_array['answers'] = array();
            $pollAnswersArr=str_replace('\\', '', $poll_item);

            foreach ($pollAnswersArr as $arr) {
                $v_new=json_decode($arr, true);

                foreach ($v_new as $key => $item) {
                    if (str_contains($item['value'], 'poll_wadi_image_pick_')) {
                        $image_picked_id = str_replace('poll_wadi_image_pick_', '', $item['value']);
                        wp_get_attachment_url($image_picked_id);
                        array_push($lead_array['answers'], wp_get_attachment_url($image_picked_id));
                    } else {
                        array_push($lead_array['answers'], $item['value']);
                    }
                }
            }


            $pollsQuestions[] = array(
            'User' => $lead_array['user'],
            'poll' => $lead_array['poll'],
            'Answers' => $lead_array['answers'],
        );
        }

        function array_flatten($array = null)
        {
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

        foreach ($pollsQuestions as $row) {
            if (!isset($row['User'])) {
                $row['User'] = 'Visitor';
            }
        
            $lead_array = (array) $row; // Cast the Object to an array
            $nArr = array_flatten($lead_array);
        
            // Add row to file
            fputcsv($output_handle, $nArr);
        }

        fclose($output_handle); //phpcs:ignore
        die();
    }
    add_action('wp_ajax_export_poll_results_to_csv', 'export_poll_results_to_csv');
}
