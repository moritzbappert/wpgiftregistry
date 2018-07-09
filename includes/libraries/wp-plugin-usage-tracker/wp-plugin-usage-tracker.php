<?php
/**
 * WP Plugin Usage Tracker.
 *
 * Copyright (c) 2016 Alessandro Tesoro
 *
 * WP Plugin Usage Tracker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Plugin Usage Tracker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @copyright  (c) 2016 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    wp-plugin-usage-tracker
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP_Plugin_Usage_Tracker class.
 */
class WP_Plugin_Usage_Tracker {

	/**
	 * Codeless library helper object.
	 * @var object
	 */
	public $helper;

	/**
	 * Installation date of the plugin using this library.
	 * @var string
	 */
	private $installation_date;

	/**
	 * How many days we should wait to show the tracking message.
	 * @var string
	 */
	private $days_passed;

	/**
	 * Prefix of the plugin using this library.
	 * @var string
	 */
	private $plugin_prefix;

	/**
	 * Plugin name identifier that will be sent to Keen.io
	 * @var string
	 */
	private $plugin_name;

	private $firebase_url = 'https://wpgiftregistry.firebaseio.com/';
	private $firebase_token = 'AIzaSyB3CNk1LP5kEDstp7r6yFUHfvrqpHxt-Ps';
	private $firebase_path = 'usage.json';

	private $firestore_url = 'https://firestore.googleapis.com/v1beta1/projects/wpgiftregistry/databases/(default)/documents/usage/';


	/**
	 * Get things started.
	 *
	 * @param string $plugin_prefix     Prefix of the plugin using this library.
	 * @param string $plugin_name       Name of the plugin, this will be sent to keen.io so you can identify the data easily.
	 * @param string $installation_date Installation date of the plugin using this library.
	 * @param string $days_passed       How many days we should wait to show the tracking message.
	 * @param string $project_id        Keen.io project id.
	 * @param [type] $write_key         Keen.io write key.
	 */
	public function __construct( $plugin_prefix, $plugin_name, $installation_date, $days_passed ) {

		$this->plugin_prefix     = sanitize_title( $plugin_prefix );
		$this->plugin_name       = strip_tags( $plugin_name );
		$this->installation_date = strtotime( $installation_date );
		$this->days_passed       = $days_passed;

		require __DIR__ . '/vendor/autoload.php';

		$this->helper = new TDP\Codeless;

	}

	/**
	 * Run hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_action( 'admin_init', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'approve_tracking' ), 10 );
		add_action( 'admin_init', array( $this, 'schedule_tracking' ), 10 );

		if( $this->is_tracking_enabled() && $this->is_date_passed() ) {
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
			add_action( $this->plugin_prefix.'_usage_tracking', array( $this, 'track' ) );
		}

	}

	/**
	 * Show admin notice.
	 *
	 * @return void
	 */
	public function admin_notice() {

		if( current_user_can( 'manage_options' ) && ! $this->is_tracking_enabled() && $this->is_date_passed() ) {
			$this->helper->show_admin_notice( $this->get_message() , 'info' , $this->plugin_prefix . '_usage_tracking_message' );
		}

	}

	/**
	 * Retrieve the message for the admin notice.
	 *
	 * @return string
	 */
	public function get_message() {

		$message = esc_html__( 'Please help us improve WPGiftRegistry by allowing us to gather anonymous usage stats so we know which configurations, plugins and themes to test with.' );
		$message .= '&nbsp;&nbsp;&nbsp;<a href="'. esc_url( $this->get_tracking_approval_url() ) .'" class="button-primary">'. esc_html( 'Allow tracking' ) .'</a>';

		return $message;

	}

	/**
	 * Get the url of the approval button.
	 *
	 * @return string
	 */
	protected function get_tracking_approval_url() {
		return add_query_arg( array( 'wpput_tracker' => 'approved', 'plugin' => $this->plugin_prefix ), admin_url() );
	}

	/**
	 * Check if it's time to display the tracking message.
	 *
	 * @return boolean
	 */
	private function is_date_passed() {

		$passed = false;

		$installation_date = $this->installation_date;

		$past_date         = strtotime( '-'. $this->days_passed .' days' );

		if( $installation_date && $past_date >= $installation_date ) {
			$passed = true;
		}

		return $passed;

	}

	/**
	 * Set the required flags to approve the tracking.
	 *
	 * @return void
	 */
	public function approve_tracking() {

		if(
			isset( $_GET['wpput_tracker'] )
			&& $_GET['wpput_tracker'] == 'approved'
			&& isset( $_GET['plugin'] )
			&& $_GET['plugin'] == $this->plugin_prefix
			&& current_user_can( 'manage_options' )
			&& $this->is_date_passed()
		) {

			update_option( $this->plugin_prefix . '_tracking' , true );

			wp_redirect( admin_url('edit.php?post_type=wpgr_wishlist') );
			exit;

		}

	}

