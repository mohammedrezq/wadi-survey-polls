<?php
$the_post_id =  get_the_ID();
$the_current_user_id = get_current_user_id();
$redirect_url =  carbon_get_post_meta($the_post_id, 'wadi_survey_redirect_link');
$redirect_time =  carbon_get_post_meta($the_post_id, 'wadi_survey_settimeout');
$survey_finish_message =  carbon_get_post_meta($the_post_id, 'wadi_survey_finishing_message');
$survey_already_taken_message =  carbon_get_post_meta($the_post_id, 'wadi_survey_already_taken_message');

?>
<form method="POST" class="survey_container" data-survey-id="<?php echo $the_post_id; ?>" 
data-user-id="<?php echo $the_current_user_id; ?>"
data-post-type="<?php echo get_post_type($the_post_id); ?>"
>
<div class="survey_questions_conatiner">
    <?php
    $survey_items = carbon_get_post_meta(get_the_ID(), 'survey_items');
    // echo '<pre>';
    // print_r($survey_items);
    // echo '</pre>';

    if (!empty($survey_items)) :
        foreach ($survey_items as $survey_item) {
            /**
             * Single Choice Questions
             */
            if ($survey_item['select_survey_question_type'] == 'single_choice') {
    ?>
                <div class="single_question"><?php echo $survey_item['single_question']; ?></div>
                <?php
                foreach ($survey_item['single_answers'] as $single_answer) {
                ?>
                    <div class="survey_single_question survey_custom_control">
                        <input type="radio" id="customRadio_<?php echo $single_answer['single_text_answers']; ?>" value="<?php echo $single_answer['single_text_answers']; ?>" name="<?php echo $survey_item['single_question']; ?>" class="custom-control-input">
                        <label class="survey_single_question_label" for="customRadio_<?php echo $single_answer['single_text_answers']; ?>"><?php echo $single_answer['single_text_answers']; ?></label>
                    </div>

                <?php
                }
            }
            /**
             * Muliple Choices Questions
             */
            if ($survey_item['select_survey_question_type'] == 'multiple_choices') {
                $multiple_question = $survey_item['multiple_question'];
                /**
                 * Cleaning Up the Question to be multiple container ID
                 */
                $multiple_question_cleanup = strip_tags($survey_item['multiple_question']);
                $theQuestion =  preg_replace('/\s+/', '', $multiple_question_cleanup);
                $theQuestionCleaned =  trim($theQuestion, " \t\n\r\0\x0B\xC2\xA0");
                $theQuestionCleaned = preg_replace('/[^A-Za-z0-9\-]/', '', $theQuestionCleaned);
                $theQuestionCleaned = preg_replace('/[?]/', '',$theQuestionCleaned);
                // End of cleaning up the question to be multiple container ID
                
                
                ?>
                <div class="multiple_container" id="<?php echo $theQuestionCleaned ?>">
                    <div class="multiple_choices_question"><?php echo $multiple_question; ?></div>
                    <input type="hidden" class="multiple_choice_question_answers" name="<?php echo $multiple_question_cleanup; ?>" value="" />
                <?php
                foreach ($survey_item['multiple_answers'] as $multiple_text_answers) {
                    $available_multiple_answers = $multiple_text_answers['multiple_text_answers'];
                    $theAnswerCleanup = trim(preg_replace('/\s+/', '', $available_multiple_answers));

                ?>
                        <div class="custom-control custom-radio">
                            <input type="checkbox" id="customCheckbox_<?php echo $available_multiple_answers; ?>" data-answer="<?php echo $available_multiple_answers; ?>" data-question="<?php echo $multiple_question_cleanup; ?>" class="custom-control-input">
                            <label class="custom-control-label" for="customCheckbox_<?php echo $available_multiple_answers; ?>"><?php echo $available_multiple_answers; ?></label>
                        </div>
                        <?php
                }?>
                </div>
                <?php
            }
            /**
             * Matrix Questions
             */
            if ($survey_item['select_survey_question_type'] == 'matrix_question') {

                $matrix_question_statement = $survey_item['matrix_statement'];
                $matrix_answers_row_head = $survey_item['matrix_answers_array'];
                $matrix_questions_row_head = $survey_item['matrix_questions_array'];
                ?>
                <div class="matrix_statement"><?php echo $matrix_question_statement; ?></div>
                <div class="matrix_table_container">
                    <table>
                        <thead>
                            <tr>
                                <th><?php echo __('Questions') ?></th>
                                <?php
                                foreach ($matrix_answers_row_head as $theadAnswer) {
                                    $thAnswer = $theadAnswer['matrix_answer_text'];
                                ?>

                                    <th><?php echo $thAnswer; ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $length = count($matrix_questions_row_head);
                            for ($i = 0; $i < $length; $i++) {
                            ?>
                                <tr>
                                    <th><?php echo $matrix_questions_row_head[$i]['matrix_text_questions']; ?></th>
                                    <?php
                                    foreach ($matrix_answers_row_head as $answers) {
                                        $theAnswers = $answers['matrix_answer_text'];
                                    ?>
                                        <td>
                                            <input type='radio' id="id_<?php echo $theAnswers ?>" class='radio_input' name="<?php echo $matrix_questions_row_head[$i]['matrix_text_questions']; ?>" value="<?php echo $theAnswers ?>" />
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            <?php
            }
            /**
             * Textarea Questions
             */
            if ($survey_item['select_survey_question_type'] == 'textarea') { ?>
                <div class="textarea_container">
                    <div class="textarea_question survey_question">
                        <?php echo $survey_item['textarea_question']; ?>
                    </div>
                    <div class="survey_textarea_answer textarea_answer">
                        <textarea id="customText" name="<?php echo $survey_item['textarea_question']; ?>" rows="3" col="30"></textarea>
                    </div>

                </div>

    <?php

            }
            if ($survey_item['select_survey_question_type'] == 'rating_question') {
                ?>
                <div class="rating_scale_container">

                    <div class="rating_scale_question"><?php echo $survey_item['rating_question']; ?></div>
                    <div class="rating_scale_answer_container" data-start-rating-scale-range="<?php echo $survey_item['rating_question_number_1']; ?>" data-end-rating-scale-range="<?php echo $survey_item['rating_question_number_2']; ?>">
                        <div class="rating_scale_answer_text_container">
                            <div class="rating_scale_question_starting">
                                <?php echo $survey_item['rating_scale_question_starting'] ?>
                            </div>
                            <div class="rating_scale_question_ending">
                                <?php echo $survey_item['rating_scale_question_ending'] ?>
                            </div>
                        </div>
                        <?php
                        $starting = $survey_item['rating_question_number_1'];
                        $ending = $survey_item['rating_question_number_2'];
                        ?>
                        <ul class="rating_scale_answers">
                        <?php
                        for($i = $starting; $i<= $ending; $i++) {
                            ?>
                            <li class="rating_scale_item">
                                <input type='radio' id="id_<?php echo $i ?>" class='radio_input' name="<?php echo $survey_item['rating_question']; ?>" value="<?php echo $i; ?>" />
                                <label id="rating_scale_label" for="id_<?php echo $i ?>"><?php echo $i; ?></label>
                            </li>
                            <?php
                        }
    
                        ?>
                        </ul>
    
                    </div>
                </div>
                
                <?php
            }

            if ($survey_item['select_survey_question_type'] == 'dropdown_question') {

                $dropdownQuestion = $survey_item['dropdown_question'];
                $theDropdownQuestionCleanup = trim(preg_replace('/\s+/', '', $dropdownQuestion));
                $theQuestionCleaned =  trim($theDropdownQuestionCleanup, " \t\n\r\0\x0B\xC2\xA0");
                $theQuestionCleaned = preg_replace('/[^A-Za-z0-9\-]/', '', $theDropdownQuestionCleanup);
                $theQuestionCleaned = preg_replace('/[?]/', '',$theDropdownQuestionCleanup);
                
                ?>
                <div class="dropdown_question_container">
                    <div class="dropdown_question">
                        <?php echo $survey_item['dropdown_question']; ?>
                    </div>

                        <div class="custom-control custom-select">
                            <select name="<?php echo $dropdownQuestion; ?>" id="id_<?php echo $theQuestionCleaned; ?>">
                                <option value=""><?php echo __('Select Option', 'wqsp') ?></option>

                        <?php
                        foreach($survey_item['dropdown_answer'] as $dropdownAnswer) {

                            $dropdownAnswerAvailable = $dropdownAnswer['dropdown_text_answers'];
                            $theDropdownAnswerCleanup = trim(preg_replace('/\s+/', '', $dropdownAnswerAvailable));               
                            ?>
                                <option value="<?php echo $dropdownAnswerAvailable; ?>"><?php echo $dropdownAnswerAvailable; ?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                </div>
                <?php

            }

            if ($survey_item['select_survey_question_type'] == 'radio_image_question') {
                $image_pick_question = $survey_item['image_pick_question'];
                $imageQuestionCleanup = trim(preg_replace('/\s+/', '', $image_pick_question));
                $theImageQuestionCleaned =  trim($imageQuestionCleanup, " \t\n\r\0\x0B\xC2\xA0");
                $theImageQuestionCleaned = preg_replace('/[^A-Za-z0-9\-]/', '', $imageQuestionCleanup);
                $theImageQuestionCleaned = preg_replace('/[?]/', '',$imageQuestionCleanup);
                $images_answers = $survey_item['images_answers'];

                
                
                ?>
                <div class="image_question_radio_container">
                    <div class="image_picking_question" id="<?php echo $theImageQuestionCleaned;?>">
                        <?php echo $image_pick_question; ?>
                    </div>
                    <div class="images_answers_container">
                        <ul class="image_question_answers">
                            <?php
                            foreach($images_answers as $image_answer) {
                                ?>
                                <li>
                                    <input type="radio" name="<?php echo $image_pick_question; ?>" value="wadi_image_pick_<?php echo $image_answer['image_radio_answer']; ?>" id="<?php echo $image_answer['image_radio_answer']; ?>">
                                    <label for="<?php echo $image_answer['image_radio_answer']; ?>"><?php echo wp_get_attachment_image($image_answer['image_radio_answer']); ?></label>
                                </li>               
                                <?php
                                
                            }
                            ?>

                        </ul>
                    </div>
                </div>


                <?php
            }
        }
    endif;
    ?>

</div>
<button type="submit" class="wadi_survey_submit">Submit</button>
</form>
<input type="hidden" data-survey-finish-message='<?php echo $survey_finish_message; ?>'  data-survey-already-taken-message='<?php echo $survey_already_taken_message; ?>'  class="redirect_url" data-redirect-url='<?php echo $redirect_url; ?>' />
