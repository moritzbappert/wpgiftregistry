<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://dreiqbik.de
 * @since      1.0.0
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/admin
 * @author     Moritz Bappert <mb@dreiqbik.de>
 */
namespace WPGiftRegistry;
use \WPGiftRegistry;

class WP_Gift_Registry_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gift_Registry_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gift_Registry_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/WP_Gift_Registry-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gift_Registry_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gift_Registry_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/WP_Gift_Registry-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Create a Settings Page
	 *
	 * @since    1.0.0
	 */
  public function create_plugin_settings_page() {
  	// Add the menu item and page
  	$page_title = 'Wishlist';
  	$menu_title = 'Wishlist';
  	$capability = 'manage_options';
  	$slug = 'wishlist';
  	$callback = array( $this, 'plugin_settings_page_content' );
  	$icon = plugins_url( "../images/gift_registry_icon.png", __FILE__ );
  	$position = 100;
  	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
  }


  /**
	 * The Settings Page Content
	 *
	 * @since    1.0.0
	 */
  public function plugin_settings_page_content() {

    // Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-wishlist", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

		?>
		<div class="wrap cmb2-options-page wishlist">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<p>First add some gifts to your wishlist below. Then use the <code>[wishlist]</code> shortcode anywhere on your page to include this whishlist.</p>
			<br>
			<?php cmb2_metabox_form( 'wishlist', 'wishlist' ); ?>
			<br>
			<p>This plugin was created by <a href="http://dreiqbik.de">dreiQBIK</a>. Feel free to contact us at kontakt@dreiqbik.de for any feature requests!</p>
		</div>
		<?php
  }


  /**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_wishlist", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id'         => 'wishlist',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'wishlist' )
			),
		) );

		// Set our CMB2 fields

		$group_field_id = $cmb->add_field( array(
		    'id'          => 'wishlist_group',
		    'type'        => 'group',
		    // 'repeatable'  => false, // use false if you want non-repeatable group
		    'options'     => array(
		        'group_title'   => __( 'Gift {#}', 'WPGiftRegistry' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add Another Gift', 'WPGiftRegistry' ),
		        'remove_button' => __( 'Remove Gift', 'WPGiftRegistry' ),
		        'sortable'      => true, // beta
		        'closed'     => true, // true to have the groups closed by default
		    ),
		) );



		// Title
		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'Gift Title', 'WPGiftRegistry' ),
			'desc' => __( '', 'WPGiftRegistry' ),
			'id'   => 'gift_title',
			'type' => 'text',
			'default' => '',
		) );

		// Image
		$cmb->add_group_field( $group_field_id, array(
		    'name'    => __( 'Gift Image', 'WPGiftRegistry' ),
		    'desc'    => __( 'Upload an image or enter a URL. If left empty, we\'ll try to automatically retrieve an image (currently only working with amazon.com).', 'WPGiftRegistry' ),
		    'id'      => 'gift_image',
		    'type'    => 'file',
		    // Optional:
		    'options' => array(
		        'url' => true, // Hide the text input for the url
		    ),
		    'text'    => array(
		        'add_upload_file_text' => __( 'Add Image', 'WPGiftRegistry' ) // Change upload button text. Default: "Add or Upload File"
		    ),
		    // query_args are passed to wp.media's library query.
		    'query_args' => array(
		        'type' => 'image/jpg', // Make library only display PDFs.
		    ),
		) );

		// Description
    $cmb->add_group_field( $group_field_id, array(
        'name' => __( 'Description (optional)', 'WPGiftRegistry' ),
        'desc' => __( '', 'gift_registry' ),
        'id'   => 'gift_description',
        'type' => 'textarea_small',
    ) );

    // Price
    $cmb->add_group_field( $group_field_id, array(
        'name' => __( 'Price', 'WPGiftRegistry' ),
        'desc' => '',
        'id' => 'gift_price',
        'type' => 'text_money',
        //'before_field' => '€', // Replaces default '$'
    ) );

    // URL
    $cmb->add_group_field( $group_field_id, array(
        'name' => __( 'Product URL', 'WPGiftRegistry' ),
        'desc' => __( '', 'WPGiftRegistry' ),
        'id'   => 'gift_url',
        'type' => 'text_url',
    ) );

    // Availability
    $cmb->add_group_field( $group_field_id, array(
        'name' => __( 'Availability', 'WPGiftRegistry' ),
        'desc' => __( 'Is the gift available for purchase (nobody already buying it)?', 'WPGiftRegistry' ),
        'id'   => 'gift_availability',
        'type' => 'radio_inline',
        'options' => array(
            'true' => __( 'Yes', 'WPGiftRegistry' ),
            'false'   => __( 'No', 'WPGiftRegistry' ),
        ),
        'default' => 'true',
    ) );


	}



	/**
	 * Register settings notices for display
	 *
	 * @since  1.0.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== 'wishlist' || empty( $updated ) ) {
			return;
		}
		add_settings_error( 'wishlist' . '-notices', '', __( 'Wishlist updated.', 'WPGiftRegistry' ), 'updated' );
		settings_errors( 'wishlist' . '-notices' );
	}
}
