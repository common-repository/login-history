<?php

/**
 * Fired during plugin activation
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Login_History
 * @subpackage Login_History/includes
 * @author     Opal Devs <info@opaldevs.com>
 */
class Login_History_Activator {

	/**
	 * Runs when Login History is activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;

		// Create database table to store the login data
		$auth_log_tablename = $wpdb->prefix."login_history_auth_log";
		if($wpdb->get_var("SHOW TABLES LIKE '$auth_log_tablename'") != $auth_log_tablename ){

			$sql = "CREATE TABLE `$auth_log_tablename`  (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`attempt_time` BIGINT UNSIGNED,
				`ip_address` VARCHAR(50),
				`ip_location` VARCHAR(255),
				`device_type` VARCHAR(50),
				`username` VARCHAR(100),
				`result_code` VARCHAR(50),
				`result_description` VARCHAR(255),
				PRIMARY KEY  (id)
				);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		// Set up our housekeeping cron job 

		//Use wp_next_scheduled to check if an event is already scheduled
		$timestamp = wp_next_scheduled( 'login_history_housekeeping' );

		if( $timestamp == false ){
			//Schedule the event for right now, then to repeat twice daily using the hook 'login_history_housekeeping'
			wp_schedule_event( time(), 'twicedaily', 'login_history_housekeeping' );
		}

		// if this is a new installation then we need to put in some default settings
		$default_settings = get_option('login-history-settings');
		if (!$default_settings) {
			$default_settings = array();
			$default_settings['delete_records_after_days'] = LOGIN_HISTORY_DELETE_RECORDS_FROM_DB_AFTER_DAYS;
			add_option('login-history-settings',$default_settings);
		}

		// Add a welcome message
		$msg = '<strong>' . __('Thank you for installing Login History','login-history') . '</strong> </p><p>';
		$msg .=  __('Login records will be recorded ','login-history') . ' <a href="' . admin_url( 'users.php?page=login-history' ) . '">' . __('here','login-history') . '</a>';
		Login_History_Admin::add_flash_notice($msg,'success');

	}

}
