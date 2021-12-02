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
$pollID = intval((int)$_GET['poll_id']);

if(!empty( $pollID ) ) {

    global $wpdb;
$wpdb_table = $wpdb->prefix . 'wadi_poll_submissions';



$poll_query = $wpdb->prepare("SELECT
*
FROM
$wpdb_table WHERE poll_id=%d", $pollID);


$query_results = $wpdb->get_results($poll_query, ARRAY_A);



$poll_ids = $wpdb->prepare("SELECT
        DISTINCT user_id, poll_id
        FROM
        $wpdb_table WHERE poll_id=%d", $pollID);

$query_poll_ids = $wpdb->get_results($poll_ids, ARRAY_A);


// Testing DataTables Bootstrap
?>
<div style="display:flex;justify-content:space-between;margin-bottom:30px;">
        <h2>Poll Submissions</h2>
        <?php  if (ws_fs()->is_premium()) {
            ?>
            <button id="export_btn" class="button-primary" data-poll="<?php echo esc_attr($pollID); ?>"><?php esc_html_e('Export to CSV', 'wadi-survey'); ?></button>
            
        <?php } else {

            ?><?php
            echo wp_kses_post('<div class="csv_free_container">
                <button id="export_btn" class="button-primary" data-poll="'.$pollID.'">Export to CSV</button>
                <div class="tooltip_text">Upgarde to enable Single Survey/Poll table Export</div>
            </div>'); ?>
            <?php
        }?>
    </div>
<table id="single_poll_table" class="table table-striped table-bordered wadi_poll_table">
    <thead>
        <tr>
            <th>
            <?php esc_html_e('User', 'wadi-survey'); ?>
            </th>
            <th>
            <?php esc_html_e('Poll', 'wadi-survey'); ?>
            </th>
            
                <?php

                if(isset($query_results[0])) {

                    foreach ($query_results[0] as $poll_item) {
    
                        $pollQArr=str_replace('\\','', $poll_item);
    
                        
                        $v_new=json_decode($pollQArr,true);
    
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
        $wpdb_table WHERE poll_id=%d", $pollID);

        $answers_query_results = $wpdb->get_results($answers_query, ARRAY_A);

        $query_results = $wpdb->get_results($poll_query, ARRAY_A);

    foreach ($query_poll_ids as $single_result) {
        $user_data = get_userdata($single_result['user_id']);
        $pollId = $single_result['poll_id'];
        $PollPermalink = get_edit_post_link($pollId);
    }        
        foreach ($answers_query_results as $poll_item) {
                $user_data = get_userdata($poll_item['user_id']);
                ?>
                <tr>
                    <?php
                    if (isset($user_data->ID)) {
                        ?>
                        <td><a href="<?php echo esc_url(get_edit_user_link($user_data->ID)); ?>" target="_blank"><?php esc_attr_e($user_data->display_name); ?></a></td>
                        <?php
                    } 
                    else {
                        ?>
                        <td><span><?php esc_html_e('Visitor', 'wadi-survey'); ?></span></td>
                            <?php
                    } 
                    ?>  
                    <td>
                    <a href='<?php echo esc_url($PollPermalink); ?>' target="_blank"><?php esc_html_e(get_the_title($pollId)); ?></a>
                    </td>
                    <?php

                $pollQArr=str_replace('\\','', $poll_item);
                

                foreach ($pollQArr as $arr) {


                $v_new=json_decode($arr,true);

                    if(isset($v_new) && is_array($v_new) || is_object($v_new)) {
                        foreach ($v_new as $key => $item) {
                            
                            echo wp_kses_post("<td class='answer_th'>");
                            if(str_contains($item['value'], 'poll_wadi_image_pick_')){
                                $image_picked_id = str_replace('poll_wadi_image_pick_', '', $item['value']);
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