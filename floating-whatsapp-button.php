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

function floating_whatsapp_button_activate() {
	// pass
}

register_activation_hook(__FILE__, 'floating_whatsapp_button_activate');

function load_plugin() {

    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {
        delete_option( 'Activated_Plugin' );
    }
}
add_action( 'admin_init', 'load_plugin' );

add_action("wp_head", function() {
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<?php
});

add_action("wp_footer", function() {
	?>
	<div class="fwb-wrapper">
		<a class="fwb-button"><i class="fa fa-whatsapp"></i></a>
	</div>
	<?php
});

function floating_whatsapp_button_enqueue_styles() {
    wp_enqueue_style(
        "floating-whatsapp-button-style",
        plugins_url("res/style.css", __FILE__),
        [],
        "1.0.0"
    );
}
add_action("wp_enqueue_scripts", "floating_whatsapp_button_enqueue_styles");
?>
