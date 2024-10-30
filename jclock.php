<?php
/*
Plugin Name: Jquery Simple Clock for Wordpress
Plugin URI: http://swaind.com/wpjclock
Description: Jquery Simple Clock for Wordpress
Version: 1.1.1
Author: Swaind
Author URI: http://swaind.com/wpjclock

Copyright 2011 Chris . (email : chris.chenxt@swaind.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


//Define the plugin directory and url for file access.
$jclockuploads = wp_upload_dir();
define( 'jclock_INSERTJS',  plugin_dir_url( __FILE__ ) . 'js/'  );
define( 'jclock_INSERTCSS', plugin_dir_url( __FILE__ ) . 'css/' );

//include javacript and css into header
wp_enqueue_script( 'jquery' );
wp_enqueue_script('jclock_script_handle1', jclock_INSERTJS . 'jquery.jclock-1.2.0.js',array( 'jquery' ),'1.2.0');


add_action( 'wp_head', 'jclock_addHeaderCode',30);
function jclock_addHeaderCode() {
	echo '
	<script type="text/javascript">
	jQuery(document).ready(function() {
    jQuery(\'.jclock\').jclock();
	});
	</script>';
}

add_action( 'wp_print_styles', 'jclock_addHeaderStyle');
function jclock_addHeaderStyle() {
        // TBD
		}

add_shortcode( 'jclock', 'jclock_shortcode' );		
function jclock_shortcode ($atts) {
		extract(shortcode_atts(array(
			"size" => '',
			"color" => ''
            ), $atts));
		if (!$size && !$color) {
		$size = get_option('clock-font-size', '12');
		$color = get_option('clock-font-color', 'black'); }
		return "<span style=\"font-size:{$size}; font-color:{$color};\"><div class='jclock'></div></span>";
	}


// create custom plugin settings menu
if (is_admin()) {
add_action('admin_menu', 'jclock_create_menu');
}

function jclock_create_menu() {

	//create new top-level menu
	add_options_page('jClock Plugin Settings', 'jClock Settings', 'administrator', __FILE__, 'jClock_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register jClock settings
	register_setting( 'jClock-settings-group', 'clock-font-size' );
	register_setting( 'jClock-settings-group', 'clock-font-color' );
}

function jClock_settings_page() {
?>
<div class="wrap">
<h2>jQuery Simple Clock</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'jClock-settings-group' ); ?>
    <?php do_settings_fields( 'jClock-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Font Size</th>
        <td><input type="text" name="clock-font-size" value="<?php echo get_option('clock-font-size', '12'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Font Clock</th>
        <td><input type="text" name="clock-font-color" value="<?php echo get_option('clock-font-color', 'black'); ?>" /></td>
        </tr>
  
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>