<?php

/**
 * Fired during plugin deactivation
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Login_History
 * @subpackage Login_History/includes
 * @author     Opal Devs <info@opaldevs.com>
 */
class Login_History_Deactivator {

	/**
	 * Deactivate the plugin
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// remove our cron job
		wp_clear_scheduled_hook('login_history_housekeeping');

		// tasks such as deleting database tables are only performed when the plugin is uninstalled
		// or the site is deleted (when in multisite mode)
	}

}
