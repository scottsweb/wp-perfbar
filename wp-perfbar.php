<?php
/*
Plugin Name: WP PerfBar
Plugin URI:
Description: PerfBar for WordPress
Version: 0.1
Author: Scott Evans
Author URI: http://scott.ee
Text Domain: wp-perfbar
License: GPL v2 or later
*/

/**
 * wp_perfbar
 *
 * Determine the lowest possible place to hook the JS
 *
 * @author Scott Evans
 * @return void
 */
function wp_perfbar() {
	if ( function_exists( 'tha_body_bottom' ) ) {
		add_action('tha_body_bottom', 'wp_perfbar_tha_js'); // needs to be output as standard JS in this instance - boo
	} else {
		add_action('wp_enqueue_scripts', 'wp_perfbar_js', 10000);
	}
}
add_action( 'init', 'wp_perfbar' );

/**
 * wp_perfbar_tha_js
 *
 * Output scripts low if theme supports theme hook alliance hooks: https://github.com/zamoose/themehookalliance
 *
 * @author Scott Evans
 * @return void
 */
function wp_perfbar_tha_js() {

	// should we load?
	if ( ! wp_perfbar_load() )
		return;

	?>
	<script type="text/javascript" src="<?php echo plugins_url( 'wp-perfbar/assets/js/perfbar.js' ); ?>?ver=<?php echo filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/perfbar.js' ) ?>"></script>
	<script type="text/javascript" src="<?php echo plugins_url( 'wp-perfbar/assets/js/wp-perfbar.js' ); ?>?ver=<?php echo filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/wp-perfbar.js' ) ?>"></script>
	<?php
}

/**
 * wp-perfbar_js
 *
 * Register and enqueue the required JS for admins only
 *
 * @author Scott Evans
 * @return void
 */
function wp_perfbar_js() {

	// should we load?
	if ( ! wp_perfbar_load() )
		return;

	// register all scripts
	wp_register_script( 'perfbar', plugins_url( 'assets/js/perfbar.js', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/perfbar.js' ), true);
	wp_register_script( 'wp-perfbar', plugins_url( 'assets/js/wp-perfbar.js', __FILE__ ), array('perfbar'), filemtime( plugin_dir_path( __FILE__ ). 'assets/js/wp-perfbar.js' ), true );

	// enqueue
	wp_enqueue_script('wp-perfbar');

}

/**
 * wp-perfbar_load
 *
 * Should we load the perfbar?
 *
 * @author Scott Evans
 * @return bool
 */
function wp_perfbar_load() {

	$load = true;

	// if not current user can activate plugins
	if ( ! current_user_can( 'activate_plugins' ) )
		$load = false;

	return apply_filters( 'perfbar_load', $load );

}
