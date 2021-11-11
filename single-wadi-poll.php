<?php

/**
 * Single Post type for Poll
 */
get_header();

$the_post_id =  get_the_ID();
$the_current_user_id = get_current_user_id();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



    <div class="entry-content">

    <?php
        $allow_multiple_responses_poll =  carbon_get_post_meta(get_the_ID(), 'wadi_poll_multiple_responses');
        $the_current_user_id = get_current_user_id();
        $the_current_post_id = get_the_ID();
        $table_name = $wpdb->prefix . 'wadi_poll_submissions';

        $polll_already_taken_message =  carbon_get_post_meta(get_the_ID(), 'wadi_poll_already_taken_message');


        global $wpdb;

        $existedRow = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM " . $table_name . "
				WHERE user_id = %d AND poll_id = %d LIMIT 1",
                $the_current_user_id,
                $the_current_post_id
            )
        );


    if (!isset($existedRow)) { // Check if poll item row exist in database
        require_once PLUGIN_PATH . 'templates/poll-single.php';
    } 
    elseif (isset($existedRow) && $allow_multiple_responses_poll == true) {

        require_once PLUGIN_PATH . 'templates/poll-single.php';
    } 
    elseif (isset($existedRow) && $allow_multiple_responses_poll == false) {
        ?>
			<p><?php echo $polll_already_taken_message; ?></p>
			<?php
    }

    ?>


        

    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php

get_footer();
