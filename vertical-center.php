<?php
/**
 * Vertical Center
 *
 * @package             Vertical_Center
 * @author              Braad Martin <wordpress@braadmartin.com>
 * @license             GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:         Vertical Center
 * Plugin URI:          https://wordpress.org/plugins/vertical-center/
 * Description:         Easily vertically center any element relative to its container. Fully responsive.
 * Version:             1.1.1
 * Author:              Braad Martin
 * Author URI:          http://braadmartin.com
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         vertical-center
 * Domain Path:         /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'init', 'init_vertical_center_plugin' );
/**
 * Initialize the plugin.
 *
 * @since  1.0.0
 */
function init_vertical_center_plugin() {

	define( 'VERTICAL_CENTER_PATH', plugin_dir_path( __FILE__ ) );
	define( 'VERTICAL_CENTER_URL', plugin_dir_url( __FILE__ ) );
	define( 'VERTICAL_CENTER_VERSION', '1.1.1' );

	// Load translation files.
	load_plugin_textdomain( 'vertical-center', false, VERTICAL_CENTER_PATH . 'languages' );

	// Include the main plugin class.
	require_once( VERTICAL_CENTER_PATH . 'classes/class-vertical-center.php' );

	// Start the party.
	new Vertical_Center();
}
