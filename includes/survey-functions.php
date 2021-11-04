<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// /**
//  * Survey Optinos
//  */

//  add_action('carbon_fields_register_fields', 'wadi_survey_options');
//  function wadi_survey_options() {
//     Container::make('post_meta', __('Survey Options', 'wqsp'))
//     ->where( 'post_type', '=', 'survey')
//     ->add_fields( array(
//         Field::make( 'checkbox', 'wadi_survey_multiple_steps', __('Multiple Steps Survey', 'wqsp') )
//         ->set_option_value('yes'),
//         Field::make( 'checkbox', 'wadi_survey_redirect_to', __('Redirect After Survey Completed', 'wqsp') )
//         ->set_option_value('yes'),
//         Field::make( 'text', 'wadi_survey_redirect_link', __( 'Redirect Link' ) )
//         ->set_conditional_logic( array(
//             'relation' => 'AND', // Optional, defaults to "AND"
//             array(
//                 'field' => 'wadi_survey_redirect_to',
//                 'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
//                 'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
//             )
//         ) ),
//         Field::make( 'text', 'wadi_survey_settimeout', __( 'Time Before Redirecting' ) )
//         ->set_help_text('Time in seconds before user gets redirected to specified URL, example: 1000 is 1 second')
//         ->set_default_value( '1000' )
//         ->set_conditional_logic( array(
//             'relation' => 'AND', // Optional, defaults to "AND"
//             array(
//                 'field' => 'wadi_survey_redirect_to',
//                 'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
//                 'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
//             )
//         ) ),
//         Field::make( 'checkbox', 'wadi_survey_multiple_responses', __('Allow Multiple Responses', 'wqsp') )
//         ->set_option_value('yes'),
//         Field::make( 'text', 'wadi_survey_finishing_message', __( 'Finish Message' ) )
//         ->set_help_text('Survey finishing message sent after user finishing and submit the survey.')
//         ->set_default_value( 'Thank you for taking the survey.' ),
//         Field::make( 'text', 'wadi_survey_already_taken_message', __( 'Message if user has already taken the exam' ) )
//         ->set_help_text('Survey finishing message sent after user finishing and submit the survey.')
//         ->set_default_value( 'You have already taken this survey.' )
//         ->set_conditional_logic( array(
//             'relation' => 'AND', // Optional, defaults to "AND"
//             array(
//                 'field' => 'wadi_survey_multiple_responses',
//                 'value' => false, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
//                 'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
//             )
//         ) ),
//     ) );
//  }


// /**
//  * Survey Form Creation
//  */

//  add_action('carbon_fields_register_fields', 'survey_elements');

