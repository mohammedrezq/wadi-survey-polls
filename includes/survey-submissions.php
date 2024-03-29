<?php
if (! defined('ABSPATH')) {
    exit;
}
?>

<?php
/**
 * Check if String contains substring // https://stackoverflow.com/questions/66519169/call-to-undefined-function-str-contains-php
 */
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

global $wpdb;
$wpdb_table = $wpdb->prefix . 'wadi_survey_submissions';

$query_results = $wpdb->get_results("SELECT
*
FROM
$wpdb_table", ARRAY_A);


$query_survey_ids = $wpdb->get_results("SELECT
DISTINCT user_id, survey_id
FROM
$wpdb_table", ARRAY_A);

?>
<div class="wrap" id="wadi_survey_submissions">
    <div style="display:flex;justify-content:space-between;margin-bottom:30px;">
        <h2><?php esc_html_e('Users Survey Submissions', 'wadi-survey'); ?></h2>
    </div>

    <table class="table" id="survey_table">
        <thead>
            <tr>
                <th scope="col"><?php esc_html_e('User', 'wadi-survey'); ?></th>
                <th scope="col"><?php esc_html_e('Survey', 'wadi-survey'); ?></th>
                <th scope="col lg-col"><?php esc_html_e('Survey/Answers', 'wadi-survey'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($query_survey_ids as $single_result) {
                // User Data
                $user_data = get_userdata($single_result['user_id']);
                
                // Quiz Data
                $surveyId = $single_result['survey_id'];
                $SurveyPermalink = get_edit_post_link($surveyId); ?>
                <tr>
                <?php
                    if (isset($user_data->ID) && !empty($user_data)) {
                        ?>
                        <td style="width: 25%"><a href="<?php echo esc_url(get_edit_user_link($user_data->ID)); ?>" target="_blank"><?php esc_attr_e($user_data->display_name); ?></a></td>
                        <?php
                    } else {
                        ?>
                        <td style="width: 25%"><span>Visitor</span></td>
                            <?php
                    } ?>                    <td style="width: 25%">
                        <a href='<?php echo esc_url(site_url()."/wp-admin/admin.php?page=single_survey&survey_id=$surveyId"); ?>' target="_blank"><?php esc_html_e(get_the_title($surveyId)); ?></a>
                    </td>
                    <?php
                    $theUserId = $single_result['user_id'];
                $theSurveyId = $single_result['survey_id'];

                $survey_answers = $wpdb->prepare(
                    "SELECT DISTINCT
                         questions_answers
                         FROM
                         $wpdb_table WHERE user_id=$theUserId AND survey_id=%s ", $theSurveyId
                );

                $query_survey_answers = $wpdb->get_results($survey_answers, ARRAY_A); ?>

                    </td>
                    <td>
                    <?php foreach ($query_survey_answers as $survey_item) {
                    $surveyQArr=str_replace('\\', '', $survey_item); ?>
                    <div style="border:2px solid #ccc; padding: 12px; margin-bottom: 12px;">
                        <?php
                        
                    foreach ($surveyQArr as $arr) {
                        $v_new=json_decode($arr, true);

                        foreach ($v_new as $key => $item) {
                            echo wp_kses_post("<strong>" . $item['name'] ."</strong>".'<br /><br />');
                            if (isset($item['value'])) {
                                if (str_contains($item['value'], 'wadi_image_pick_')) {
                                    $image_picked_id = str_replace('wadi_image_pick_', '', $item['value']);
                                    echo wp_kses_post(wp_get_attachment_image($image_picked_id) . '<br /><br />' . wp_get_attachment_url($image_picked_id).'<br /><br />');
                                } else {
                                    echo wp_kses_post($item['value'].'<br /><br />');
                                }
                            }
                        }
                    }
                    ?>
                   </div>
                   <?php
                } 
                ?>
                </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>


</div>