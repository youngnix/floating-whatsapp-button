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

add_action("wp_head", function() {
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<?php
	});

	add_action("wp_footer", function() {
	$whatsapp_link = "https://wa.me/" . get_option("fwb_whatsapp_number") . "?text=" . rawurlencode(get_option("fwb_whatsapp_message"));
	$new_page = get_option("fwb_open_blank") ? "_blank" : "_self";
	?>
	<div id="fwb" class="fwb-wrapper">
	<a class="fwb-button" href="<?php echo esc_url($whatsapp_link); ?>"><i class="fa fa-whatsapp"></i></a>
	</div>
	<?php
	});

	function fwb_enqueue_styles() {
  wp_enqueue_style(
  "floating-whatsapp-button-style",
  plugins_url("res/css/style.css", __FILE__),
  [],
  "1.0.0"
  );
	}
	add_action("wp_enqueue_scripts", "fwb_enqueue_styles");

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

function fwb_whatsapp_number_field() {
  $value = get_option("fwb_whatsapp_number");
  echo '<input type="number" name="fwb_whatsapp_number" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwb_whatsapp_message_field() {
  $value = get_option("fwb_whatsapp_message");
  echo '<input type="text" name="fwb_whatsapp_message" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwb_open_blank_field() {
  $value = get_option("fwb_open_blank");
  echo '<input type="checkbox" name="fwb_open_blank" value="1" ' . checked(1, $value, false) . '>';
}
?>
