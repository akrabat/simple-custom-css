<?php
/**
 * Plugin Name: Simple Custom CSS
 * Plugin URI: http://johnregan3.github.io/simple-custom-css
 * Description: The simple, solid way to add custom CSS to your WordPress website. Simple Custom CSS allows you to add your own styles or override the default CSS of a plugin or theme.</p>
 * Author: John Regan
 * Author URI: http://johnregan3.me
 * Version: 1.0
 * Text Domain: sccss
 *
 * Copyright 2013  John Regan  (email : johnregan3@outlook.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package SCCSS
 * @author John Regan
 * @version 1.1
 */


/**
 * Print direct link to Custom CSS admin page
 *
 * Fetches array of links generated by WP Plugin admin page ( Deactivate | Edit )
 * and inserts a link to the Custom CSS admin page
 *
 * @since  1.0
 * @param  array $links Array of links generated by WP in Plugin Admin page.
 * @return array        Array of links to be output on Plugin Admin page.
 */

function sccss_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url('themes.php?page=simple-custom-css.php' ) .'">Settings</a>';
	array_unshift( $links, $settings_page );
	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter( "plugin_action_links_$plugin", 'sccss_settings_link' );


/**
 * Print custom CSS to <HEAD>
 *
 * Fetches content of scss-settings and pulls out sccss-content field.
 * Then, echoes the sccss-content field.
 *
 * @since 1.0
 */
function sccss_style() {
?>
	<style type="text/css">
		<?php
			$options = get_option( 'sccss_settings' );
			$content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';
			echo esc_html( $content );
		?>
	</style>
<?php
}

//Don't load in WP Admin
if ( ! is_admin() )
	add_action('wp_print_scripts','sccss_style', 99);


/**
 * Register text domain
 *
 * @since 1.0
 */
function sccss_textdomain() {
	load_plugin_textdomain('sccss');
}

add_action('init', 'sccss_textdomain');


/**
 * Register "Custom CSS" submenu in "Appearance" Admin Menu
 *
 * @since 1.0
 */
function sccss_register_submenu_page() {
	add_theme_page( __( 'Simple Custom CSS', 'sccss' ), __( 'Custom CSS', 'sccss' ), 'edit_themes', basename(__FILE__), 'sccss_render_submenu_page' );
}

add_action( 'admin_menu', 'sccss_register_submenu_page' );


/**
 * Register settings
 *
 * @since 1.0
 */
function sccss_register_settings() {
	register_setting('sccss_settings_group', 'sccss_settings');
}

add_action('admin_init', 'sccss_register_settings');


/**
 * Render Admin Menu page
 *
 * @since 1.0
 */
function sccss_render_submenu_page() {

	$options = get_option( 'sccss_settings' );
	$content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';

	if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated"><p><?php _e( 'Custom CSS updated successfully.' ); ?></p></div>
	<?php endif; ?>
 
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Simple Custom CSS', 'sccss' ); ?></h2>
		<p><?php _e('Simple Custom CSS allows you to add your own styles or override the default CSS of a plugin or theme.', 'sccss') ?></p>

		<div id="templateside">
			<h3><?php _e('Instructions', 'sccss') ?></h3>
			<ol>
				<li><?php _e('Enter your custom CSS in the the texarea to the right.', 'sccss') ?></li>
				<li><?php _e('Click "Update Custom CSS."', 'sccss') ?></li>
				<li><?php _e('Enjoy your new CSS styles!', 'sccss') ?></li>
			</ol>
			<p>&nbsp;</p>
			<h3><?php _e('Help', 'sccss') ?></h3>
			<p><a href="<?php echo esc_url('https://github.com/johnregan3/simple-custom-css/wiki'); ?>" ><?php _e('Simple Custom CSS Wiki', 'sccss'); ?></a></p>
		</div>

		<form name="sccss-form" id="template" action="options.php" method="post" enctype="multipart/form-data">
			<?php do_action('sccss-form-top'); ?>
			<?php settings_fields('sccss_settings_group'); ?>
			<div>
				<textarea cols="70" rows="30" name="sccss_settings[sccss-content]" id="sccss_settings[sccss-content]" ><?php echo esc_html( $content ); ?></textarea>
			</div>
			<?php do_action('sccss-textarea-bottom'); ?>
			<div>
				<?php submit_button( __( 'Update Custom CSS', 'sccss' ), 'primary', 'submit', true ); ?>
			</div>
			<?php do_action('sccss-form-bottom'); ?>
		</form>


	</div>

<?php }
