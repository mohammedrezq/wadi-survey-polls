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
		 * getSurveyData Ajax WordPress
		 */
		add_action('wp_ajax_getSurveyData', array($this, 'getSurveyData'));
		add_action('wp_ajax_nopriv_getSurveyData', array($this, 'getSurveyData'));


		/**
		 * Creating Wadi Survey DB Submissions For Survey
		 */
		add_action('admin_init', array($this, 'init_poll_db'));


		/**
		 * getPollData Ajax WordPress
		 *
		 * @return void
		 */
		add_action('wp_ajax_getPollData', array($this, 'getPollData'));
		add_action('wp_ajax_nopriv_getPollData', array($this, 'getPollData'));
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



	public function getSurveyData()
	{
		global $wpdb;
        if (isset($_POST['data']) && !empty($_POST['data'])) {
            $userID = intval($_POST['data']['user_id']);
            $surveyID = intval($_POST['data']['survey_id']);
            $surveyData = sanitize_text_field($_POST['data']['surveyData']);
			
            $survey_time =  current_time('mysql', true);

            $table_name = $wpdb->prefix . 'wadi_survey_submissions';


            $existedRow = $wpdb->get_var(
                $wpdb->prepare(
                "SELECT id FROM " . $table_name . "
				WHERE user_id = %d AND survey_id = %d LIMIT 1",
                $userID,
                $surveyID
            )
            );


            $allow_multiple_responses =  carbon_get_post_meta($surveyID, 'wadi_survey_multiple_responses');


            if (!isset($existedRow) && $allow_multiple_responses != true) {
                $wpdb->insert(
                    $table_name,
                    array(
                    'time' => $survey_time,
                    'user_id' => $userID,
                    'survey_id' => $surveyID,
                    'questions_answers' => $surveyData,
                )
                );
            } elseif ($allow_multiple_responses == true) {
                $wpdb->insert(
                    $table_name,
                    array(
                    'time' => $survey_time,
                    'user_id' => $userID,
                    'survey_id' => $surveyID,
                    'questions_answers' => $surveyData,
                )
                );
            }

            wp_die();
        } else {
			die();
		}
	}


	/**
	 * 
	 * Create Table for Survey Info
	 * 
	 */

	public function init_poll_db()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'wadi_poll_submissions';
		$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

		if (!$wpdb->get_var($query) == $table_name) {

			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				user_id mediumint(9)NOT NULL,
				poll_id mediumint(9)NOT NULL,
				questions_answers LONGTEXT NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * set Data from Poll Form to wp_wadi_poll_submissions
	 */



	public function getPollData()
	{
		global $wpdb;
		$data = $_POST['data'];
		$poll_time =  current_time('mysql', true);

		$table_name = $wpdb->prefix . 'wadi_poll_submissions';

		


		$existedRow = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM " . $table_name . "
				WHERE user_id = %d AND poll_id = %d LIMIT 1",
				$data['user_id'],
				$data['poll_id']
			)
		);


		$allow_multiple_responses_poll =  carbon_get_post_meta($data['poll_id'], 'wadi_poll_multiple_responses');
		

		if (!isset($existedRow) && $allow_multiple_responses_poll != TRUE) {


			$wpdb->insert(
				$table_name,
				array(
					'time' => $poll_time,
					'user_id' => $data['user_id'],
					'poll_id' => $data['poll_id'],
					'questions_answers' => $data['pollData'],
				)
			);
		} elseif ($allow_multiple_responses_poll == TRUE) {
			$wpdb->insert(
				$table_name,
				array(
					'time' => $poll_time,
					'user_id' => $data['user_id'],
					'poll_id' => $data['poll_id'],
					'questions_answers' => $data['pollData'],
				)
			);
		}

		wp_die();
	}
}

new WadiSurveyDB;