	/**
	 * Retrieves the data to send to Keen.io
	 *
	 * @return array
	 */
	public function get_data() {

		$data = [
			'timestamp' => ["stringValue" => date('YmdHis')],
			'php_version' => ["stringValue" => phpversion()],
			'wp_version' => ["stringValue" => get_bloginfo( 'version' )],
			'server' => ["stringValue" => isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE']: ''],
			'multisite' => ["booleanValue" => is_multisite()],
			'theme' => ["stringValue" => $this->get_theme_name()],
			'locale' => ["stringValue" => get_locale()],
			'active_plugins' => ["arrayValue" => $this->get_active_plugins()],
			'product_urls' => ["arrayValue" => $this->get_product_urls()],
		];

		return $data;

	}

	/**
	 * Retrieve the current theme's name and version.
	 *
	 * @return string
	 */
	private function get_theme_name() {

		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		return $theme;

	}

	/**
	 * Get list of activated plugins.
	 *
	 * @return array
	 */
	private function get_active_plugins() {

		$active_plugins = get_option( 'active_plugins', array() );

		$new_active_plugins = [];
		foreach ( $active_plugins as $p ) {
			$new_active_plugins['values'][] = ["stringValue" => $p];
		}

		return (object) $new_active_plugins;

	}

	/**
	 * Get list of product urls from user's wishlists.
	 *
	 * @return array
	 */
	private function get_product_urls() {

		$product_urls = [];

		$all_wishlists = get_posts(array(
		    'fields'          => 'ids',
		    'posts_per_page'  => -1,
		    'post_type' => 'wpgr_wishlist'
		));

		foreach ( $all_wishlists as $wishlist_id ) {
			$wishlist = get_post_meta($wishlist_id, 'wpgr_wishlist', true);
			if ( !empty( $wishlist ) ) {
				foreach ( $wishlist as $gift ){
					if ( !empty($gift['gift_url']) ) {
						$product_urls['values'][] = ["stringValue" => $gift['gift_url']];
					}
				}
			}
		}

		return (object) $product_urls;

	}

	/**
	 * Register a new cron schedule with WP.
	 *
	 * @param  array $schedules existing ones.
	 * @return array
	 */
	public function cron_schedules( $schedules ) {

		$schedules['monthly'] = array(
			'interval' => 14 * DAY_IN_SECONDS,
			'display' => __( 'Twice a month' )
		);

		return $schedules;

	}

	/**
	 * Determines whether tracking is enabled for this plugin.
	 *
	 * @return boolean
	 */
	private function is_tracking_enabled() {
		return (bool) get_option( $this->plugin_prefix . '_tracking' , false );
	}

	/**
	 * Disables the tracking for a plugin.
	 *
	 * @return void
	 */
	public function disable_tracking() {

		delete_option( $this->plugin_prefix . '_tracking' );
		wp_clear_scheduled_hook( $this->plugin_prefix.'_usage_tracking' );

	}

	/**
	 * Use this method to schedule the tracking.
	 *
	 * @return void
	 */
	public function schedule_tracking() {

		if( $this->is_tracking_enabled() && $this->is_date_passed() && ! wp_next_scheduled ( $this->plugin_prefix.'_usage_tracking' ) ) {
			wp_schedule_event( time(), 'monthly', $this->plugin_prefix.'_usage_tracking' );
		}

	}

	/**
	 * Send the data to Keen.io
	 *
	 * @param  array $data the data to send.
	 * @return void
	 */
	private function send_data( $data ) {

		$new_data = [
			'fields' => (object)$data,
		];

		$json = json_encode( $new_data );

		// Initialize cURL
		$curl = curl_init();

		$wpgr_settings = get_option('wpgr_settings');
		if (isset($wpgr_settings['unique_id'])) {
			$object_unique_id = $wpgr_settings['unique_id'];
		} else {
			$object_unique_id = uniqid();
			$wpgr_settings['unique_id'] = $object_unique_id;
			update_option('wpgr_settings', $wpgr_settings);
		}

		// Create
		// curl_setopt( $curl, CURLOPT_URL, $this->firebase_url . $this->firebase_path . '?auth=' . $this->firebase_token );
		curl_setopt( $curl, CURLOPT_URL, $this->firestore_url . $object_unique_id . '?key=' . $this->firebase_token );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Content-Length: ' . strlen($json),
            'X-HTTP-Method-Override: PATCH'));
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );

		// Get return value
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		// Make request
		// Close connection
		$response = curl_exec( $curl );
		curl_close( $curl );
		// Show result
		//error_log($response . "\n");

	}

	/**
	 * Task triggered by the cron Event.
	 *
	 * @return void
	 */
	public function track() {

		$this->send_data( $this->get_data() );

	}

}
