<?php
/**
 * Plugin Name: Color Mobile Browser Address Bar
 * Plugin URI: https://wordpress.org/plugins/color-mobile-browser-address-bar
 * Description: A WordPress plugin that lets you add a custom color to the address bar of mobile browsers.
 * Version: 1.0.10
 * Author: David Webb Espiritu
 * Author URI: https://profiles.wordpress.org/webbteche
 * License: GPLv2 or later
 */

// For security purposes
if ( !defined( 'ABSPATH' ) )
    exit;

// Create a sub menu on the appearance admin menu
add_action( 'admin_menu', 'cmbab_create_menu' );
function cmbab_create_menu( )
  {
    add_theme_page( 'Color Mobile Browser Address Bar', 'Mobile Browser Address Bar Color', 'manage_options', 'color-mobile-browser-address-bar', 'cmbab_main_function' );
    add_action( 'admin_init', 'cmbab_register_settings' );
  }

// Register settings
function cmbab_register_settings( )
  {
    register_setting( 'cmbab-settings-group', 'cmbab_color_value', 'sanitize_hex_color' );
  }

// Enqueue Color picker scripts
add_action( 'admin_enqueue_scripts', 'cmbab_enqueue_color_picker_scripts' );
function cmbab_enqueue_color_picker_scripts( $hook )
  {
    if ( 'appearance_page_color-mobile-browser-address-bar' != $hook )
        return;
	
	wp_enqueue_style
	( 'wp-color-picker' );
	
	wp_enqueue_script
	( 'custom-script-handle', plugins_url( 'admin/js/custom-script.js', __FILE__ ), array('wp-color-picker'), false, true );
  }
  
// Create plugin settings page
function cmbab_main_function( )
  {
    if ( !current_user_can( 'manage_options' ) )
      {
        wp_die( "Oops! You've gone too far." );
      }
?>

	<div class="wrap">
	<h2>Color Mobile Browser Address Bar</h2>
        <form method="post" action="options.php">
		<?php
		settings_fields( 'cmbab-settings-group' );
		do_settings_sections( 'cmbab-settings-group' );
		
		//Display admin notices
		settings_errors();
		
		$cmbab_color_value = esc_attr( get_option( 'cmbab_color_value' ) );

		?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Set Color</th>
						<td>
							<input type="text" id="cmbab_color_picker_id" name="cmbab_color_value" data-default-color="#f3f3f3" value="<?php echo $cmbab_color_value;?>" />
						</td>
				</tr>
            </table>
		
		<?php submit_button();?>
		
		</form>
    </div>
	<?php
  }
  
// add theme color meta data on the <head>
add_action( 'wp_head', 'cmbab_add_theme_color_metadata' );
function cmbab_add_theme_color_metadata( )
  {
    $cmbab_color_value = esc_attr( get_option( 'cmbab_color_value' ) );

	// Add default value when first activated or somebody made an invalid hex color input
	if (strlen($cmbab_color_value) == 0)
	{
		$cmbab_color_value = "#f3f3f3";
	}
    
	//this is for Chrome, Firefox
    echo '<meta name="theme-color" content="' . $cmbab_color_value . '">';
    //this is for Windows Phone
    echo '<meta name="msapplication-navbutton-color" content="' . $cmbab_color_value . '">';
    //this is for iOS Safari
    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
  }
?>