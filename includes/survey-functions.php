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
        Field::make( 'checkbox', 'wadi_survey_multiple_steps', __('Multiple Steps Survey', 'wqsp') )
        ->set_option_value('yes'),
        Field::make( 'checkbox', 'wadi_survey_redirect_to', __('Redirect After Survey Completed', 'wqsp') )
        ->set_option_value('yes'),
        Field::make( 'text', 'wadi_survey_redirect_link', __( 'Redirect Link' ) )
        ->set_conditional_logic( array(
            'relation' => 'AND', // Optional, defaults to "AND"
            array(
                'field' => 'wadi_survey_redirect_to',
                'value' => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
            )
        ) ),

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




// /**
//  * Repeater Testing on Carbon Feilds
//  */


//  add_action('carbon_fields_register_fields', 'testing_conditional_select');
//  function testing_conditional_select() {
//      Container::make('post_meta', __('Survey Conditional Testing', 'survey'))
//      ->where( 'post_type', '=', 'survey')
//      ->add_fields( array(
//         Field::make('complex', 'survey_element', 'Survey Item')
//         ->add_fields(array(
//          Field::make( 'select', 'select_survey_type', __('Survey Types') )
//             ->set_options( array(
//                 'matrix_question'   => 'Matrix Question',
//                 'single_choice'     => 'Single Choice',
//                 'multiple_choices'  => 'Multiple Choices',
//                 'textarea'          => 'Textarea',
//             ) ),
//         Field::make( 'text', 'question_1', 'Question'),
//         Field::make( 'radio_image', 'crb_background_image', __( 'Choose Background Image' ) )
// 	        ->set_options( array(
// 	        	'mountain' => 'https://source.unsplash.com/X1UTzW8e7Q4/800x600',
// 	        	'temple' => 'https://source.unsplash.com/ioJVccFmWxE/800x600',
// 	        	'road' => 'https://source.unsplash.com/5c8fczgvar0/800x600',
// 	        ) ),
//         Field::make( 'complex', 'crb_slides', 'Slides' )
//         ->set_conditional_logic( array(
//             'relation' => 'AND', // Optional, defaults to "AND"
//             array(
//                 'field' => 'select_survey_type',
//                 'value' => 'single_choice', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
//                 'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
//             )
//         ) )
//         ->set_layout( 'tabbed-horizontal' )
//         ->add_fields( array(
//             Field::make( 'text', 'title', 'Title' ),
//             Field::make( 'color', 'title_color', 'Title Color' ),
//             Field::make( 'image', 'image', 'Image' ),
//             Field::make( 'complex', 'crb_slides', 'Heading' )
//             ->add_fields( array (
//                 Field::make( 'text', 'heading', 'Heading' ),
//                 Field::make( 'complex', 'testing', 'Testing Levels' )
//                 ->add_fields( array (
//                     Field::make( 'text', 'another_level', 'Another Level!' )
//                 ) )
//             ) )
//         ) ),
//         Field::make( 'text', 'question_2', 'Question 2')
//         ->set_conditional_logic( array(
//             'relation' => 'AND', // Optional, defaults to "AND"
//             array(
//                 'field' => 'select_survey_type',
//                 'value' => 'multiple_choices', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
//                 'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
//             )
//         ) )
//         ))
//      ) );
//  }

// add_action( 'carbon_fields_register_fields', 'crb_attach_post_meta' );
// function crb_attach_post_meta() {
//     Container::make( 'post_meta', __( 'Page Options', 'crb' ) )
//         ->where( 'post_type', '=', 'survey' ) // only show our new fields on pages
//         ->add_fields( array(
//             Field::make( 'complex', 'crb_slides', 'Slides' )
//                 ->set_layout( 'tabbed-horizontal' )
//                 ->add_fields( array(
//                     Field::make( 'text', 'title', 'Title' ),
//                     Field::make( 'color', 'title_color', 'Title Color' ),
//                     Field::make( 'image', 'image', 'Image' ),
//                     Field::make( 'complex', 'crb_slides', 'Heading' )
//                     ->add_fields( array (
//                         Field::make( 'text', 'heading', 'Heading' ),
//                         Field::make( 'complex', 'testing', 'Testing Levels' )
//                         ->add_fields( array (
//                             Field::make( 'text', 'another_level', 'Another Level!' )
//                         ) )
//                     ) )
//                 ) ),
//         ) );
// }

// add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
// function crb_attach_theme_options() {
//     Container::make( 'theme_options', __( 'Theme Options' ) )
//         ->add_fields( array(
//             Field::make( 'text', 'crb_text', 'Text Field' ),
//             Field::make( 'text', 'crb_text_das', 'Text Field' ),
//         ) );

//         Container::make( 'post_meta', 'Custom Data' )
//         ->where( 'post_type', '=', 'survey' )
//         ->add_fields( array(
//             Field::make( 'text', 'das', 'Text Field' ),
//             Field::make( 'text', 'dasdas', 'Text Field' ),
//         ) );

//         Container::make( 'post_meta', __( 'User Settings' ) )
//     ->where( 'post_type', '=', 'survey' )
//     ->add_tab( __( 'Profile' ), array(
//         Field::make( 'text', 'crb_first_name', __( 'First Name' ) ),
//         Field::make( 'text', 'crb_last_name', __( 'Last Name' ) ),
//         Field::make( 'text', 'crb_position', __( 'Position' ) ),
//     ) )
//     ->add_tab( __( 'Notification' ), array(
//         Field::make( 'text', 'crb_email', __( 'Notification Email' ) ),
//         Field::make( 'text', 'crb_phone', __( 'Phone Number' ) ),
//     ) );
// }

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}