//  function survey_elements() {
//     $label_answers = array(
//         'plural_name' => 'Answers',
//         'singular_name' => 'Answer',
//     );
//     $survey_item_label = array(
//         'plural_name' => 'Survey Items',
//         'singular_name' => 'Survey Item',
//     );
//      Container::make( 'post_meta', __('Survey', 'wqsp') )
//      ->where( 'post_type', '=', 'survey' )
//      ->add_fields( array(
//          Field::make( 'complex', 'survey_items', 'Survey Items' )
//          ->setup_labels( $survey_item_label )
//          ->add_fields( array(
//              Field::make( 'select', 'select_survey_question_type', __( 'Survey Question Types', 'wqsp' ) )
//              ->set_options( array(
//                 'matrix_question'   => 'Matrix Question',
//                 'single_choice'     => 'Single Choice',
//                 'multiple_choices'  => 'Multiple Choices',
//                 'textarea'          => 'Textarea',
//             ) ),
//             /**
//              * Single Question Survey Item
//              */
//             Field::make( 'rich_text', 'single_question', 'Question' )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'single_choice',
//                     'compare' => '=',
//                 )
//             )),
//             Field::make( 'complex', 'single_answers', __('Answers', 'wqsp') )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'single_choice',
//                     'compare' => '=',
//                 )
//                 ))
//             ->set_layout('tabbed-vertical')
//             ->add_fields( array (
//                 Field::make( 'text', 'single_text_answers', __('Answers','wqsp') )
//             ) ),
//             /**
//              * Multiple Answers Question Survey Item
//              */
//             Field::make( 'rich_text', 'multiple_question', __('Question', 'wqsp') )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'multiple_choices',
//                     'compare' => '=',
//                 )
//             )),
//             Field::make( 'complex', 'multiple_answers', __('Answers', 'wqsp') )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'multiple_choices',
//                     'compare' => '=',
//                 )
//                 ))
//             ->set_layout('tabbed-vertical')
//             ->add_fields( array (
//                 Field::make( 'text', 'multiple_text_answers', __('Answers', 'wqsp') )
//             ) ),
//             /**
//              * Martix Questions Survey Item
//              */
//             Field::make( 'rich_text', 'matrix_statement', __('Statement', 'wqsp') )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'matrix_question',
//                     'compare' => '=',
//                     )
//                 )),
//                 Field::make( 'complex', 'matrix_questions_array', __('Questions Column', 'wqsp') )
//                 ->set_conditional_logic( array(
//                     'relation' => 'AND',
//                     array(
//                         'field' => 'select_survey_question_type',
//                         'value' => 'matrix_question',
//                         'compare' => '=',
//                         )
//                         ))
//                         ->set_layout('tabbed-vertical')
//                         ->add_fields( array (
//                             Field::make( 'text', 'matrix_text_questions', __('Question Field', 'wqsp') ),
//                             ) )
//                         ->set_header_template( '
//                             <% if (matrix_text_questions) { %>
//                                 Question: <%- matrix_text_questions %>
//                             <% } %>
//                         ' ),
//             Field::make( 'complex', 'matrix_answers_array', __('Answers Rows', 'wqsp') )
//             ->setup_labels( $label_answers )
//             ->set_layout('tabbed-horizontal')
//             ->add_fields( array (
//             Field::make( 'text', 'matrix_answer_text', __('Answers', 'wqsp') )
//             ) )
//             ->set_header_template( '
//             <% if (matrix_answer_text) { %>
//                 <%- matrix_answer_text %>
//             <% } %>
//             ' )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'matrix_question',
//                     'compare' => '=',
//                 )
//             )),
//             /**
//              * Text Area Questions Survey Item
//              */
//             Field::make( 'rich_text', 'textarea_question', __('Textarea Question', 'wqsp') )
//             ->set_conditional_logic( array(
//                 'relation' => 'AND',
//                 array(
//                     'field' => 'select_survey_question_type',
//                     'value' => 'textarea',
//                     'compare' => '=',
//                 )
//             )),
//          ) )
//      ) );
//  }



/**
 * Tabbed Survey Settings
 */

/**
 * Survey Form Creation
 */

add_action('carbon_fields_register_fields', 'survey_tabbed');

