<?php
if (! defined('ABSPATH')) {
    exit;
}
/**
 * Check if String contains substring // https://stackoverflow.com/questions/66519169/call-to-undefined-function-str-contains-php
 */
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}
$surveyID = intval((int)$_GET['survey_id']);
if(!empty( $_GET['survey_id']) ) {

    global $wpdb;
$wpdb_table = $wpdb->prefix . 'wadi_survey_submissions';


$survey_query = $wpdb->prepare("SELECT
*
FROM
$wpdb_table WHERE survey_id=%d", $surveyID);


$query_results = $wpdb->get_results($survey_query, ARRAY_A);



$survey_ids = $wpdb->prepare("SELECT
        DISTINCT user_id, survey_id
        FROM
        $wpdb_table WHERE survey_id=%d", $surveyID);

$query_survey_ids = $wpdb->get_results($survey_ids, ARRAY_A);


// Testing DataTables Bootstrap
?>
<div style="display:flex;justify-content:space-between;margin-bottom:30px;">
        <h2>Survey Submissions</h2>
        <?php  if (ws_fs()->is_premium()) {
            ?>
            <button id="export_btn" class="button-primary" data-survey="<?php echo esc_attr($surveyID); ?>"><?php esc_html_e('Export to CSV', 'wadi-survey'); ?></button>
            
        <?php } else {

            ?><?php
            echo wp_kses_post('<div class="csv_free_container">
                <button id="export_btn" class="button-primary" data-survey="'.$surveyID.'">Export to CSV</button>
                <div class="tooltip_text">Upgarde to enable Single Survey/Poll table Export</div>
            </div>'); ?>
            <?php
        }?>
    </div>
<table id="single_survey_table" class="table table-striped table-bordered wadi_survey_table">
    <thead>
        <tr>
            <th>
                <?php esc_html_e('User ID', 'wadi-survey'); ?>
            </th>
            <th>
                <?php esc_html_e('Survey', 'wadi-survey'); ?>
            </th>
            
                <?php
                if(isset($query_results[0])) {

                    foreach ($query_results[0] as $survey_item) {
    
                        $surveyQArr=str_replace('\\','', $survey_item);
    
                        
                        $v_new=json_decode($surveyQArr,true);
    
                        if(isset($v_new) && is_array($v_new) || is_object($v_new)) {
                            foreach ($v_new as $item) {
                                echo wp_kses_post("<th class='question_th'>");
                                echo esc_attr($item['name']);
                                echo wp_kses_post("</th>");
                            }
                        }
                            
                    }
                }
                ?>
            
        </tr>
    </thead>
    <tbody>

    <?php 

        $answers_query = $wpdb->prepare("SELECT
        questions_answers, user_id
        FROM
        $wpdb_table WHERE survey_id=%d", $surveyID);

        $answers_query_results = $wpdb->get_results($answers_query, ARRAY_A);

        $query_results = $wpdb->get_results($survey_query, ARRAY_A);

    foreach ($query_survey_ids as $single_result) {
        $user_data = get_userdata($single_result['user_id']);
        $surveyId = $single_result['survey_id'];
        $SurveyPermalink = get_edit_post_link($surveyId);
        ?>

<?php

            }        
            foreach ($answers_query_results as $survey_item) { 
                $user_data = get_userdata($survey_item['user_id']);
                ?>
                <tr>
                    <?php
                    if (isset($user_data->ID)) {
                        ?>
                        <td><a href="<?php echo esc_url(get_edit_user_link($user_data->ID)); ?>" target="_blank"><?php esc_attr_e($user_data->display_name); ?></a></td>
                        <?php
                    } else {
                        ?>
                        <td><span><?php esc_html_e('Visitor', 'wadi-survey'); ?></span></td>
                            <?php
                    } ?> 
                    <td>
                    <a href='<?php echo esc_url($SurveyPermalink); ?>' target="_blank"><?php esc_html_e(get_the_title($surveyId)); ?></a>
                    </td>
                    <?php

                $surveyQArr=str_replace('\\','', $survey_item);



                

                foreach ($surveyQArr as $arr) {


                $v_new=json_decode($arr,true);

                    if(isset($v_new) && is_array($v_new) || is_object($v_new)) {
                        foreach ($v_new as $key => $item) {
                            
                            echo wp_kses_post("<td class='answer_th'>");
                            if(str_contains($item['value'], 'wadi_image_pick_')){
                                $image_picked_id = str_replace('wadi_image_pick_', '', $item['value']);
                                echo wp_kses_post(wp_get_attachment_image($image_picked_id) . '<br /><br />' . wp_get_attachment_url($image_picked_id).'<br /><br />');
                            } else {
                                echo esc_attr($item['value']);
                            }

                            echo wp_kses_post("</td>");
                        }
                    }
                
            }
        }
        ?>
        </tr>
        <?php
        ?>




    </tbody>


</table>
<?php
} else {
    header("Location:" .site_url() . "/wp-admin/edit.php?post_type=survey&page=survey_submissions");
}