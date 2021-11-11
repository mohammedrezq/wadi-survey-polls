<?php


$the_post_id =  get_the_ID();
$the_current_user_id = get_current_user_id();
$redirect_check =  carbon_get_post_meta($the_post_id, 'wadi_poll_redirect_to');
$poll_redirect_url =  carbon_get_post_meta($the_post_id, 'wadi_poll_redirect_link');
$poll_redirect_time =  carbon_get_post_meta($the_post_id, 'wadi_poll_settimeout');
$poll_finish_message =  carbon_get_post_meta($the_post_id, 'wadi_poll_finishing_message');
$poll_already_taken_message =  carbon_get_post_meta($the_post_id, 'wadi_poll_already_taken_message');
$allow_multiple_responses_poll =  carbon_get_post_meta($the_post_id, 'wadi_poll_multiple_responses');
?>
<form method="POST" class="poll_container" data-poll-id="<?php echo $the_post_id; ?>" data-user-id="<?php echo $the_current_user_id; ?>" data-post-type="<?php echo get_post_type($the_post_id); ?>">

    <div class="poll_questions_conatiner">

        <?php
        $poll_question_type = carbon_get_post_meta(get_the_ID(), 'select_poll_question_type');

        /**
         * Poll Single Choice Questions
         */
        if ($poll_question_type === 'poll_single_choice') {
            $poll_single_question = carbon_get_post_meta(get_the_ID(), 'poll_single_question');
            $poll_single_answers = carbon_get_post_meta(get_the_ID(), 'poll_single_answers'); ?>

            <div class="poll_single_question_container">
                <div class="wadi_poll_single_question">
                    <?php echo $poll_single_question; ?>
                </div>

                <?php

                foreach ($poll_single_answers as $poll_single_answer) {
                    ?>
                    <div class="poll_single_question poll_custom_control">
                        <input type="radio" id="customRadio_<?php echo $poll_single_answer['poll_single_text_answers']; ?>" value="<?php echo $poll_single_answer['poll_single_text_answers']; ?>" name="<?php echo $poll_single_question; ?>" class="custom-control-input">
                        <label class="poll_single_question_label" for="customRadio_<?php echo $poll_single_answer['poll_single_text_answers']; ?>"><?php echo $poll_single_answer['poll_single_text_answers']; ?></label>
                    </div>

                <?php
                } ?>
            </div>
        <?php
        }

        /**
         * Muliple Choices Questions
         */
        if ($poll_question_type === 'poll_multiple_choices') {
            $poll_multi_answer_question = carbon_get_post_meta(get_the_ID(), 'multiple_question');
            $poll_multiple_answers = carbon_get_post_meta(get_the_ID(), 'poll_multiple_answers');

            //Cleaning Up the Question to be multiple container ID
            $multiple_question_cleanup = strip_tags($poll_multi_answer_question);
            $theQuestion =  preg_replace('/\s+/', '', $multiple_question_cleanup);
            $theQuestionCleaned =  trim($theQuestion, " \t\n\r\0\x0B\xC2\xA0");
            $theQuestionCleaned = preg_replace('/[^A-Za-z0-9\-]/', '', $theQuestionCleaned);
            $theQuestionCleaned = preg_replace('/[?]/', '', $theQuestionCleaned);
            // End of cleaning up the question to be multiple container ID?>
            <div class="poll_multiple_container" id="<?php echo $theQuestionCleaned ?>">
                <div class="poll_multiple_choices_question"><?php echo $poll_multi_answer_question; ?></div>
                <input type="hidden" class="poll_multiple_choice_question_answers" name="<?php echo $multiple_question_cleanup; ?>" value="" />
                <?php
                foreach ($poll_multiple_answers as $multiple_text_answers) {
                    $available_multiple_answers = $multiple_text_answers['poll_multiple_text_answers'];
                    $theAnswerCleanup = trim(preg_replace('/\s+/', '', $available_multiple_answers)); ?>
                    <div class="custom-control custom-radio">
                        <input type="checkbox" id="poll_customCheckbox_<?php echo $available_multiple_answers; ?>" data-answer="<?php echo $available_multiple_answers; ?>" data-question="<?php echo $multiple_question_cleanup; ?>" class="poll_custom-control-input">
                        <label class="poll_custom-control-label" for="poll_customCheckbox_<?php echo $available_multiple_answers; ?>"><?php echo $available_multiple_answers; ?></label>
                    </div>
                <?php
                } ?>
            </div>

        <?php
        }
        /**
         * Rating Scale Question
         */
        if ($poll_question_type === 'poll_rating_question') {
            $rating_question = carbon_get_post_meta(get_the_ID(), 'rating_question');
            $start_rating_scale_range = carbon_get_post_meta(get_the_ID(), 'rating_question_number_1');
            $end_rating_scale_range = carbon_get_post_meta(get_the_ID(), 'rating_question_number_2');
            $rating_scale_starting_text = carbon_get_post_meta(get_the_ID(), 'rating_scale_question_starting');
            $rating_scale_ending_text = carbon_get_post_meta(get_the_ID(), 'rating_scale_question_ending'); ?>
            <div class="poll_rating_scale_container">

                <div class="rating_scale_question"><?php echo $rating_question; ?></div>
                <div class="poll_rating_scale_answer_container" data-start-rating-scale-range="<?php echo $start_rating_scale_range; ?>" data-end-rating-scale-range="<?php echo $end_rating_scale_range; ?>">
                    <div class="rating_scale_answer_text_container">
                        <div class="rating_scale_question_starting">
                            <?php echo $rating_scale_starting_text; ?>
                        </div>
                        <div class="rating_scale_question_ending">
                            <?php echo $rating_scale_ending_text; ?>
                        </div>
                    </div>
                    <?php
                    $starting = $start_rating_scale_range;
            $ending = $end_rating_scale_range; ?>
                    <ul class="rating_scale_answers">
                        <?php
                        for ($i = $starting; $i <= $ending; $i++) {
                            ?>
                            <li class="poll_rating_scale_item rating_range_item_<?php echo $i; ?>">
                                <input type='radio' id="id_<?php echo $i ?>" class='radio_input' name="<?php echo $rating_question; ?>" value="<?php echo $i; ?>" />
                                <label id="rating_scale_label" for="id_<?php echo $i ?>"><?php echo $i; ?></label>
                            </li>
                        <?php
                        } ?>
                    </ul>

                </div>
            </div>

        <?php
        }

        /**
         * Poll Radio Image Question (select radio image)
         */
        if ($poll_question_type === 'poll_radio_image_question') {

            // Poll Image Radio Question
            $image_pick_question = carbon_get_post_meta(get_the_ID(), 'poll_image_pick_question');
            $imageQuestionCleanup = trim(preg_replace('/\s+/', '', $image_pick_question));
            $theImageQuestionCleaned =  trim($imageQuestionCleanup, " \t\n\r\0\x0B\xC2\xA0");
            $theImageQuestionCleaned = preg_replace('/[^A-Za-z0-9\-]/', '', $imageQuestionCleanup);
            $theImageQuestionCleaned = preg_replace('/[?]/', '', $imageQuestionCleanup);
            // Poll Image Radio Question Answers
            $images_answers = carbon_get_post_meta(get_the_ID(), 'poll_images_answers'); ?>
        <div class="poll_image_question_radio_container">
            <div class="poll_image_picking_question" id="<?php echo $theImageQuestionCleaned; ?>">
                <?php echo $image_pick_question; ?>
            </div>
            <div class="poll_images_answers_container">
                <ul class="poll_image_question_answers">
                    <?php
                    foreach ($images_answers as $image_answer) {
                        ?>
                        <li>
                            <input type="radio" name="<?php echo $image_pick_question; ?>" value="poll_wadi_image_pick_<?php echo $image_answer['poll_image_radio_answer']; ?>" id="<?php echo $image_answer['poll_image_radio_answer']; ?>">
                            <label for="<?php echo $image_answer['poll_image_radio_answer']; ?>"><?php echo wp_get_attachment_image($image_answer['poll_image_radio_answer']); ?></label>
                        </li>               
                        <?php
                    } ?>

                </ul>
            </div>
        </div>
        <?php
        }



        ?>
    </div>
    <button type="submit" class="wadi_poll_submit">Submit</button>

</form>
<input type="hidden" data-poll-finish-message='<?php echo $poll_finish_message; ?>' data-poll-already-taken-message='<?php echo $poll_already_taken_message; ?>' class="poll_redirect_url" data-poll-redirect-time='<?php echo $poll_redirect_time; ?>' data-poll-redirect-url='<?php echo $poll_redirect_url; ?>' />