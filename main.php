<?php
/**
 * @package Floating_Whatsapp_Button
 * @version 1.0.0
 */
/*
Plugin Name: Floating WhatsApp Button
Plugin URI: 
Description: Floating WhatsApp Button.
Author: 3MEIA9
Version: 1.0.0
Author URI: 
*/

add_action("wp_head", function() {
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<?php
})

add_action('wp_close_body', function() {
	?>
	<div class="fwb-wrapper">
		<a class="fwb-button"><i class="fa-fw fa-whatsapp"></i></a>
	</div>
	<?php
})

wp_register_style("floating_whatsapp_button", "wp-content/plugins/floating-whatsapp-button/res/style.css");

?>
