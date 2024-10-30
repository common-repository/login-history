<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$tablename = $wpdb->prefix."login_history_auth_log";
$wpdb->query( "DROP TABLE IF EXISTS `$tablename`" );

delete_option('login-history-settings');

// delete user meta
$users = get_users();
foreach ($users as $user) {
	delete_user_meta($user->ID, 'last_login');
}


