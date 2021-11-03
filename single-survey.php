<?php

/**
 * Single Post type for Survey
 */
get_header();

// $the_post_id =  get_the_ID();
// $the_current_user_id = get_current_user_id();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="entry-content">

		<?php
		/**
		 * Get multistep checkbox value
		 */

		$multistep_survey = carbon_get_post_meta(get_the_ID(), 'wadi_survey_multiple_steps');
		
		/**
		 * If multistep checkbox is NOT checked, make survey into single page (All Questions in one page)
		 * 
		 * If multistep checkbox is checked, make the survey into multistep page going next and prev through the survey questions  
		 */

		$allow_multiple_responses =  carbon_get_post_meta(get_the_ID(), 'wadi_survey_multiple_responses');
		$the_current_user_id = get_current_user_id();
		$the_current_post_id = get_the_ID();
		$table_name = $wpdb->prefix . 'wadi_survey_submissions';

		$survey_already_taken_message =  carbon_get_post_meta(get_the_ID(), 'wadi_survey_already_taken_message');


		global $wpdb;

		$existedRow = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM " . $table_name . "
				WHERE user_id = %d AND survey_id = %d LIMIT 1",
				$the_current_user_id,
				$the_current_post_id
			)
		);
		

		if($allow_multiple_responses == true && $multistep_survey != TRUE) {
			require_once PLUGIN_PATH . 'includes/templates/survey-single.php';
		} else if ($allow_multiple_responses == true && $multistep_survey == TRUE) {
			require_once PLUGIN_PATH . 'includes/templates/survey-multistep.php';
		}
		else if (isset($existedRow) && $allow_multiple_responses == false) {
			?>
			<p><?php echo $survey_already_taken_message; ?></p>
			<?php
		}


		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php

get_footer();
