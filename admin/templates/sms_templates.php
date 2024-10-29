<?php
if ( ! defined( 'ABSPATH' ) ) 
    exit; // Exit if accessed directly
?>
<h1><?php _e( 'SMS Templates', 'awesome-sms-for-woocommerce' ) ?></h1>

<form method="post" action="options.php">
	<?php settings_fields( 'asmsfw-templates-settings' ); ?>
	<?php do_settings_sections( 'asmsfw-templates-settings' ) ?>
	<table class="form-table">
		<?php do_action( 'asmsfw_sms_templates' ) ?>
	</table>
	<?php submit_button();?>
</form>


 <?php /* SMS template customization fields are hooked to do_action('asmsfw_sms_templates') from admin_settings.php */