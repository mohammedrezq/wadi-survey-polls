<?php

class WadiSurveyDB
{
	public function __construct()
	{

		/**
		 * Creating Wadi Survey DB Submissions For Survey
		 */
		add_action('admin_init', array($this, 'init_db'));

		/**
		 * getQuizData Ajax WordPress
		 */
		add_action('wp_ajax_getQuizData', array($this, 'getQuizData'));
		add_action('wp_ajax_nopriv_getQuizData', array($this, 'getQuizData'));
	}

	/**
	 * 
	 * Create Table for Survey Info
	 * 
	 */

	public function init_db()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'wadi_survey_submissions';
		$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

		if (!$wpdb->get_var($query) == $table_name) {

			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				user_id mediumint(9)NOT NULL,
				survey_id mediumint(9)NOT NULL,
				questions_answers LONGTEXT NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * set Data from Survey Form to wp_wadi_survey_submissions
	 */



	public function getQuizData()
	{
		global $wpdb;
		$data = $_POST['data'];
		$survey_time =  current_time('mysql', true);

		$table_name = $wpdb->prefix . 'wadi_survey_submissions';


		$existedRow = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM " . $table_name . "
				WHERE user_id = %d AND survey_id = %d LIMIT 1",
				$data['user_id'],
				$data['survey_id']
			)
		);


		$allow_multiple_responses =  carbon_get_post_meta($data['survey_id'], 'wadi_survey_multiple_responses');


		if (!isset($existedRow) && $allow_multiple_responses != TRUE) {


			$wpdb->insert(
				$table_name,
				array(
					'time' => $survey_time,
					'user_id' => $data['user_id'],
					'survey_id' => $data['survey_id'],
					'questions_answers' => $data['surveyData'],
				)
			);
		} elseif ($allow_multiple_responses == TRUE) {
			$wpdb->insert(
				$table_name,
				array(
					'time' => $survey_time,
					'user_id' => $data['user_id'],
					'survey_id' => $data['survey_id'],
					'questions_answers' => $data['surveyData'],
				)
			);
		}

		wp_die();
	}
}

new WadiSurveyDB;
