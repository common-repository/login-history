<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Login_History
 * @subpackage Login_History/includes
 * @author     Opal Devs <info@opaldevs.com>
 */
class Login_History {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Login_History_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOGIN_HISTORY_VERSION' ) ) {
			$this->version = LOGIN_HISTORY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'login-history';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Login_History_Loader. Orchestrates the hooks of the plugin.
	 * - Login_History_i18n. Defines internationalization functionality.
	 * - Login_History_Admin. Defines all hooks for the admin area.
	 * - Login_History_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-login-history-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-login-history-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-login-history-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-login-history-public.php';

		/**
		 * The class responsible for recording login attempts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-login-history-auth-log.php';

		/**
		 * The class responsible for functions related to displaying the table on the activity log page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-login-history-table-auth-log.php';


		$this->loader = new Login_History_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Login_History_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Login_History_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Login_History_Admin( $this->get_plugin_name(), $this->get_version() );

		// register our admin page in the sidebar
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page' );

		// Hook our settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

		// Screen option for the admin page
		$this->loader->add_filter( 'set-screen-option' , $plugin_admin, 'set_screen_option', 10, 3 );

		// extra action to display in the Users table
		$this->loader->add_filter( 'user_row_actions', $plugin_admin, 'user_row_actions', 10, 2 );

		// extra column in the Users table
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'manage_users_columns' );
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'manage_users_custom_column', 10, 3 );


		// Admin notices in the admin area
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_flash_notices', 12 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Login_History_Public( $this->get_plugin_name(), $this->get_version() );

		// login success
		$this->loader->add_action( 'wp_login', $plugin_public, 'wp_login',10,2 );

		// login failed
		$this->loader->add_action( 'wp_login_failed', $plugin_public, 'wp_login_failed',9999,2);

		// cron job for DB housekeeping
		$this->loader->add_action( 'login_history_housekeeping', $plugin_public, 'login_history_cron_job' );

		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Login_History_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get the first sentence of a string.
	 *
	 * If no ending punctuation is found then $text will
	 * be returned as the sentence. If $strict is set
	 * to TRUE then FALSE will be returned instead.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $text   Text
	 * @param  boolean $strict Sentences *must* end with one of the $end characters
	 * @param  string  $end    Ending punctuation
	 * @return string|bool     Sentence or FALSE if none was found
	 */
	public static function get_first_sentence($text, $strict = false, $end = '.?!') {
		preg_match("/^[^{$end}]+[{$end}]/", $text, $result);
		if (empty($result)) {
			return ($strict ? false : $text);
		}
		return $result[0];
	}

	/**
	 * Get the IP address of the connecting user
	 *
	 * note: REMOTE_ADDR is provided by the server. The others are provided as headers in the request.
	 * 
	 * @since 	1.0.0
	 * 
	 * @return	string|NULL    The IP address if found or NULL.
	 * 
	 */
	public static function get_ip_address() {
		$ip = NULL;
		$site_uses_reverse_proxy = FALSE;
		$reverse_proxy_trusted_header = NULL;

		// this list is in order of importance
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED') as $key){
            if (array_key_exists($key, $_SERVER) === TRUE) {
				$reverse_proxy_trusted_header = $key;
                foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip); // just to be safe
					
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE){
						
						// we have a valid IP address here, so assume this to be a reverse proxy
						$reverse_proxy_trusted_header = $key;
						$site_uses_reverse_proxy = TRUE;
                    }
                }
            }
		}

		if ( ($site_uses_reverse_proxy) && (isset( $_SERVER[ $reverse_proxy_trusted_header ])) ) {
			$ip = $_SERVER[ $reverse_proxy_trusted_header ];
		} else {
			if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) {
				$ip = $_SERVER[ 'REMOTE_ADDR' ];
			}
		}

		// Clean up our IP string if found
		if (!empty($ip)) {
			$ip = trim($ip); // just to be safe
			$ip = filter_var($ip, FILTER_VALIDATE_IP);
			if (!empty($ip)) {
				return $ip;
			}	
		}
		
		return NULL;

	}
}