function survey_tabbed()
{
    $label_answers = array(
        'plural_name' => 'Answers',
        'singular_name' => 'Answer',
    );
    $survey_item_label = array(
        'plural_name' => 'Survey Items',
        'singular_name' => 'Survey Item',
    );
    Container::make('post_meta', __('Survey Settings', 'wqsp'))
        ->where('post_type', '=', 'survey')
        ->add_tab(__('Survey Form Building'), array(
            Field::make('complex', 'survey_items', 'Survey Items')
                ->setup_labels($survey_item_label)
                ->add_fields(array(
                    Field::make('select', 'select_survey_question_type', __('Survey Question Types', 'wqsp'))
                        ->set_options(array(
                            ''                      => 'Select Question Type',
                            'matrix_question'       => 'Matrix Question',
                            'single_choice'         => 'Single Choice Question',
                            'multiple_choices'      => 'Multiple Choices Question',
                            'textarea'              => 'Open Ended Question',
                            'rating_question'       => 'Rating Question',
                            'dropdown_question'     => 'Dropdown Question',
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
                    Field::make('complex', 'single_answers', __('Answers', 'wqsp'))
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
                            Field::make('text', 'single_text_answers', __('Answers', 'wqsp'))
                        )),
                    /**
                     * Multiple Answers Question Survey Item
                     */
                    Field::make('rich_text', 'multiple_question', __('Question', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'multiple_choices',
                                'compare'    => '=',
                            )
                        )),
                    Field::make('complex', 'multiple_answers', __('Multiple Question Answers', 'wqsp'))
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
                            Field::make('text', 'multiple_text_answers', __('Multiple Question Answers', 'wqsp'))
                        )),
                    /**
                     * Martix Questions Survey Item
                     */
                    Field::make('rich_text', 'matrix_statement', __('Statement', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'matrix_question',
                                'compare'    => '=',
                            )
                        )),
                    Field::make('complex', 'matrix_questions_array', __('Questions Column', 'wqsp'))
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
                            Field::make('text', 'matrix_text_questions', __('Question Field', 'wqsp')),
                        ))
                        ->set_header_template('
                           <% if (matrix_text_questions) { %>
                               Question: <%- matrix_text_questions %>
                           <% } %>
                       '),
                    Field::make('complex', 'matrix_answers_array', __('Answers Rows', 'wqsp'))
                        ->setup_labels($label_answers)
                        ->set_layout('tabbed-horizontal')
                        ->add_fields(array(
                            Field::make('text', 'matrix_answer_text', __('Answers', 'wqsp'))
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
                    Field::make('rich_text', 'textarea_question', __('Textarea Question', 'wqsp'))
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
                    Field::make('text', 'rating_question', __('Rating Question', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'rating_question',
                                'compare'    => '='
                            )
                        )),
                    Field::make('text', 'rating_question_number_1', __('Rating Starting Range', 'wqsp'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('min', 0)
                        ->set_required(true)
                        ->set_help_text(__('Set the starting number for rating question for instance 0', 'wqsp'))
                        ->set_width(50)
                        ->set_default_value(0)
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'rating_question',
                                'compare'    => '='
                            )
                        )),
                    Field::make('text', 'rating_question_number_2', __('Rating Ending Range', 'wqsp'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('min', 0)
                        ->set_required(true)
                        ->set_help_text(__('Set the ending number for rating question for instance 10, Please note that ending range should always be bigger than starting range.', 'wqsp'))
                        ->set_width(50)
                        ->set_default_value(10)
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'rating_question',
                                'compare'    => '='
                            )
                        )),
                    Field::make('text', 'rating_scale_question_starting', __('Rating scale starting text', 'wqsp'))
                        // ->set_attribute('type', 'number')
                        ->set_help_text(__('Set rating scale starting text ', 'wqsp'))
                        ->set_width(50)
                        ->set_default_value(__('Not likely at all', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'rating_question',
                                'compare'    => '='
                            )
                        )),
                    Field::make('text', 'rating_scale_question_ending', __('Rating scale ending text', 'wqsp'))
                        // ->set_attribute('type', 'number')
                        ->set_help_text(__('Set rating scale ending text ', 'wqsp'))
                        ->set_width(50)
                        ->set_default_value(__('Extremely Likely', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'rating_question',
                                'compare'    => '='
                            )
                        )),
                        Field::make('rich_text', 'dropdown_question', __('Dropdown Question', 'wqsp'))
                        ->set_conditional_logic(array(
                            'relation' => 'AND',
                            array(
                                'field'      => 'select_survey_question_type',
                                'value'      => 'dropdown_question',
                                'compare'    => '='
                            )
                        )),
                        Field::make('complex', 'dropdown_answer', __('Dropdown Answers', 'wqsp'))
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
                            Field::make('text', 'dropdown_text_answers', __('Dropdown Answers', 'wqsp'))
                        )),
                ))
        ))
        ->add_tab(__('Survey Options'), array(
            Field::make('checkbox', 'wadi_survey_multiple_steps', __('Multiple Steps Survey', 'wqsp'))
                ->set_option_value('yes'),
            Field::make('checkbox', 'wadi_survey_redirect_to', __('Redirect After Survey Completed', 'wqsp'))
                ->set_option_value('yes'),
            Field::make('text', 'wadi_survey_redirect_link', __('Redirect Link'))
                ->set_conditional_logic(array(
                    'relation' => 'AND', // Optional, defaults to "AND"
                    array(
                        'field' => 'wadi_survey_redirect_to',
                        'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    )
                )),
            Field::make('text', 'wadi_survey_settimeout', __('Time Before Redirecting'))
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
            Field::make('checkbox', 'wadi_survey_multiple_responses', __('Allow Multiple Responses', 'wqsp'))
                ->set_option_value('yes'),
            Field::make('text', 'wadi_survey_finishing_message', __('Finish Message'))
                ->set_help_text('Survey finishing message sent after user finishing and submit the survey.')
                ->set_default_value('Thank you for taking the survey.'),
            Field::make('text', 'wadi_survey_already_taken_message', __('Message if user has already taken the exam'))
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




add_action('after_setup_theme', 'crb_load');
function crb_load()
{
    require_once('vendor/autoload.php');
    \Carbon_Fields\Carbon_Fields::boot();
}
