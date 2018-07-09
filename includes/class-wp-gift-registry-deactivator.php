<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://dreiqbik.de
 * @since      1.0.0
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/includes
 * @author     Moritz Bappert <mb@dreiqbik.de>
 */

class WP_Gift_Registry_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		delete_option( 'wpgiftregistry_tracking' );
		wp_clear_scheduled_hook( 'wpgiftregistry_usage_tracking' );

	}

}
