<h1>Hello Poll Submissions</h1>

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
$wpdb_table = $wpdb->prefix . 'wadi_poll_submissions';

$poll_query = $wpdb->prepare("SELECT
*
FROM
$wpdb_table");

$query_results = $wpdb->get_results($poll_query, ARRAY_A);

$poll_ids = $wpdb->prepare("SELECT
        DISTINCT user_id, poll_id
        FROM
        $wpdb_table");

$query_poll_ids = $wpdb->get_results($poll_ids, ARRAY_A);


?>

<div class="wrap" id="wadi_poll_submissions">
    <div style="display:flex;justify-content:space-between;margin-bottom:30px;">
        <h2>Users Poll Submissions</h2>
    </div>

    <table class="table" id="poll_table">
        <thead>
            <tr>
                <th scope="col">User</th>
                <th scope="col">Poll</th>
                <th scope="col lg-col">Poll/Answers</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($query_poll_ids as $single_result) {
                // User Data
                $user_data = get_userdata($single_result['user_id']);
                
                // Quiz Data
                $pollId = $single_result['poll_id'];
                $pollPermalink = get_edit_post_link($pollId); ?>

                <tr>
                    <?php
                    if (isset($user_data->ID) && !empty($user_data)) {
                        ?>
                        <td style="width: 25%"><a href="<?php echo get_edit_user_link($user_data->ID); ?>" target="_blank"><?php echo $user_data->display_name; ?></a></td>
                        <?php
                    } else {
                        ?>
                        <td style="width: 25%"><span>Visitor</span></td>
                            <?php
                    } ?>
                    <td style="width: 25%">
                        <a href='<?php echo site_url()."/wp-admin/admin.php?page=single_poll&poll_id=$pollId" ?>' target="_blank"><?php echo get_the_title($pollId); ?></a>
                    </td>
                    <?php
                    $theUserId = $single_result['user_id'];
                $thePollId = $single_result['poll_id'];

                $poll_answers = $wpdb->prepare(
                    "SELECT DISTINCT
                         questions_answers
                         FROM
                         $wpdb_table WHERE user_id=$theUserId AND poll_id=$thePollId "
                );

                $query_poll_answers = $wpdb->get_results($poll_answers, ARRAY_A); ?>

                    </td>
                    <td>
                    <?php foreach ($query_poll_answers as $poll_item) {
                    $pollQArr=str_replace('\\', '', $poll_item); ?>
                    <div style="border:2px solid #ccc; padding: 12px; margin-bottom: 12px;">
                        <?php
                        foreach ($pollQArr as $arr) {
                            $v_new=json_decode($arr, true);

                            foreach ($v_new as $key => $item) {
                                echo "<strong>" . $item['name'] ."</strong>".'<br /><br />';
                                if (isset($item['value'])) {
                                    if (str_contains($item['value'], 'poll_wadi_image_pick_')) {
                                        $image_picked_id = str_replace('poll_wadi_image_pick_', '', $item['value']);
                                        echo wp_get_attachment_image($image_picked_id) . '<br /><br />' . wp_get_attachment_url($image_picked_id).'<br /><br />';
                                    } else {
                                        echo $item['value'].'<br /><br />';
                                    }
                                }
                            }
                        }
                    echo "</div>";
                } ?>
                    </td>
                </tr>

            <?php
            }
                ?>
                </tbody>
    </table>

</div>