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

		wp_enqueue_style( $this->plugin_name . '-style-admin', plugin_dir_url( __FILE__ ) . 'css/style-admin.css', array(), $this->version, 'all' );
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/main-admin.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '_vendor', plugin_dir_url( __FILE__ ) . 'js/vendor/vendor.js', array(), $this->version, true );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( $this->plugin_name, 'variables', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		) );

	}


	/**
	 * Register our custom post type
	 *
	 * @since 1.3.0
	 */
	public function register_post_types() {

		register_post_type('wpgr_wishlist', [
		  'labels' => [
		    'name' => __('Wishlist', 'wpgiftregistry'),
		    'singular_name' => __('Wishlist', 'wpgiftregistry'),
		    'menu_name' => __('Wishlists', 'wpgiftregistry'),
		    'add_new_item' => __('Add new Wishlist', 'wpgiftregistry'),
		    'edit_item' => __('Edit Wishlist', 'wpgiftregistry'),
		    'new_item' => __('New Wishlist', 'wpgiftregistry'),
		    'search_items' => __('Search Wishlists', 'wpgiftregistry'),
		    'all_items' => __('All Wishlists', 'wpgiftregistry'),
		  ],
		  'description' => '',
		  'public' => TRUE,
		  'publicly_queryable' => TRUE,
		  'show_ui' => TRUE,
		  'show_in_rest' => FALSE,
		  'rest_base' => '',
		  'has_archive' => FALSE,
		  'show_in_menu' => TRUE,
		  'exclude_from_search' => FALSE,
		  'capability_type' => 'post',
		  'map_meta_cap' => TRUE,
		  'hierarchical' => FALSE,
		  'rewrite' => [
		    'slug' => 'wishlist',
		    'with_front' => FALSE
		  ],
		  'supports' => ['title', 'author'],
		  'menu_icon' => plugins_url( "../images/gift_registry_icon.svg", __FILE__ ),
		]);

	}

	/**
	 * Add custom field type for unique ids
	 *
	 * @since    1.3.0
	 */
	public function add_custom_cmb2_fields() {

		add_filter( 'cmb2_render_unique_id', 'cmb2_render_unique_id', 10, 5 );
		add_filter( 'cmb2_sanitize_unique_id', 'cmb2_sanitize_unique_id', 10, 3 );

		// render unique id
		function cmb2_render_unique_id( $field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {
		    echo $field_type_object->input( array( 'class' => 'cmb2_unique_id', 'type' => 'hidden' ) );
		}

		// sanitize the field
		function cmb2_sanitize_unique_id( $override, $new, $object_id ) {
		    // Set unique id if it's not already set
		    if( empty( $new ) ) {
		        $value = uniqid( $object_id, false );
		    } else {
		        $value = $new;
		    }
		    return $value;
		}
	}

	/**
	 * Create a Settings Page
	 *
	 * @since    1.0.0
	 */
	  public function add_old_settings_pages() {

	  	// Add the old Wishlist menu item and page
	  	$page_title = __('Old Wishlist', 'wpgiftregistry');
	  	$menu_title = __('Old Wishlist', 'wpgiftregistry');
	  	$capability = 'manage_options';
	  	$slug = 'wishlist';
	  	$callback = array( $this, 'plugin_wishlist_page_content' );
	  	$icon = plugins_url( "../images/gift_registry_icon.svg", __FILE__ );
	  	$position = 100;
	  	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );

	  	// Add the old Wishlist Settings Page
	  	$page_title = __('Settings', 'wpgiftregistry');
	  	$menu_title = __('Settings', 'wpgiftregistry');
	  	$capability = 'manage_options';
	  	$slug = 'wishlist_settings';
	  	$callback = array( $this, 'plugin_wishlist_settings_page_content' );
	  	$icon = plugins_url( "../images/gift_registry_icon.svg", __FILE__ );
	  	$position = 100;
	  	add_submenu_page( 'wishlist', $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
	  }

  	/**
  	 * Add a metabox with custom fields to our wishlist post type
  	 *
  	 * @since 1.3.0
  	 */
  	public function add_settings_pages() {

  		// Add the Wishlist Settings Page
  		$page_title = __('Settings', 'wpgiftregistry');
  		$menu_title = __('Settings', 'wpgiftregistry');
  		$capability = 'manage_options';
  		$slug = 'wpgr_settings';
  		$callback = array( $this, 'plugin_wpgr_settings_page_content' );
  		$icon = plugins_url( "../images/gift_registry_icon.svg", __FILE__ );
  		$position = 100;
  		add_submenu_page( 'edit.php?post_type=wpgr_wishlist', $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
  	}

  	/**
  	 * Add a metabox with custom fields to our wishlist post type
  	 *
  	 * @since 1.3.0
  	 */
  	public function add_wishlist_metaboxes() {

  		$prefix = 'wpgr_';
  		$settings = get_option('wpgr_settings');

  		$metabox = new_cmb2_box( array(
			'id'            => $prefix . 'wishlist',
			//'title'         => esc_html__( 'Wishlist', 'wpgiftregistry' ),
			'remove_box_wrap' => true,
			'object_types'  => array( 'wpgr_wishlist' ), // Post type
			'context'    => 'after_title',
			'priority'   => 'high',
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
			// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
		) );

  		$shortcode_description = sprintf( __('First add some gifts to your wishlist below. Then use the %s shortcode anywhere on your page to include this whishlist.', 'wpgiftregistry'), "<code>[wishlist id='" . ($metabox->object_id() != 0 ? $metabox->object_id() : 'all') . "']</code>" );

  		// General description about shortcode functionality
  		$metabox->add_field( array(
			'name' => __( 'Shortcode', 'wpgiftregistry' ),
			'desc' => $shortcode_description,
			'type' => 'title',
			'id'   => $prefix . 'shortcode_description',
		) );

		// General description about shortcode functionality
  		$metabox->add_field( array(
			'name' => __( 'Shortcode', 'wpgiftregistry' ),
			'desc' => $shortcode_description,
			'type' => 'title',
			'id'   => $prefix . 'shortcode_description',
		) );

		// Wishes title
  		$metabox->add_field( array(
			'name' => __( 'Wishes', 'wpgiftregistry' ),
			'type' => 'title',
			'id'   => $prefix . 'wishes_title',
		) );

  		// Add a group field (repeater)
  		$group_field = $metabox->add_field( array(
  		    'id'          => 'wpgr_wishlist',
  		    'type'        => 'group',
  		    // 'repeatable'  => false, // use false if you want non-repeatable group
  		    'options'     => array(
  		        'group_title'   => __( 'Gift {#}', 'wpgiftregistry' ), // since version 1.1.4, {#} gets replaced by row number
  		        'add_button'    => __( 'Add Another Gift', 'wpgiftregistry' ),
  		        'remove_button' => __( 'Remove Gift', 'wpgiftregistry' ),
  		        'sortable'      => true, // beta
  		        'closed'     => true, // true to have the groups closed by default
  		    ),
  		) );

  		// Unique ID
  		$metabox->add_group_field( $group_field, array(
  			'id' => 'gift_id',
  			'type' => 'unique_id'
  		) );


		// Title
		$metabox->add_group_field( $group_field, array(
			'name' => __( 'Gift Title', 'wpgiftregistry' ) . '*',
			'desc' => '',
			'id'   => 'gift_title',
			'type' => 'text',
			'default' => '',
			'attributes'  => array(
				'required'    => 'required',
			),
		) );

		// Image
		$metabox->add_group_field( $group_field, array(
		    'name'    => __( 'Gift Image', 'wpgiftregistry' ),
		    'desc'    => __( 'Upload an image or enter a URL. If left empty, we\'ll try to automatically retrieve an image (currently only working with amazon.com).', 'wpgiftregistry' ),
		    'id'      => 'gift_image',
		    'type'    => 'file',
		    'options' => array(
		        'url' => true, // Hide the text input for the url
		    ),
		    'text'    => array(
		        'add_upload_file_text' => __( 'Add Image', 'wpgiftregistry' ) // Change upload button text.
		    ),
		    // query_args are passed to wp.media's library query.
		    'query_args' => array(
		        'type' => 'image/jpg',
		    ),
		) );

  		// Description
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Description (optional)', 'wpgiftregistry' ),
	        'desc' => '',
	        'id'   => 'gift_description',
	        'type' => 'textarea_small',
	    ) );

		$currency_symbol_placement = get_option('wishlist_settings')['currency_symbol_placement'];
		$currency_symbol = get_option('wishlist_settings')['currency_symbol'];

		if ( $currency_symbol_placement === 'before' ) {
			$before = $currency_symbol . ' ';
			$after = '';
		} else {
			$before = '';
			$after = ' ' . $currency_symbol;
		}

		// Price
		$metabox->add_group_field( $group_field, array(
		    'name' => __( 'Price', 'wpgiftregistry' ),
		    'desc' => '',
		    'id' => 'gift_price',
		    'type' => 'text_small',
		    'before_field' => $before,
		    'after_field' => $after
		) );

	    // URL
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Product URL', 'wpgiftregistry' ),
	        'desc' => '',
	        'id'   => 'gift_url',
	        'type' => 'text_url',
	    ) );

	    // Do you want to split this gift in parts?
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Split gift?', 'wpgiftregistry' ),
	        'desc' => __( 'Do you want to split this gift into parts?', 'wpgiftregistry' ),
	        'id'   => 'gift_has_parts',
	        'type' => 'radio_inline',
	        'options' => array(
	            'true' => __( 'Yes', 'wpgiftregistry' ),
	            'false' => __( 'No', 'wpgiftregistry' ),
	        ),
	        'default' => 'false',
			) );


	    // How many parts?
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'How many parts?', 'wpgiftregistry' ),
	        'id'   => 'gift_parts_total',
	        'type' => 'text_small',
	        'attributes' => array(
	        	'required' => true,
	        	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_has_parts' ) ),
	        	'data-conditional-value' => wp_json_encode( array('true') ),
	        ),
	    ) );

	    // Plural Parts Label
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Plural Parts Label ', 'wpgiftregistry' ),
	        'desc' => '<br><br>' . __( 'If your gift consists of 12 spoons for example, you can rename the parts label to "spoons".', 'wpgiftregistry'),
	        'id'   => 'gift_parts_string',
	        'type' => 'text_small',
	        'default' => __( 'parts', 'wpgiftregistry' ),
	        'attributes' => array(
	        	'required' => true,
	        	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_has_parts' ) ),
	        	'data-conditional-value' => wp_json_encode( array('true') ),
	        ),
	    ) );

	    // Single Part Label
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Single Part Label', 'wpgiftregistry' ),
	        'id'   => 'gift_part_string',
	        'type' => 'text_small',
	        'default' => __( 'part', 'wpgiftregistry' ),
	        'attributes' => array(
	        	'required' => true,
	        	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_has_parts' ) ),
	        	'data-conditional-value' => wp_json_encode( array('true') ),
	        ),
	    ) );

	    // Availability
	    $metabox->add_group_field( $group_field, array(
	        'name' => __( 'Availability', 'wpgiftregistry' ),
	        'desc' => __( 'Is the gift available for purchase (nobody already buying it)?', 'wpgiftregistry' ),
	        'id'   => 'gift_availability',
	        'type' => 'radio_inline',
	        'options' => array(
	            'true' => __( 'Yes', 'wpgiftregistry' ),
	            'false'   => __( 'No', 'wpgiftregistry' ),
	        ),
	        'default' => 'true',
			) );

		// Unlimited gifts
	    $metabox->add_group_field( $group_field, array(
				'name' => __( 'Unlimited?', 'wpgiftregistry' ),
				'desc' => __( 'Do you want to enable giving this gift unlimitedly, so it doesn\'t get locked?', 'wpgiftregistry' ),
				'id'   => 'gift_unlimited',
				'type' => 'radio_inline',
				'options' => array(
						'true' => __( 'Yes', 'wpgiftregistry' ),
						'false' => __( 'No', 'wpgiftregistry' ),
				),
				'default' => 'false',
				'attributes' => array(
					'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_has_parts' ) ),
					'data-conditional-value' => wp_json_encode( array('false') ),
				),

		) );

	    // Reset reserved parts for this gift
	    $metabox->add_group_field( $group_field, array(
			'name' => '',
			'desc' => '<br><a id="reset-reserved-parts" data-nonce="' . wp_create_nonce('wpgr_reset_parts') . '" data-wishlist="' . (isset($_GET['post']) ? esc_attr( $_GET['post'] ) : '' ) . '" href="#" class="button">' . __('Reset Reserved Parts', 'wpgiftregistry') . '</a>',
			'type' => 'title',
			'id'   => 'gift_reset_parts',
		) );

	    // // Who reserved this?
	    // $metabox->add_group_field( $group_field, array(
	    //     'name' => __( 'Who reserved this?', 'wpgiftregistry' ),
	    //     'desc' => '',
	    //     'id'   => 'gift_reserver',
	    //     'type' => 'text_medium',
	    //  	// 'attributes' => array(
    	// 	// 	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_availability' ) ),
    	// 	// 	'data-conditional-value' => 'false',
    	// 	// ),
	    // ) );



	    // if ( isset($settings['show_email_field']) && $settings['show_email_field'] ) {
	   	//  // E-Mail
	   	//  $metabox->add_group_field( $group_field, array(
	   	//      'name' => __( 'Email', 'wpgiftregistry' ),
	   	//      'desc' => '',
	   	//      'id'   => 'gift_reserver_email',
	   	//      'type' => 'text_medium',
	   	//   	// 'attributes' => array(
	    // 		// 	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_availability' ) ),
	    // 		// 	'data-conditional-value' => 'false',
	    // 		// ),
		//  ) );
	    // }

		// if ( isset($settings['show_message_field']) && $settings['show_message_field'] ) {
		//     // Message
		//     $metabox->add_group_field( $group_field, array(
		//         'name' => __( 'Message', 'wpgiftregistry' ),
		//         'desc' => '',
		//         'id'   => 'gift_reserver_message',
		//         'type' => 'textarea_small',
		//      	// 'attributes' => array(
		//    		// 	'data-conditional-id' => wp_json_encode( array( $group_field, 'gift_availability' ) ),
		//    		// 	'data-conditional-value' => 'false',
		//    		// ),
		//     ) );
		// }

	    // Shortcode Metabox
	    $shortcode_metabox = new_cmb2_box( array(
			'id'            => $prefix . 'wishlist_shortcode',
			'title'         => __( 'Shortcode', 'wpgiftregistry' ),
			'object_types'  => array( 'wpgr_wishlist' ), // Post type
			'context'    => 'side',
			'priority'   => 'high',
		) );

		// Shortcode
  		$shortcode_metabox->add_field( array(
			'name' => "",
			'desc' => __('Place the following shortcode into the content editor or widget area of your theme.', 'wpgiftregistry') .
			"<code class='shortcode'>[wishlist id='" . ($metabox->object_id() != 0 ? $metabox->object_id() : 'all') . "']</code><a class='button button-primary copy copy-to-clipboard'>" . __('Copy Shortcode', 'wpgiftregistry') . "</a>",
			'type' => 'title',
			'id'   => $prefix . 'shortcode',
		) );
  	}

  	/**
  	 * Add a metabox to our cpt for showing which gifts have been reserved yet
  	 *
  	 * @since 1.4.0
  	 */
  	public function add_reserved_gift_metabox() {
  		add_meta_box( 'wpgr_reserved_gifts', __( 'Reserved Gifts', 'wpgiftregistry' ), array( $this, 'reserved_gifts_meta_box' ), 'wpgr_wishlist', 'normal', 'high' );
  	}


  	/**
	 * Add a custom shortcode column
	 *
	 * @since 1.4.0
	 */
  	public function reserved_gifts_meta_box( $post ) {

  		$reserved_gifts = get_post_meta($post->ID, 'wpgr_reserved_gifts', true);
  		$wishlist = get_post_meta($post->ID, 'wpgr_wishlist', true);
  		$settings = get_option('wpgr_settings');
  		$gifts_reserved = false;
  		$show_email = isset($settings['show_email_field']) && $settings['show_email_field'];
  		$show_message = isset($settings['show_message_field']) && $settings['show_message_field'];
  		?>
<div
    class="gifts-reserved-metabox">
    <table>
        <thead>
            <tr>
                <th>
                    <?= __('Gift', 'wpgiftregistry') ?>
                </th>
                <th>
                    <?= __('# of parts', 'wpgiftregistry') ?>
                </th>
                <th>
                    <?= __('Reserved by', 'wpgiftregistry') ?>
                </th>

                <?php if ( $show_email ): ?>
                <th>
                    <?= __('Email', 'wpgiftregistry') ?>
                </th>
                <?php endif; ?>
                <?php if ( $show_message ): ?>
                <th>
                    <?= __('Message', 'wpgiftregistry') ?>
                </th>
                <?php endif; ?>

                <th>
                    <?= __('Date', 'wpgiftregistry') ?>
                </th>
            </tr>
        </thead>

        <?php
	  			// output the reserver's name from our old format here
	  			if ( !empty($reserved_gifts) ):
		  			foreach ( $wishlist as $gift ):
		  				if (isset($gift['gift_reserver']) && $gift['gift_reserver'] != ''):
		  					$gifts_reserved = true; ?>

        <tr class="<?= sanitize_html_class( $gift['gift_id'] ) ?>">
            <td>
                <?= esc_html( $gift['gift_title'] ) ?>
            </td>
            <td>1 / 1</td>
            <td>
                <?= esc_html( $gift['gift_reserver'] ) ?>
            </td>

            <?php if ( $show_email ): ?>
            <td>
                <?= isset($gift['gift_reserver_email']) ? esc_html( $gift['gift_reserver_email'] ) : '' ?>
            </td>
            <?php endif; ?>
            <?php if ( $show_message ): ?>
            <td>
                <?= isset($gift['gift_reserver_message']) ? esc_html( $gift['gift_reserver_message'] ) : '' ?>
            </td>
            <?php endif; ?>

            <td>–</td>
        </tr>
        <?php
		  				endif;
		  			endforeach;
		  		endif;
	  		?>


        <?php if ( !empty($reserved_gifts) ): ?>
        <?php foreach ( $reserved_gifts as $g ): ?>
        <tr class="<?= sanitize_html_class( $g['gift_id'] ) ?>">
            <td>
                <?= esc_html( $g['gift_title'] ) ?>
            </td>
            <?php
	  						$total = count($g['gift_reservations']);
	  						$count = 0;
	  					?>
            <?php foreach ( $g['gift_reservations'] as $r ):
	  						$count++;
	  					?>
            <?php if ($count > 1): ?>
            <tr class="<?= sanitize_html_class( $g['gift_id'] ) ?>">
                <td></td>
                <?php endif; ?>

                <td>
                    <?= esc_html( $r['gift_parts'] ) ?> /
                    <?= esc_html( $g['gift_parts_total'] ) ?>
                </td>
                <td>
                    <?= esc_html( $r['gift_reserver'] ) ?>
                </td>

                <?php if ( $show_email ): ?>
                <td>
                    <?= esc_html( $r['gift_reserver_email'] ) ?>
                </td>
                <?php endif; ?>
                <?php if ( $show_message ): ?>
                <td>
                    <?= esc_html( $r['gift_reserver_message'] ) ?>
                </td>
                <?php endif; ?>

                <td>
                    <?= date_i18n('d.m.Y, H:i', strtotime($r['gift_reservation_date'])) ?>
                </td>
                <?php if ($count > 1): ?>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        <?php elseif ( !$gifts_reserved ): ?>
        <tr>
            <td colspan="6">
                <?= __('No gifts reserved yet!', 'wpgiftregistry') ?>
            </td>
        </tr>

        <?php endif; ?>
    </table>
</div>
<?php
  	}

  	/**
	 * Add a custom message to the wishlist edit screen offering support and asking for reviews
	 *
	 * @since    1.4.3
	 */
  	public function add_custom_edit_screen_message() {

		function add_content_at_advanced( $post ) {
			if ( $post->post_type == 'wpgr_wishlist' ): ?>
				<p>
					<br></p>
				<p>
					<?php echo sprintf( __('This plugin was created by %s. Feel free to contact us at kontakt@dreiqbik.de for any feature requests or use the %ssupport forum%s!', 'wpgiftregistry'), '<a href="https://dreiqbik.de">dreiQBIK</a>', '<a href="https://wordpress.org/support/plugin/wpgiftregistry">', '</a>'); ?>
				</p>
				<p>
					<?php echo sprintf( __('Please %ssupport us with a good review%s if you find the plugin useful!', 'wpgiftregistry'), '<a href="https://wordpress.org/support/plugin/wpgiftregistry/reviews/?rate=5#new-post">', '</a>' ); ?>
				</p>
			<?php endif;
        }
        add_action( 'edit_form_advanced', 'add_content_at_advanced' );

  	}


  	/**
	 * Add a custom shortcode column
	 *
	 * @since    1.3
	 */
  	public function add_admin_columns() {

  		function add_shortcode_column( $columns ) {

  			$new_columns = array();

  			foreach ($columns as $key => $title) {
  				if ($key == 'author') {
  					// put the shortcode column before the author column
  					$new_columns['shortcode'] = __( 'Shortcode', 'wpgiftregistry' );
  				}
  				$new_columns[$key] = $title;
  			}
  			return $new_columns;
  		}
  		add_filter( 'manage_wpgr_wishlist_posts_columns', 'add_shortcode_column', 5 );


  		function shortcode_column_content( $column, $id ) {
  		  if( 'shortcode' == $column ) {
  		    echo "<code>[wishlist id='" . esc_attr( $id ) . "']</code>";
  		  }
  		}
  		add_action( 'manage_wpgr_wishlist_posts_custom_column', 'shortcode_column_content', 5, 2 );

  	}


  	/**
	 * The Wishlist Page Content
	 *
	 * @since    1.0.0
	 */
	  public function plugin_wishlist_page_content() {

	    // Include CMB CSS in the head to avoid FOUC
			add_action( "admin_print_styles-wishlist", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

			?>
<div
    class="wrap cmb2-options-page wishlist">
    <h2>
        <?php echo esc_html( get_admin_page_title() ); ?>
    </h2>
    <p>
        <?php echo sprintf( __('First add some gifts to your wishlist below. Then use the %s shortcode anywhere on your page to include this whishlist.', 'wpgiftregistry'), '<code>[wishlist]</code>' ); ?>
    </p>
    <br>
    <?php cmb2_metabox_form( 'wishlist', 'wishlist' ); ?>
    <br>
    <p>
        <?php echo sprintf( __('This plugin was created by %s. Feel free to contact us at kontakt@dreiqbik.de for any feature requests!', 'wpgiftregistry'), '<a href="http://dreiqbik.de">dreiQBIK</a>'); ?>
    </p>
    <p>
        <?php echo sprintf( __('Please %ssupport us with a good review%s if you find the plugin useful!', 'wpgiftregistry'), '<a href="https://wordpress.org/support/plugin/wpgiftregistry/reviews/?rate=5#new-post">', '</a>' ); ?>
    </p>
</div>
<?php
	  }

  	/**
	 * wpgr_settings Page Content
	 *
	 * @since    1.3.0
	 */
  	public function plugin_wpgr_settings_page_content() {
  	    // Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-wishlist", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

		?>
<div
    class="wrap cmb2-options-page wishlist">
    <h2>
        <?php echo esc_html( get_admin_page_title() ); ?>
    </h2>
    <?php cmb2_metabox_form( 'wishlist_settings', 'wpgr_settings' ); ?>
    <p><br></p>
    <p>
        <?php echo sprintf( __('This plugin was created by %s. Feel free to contact us at kontakt@dreiqbik.de for any feature requests or use the support forum!', 'wpgiftregistry'), '<a href="https://dreiqbik.de">dreiQBIK</a>'); ?>
    </p>
    <p>
        <?php echo sprintf( __('Please %ssupport us with a good review%s if you find the plugin useful!', 'wpgiftregistry'), '<a href="https://wordpress.org/support/plugin/wpgiftregistry/reviews/?rate=5#new-post">', '</a>' ); ?>
    </p>
</div>
<?php
  	}



  	/**
	 * The Wishlist Settings Page Content
	 *
	 * @since    1.1.0
	 */
  	public function plugin_wishlist_settings_page_content() {
    	// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-wishlist", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

		?>
<div
    class="wrap cmb2-options-page wishlist">
    <h2>
        <?php echo esc_html( get_admin_page_title() ); ?>
    </h2>
    <?php cmb2_metabox_form( 'wishlist_settings', 'wishlist_settings' ); ?>
</div>
<?php
  	}



  	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  1.0.0
	 */
	function add_old_wishlist_page_metaboxes() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_wishlist", array( $this, 'wishlist_notices' ), 10, 2 );
		add_action( "cmb2_save_options-page_fields_wishlist_settings", array( $this, 'wishlist_settings_notices' ), 10, 2 );

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
		        'group_title'   => __( 'Gift {#}', 'wpgiftregistry' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add Another Gift', 'wpgiftregistry' ),
		        'remove_button' => __( 'Remove Gift', 'wpgiftregistry' ),
		        'sortable'      => true, // beta
		        'closed'     => true, // true to have the groups closed by default
		    ),
		) );



		// Title
		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'Gift Title', 'wpgiftregistry' ),
			'desc' => '',
			'id'   => 'gift_title',
			'type' => 'text',
			'default' => '',
		) );

		// Image
		$cmb->add_group_field( $group_field_id, array(
		    'name'    => __( 'Gift Image', 'wpgiftregistry' ),
		    'desc'    => __( 'Upload an image or enter a URL.', 'wpgiftregistry' ),
		    'id'      => 'gift_image',
		    'type'    => 'file',
		    // Optional:
		    'options' => array(
		        'url' => true, // Hide the text input for the url
		    ),
		    'text'    => array(
		        'add_upload_file_text' => __( 'Add Image', 'wpgiftregistry' ) // Change upload button text. Default: "Add or Upload File"
		    ),
		    // query_args are passed to wp.media's library query.
		    'query_args' => array(
		        'type' => 'image/jpg', // Make library only display PDFs.
		    ),
		) );

		// Description
		$cmb->add_group_field( $group_field_id, array(
	    	'name' => __( 'Description (optional)', 'wpgiftregistry' ),
	    	'desc' => '',
	    	'id'   => 'gift_description',
	    	'type' => 'textarea_small',
	    ) );


		$currency_symbol_placement = get_option('wishlist_settings')['currency_symbol_placement'];
		$currency_symbol = get_option('wishlist_settings')['currency_symbol'];

		if ( $currency_symbol_placement === 'before' ) {
			$before = $currency_symbol . ' ';
			$after = '';
		} else {
			$before = '';
			$after = ' ' . $currency_symbol;
		}


		// Price
		$cmb->add_group_field( $group_field_id, array(
		    'name' => __( 'Price', 'wpgiftregistry' ),
		    'desc' => '',
		    'id' => 'gift_price',
		    'type' => 'text_small',
		    //'type' => 'text_money',
		    //'before_field' => '€', // Replaces default '$'
		    'before_field' => $before,
		    'after_field' => $after
		) );


	    // URL
	    $cmb->add_group_field( $group_field_id, array(
	        'name' => __( 'Product URL', 'wpgiftregistry' ),
	        'desc' => '',
	        'id'   => 'gift_url',
	        'type' => 'text_url',
	    ) );

	    // Availability
	    $cmb->add_group_field( $group_field_id, array(
	        'name' => __( 'Availability', 'wpgiftregistry' ),
	        'desc' => __( 'Is the gift available for purchase (nobody already buying it)?', 'wpgiftregistry' ),
	        'id'   => 'gift_availability',
	        'type' => 'radio_inline',
	        'options' => array(
	            'true' => __( 'Yes', 'wpgiftregistry' ),
	            'false'   => __( 'No', 'wpgiftregistry' ),
	        ),
	        'default' => 'true',
	    ) );



	    /**
	     *  Settings Page
	     */

	    $cmb = new_cmb2_box( array(
	    	'id'         => 'wishlist_settings',
	    	'hookup'     => false,
	    	'cmb_styles' => false,
	    	'show_on'    => array(
	    		// These are important, don't remove
	    		'key'   => 'options-page',
	    		'value' => array( 'wishlist_settings' )
	    	),
	    ) );

		// Currency
		$cmb->add_field( array(
		    'name'    => __( 'Currency', 'wpgiftregistry' ),
	        'desc'    => __( 'Currency in which the gift price will be displayed', 'wpgiftregistry' ),
	        'default' => '$',
	        'id'      => 'currency_symbol',
	        'type'    => 'text_small'
		) );

		// Currency Symbol Placement
		$cmb->add_field( array(
		    'name'    => __( 'Currency Symbol Placement', 'wpgiftregistry' ),
	        'desc'    => '',
	        'id'      => 'currency_symbol_placement',
	        'type'    => 'radio_inline',
	        'options' => array(
		        'before' => __( 'Before the price', 'wpgiftregistry' ),
		        'after'   => __( 'After the price', 'wpgiftregistry' )
			),
			'default' => 'before'
		) );

		// Hide decimals?
		$cmb->add_field( array(
		    'name'    => __( 'Show price without decimals? ', 'wpgiftregistry' ),
	        'desc'    => '',
	        'id'      => 'hide_decimals',
	        'type'    => 'checkbox',
		) );

		// Hide total price?
		$cmb->add_field( array(
		    'name'    => __( 'Hide total price for splitted gifts? ', 'wpgiftregistry' ),
	        'desc'    => '',
	        'id'      => 'split_gift_hide_total_price',
	        'type'    => 'checkbox',
		) );

		// Show email field?
		$cmb->add_field( array(
		    'name'    => __( 'Show email field in gift reservation dialogue?', 'wpgiftregistry' ),
        	'desc'    => '',
        	'id'      => 'show_email_field',
        	'type'    => 'checkbox',
        ) );


		// Show message field?
		$cmb->add_field( array(
		    'name'    => __( 'Show custom message field in gift reservation dialogue?', 'wpgiftregistry' ),
        	'desc'    => '',
        	'id'      => 'show_message_field',
        	'type'    => 'checkbox',
        ) );

	}

	/**
	 * Add a notice for users of the old plugin about our updates to custom post type etc.
	 */
	public function add_update_notice_for_old_plugin() {
		$screen = get_current_screen();

		if ( $screen->id === 'toplevel_page_wishlist' || $screen->id === 'old-wishlist_page_wishlist_settings' ) :
			?>
<div
    class="notice notice-success is-dismissible">
    <h3>
        <?= __( 'WPGiftRegistry has been updated!', 'wpgiftregistry' ); ?>
    </h3>
    <p>
        <?= __( 'If you want to use all the new features, like multiple wishlists, please start using the new "Wishlists" menu item.', 'wpgiftregistry' ); ?>
    </p>
</div>

<?php endif;
	}


	/**
	 * Initialize plugin usage tracking
	 */
	public function track_plugin_usage() {

		$wpgr_settings = get_option('wpgr_settings');

		if (isset($wpgr_settings['activation_date'])) {
			$activation_date = $wpgr_settings['activation_date'];
		} else {
			$activation_date = date('j F Y');
			$wpgr_settings['activation_date'] = $activation_date;
			update_option('wpgr_settings', $wpgr_settings);
		}

		$tracker = new WP_Plugin_Usage_Tracker(
		  'wpgiftregistry',
		  'WPGiftRegistry',
		  $activation_date,
		  '3'
		);

		$tracker->init();
	}

	/**
	 * Reset reserved gift parts
	 */
	public function reset_reserved_parts() {

		$nonce 			= $_POST['nonce'];
		$wishlist_id 	= $_POST['wishlist_id'];
		$gift_id 		= $_POST['gift_id'];

		if ( !wp_verify_nonce( $nonce, 'wpgr_reset_parts' ) ) {
			die ( 'Busted!');
		}

		$reserved_gifts = get_post_meta($wishlist_id, 'wpgr_reserved_gifts', true);

		unset($reserved_gifts[$gift_id]);

		update_post_meta($wishlist_id, 'wpgr_reserved_gifts', $reserved_gifts);

		die();
	}


	/**
	 * Register Wishlist notices for display
	 *
	 * @since  1.0.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function wishlist_notices( $object_id, $updated ) {
		if ( $object_id !== 'wishlist' || empty( $updated ) ) {
			return;
		}
		add_settings_error( 'wishlist' . '-notices', '', __( 'Wishlist updated.', 'wpgiftregistry' ), 'updated' );
		settings_errors( 'wishlist' . '-notices' );
	}


	/**
	 * Register Wishlist settings notices for display
	 *
	 * @since  1.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function wishlist_settings_notices( $object_id, $updated ) {
		if ( $object_id !== 'wishlist_settings' || empty( $updated ) ) {
			return;
		}
		add_settings_error( 'wishlist' . '-notices', '', __( 'Wishlist settings updated.', 'wpgiftregistry' ), 'updated' );
		settings_errors( 'wishlist' . '-notices' );
	}
}
