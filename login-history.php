<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              opaldevs.com
 * @since             1.0.0
 * @package           Login_History
 *
 * @wordpress-plugin
 * Plugin Name:       Login History
 * Plugin URI:        opalsdevs.com
 * Description:       Adds a login history page to the admin area (Users section).
 * Version:           2.1.2
 * Author:            Opal Plugins
 * Author URI:        https://opaldevs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       login-history
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/* Current plugin version */
define( 'LOGIN_HISTORY_VERSION', '2.1.2' );

/* Default values */
define( 'LOGIN_HISTORY_DELETE_RECORDS_FROM_DB_AFTER_DAYS',90);
define( 'LOGIN_HISTORY_DEFAULT_ENTRIES_PER_PAGE',25);

/* Useful constants */
define( 'LOGIN_HISTORY_SECONDS_IN_1_DAY',86400);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-login-history-activator.php
 */
function activate_login_history() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-login-history-activator.php';
	Login_History_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-login-history-deactivator.php
 */
function deactivate_login_history() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-login-history-deactivator.php';
	Login_History_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_login_history' );
register_deactivation_hook( __FILE__, 'deactivate_login_history' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-login-history.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_login_history() {

	$plugin = new Login_History();
	$plugin->run();

}
run_login_history();
