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


        <form method="POST" class="poll_container" data-poll-id="<?php echo $the_post_id; ?>" data-user-id="<?php echo $the_current_user_id; ?>" data-post-type="<?php echo get_post_type($the_post_id); ?>">
            <?php
            $poll_question_type = carbon_get_post_meta(get_the_ID(), 'select_poll_question_type');
            $poll_single_question = carbon_get_post_meta(get_the_ID(), 'poll_single_question');
            $poll_single_answers = carbon_get_post_meta(get_the_ID(), 'poll_single_answers');

            if ($poll_question_type === 'poll_single_choice') {
            ?>

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
                    }
                    ?>
                </div>
            <?php
            }

            $poll_multi_answer_question = carbon_get_post_meta(get_the_ID(), 'multiple_question');
            $poll_multiple_answers = carbon_get_post_meta(get_the_ID(), 'poll_multiple_answers');

            if ($poll_question_type === 'poll_multiple_choices') {
                ?>

               <h1>Multiple Answer Question</h1>
            <?php
            }


            ?>
            <button type="submit" class="wadi_poll_submit">Submit</button>

        </form>

    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php

get_footer();
