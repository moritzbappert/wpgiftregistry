<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://dreiqbik.de
 * @since             1.0.0
 * @package           WP_Gift_Registry
 *
 * @wordpress-plugin
 * Plugin Name:       WPGiftRegistry
 * Plugin URI:
 * Description:       A simple way to create a linked list of wishes for your wedding, birthday or other occasion.
 * Version:           1.4.13
 * Author:            Moritz Bappert
 * Author URI:        https://dreiqbik.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpgiftregistry
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-WP_Gift_Registry-activator.php
 */
function activate_wp_gift_registry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-gift-registry-activator.php';
	WP_Gift_Registry_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-WP_Gift_Registry-deactivator.php
 */
function deactivate_wp_gift_registry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-gift-registry-deactivator.php';
	WP_Gift_Registry_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_gift_registry' );
register_deactivation_hook( __FILE__, 'deactivate_wp_gift_registry' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-gift-registry.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_gift_registry() {

	$plugin = new WP_Gift_Registry();
	$plugin->run();

}
run_wp_gift_registry();
