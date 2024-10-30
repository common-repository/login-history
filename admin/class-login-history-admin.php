<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       opaldevs.com
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Login_History
 * @subpackage Login_History/admin
 * @author     Opal Devs <info@opaldevs.com>
 */
class Login_History_Admin {

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
	 * The date format that the user has set in their profile.
	 *
	 * @access   private
	 * @var      string    $date_format    
	 */
    private $date_format;

    /**
	 * The time format that the user has set in their profile.
	 *
	 * @access   private
	 * @var      string    $time_format    
	 */
    private $time_format;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		   // get formats so we can display the date & time in the correct way for the user
        // the user sets this in their profile
        $this->date_format = get_option( 'date_format' );
        $this->time_format = get_option( 'time_format' ); 

	}


	/**
	 * Display the last login column in the admin->users page.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function manage_users_columns( $columns ) {
		$columns['last_login'] = 'Last Login';
		return $columns;
	}
	

	/**
	 * Display the content for the last login column in admin->users.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function manage_users_custom_column( $output, $column_id, $user_id ){
		if( $column_id == 'last_login' ) {
			$last_login = get_user_meta( $user_id, 'last_login', true );

			if ($last_login) {
				$last_login_date = wp_date($this->date_format,$last_login);
				$last_login_time = wp_date($this->time_format,$last_login);
						
				$output = $last_login_date . '<br/>' . $last_login_time;
			}
			else
			{
				$output = "—";
			}
		}
	  
		return $output;
	}


	/**
	 * Display the login history page content.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function display_login_history_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/login-history-page.php';

	}

	/**
	 * Display the settings page content.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function display_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/login-history-settings-page.php';

	}

	/**
	 * Register the settings for our settings page.
	 *
	 * @since	1.0.0
	 * 
	 */
	public function register_settings() {

		// Here we are going to register our setting.
		register_setting(
			'login_history_options_group',							// Option group name
			'login-history-settings',								// Option name
			array( $this, 'sanitize_settings' )					// Sanitize callback
		);


		// Add a section for the trusted devices.
		add_settings_section(
			'login_history_settings_section',				// ID used to identify this section and with which to register options
			'',									// Title to be displayed on the administration page
			array( $this, 'login_history_settings_section_callback' ),	// Callback used to render the description of the section
			'login_history_settings_page'								// Page on which to add this section of options
		);

		add_settings_field(
			'delete_records_after_days',									// ID used to identify the field
			__( 'Delete records older than', 'login_history' ),					// The label to the left of the option interface element
			array( $this, 'settings_field_input_number_callback' ),	// The name of the function responsible for rendering the option interface
			'login_history_settings_page',									// The page on which this option will be displayed
			'login_history_settings_section',						// The name of the section to which this field belongs
			array(
				'label_for' => 'delete_records_after_days',
				'default'   => '',
				'after_text' => __('days','login-history'),
			)															// The array of arguments to pass to the callback
		);

	}

	/**
	 * This function provides content for the section. 
	 * 
	 * @since	1.0.0
	 * 
	 */
	public function login_history_settings_section_callback() {
		
		echo '<p>' . __('It is good practice to delete old login records when they are no longer needed. You can enter the age of records that should be deleted, or enter 0 to disable this functionality.','login-history') . '</p>';	
		return;

	}


	/**
	 * This function renders the interface elements for a number input field
	 *
	 * @since	1.0.0
	 * 
	 * @param	array		$args
	 * 
	 */
	public function settings_field_input_number_callback( $args ) {

		$field_id = isset($args['label_for']) ? $args['label_for'] : null;
		$field_default = isset($args['default']) ? $args['default'] : null;
		$before_text = isset($args['before_text']) ? $args['before_text'] : null;
		$after_text = isset($args['after_text']) ? $args['after_text'] : null;
		$further_text = isset($args['further_text']) ? $args['further_text'] : null;

		$options = get_option( 'login-history-settings' );
		$option = $field_default;

		if ( isset( $options[ $field_id ] ) ) 
			$option = $options[ $field_id ];
		
		?>
			<span class="description"><?php echo esc_html($before_text); ?> </span>
			<input type="text" step="1" min="0" name="<?php echo 'login-history-settings[' . esc_html($field_id) . ']'; ?>" id="<?php echo 'login-history-settings[' . esc_html($field_id) . ']'; ?>" value="<?php echo esc_attr( $option ); ?>" class="small-text" />
			<span class="description"><?php if (!empty($after_text)) echo esc_html($after_text); ?> </span>
			<?php if (!empty($further_text)) echo '<p class="description">' . esc_html($further_text) . '</p>'; ?>
		<?php

	}


	/**
	 * Sanitize the input from our form i.e. what the user has enetered
	 *
	 * @since 	1.0.0
	 * 
	 * @param	array	$input
	 * @return	array	$sanitized_input
	 * 
	 */
	public function sanitize_settings( $input ) {

		$settings = get_option( 'login-history-settings' );
		$new_input = array();
 
		if ( isset( $input ) ) {
			// Loop trough each input and sanitize the value
			foreach ( $input as $key => $value ) {
				switch ($key) {
					case 'delete_records_after_days':
						$sanitized_value = sanitize_text_field( trim($value) );
						if (filter_var($sanitized_value, FILTER_VALIDATE_INT) !== false) {
							if ( ((int)$sanitized_value < 0) || ((int)$sanitized_value > 10000) ) {
								$message = __('Please enter a numreic value between 0 and 10000','login-history')  ;
								add_settings_error('delete_record_after_days','delete_record_after_days', $message, 'error' );
								$new_input[ $key] = $settings [$key];	// use previous value;
							} else
								$new_input[ $key ] = (int)$sanitized_value;
						}
						else {
							$message = __('Please enter a numeric value between 0 and 10000','login-history')  ;
							add_settings_error('delete_record_after_days','delete_record_after_days', $message, 'error' );
							$new_input[ $key] = $settings [$key];	// use previous value;
						}					
					break;
				}
			}
		}
		return $new_input;
	}



	/**
	 * This function renders the login history table
	 *
	 * @since	1.0.0
	 * 
	 */
	public function show_login_history_table() {
		global $login_history_auth_log_table;

        $login_history_auth_log_table->prepare_items();
		$login_history_auth_log_table->views();
		$login_history_auth_log_table->search_box('Search', 'search-table'); 
		$login_history_auth_log_table->display();
            
	}

	/**
	 * This function adds a 'Login history' link to each row in the Users table
	 *
	 * @since	1.0.0
	 * 
	 */
	public function user_row_actions($actions, $user_object)
	{
		if ( current_user_can( 'administrator', $user_object->ID ) ) {
			$url = admin_url( "users.php?page=login-history&amp;s=$user_object->user_login");
			$actions['login_history'] = '<a href="' . $url . '">Login history</a>';
		}
    	return $actions;
	}

	
	/**
	 * Register the settings for the admin area.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function register_settings_page() {
		// Create our menu page.
		$settings_hook = add_submenu_page(
			'options-general.php',								// Register this submenu under this parent 
			__( 'Login history', 'login-history' ),			// The text to the display in the browser when this menu item is active
			__( 'Login history', 'login-history' ),        			// The text for this menu item
			'manage_options',                        			// Which type of users can see this menu
			'login-history-settings',                            			// The unique ID - the slug - for this menu item
			array( $this, 'display_settings_page' )  			// The function used to render the menu for this page to the screen
		);

		$activity_log_hook = add_submenu_page(
			'users.php',								// Register this submenu under this parent 
			__( 'Login History', 'login-history' ),			// The text to the display in the browser when this menu item is active
			__( 'Login History', 'login-history' ),        			// The text for this menu item
			'list_users',                        			// Which type of users can see this menu
			'login-history',                            			// The unique ID - the slug - for this menu item
			array( $this, 'display_login_history_page' )  			// The function used to render the menu for this page to the screen
		);

		add_action( "load-".$activity_log_hook, 'Login_History_Admin::add_screen_option' );
	}


	/**
	 * Add the screen option on the activity log page
	 *
	 * @since 1.0.0
	 * 
	 */
	public static function add_screen_option() {
		global $login_history_auth_log_table;

		$option = 'per_page';
		 
		$args = array(
			'label' => 'Number of entries per page',
			'default' => LOGIN_HISTORY_DEFAULT_ENTRIES_PER_PAGE,
			'option' => 'login_history_entries_per_page'
		);
		 
		add_screen_option( $option, $args );	
		
		$login_history_auth_log_table = new Login_History_Table_Auth_Log();
		
		// add a help tab

		// set up the text content
		$overview_content = '<p>' . __("This screen provides visibility to all login attempts on your site. You can customize the display of this screen to suit your needs.",'login-history') . '</p>';
		$screen_content = '<p>' . __("You can customize the display of this screen’s contents in a number of ways:",'login-history') . '</p>';
		$screen_content .= '<ul><li>' . __("You can hide/display columns based on your needs and decide how many login attempts to list per screen using the Screen Options tab.",'login-history') . '</li>';
		$screen_content .= '<li>' . __("You can filter the login attempts by time period using the text links above the table, for example to only show login attempts within the last 7 days. The default view is to show all available data.",'login-history') . '</li>';
		$screen_content .= '<li>' . __("You can search for login attempts by a certain IP address or username using the search box.",'login-history') . '</li>';
		$screen_content .= '<li>' . __("You can refine the list to show only failed or successful login attempts using the dropdown menus above the table. Click the Filter button after making your selection.",'login-history') . '</li></ul>';

		$current_screen = get_current_screen();
		
		// register our help overview tab
		$current_screen->add_help_tab( array(
			'id' => 'lh_activity_help_overview',
			'title' => __('Overview','login-history'),
			'content' => $overview_content
			)
			);

		// register our screen content tab
		$current_screen->add_help_tab( array(
			'id' => 'lh_activity_help_screen_content',
			'title' => __('Screen Content','login-history'),
			'content' => $screen_content
			)
			);	

	}


	/**
	 * Set the screen option.
	 *
	 * @since 1.0.0
	 * 
	 */
	public function set_screen_option($status, $option, $value) {
		if ( $option == 'login_history_entries_per_page' ) 
			return $value;
		
		return $status;
	}


	/**
	 * Displays any flash messages that have been
	 * (Messages are displayed once only)
	 *
	 * @since    1.0.0
     * 	 
     */
	public function display_flash_notices() {
		$notices = get_option( "login_history_flash_notices", array() );
		 
		// Iterate through our notices to be displayed and print them.
		foreach ( $notices as $notice ) {
				printf('<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
					$notice['type'],
					$notice['dismissible'],
					$notice['notice']
				);
			
		}
	 
		// Now we reset our options to prevent notices being displayed forever.
		if( ! empty( $notices ) ) {
			delete_option( "login_history_flash_notices", array() );
		}
	}


	/**
	 * Adds a notice that is displayed once on the next admin page
	 *
	 * @since    1.0.0
     * 
     * @param	string  The notice to be displayed
	 * @param	string	the type/class of message
	 * @param	bool	whether the message can be dismissed
     * 	 
     */
	public static function add_flash_notice( $notice = "", $type = "warning", $dismissible = true ) {
		// Here we return the notices saved on our option, if there are not notices, then an empty array is returned
		$notices = get_option( "login_history_flash_notices", array() );
	 
		$dismissible_text = ( $dismissible ) ? "is-dismissible" : "";
	 
		$duplicate = FALSE;
		foreach($notices as $existing_notice) {
			if ($existing_notice['notice'] == $notice) 
				$duplicate = TRUE;
		}

		if (!$duplicate) {
			// We add our new notice.
			array_push( $notices, array( 
					"notice" => $notice, 
					"type" => $type, 
					"dismissible" => $dismissible_text
				) );
		
			// Then we update the option with our notices array
			update_option("login_history_flash_notices", $notices );
		}
	}



}
