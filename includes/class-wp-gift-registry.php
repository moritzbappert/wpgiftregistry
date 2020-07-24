<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://dreiqbik.de
 * @since      1.0.0
 *
 * @package    WPGiftRegistry
 * @subpackage WPGiftRegistry/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPGiftRegistry
 * @subpackage WPGiftRegistry/includes
 * @author     Moritz Bappert <mb@dreiqbik.de>
 */

if ( !class_exists( 'WP_Gift_Registry' ) ) {
	class WP_Gift_Registry {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      WP_Gift_Registry_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

			$this->plugin_name = 'WPGiftRegistry';
			$this->version = '1.4.12';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - WP_Gift_Registry_Loader. Orchestrates the hooks of the plugin.
		 * - WP_Gift_Registry_i18n. Defines internationalization functionality.
		 * - WP_Gift_Registry_Admin. Defines all hooks for the admin area.
		 * - WP_Gift_Registry_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-gift-registry-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-gift-registry-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-gift-registry-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-gift-registry-public.php';

			// Include CMB2
			if ( file_exists( __DIR__ . '/libraries/cmb2/init.php' ) ) {
			  require_once __DIR__ . '/libraries/cmb2/init.php';
			}

			// Include CMB2 Conditionals
			if ( file_exists( __DIR__ . '/libraries/cmb2-conditionals/cmb2-conditionals.php' ) ) {
			  require_once __DIR__ . '/libraries/cmb2-conditionals/cmb2-conditionals.php';
			}

			// Include plugin usage tracker
			if ( file_exists( __DIR__ . '/libraries/wp-plugin-usage-tracker/wp-plugin-usage-tracker.php' ) ) {
			  require_once __DIR__ . '/libraries/wp-plugin-usage-tracker/wp-plugin-usage-tracker.php';
			}

			$this->loader = new WP_Gift_Registry_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the WP_Gift_Registry_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new WP_Gift_Registry_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new WP_Gift_Registry_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 9999 );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


			// Register custom post type
			$this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );

			// Add custom admin columns for our custom post type
			$this->loader->add_action( 'init', $plugin_admin, 'add_admin_columns' );

			// Add metaboxes to our custom post type
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'add_wishlist_metaboxes' );

			// Add reserved gift metabox to our cpt
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_reserved_gift_metabox' );

			// Add custom plugin message to our cpt
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_custom_edit_screen_message');

			// Add custom field type for unique ids
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'add_custom_cmb2_fields' );

			// Add settings pages
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_pages' );

			// Add metaboxes to our settings page
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'add_old_wishlist_page_metaboxes' );

			// Init plugin usage tracker
			$this->loader->add_action( 'init', $plugin_admin, 'track_plugin_usage' );

			// Hook ajax action for resetting reserved gift parts
			$this->loader->add_action( 'wp_ajax_reset_reserved_parts', $plugin_admin, 'reset_reserved_parts' );


		// Old version stuff for compatibility

			// Only show old menu pages if old data is in the db
			if ( get_option('wishlist') !== false ) {

				// Hook into the admin menu
				$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_old_settings_pages' );

				// Add admin notices (notice about the new version)
				$this->loader->add_action( 'admin_notices', $plugin_admin, 'add_update_notice_for_old_plugin' );
			}

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new WP_Gift_Registry_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 9999 );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			// Add the wishlist shortcode
			$this->loader->add_action( 'init', $plugin_public, 'create_wishlist_shortcode' );

			// Register the functions to update gift availability for AJAX
			$this->loader->add_action( 'wp_ajax_update_gift_availability', $plugin_public, 'update_gift_availability' );
			$this->loader->add_action( 'wp_ajax_nopriv_update_gift_availability', $plugin_public, 'update_gift_availability' );

			// integrate our wishlist into the page content of single wishlist pages
			$this->loader->add_filter( 'the_content', $plugin_public, 'filter_wishlist_content' );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    WPGiftRegistry_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}
}
