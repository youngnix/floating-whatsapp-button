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

function fwb_activate() {
	// pass
}

register_activation_hook(__FILE__, 'fwb_activate');

function load_plugin() {
  if ( is_admin() && get_option( 'Activated_Plugin' ) == 'fwb' ) {
    delete_option( 'Activated_Plugin' );
  }
}
add_action( 'admin_init', 'load_plugin' );

// Register custom meta field for hiding the WhatsApp button
function fwb_register_meta() {
	register_post_meta('page', 'fwb_hide', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'boolean',
		'auth_callback' => function () {
			return current_user_can('edit_pages');
		}
	]);
}
add_action('init', 'fwb_register_meta');

// Add WhatsApp icon styles to the head
add_action("wp_head", function() {
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<?php
});

// Add the floating WhatsApp button in the footer
add_action("wp_footer", function() {
	$whatsapp_link = "https://wa.me/" . get_option("fwb_whatsapp_number") . "?text=" . rawurlencode(get_option("fwb_whatsapp_message"));
	$new_page = get_option("fwb_open_blank") ? "_blank" : "_self";

	// Check if the meta field for hiding the WhatsApp button is not set
	if (!get_post_meta(get_the_ID(), "fwb_hide", true)) {
		?>
		<div class="fwb-wrapper">
			<a id="fwb" class="fwb-button" target="<?php echo $new_page; ?>" href="<?php echo esc_url($whatsapp_link); ?>"><i class="fa fa-whatsapp"></i></a>
		</div>
		<?php
	}
});

// Enqueue styles for the button
function fwb_enqueue_styles() {
  	wp_enqueue_style(
  		"floating-whatsapp-button-style",
  		plugins_url("res/css/style.css", __FILE__),
  		[],
  		"1.0.0"
  	);
}
add_action("wp_enqueue_scripts", "fwb_enqueue_styles");

// Add settings page for plugin configuration
function fwb_add_options_page() {
	add_options_page(
		"Floating WhatsApp Button Settings",  // Page title
  		"WhatsApp Button",                   // Menu title
  		"manage_options",                     // Capability
  		"floating-whatsapp-button",           // Menu slug
  		"fwb_options_page"                    // Function to display settings page
	);
}
add_action("admin_menu", "fwb_add_options_page");

// Settings page content
function fwb_options_page() {
  	?>
  	<div class="wrap">
    	<h1>Floating WhatsApp Button Settings</h1>
    		<form method="post" action="options.php">
      			<?php
				settings_fields("floating-whatsapp-button");
  			do_settings_sections("floating-whatsapp-button");
  			submit_button();
  		?>
  	</form>
  	</div>
  	<?php
}

// Register plugin settings
function fwb_register_settings() {
  	register_setting("floating-whatsapp-button", "fwb_whatsapp_number");
  	register_setting("floating-whatsapp-button", "fwb_whatsapp_message");
	register_setting("floating-whatsapp-button", "fwb_open_blank", [
    		"sanitize_callback" => function($value) {
      			return $value ? 1 : 0;
    		}
	]);

  	add_settings_section(
    		"fwb_settings_section",
    		"WhatsApp Button Settings",
    		null,
    		"floating-whatsapp-button"
  	);

  	add_settings_field(
    		"fwb_whatsapp_number",
    		"WhatsApp Number or Link",
    		"fwb_whatsapp_number_field",
    		"floating-whatsapp-button",
    		"fwb_settings_section"
  	);

  	add_settings_field(
    		"fwb_whatsapp_message",
    		"Sent Message",
    		"fwb_whatsapp_message_field",
    		"floating-whatsapp-button",
    		"fwb_settings_section"
  	);

  	add_settings_field(
    		"fwb_open_blank",
    		"Open in New Page",
    		"fwb_open_blank_field",
    		"floating-whatsapp-button",
    		"fwb_settings_section"
  	);
}
add_action("admin_init", "fwb_register_settings");

// WhatsApp Number field
function fwb_whatsapp_number_field() {
  	$value = get_option("fwb_whatsapp_number");
  	echo '<input type="number" name="fwb_whatsapp_number" value="' . esc_attr($value) . '" class="regular-text">';
}

// WhatsApp Message field
function fwb_whatsapp_message_field() {
  	$value = get_option("fwb_whatsapp_message");
  	echo '<input type="text" name="fwb_whatsapp_message" value="' . esc_attr($value) . '" class="regular-text">';
}

// Open in New Page checkbox field
function fwb_open_blank_field() {
  	$value = get_option("fwb_open_blank");
  	echo '<input type="checkbox" name="fwb_open_blank" value="1" ' . checked(1, $value, false) . '>';
}

// Add Meta Box in the Page Editor for hiding the WhatsApp button
function fwb_add_meta_box() {
	add_meta_box(
		'fwb_hide_meta_box',
		'Hide Floating WhatsApp Button',
		'fwb_meta_box_content',
		'page',
		'side',
		'high'
	);
}
add_action('add_meta_boxes', 'fwb_add_meta_box');

// Meta Box content
function fwb_meta_box_content($post) {
	$value = get_post_meta($post->ID, 'fwb_hide', true);
	wp_nonce_field('fwb_hide_nonce', 'fwb_hide_nonce_field');
	?>
	<label for="fwb_hide">
		<input type="checkbox" name="fwb_hide" id="fwb_hide" value="1" <?php checked(1, $value); ?> />
		Hide the WhatsApp button on this page
	</label>
	<?php
}

// Save Meta Data when the page is saved
function fwb_save_meta_box_data($post_id) {
	if (isset($_POST['fwb_hide_nonce_field']) && !wp_verify_nonce($_POST['fwb_hide_nonce_field'], 'fwb_hide_nonce')) {
		return;
	}

	if (array_key_exists('fwb_hide', $_POST)) {
		update_post_meta($post_id, 'fwb_hide', $_POST['fwb_hide']);
	} else {
		delete_post_meta($post_id, 'fwb_hide');
	}
}
add_action('save_post', 'fwb_save_meta_box_data');

?>
