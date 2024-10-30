<?php

/**
 * This file is used to markup the login history page in the admin area.
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
			<form id="activity-log" method="get">
            <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
				
				<?php	
			$this->show_login_history_table(); ?>
	</form>
	</div>
</div>