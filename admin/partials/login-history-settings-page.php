<?php

/**
 *
 * This file is used to markup the settings page of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Login_History
 * @subpackage Login_History/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div id="wrap">	
	<div class="wrap">
	<h1>Login History</h1>
	
	<form method="post" action="options.php">

	<?php
	settings_fields( 'login_history_options_group' );
	do_settings_sections( 'login_history_settings_page' );
	submit_button();

	?>
	</form>
	</div>
</div>