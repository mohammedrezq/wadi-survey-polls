<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Survey Optinos
 */

 add_action('carbon_fields_register_fields', 'wadi_survey_options');
 function wadi_survey_options() {
    Container::make('post_meta', __('Survey Options', 'wqsp'))
    ->where( 'post_type', '=', 'survey')
    ->add_fields( array(
        Field::make( 'checkbox', 'wadi_survey_multistep', __('Multiple Steps', 'wqsp') )
        ->set_option_value('multistep')
    ) );
 }


/**
 * Survey Form Creation
 */

 add_action('carbon_fields_register_fields', 'survey_elements');

 function survey_elements() {
    $label_answers = array(
        'plural_name' => 'Answers',
        'singular_name' => 'Answer',
    );
    $survey_item_label = array(
        'plural_name' => 'Survey Items',
        'singular_name' => 'Survey Item',
    );
     Container::make( 'post_meta', __('Survey', 'wqsp') )
     ->where( 'post_type', '=', 'survey' )
     ->add_fields( array(
         Field::make( 'complex', 'survey_items', 'Survey Items' )
         ->setup_labels( $survey_item_label )
         ->add_fields( array(
             Field::make( 'select', 'select_survey_question_type', __( 'Survey Question Types', 'wqsp' ) )
             ->set_options( array(
                'matrix_question'   => 'Matrix Question',
                'single_choice'     => 'Single Choice',
                'multiple_choices'  => 'Multiple Choices',
                'textarea'          => 'Textarea',
            ) ),
            /**
             * Single Question Survey Item
             */
            Field::make( 'checkbox', 'wadi_survey_single_question_required', __('Single Question Required', 'wqsp') )
            ->set_help_text( __('Checking Single Question Required means that user cannot skip, and cannot submit without answering.', 'wqsp') )
            ->set_option_value('yes')
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'single_choice',
                    'compare' => '=',
                )
                )),
            Field::make( 'rich_text', 'single_question', 'Question' )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'single_choice',
                    'compare' => '=',
                )
            )),
            Field::make( 'complex', 'single_answers', __('Answers', 'wqsp') )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'single_choice',
                    'compare' => '=',
                )
                ))
            ->set_layout('tabbed-vertical')
            ->add_fields( array (
                Field::make( 'text', 'single_text_answers', __('Answers','wqsp') )
            ) ),
            /**
             * Multiple Answers Question Survey Item
             */
            Field::make( 'checkbox', 'wadi_survey_multiple_question_required', __('Multiple Question Required', 'wqsp') )
            ->set_help_text( __('Checking Multiple Question Required means that user cannot skip, and cannot submit without answering.', 'wqsp') )
            ->set_option_value('yes')
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'multiple_choices',
                    'compare' => '=',
                )
            )),
            Field::make( 'rich_text', 'multiple_question', __('Question', 'wqsp') )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'multiple_choices',
                    'compare' => '=',
                )
            )),
            Field::make( 'complex', 'multiple_answers', __('Answers', 'wqsp') )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'multiple_choices',
                    'compare' => '=',
                )
                ))
            ->set_layout('tabbed-vertical')
            ->add_fields( array (
                Field::make( 'text', 'multiple_text_answers', __('Answers', 'wqsp') )
            ) ),
            /**
             * Martix Questions Survey Item
             */
            Field::make( 'checkbox', 'wadi_survey_matrix_question_required', __('Matrix Question Required', 'wqsp') )
            ->set_help_text( __('Checking Matrix Question Required means that user cannot skip, and cannot submit without answering.', 'wqsp') )
            ->set_option_value('yes')
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'matrix_question',
                    'compare' => '=',
                    )
                )),
            Field::make( 'rich_text', 'matrix_statement', __('Statement', 'wqsp') )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'matrix_question',
                    'compare' => '=',
                    )
                )),
                Field::make( 'complex', 'matrix_questions_array', __('Questions Column', 'wqsp') )
                ->set_conditional_logic( array(
                    'relation' => 'AND',
                    array(
                        'field' => 'select_survey_question_type',
                        'value' => 'matrix_question',
                        'compare' => '=',
                        )
                        ))
                        ->set_layout('tabbed-vertical')
                        ->add_fields( array (
                            Field::make( 'text', 'matrix_text_questions', __('Question Field', 'wqsp') ),
                            ) )
                        ->set_header_template( '
                            <% if (matrix_text_questions) { %>
                                Question: <%- matrix_text_questions %>
                            <% } %>
                        ' ),
            Field::make( 'complex', 'matrix_answers_array', __('Answers Rows', 'wqsp') )
            ->setup_labels( $label_answers )
            ->set_layout('tabbed-horizontal')
            ->add_fields( array (
            Field::make( 'text', 'matrix_answer_text', __('Answers', 'wqsp') )
            ) )
            ->set_header_template( '
            <% if (matrix_answer_text) { %>
                <%- matrix_answer_text %>
            <% } %>
            ' )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'matrix_question',
                    'compare' => '=',
                )
            )),
            /**
             * Text Area Questions Survey Item
             */
            Field::make( 'checkbox', 'wadi_survey_textarea_question_required', __('Textarea Question Required', 'wqsp') )
            ->set_help_text( __('Checking Textarea Question Required means that user cannot skip, and cannot submit without answering.', 'wqsp') )
            ->set_option_value('yes')
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'textarea',
                    'compare' => '=',
                    )
                )),
            Field::make( 'rich_text', 'textarea_question', __('Textarea Question', 'wqsp') )
            ->set_conditional_logic( array(
                'relation' => 'AND',
                array(
                    'field' => 'select_survey_question_type',
                    'value' => 'textarea',
                    'compare' => '=',
                )
            )),
         ) )
     ) );
 }


add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}