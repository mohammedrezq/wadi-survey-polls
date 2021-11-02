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

		if($multistep_survey != TRUE) {
			require_once PLUGIN_PATH . 'includes/templates/survey-single.php';
		} else {
			require_once PLUGIN_PATH . 'includes/templates/survey-multistep.php';
		}
		
		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php

get_footer();
