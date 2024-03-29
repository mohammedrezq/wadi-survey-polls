<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class WadiSurveyBackend
{


    public function __construct()
    {
        add_action('carbon_fields_register_fields', array($this, 'survey_tabbed'));

        add_action('carbon_fields_register_fields', array($this, 'poll_forms'));

        add_action('after_setup_theme', array($this, 'wadi_backend_load'));
    }

    /**
     * Tabbed Survey Settings
     */

    /**
     * Survey Form Creation
     */


    public function survey_tabbed()
    {
        $label_answers = array(
            'plural_name' => esc_html__('Answers', 'wadi-survey'),
            'singular_name' => esc_html__('Answer', 'wadi-survey')
        );
        $survey_item_label = array(
            'plural_name' => esc_html__('Survey Items', 'wadi-survey'),
            'singular_name' => esc_html__('Survey Item', 'wadi-survey')
        );
        Container::make('post_meta', esc_html__('Survey Settings', 'wadi-survey'))
            ->where('post_type', '=', 'wadi-survey')
            ->add_tab(esc_html__('Survey Form Building'), array(
                Field::make('complex', 'survey_items', 'Survey Items')
                    ->setup_labels($survey_item_label)
                    ->add_fields(array(
                        Field::make('select', 'select_survey_question_type', esc_html__('Survey Question Types', 'wadi-survey'))
                            ->set_options(array(
                                ''                          => 'Select Question Type',
                                'matrix_question'           => 'Matrix Question',
                                'single_choice'             => 'Single Choice Question',
                                'multiple_choices'          => 'Multiple Choices Question',
                                'textarea'                  => 'Open Ended Question',
                                'rating_question'           => 'Rating Question',
                                'dropdown_question'         => 'Dropdown Question',
                                'radio_image_question'      => 'Image Selection Question',
                            )),
                        /**
                         * Single Question Survey Item
                         */
                        Field::make('rich_text', 'single_question', 'Question')
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'     => 'select_survey_question_type',
                                    'value'     => 'single_choice',
                                    'compare'   => '=',
                                )
                            )),
                        Field::make('complex', 'single_answers', esc_html__('Answers', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'single_choice',
                                    'compare'    => '=',
                                )
                            ))
                            ->set_layout('tabbed-vertical')
                            ->add_fields(array(
                                Field::make('text', 'single_text_answers', esc_html__('Answers', 'wadi-survey'))
                            )),
                        /**
                         * Multiple Answers Question Survey Item
                         */
                        Field::make('rich_text', 'multiple_question', esc_html__('Question', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'multiple_choices',
                                    'compare'    => '=',
                                )
                            )),
                        Field::make('complex', 'multiple_answers', esc_html__('Multiple Question Answers', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'multiple_choices',
                                    'compare'    => '=',
                                )
                            ))
                            ->set_layout('tabbed-vertical')
                            ->add_fields(array(
                                Field::make('text', 'multiple_text_answers', esc_html__('Multiple Question Answers', 'wadi-survey'))
                            )),
                        /**
                         * Martix Questions Survey Item
                         */
                        Field::make('rich_text', 'matrix_statement', esc_html__('Statement', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'matrix_question',
                                    'compare'    => '=',
                                )
                            )),
                        Field::make('complex', 'matrix_questions_array', esc_html__('Questions Column', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field' => 'select_survey_question_type',
                                    'value' => 'matrix_question',
                                    'compare' => '=',
                                )
                            ))
                            ->set_layout('tabbed-vertical')
                            ->add_fields(array(
                                Field::make('text', 'matrix_text_questions', esc_html__('Question Field', 'wadi-survey')),
                            ))
                            ->set_header_template('
                               <% if (matrix_text_questions) { %>
                                   Question: <%- matrix_text_questions %>
                               <% } %>
                           '),
                        Field::make('complex', 'matrix_answers_array', esc_html__('Answers Rows', 'wadi-survey'))
                            ->setup_labels($label_answers)
                            ->set_layout('tabbed-horizontal')
                            ->add_fields(array(
                                Field::make('text', 'matrix_answer_text', esc_html__('Answers', 'wadi-survey'))
                            ))
                            ->set_header_template('
                                <% if (matrix_answer_text) { %>
                                    <%- matrix_answer_text %>
                                <% } %>
                            ')
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'matrix_question',
                                    'compare'    => '=',
                                )
                            )),
                        /**
                         * Text Area Questions Survey Item
                         */
                        Field::make('rich_text', 'textarea_question', esc_html__('Textarea Question', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'textarea',
                                    'compare'    => '=',
                                )
                            )),
                        /**
                         * Rating Questions Survey Item
                         */
                        Field::make('text', 'rating_question', esc_html__('Rating Question', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'rating_question',
                                    'compare'    => '='
                                )
                            )),
                        Field::make('text', 'rating_question_number_1', esc_html__('Rating Starting Range', 'wadi-survey'))
                            ->set_attribute('type', 'number')
                            ->set_attribute('min', '0')
                            ->set_required(true)
                            ->set_help_text(esc_html__('Set the starting number for rating question for instance 0', 'wadi-survey'))
                            ->set_width('50')
                            ->set_default_value('0')
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'rating_question',
                                    'compare'    => '='
                                )
                            )),
                        Field::make('text', 'rating_question_number_2', esc_html__('Rating Ending Range', 'wadi-survey'))
                            ->set_attribute('type', 'number')
                            ->set_attribute('min', '0')
                            ->set_required(true)
                            ->set_help_text(esc_html__('Set the ending number for rating question for instance 10, Please note that ending range should always be bigger than starting range.', 'wadi-survey'))
                            ->set_width('50')
                            ->set_default_value('10')
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'rating_question',
                                    'compare'    => '='
                                )
                            )),
                        Field::make('text', 'rating_scale_question_starting', esc_html__('Rating scale starting text', 'wadi-survey'))
                            ->set_help_text(esc_html__('Set rating scale starting text ', 'wadi-survey'))
                            ->set_width(50)
                            ->set_default_value(esc_html__('Not likely at all', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'rating_question',
                                    'compare'    => '='
                                )
                            )),
                        Field::make('text', 'rating_scale_question_ending', esc_html__('Rating scale ending text', 'wadi-survey'))
                            ->set_help_text(esc_html__('Set rating scale ending text ', 'wadi-survey'))
                            ->set_width(50)
                            ->set_default_value(esc_html__('Extremely Likely', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'rating_question',
                                    'compare'    => '='
                                )
                            )),
                        /**
                         * Dropdown Question
                         */
                        Field::make('rich_text', 'dropdown_question', esc_html__('Dropdown Question', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'dropdown_question',
                                    'compare'    => '='
                                )
                            )),
                        Field::make('complex', 'dropdown_answer', esc_html__('Dropdown Answers', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'dropdown_question',
                                    'compare'    => '=',
                                )
                            ))
                            ->set_layout('tabbed-vertical')
                            ->add_fields(array(
                                Field::make('text', 'dropdown_text_answers', esc_html__('Dropdown Answers', 'wadi-survey'))
                            )),
                        /**
                         * Image Pick Question
                         */
                        Field::make('rich_text', 'image_pick_question', esc_html__('Image Picking Question', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'radio_image_question',
                                    'compare'    => '=',
                                )
                            )),
                        Field::make('complex', 'images_answers', esc_html__('Images Answers', 'wadi-survey'))
                            ->set_conditional_logic(array(
                                'relation' => 'AND',
                                array(
                                    'field'      => 'select_survey_question_type',
                                    'value'      => 'radio_image_question',
                                    'compare'    => '=',
                                )
                            ))
                            ->set_layout('tabbed-vertical')
                            ->add_fields(array(
                                Field::make('image', 'image_radio_answer', esc_html__('Image Answer', 'wadi-survey'))
                            )),
                    ))
            ))
            ->add_tab(__('Survey Options'), array(
                Field::make('checkbox', 'wadi_survey_multiple_steps', esc_html__('Multiple Steps Survey', 'wadi-survey'))
                    ->set_option_value('yes'),
                Field::make('checkbox', 'wadi_survey_redirect_to', esc_html__('Redirect After Survey Completed', 'wadi-survey'))
                    ->set_option_value('yes'),
                Field::make('text', 'wadi_survey_redirect_link', esc_html__('Redirect Link'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_survey_redirect_to',
                            'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                Field::make('text', 'wadi_survey_settimeout', esc_html__('Time Before Redirecting'))
                    ->set_help_text('Time in seconds before user gets redirected to specified URL, example: 1000 is 1 second')
                    ->set_default_value('1000')
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_survey_redirect_to',
                            'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                Field::make('checkbox', 'wadi_survey_multiple_responses', esc_html__('Allow Multiple Responses', 'wadi-survey'))
                    ->set_option_value('yes'),
                Field::make('text', 'wadi_survey_finishing_message', esc_html__('Finish Message'))
                    ->set_help_text('Survey finishing message sent after user finishing and submit the survey.')
                    ->set_default_value('Thank you for taking the survey.'),
                Field::make('text', 'wadi_survey_already_taken_message', esc_html__('Message if user has already taken the exam'))
                    ->set_help_text('Survey finishing message sent after user finishing and submit the survey.')
                    ->set_default_value('You have already taken this survey.')
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_survey_multiple_responses',
                            'value' => false, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
            ));
    }

    /**
     * Polls backend
     */

    public function poll_forms()
    {

        // $poll_item_label = array(
        //     'plural_name' => 'Poll Items',
        //     'singular_name' => 'Poll Item',
        // );
        Container::make('post_meta', esc_html__('Poll Form'))
            ->where('post_type', '=', 'wadi-poll')
            ->add_tab(esc_html__('Poll Form Building'), array(
                Field::make('select', 'select_poll_question_type', esc_html__('Poll Question Types', 'wadi-survey'))
                    ->set_options(array(
                        ''                               => 'Select Question Type',
                        'poll_single_choice'             => 'Single Choice Question',
                        'poll_multiple_choices'          => 'Multiple Choices Question',
                        'poll_rating_question'           => 'Rating Question',
                        'poll_radio_image_question'      => 'Image Selection Question',
                    )),
                /**
                 * Poll Single Answer Question
                 */
                Field::make('rich_text', 'poll_single_question', 'Question')
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'     => 'select_poll_question_type',
                            'value'     => 'poll_single_choice',
                            'compare'   => '=',
                        )
                    )),
                Field::make('complex', 'poll_single_answers', esc_html__('Answers', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_single_choice',
                            'compare'    => '=',
                        )
                    ))
                    ->set_layout('tabbed-vertical')
                    ->add_fields(array(
                        Field::make('text', 'poll_single_text_answers', esc_html__('Answers', 'wadi-survey'))
                    )),
                /**
                 * Poll Multi Answers Question
                 */
                Field::make('rich_text', 'multiple_question', esc_html__('Question', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_multiple_choices',
                            'compare'    => '=',
                        )
                    )),
                Field::make('complex', 'poll_multiple_answers', esc_html__('Multiple Question Answers', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_multiple_choices',
                            'compare'    => '=',
                        )
                    ))
                    ->set_layout('tabbed-vertical')
                    ->add_fields(array(
                        Field::make('text', 'poll_multiple_text_answers', esc_html__('Multiple Question Answers', 'wadi-survey'))
                    )),
                /**
                 * Rating Questions Poll Item
                 */
                Field::make('text', 'rating_question', esc_html__('Rating Question', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_rating_question',
                            'compare'    => '='
                        )
                    )),
                Field::make('text', 'rating_question_number_1', esc_html__('Rating Starting Range', 'wadi-survey'))
                    ->set_attribute('type', 'number')
                    ->set_attribute('min', '0')
                    ->set_required(true)
                    ->set_help_text(__('Set the starting number for rating question for instance 0', 'wadi-survey'))
                    ->set_width('50')
                    ->set_default_value('0')
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_rating_question',
                            'compare'    => '='
                        )
                    )),
                Field::make('text', 'rating_question_number_2', esc_html__('Rating Ending Range', 'wadi-survey'))
                    ->set_attribute('type', 'number')
                    ->set_attribute('min', '0')
                    ->set_required(true)
                    ->set_help_text(esc_html__('Set the ending number for rating question for instance 10, Please note that ending range should always be bigger than starting range.', 'wadi-survey'))
                    ->set_width('50')
                    ->set_default_value('10')
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_rating_question',
                            'compare'    => '='
                        )
                    )),
                Field::make('text', 'rating_scale_question_starting', esc_html__('Rating scale starting text', 'wadi-survey'))
                    // ->set_attribute('type', 'number')
                    ->set_help_text(esc_html__('Set rating scale starting text ', 'wadi-survey'))
                    ->set_width(50)
                    ->set_default_value(esc_html__('Not likely at all', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_rating_question',
                            'compare'    => '='
                        )
                    )),
                Field::make('text', 'rating_scale_question_ending', esc_html__('Rating scale ending text', 'wadi-survey'))
                    // ->set_attribute('type', 'number')
                    ->set_help_text(esc_html__('Set rating scale ending text ', 'wadi-survey'))
                    ->set_width(50)
                    ->set_default_value(esc_html__('Extremely Likely', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_rating_question',
                            'compare'    => '='
                        )
                    )),
                /**
                 * Image Pick Question
                 */
                Field::make('rich_text', 'poll_image_pick_question', esc_html__('Poll Image Picking Question', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_radio_image_question',
                            'compare'    => '=',
                        )
                    )),
                Field::make('complex', 'poll_images_answers', esc_html__('Poll Images Answers', 'wadi-survey'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND',
                        array(
                            'field'      => 'select_poll_question_type',
                            'value'      => 'poll_radio_image_question',
                            'compare'    => '=',
                        )
                    ))
                    ->set_layout('tabbed-vertical')
                    ->add_fields(array(
                        Field::make('image', 'poll_image_radio_answer', esc_html__('Poll Image Answer', 'wadi-survey'))
                    )),
            ))
            ->add_tab(esc_html__('Poll Options'), array(
                Field::make('checkbox', 'wadi_poll_redirect_to', esc_html__('Redirect After Poll Completed', 'wadi-survey'))
                    ->set_option_value('yes'),
                Field::make('text', 'wadi_poll_redirect_link', esc_html__('Redirect Link'))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_poll_redirect_to',
                            'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                Field::make('text', 'wadi_poll_settimeout', esc_html__('Time Before Redirecting From Poll'))
                    ->set_help_text(esc_html__('Time in seconds before user gets redirected to specified URL, example: 1000 is 1 second', 'wadi-survey'))
                    ->set_default_value('1000')
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_poll_redirect_to',
                            'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                Field::make('checkbox', 'wadi_poll_multiple_responses', esc_html__('Allow Poll Multiple Responses', 'wadi-survey'))
                    ->set_option_value('yes'),
                Field::make('text', 'wadi_poll_finishing_message', esc_html__('Poll Finish Message'))
                    ->set_help_text('Poll finishing message sent after user finishes and submit the poll.')
                    ->set_default_value('Thank you for taking the poll.'),
                Field::make('text', 'wadi_poll_already_taken_message', esc_html__('Message if user has already taken the poll'))
                    ->set_help_text('Poll finishing message sent after user finishing and submit the poll.')
                    ->set_default_value('You have already taken this poll.')
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'wadi_poll_multiple_responses',
                            'value' => false, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
            ));
    }




    public function wadi_backend_load()
    {
        require_once('vendor/autoload.php');
        // To solve on live sites: https://stackoverflow.com/questions/53128991/carbon-fields-doest-show-maked-fields
        define('Carbon_Fields\URL', trailingslashit(plugin_dir_url(__FILE__)) . 'vendor/htmlburger/carbon-fields/');
        \Carbon_Fields\Carbon_Fields::boot();
    }
}


new WadiSurveyBackend;
