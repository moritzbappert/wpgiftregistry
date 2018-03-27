<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://dreiqbik.de
 * @since      1.0.0
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/public
 * @author     Moritz Bappert <mb@dreiqbik.de>
 */

namespace WPGiftRegistry;
use \WPGiftRegistry;

class WP_Gift_Registry_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPGiftRegistry_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPGiftRegistry_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_gift_registry-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPGiftRegistry_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPGiftRegistry_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_gift_registry-public.js', array( 'jquery' ), $this->version, false );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( $this->plugin_name, 'variables', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'updateGiftAvailabiltyNonce' => wp_create_nonce( 'gift-availability-81991' )
		) );

	}

	/**
	 * Register the [wishlist] shortcode
	 *
	 * @since    1.0.0
	 */
	public function create_wishlist_shortcode() {

		add_shortcode('wishlist', function() {

			$wishlist = get_option('wishlist')['wishlist_group'];
			$currency = get_option('wishlist_settings')['currency_symbol'];
			$currency_placement = get_option('wishlist_settings')['currency_symbol_placement'];

			ob_start();

			if ( !empty( $wishlist ) ) {
			?>

			<section class="wishlist">
				<ul>
				<?php
				$i = 0;
				foreach( $wishlist as $gift ) {

					$availability = $gift['gift_availability'];
					if ( $availability == 'false' ) {
						$availability_class = ' unavailable';
					} else {
						$availability_class = '';
					}
					if ( empty($gift['gift_url']) ) {
						$gift['gift_url'] = "";
					}
					if ( empty($gift['gift_image']) ) {
						$gift['gift_image'] = "";
					}

					if ( empty($gift['gift_image']) && strpos( $gift['gift_url'], 'amazon.com' ) ) {
						$pid = substr(strstr($gift['gift_url'],"p/"),2,10);
						$gift['gift_image'] = 'http://images.amazon.com/images/P/' . $pid . '.01._SCMZZZZZZZ_.jpg';
					}
				?>
					<li data-item-name="<?php echo $gift['gift_title']; ?>">
						<div class="image-wrapper">
							<?php echo ($gift['gift_image'] ? '<img src="' . $gift['gift_image'] . '">' : '<span></span>'); ?>
						</div>
						<div class="content-wrapper">
							<h2><?php echo $gift['gift_title']; ?></h2>
							<p><?php echo $gift['gift_description']; ?></p>
							<?php
								$price_string = "";
								if ( !empty($gift['gift_price'] ) ) {
									if ( $currency_placement === 'before' ) {
										$price_string = $currency . $gift['gift_price'];
									} else {
										$price_string = $gift['gift_price'] . $currency;
									}
								}
							?>
							<div class="price"><?php echo $price_string; ?></div>
							<?php echo (!empty($gift['gift_url']) ? '<a href="' . transform_to_affiliate_link( $gift['gift_url'] ) . '" class="buy-button' . $availability_class . '" target="_blank">' . __('VIEW/BUY', 'WPGiftRegistry') . '</a>' : '<a href="javascript:void(0)" class="buy-button' . $availability_class . '">' . __('VIEW/BUY', 'WPGiftRegistry') . '</a>'); ?>
						</div>
					</li>
				<?php
					$i++;
				}
				?>
				</ul>
				<div class="overlay hidden">
					<div class="content-wrapper">
						<p>
							<?php echo sprintf( __('Do you want to mark %s as %sbought%s so that nobody else gifts it?', 'WPGiftRegistry'), '<span id="item-name"></span>', '<em>', '</em>' ); ?>
						</p>
						<button id="yes"><?php echo __('Yes', 'WPGiftRegistry'); ?></button><button id="no"><?php echo __('No, Cancel', 'WPGiftRegistry'); ?></button>
					</div>
				</div>
			</section>

			<?php
			}

			$output = ob_get_clean();
			return $output;

		});
	}


	/**
	 * Updates the gift availability (called through AJAX)
	 * @since    1.0.0
	 */
	public function update_gift_availability() {

		$nonce = $_POST['nonce'];
		if ( !wp_verify_nonce( $nonce, 'gift-availability-81991' ) ) {
			die ( 'Busted!');
		}

		$item_name = $_POST['itemName'];
		$item_availability = $_POST['availability'];
		$options_array = get_option('wishlist');
		$wishlist = $options_array['wishlist_group'];

		foreach($wishlist as $gift_key => $gift) {
			if ( $gift['gift_title'] === $item_name ) {
				$wishlist[$gift_key]['gift_availability'] = $item_availability;
			}
		}

		$options_array['wishlist_group'] = $wishlist;

		update_option('wishlist', $options_array);

		die();
	}



}




/**
 * Transforms Links into Affiliate Links
 * @since    1.0.0
 */
	function transform_to_affiliate_link( $link ) {
		$link = htmlspecialchars( $link ); // This is the original unmodified link that is entered by the user.
		$pid = substr(strstr($link,"p/"),2,10);

		if ( strpos( $link, 'amazon.com' ) ) {
			// US
			$affiliate = "?tag=3qbik-20";
	    return "http://www.amazon.com/gp/product/" . $pid . $affiliate;

		} else if ( strpos( $link, 'amazon.de' ) ) {
			// Germany
			$affiliate = "?tag=dr03e-21";
			return "http://www.amazon.de/gp/product/" . $pid . $affiliate;

		}	else if ( strpos( $link, 'amazon.co.uk' ) ) {
			// UK
			$affiliate = "?tag=dr065-21";
			return "http://www.amazon.co.uk/gp/product/" . $pid . $affiliate;

		} else if ( strpos( $link, 'amazon.es' ) ) {
			// Spain
			$affiliate = "?tag=dr00a4-21";
			return "http://www.amazon.es/gp/product/" . $pid . $affiliate;

		} else if ( strpos( $link, 'amazon.fr' ) ) {
			// France
			$affiliate = "?tag=	dr0cb-21";
			return "http://www.amazon.fr/gp/product/" . $pid . $affiliate;

		} else if ( strpos( $link, 'amazon.it' ) ) {
			// Italy
			$affiliate = "?tag=dr0e7-21";
			return "http://www.amazon.it/gp/product/" . $pid . $affiliate;
		} else if ( strpos( $link, 'bol.com') ) {
			// Netherlands
			return "http://partnerprogramma.bol.com/click/click?p=1&t=url&s=48680&f=TXL&url=" . $link . "&name=plugin";
		}

		return $link;
	}