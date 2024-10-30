<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Login_History
 * @subpackage Login_History/public
 * @author     Opal Devs <info@opaldevs.com>
 */
class Login_History_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * The wp_login hook fires after a successful user login. 
	 *
	 * @since	1.0.0
	 * 
	 * @param	string		$username
	 *
	 */
	public function wp_login ($username, $user) {

		$remote_ip_address = Login_History::get_ip_address();
		$time_now = time();
		$this->add_login_attempt_to_the_activity_log($time_now, $remote_ip_address, $username);

		update_user_meta( $user->ID, 'last_login', $time_now );

	}

	/**
	 * Add login attempt to the activity log
	 * 
	 * @since	1.0.0
	 *
	 * @param	string		ip address
	 * @param	string		username 
	 * @param	WP_Error	optional - only if this was a failed login attempt
	 *
	 */
	public function add_login_attempt_to_the_activity_log($time_now, $ip_address, $username, $error = NULL ) {


		// if no error is passed to us this must be a successful login 
		if ($error == NULL) {
			$log = new Login_History_Auth_Log($time_now, $ip_address, $username, 'success', __('Successful login.','login-history'));
		}
		else {
			// get the core details of the error 
			$error_code = $error->get_error_code();

			$error_message = $error->get_error_message();
			$error_message = strip_tags($error_message);

			// Log this in the activity log
			$log = new Login_History_Auth_Log($time_now, $ip_address, $username, $error_code, $error_message);
		}

		@$log->get_ip_location();

		$log->set_device_type_from_user_agent_string();
		$log->save();

		
	}


	/**
	 * 
	 * The wp_login_failed hook fires after a user login has failed. 
	 * 
	 * @since	1.0.0
	 *
	 * @param	string		$username
	 * @param 	WP_Error	$error
	 *
	 */
	public function wp_login_failed( $username , $error) {

		$remote_ip_address = Login_History::get_ip_address();
		$time_now = time();
		
		$this->add_login_attempt_to_the_activity_log($time_now, $remote_ip_address, $username, $error);

	}

	/**
	 *
	 * @since	1.0.0
	 * 
	 * Cron job used for housekeeping the database tables etc.
	 *
	 */
	public function login_history_cron_job() {
		global $wpdb;

		$time_difference = time() - (LOGIN_HISTORY_DELETE_RECORDS_FROM_DB_AFTER_DAYS * 86400);	// 86400 = seconds in 1 day
		
		$tablename = $wpdb->prefix."login_history_auth_log";
		$query = $wpdb->prepare("DELETE FROM {$tablename} WHERE attempt_time < %d " , $time_difference);
		$wpdb->query($query);

	}
	

}
