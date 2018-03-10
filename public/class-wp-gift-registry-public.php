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


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-gift-registry-public.css', array(), $this->version, 'all' );

		// new styles
		wp_enqueue_style( $this->plugin_name . '-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-gift-registry-public.js', array( 'jquery' ), $this->version, true );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( $this->plugin_name, 'variablesOld', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'updateGiftAvailabiltyNonce' => wp_create_nonce( 'gift-availability-81991' )
		) );



		// new scripts
		wp_enqueue_script( $this->plugin_name . '-main', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, true );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( $this->plugin_name . '-main', 'variables', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'update_gift_availabilty_nonce' => wp_create_nonce( 'gift-availability-81991' )
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


			?>

            <section>
                <div class="wpgr-m_card ">
                    <div class="wpgr-m_card__price-wrapper">
                        <p class="wpgr-m_card__price">20€</p>
                        <p class="wpgr-m_card__price-text">each</p>
                    </div>
                    <div class="wpgr-m_card__main">
                        <div class="wpgr-m_card__figure-wrapper">
                            <div class="wpgr-m_card__figure"></div>
                        </div>
                        <div class="wpgr-m_card__content">
                            <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                            <div class="wpgr-m_card__content-details is-hidden">
                                <p class="wpgr-m_card__desc">
                                    Professionally enable revolutionary ideas vis-a-vis premium human capital.
                                    Progressively strategize client-focused processes via resource-leveling growth strategies.
                                </p>
                                <div class="wpgr-m_card__btn-wrapper">
                                    <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                                    <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="wpgr-m_card__footer is-hidden">
                        <div class="wpgr-m_card__buyer-wrapper">
                            <i class="wpgr-m_card__buyer"></i>
                        </div>
                        <div class="wpgr-m_card__process">
                            <div class="wpgr-m_card__process-bar"></div>
                            <p class="wpgr-m_card__process-price">
                                <span class="wpgr-m_card__process-price-partial">100€</span>
                                <span class="wpgr-m_card__process-price-total"> / 200€</span>
                            </p>
                            <p class="wpgr-m_card__process-count">
                                <span class="wpgr-m_card__process-count-partial">10</span>
                                <span class="wpgr-m_card__process-count-total"> / 20</span>
                            </p>
                        </div>
                    </footer>
                    <div class="wpgr-m_card__toggle">
                        <i class="wpgr-m_card__toggle-icon"></i>
                    </div>
                </div>

                <div class="wpgr-m_card is-collapsed">
                    <div class="wpgr-m_card__price-wrapper">
                        <p class="wpgr-m_card__price">20€</p>
                        <p class="wpgr-m_card__price-text">each</p>
                    </div>
                    <div class="wpgr-m_card__main">
                        <div class="wpgr-m_card__figure-wrapper">
                            <div class="wpgr-m_card__figure"></div>
                        </div>
                        <div class="wpgr-m_card__content">
                            <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                            <div class="wpgr-m_card__content-details is-hidden">
                                <p class="wpgr-m_card__desc">
                                    Professionally enable revolutionary ideas vis-a-vis premium human capital.
                                    Progressively strategize client-focused processes via resource-leveling growth strategies.
                                </p>
                                <div class="wpgr-m_card__btn-wrapper">
                                    <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                                    <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="wpgr-m_card__footer is-hidden">
                        <div class="wpgr-m_card__buyer-wrapper">
                            <i class="wpgr-m_card__buyer"></i>
                        </div>
                        <div class="wpgr-m_card__process">
                            <div class="wpgr-m_card__process-bar"></div>
                            <p class="wpgr-m_card__process-price">
                                <span class="wpgr-m_card__process-price-partial">100€</span>
                                <span class="wpgr-m_card__process-price-total"> / 200€</span>
                            </p>
                            <p class="wpgr-m_card__process-count">
                                <span class="wpgr-m_card__process-count-partial">10</span>
                                <span class="wpgr-m_card__process-count-total"> / 20</span>
                            </p>
                        </div>
                    </footer>
                    <div class="wpgr-m_card__toggle">
                        <i class="wpgr-m_card__toggle-icon"></i>
                    </div>
                </div>

                <div class="wpgr-m_card wpgr-m_card--single">
                    <div class="wpgr-m_card__price-wrapper">
                        <p class="wpgr-m_card__price">20€</p>
                        <p class="wpgr-m_card__price-text">each</p>
                    </div>
                    <div class="wpgr-m_card__main">
                        <div class="wpgr-m_card__figure-wrapper">
                            <div class="wpgr-m_card__figure"></div>
                        </div>
                        <div class="wpgr-m_card__content">
                            <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                            <div class="wpgr-m_card__content-details is-hidden">
                                <p class="wpgr-m_card__desc">
                                    Professionally enable revolutionary ideas vis-a-vis premium human capital.
                                    Progressively strategize client-focused processes via resource-leveling growth strategies.
                                </p>
                                <div class="wpgr-m_card__btn-wrapper">
                                    <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                                    <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpgr-m_card__toggle">
                        <i class="wpgr-m_card__toggle-icon"></i>
                    </div>
                </div>

                <div class="wpgr-m_card wpgr-m_card--single is-collapsed">
                    <div class="wpgr-m_card__price-wrapper">
                        <p class="wpgr-m_card__price">20€</p>
                        <p class="wpgr-m_card__price-text">each</p>
                    </div>
                    <div class="wpgr-m_card__main">
                        <div class="wpgr-m_card__figure-wrapper">
                            <div class="wpgr-m_card__figure"></div>
                        </div>
                        <div class="wpgr-m_card__content">
                            <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                            <div class="wpgr-m_card__content-details is-hidden">
                                <p class="wpgr-m_card__desc">
                                    Professionally enable revolutionary ideas vis-a-vis premium human capital.
                                    Progressively strategize client-focused processes via resource-leveling growth strategies.
                                </p>
                                <div class="wpgr-m_card__btn-wrapper">
                                    <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                                    <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpgr-m_card__toggle">
                        <i class="wpgr-m_card__toggle-icon"></i>
                    </div>
                </div>

                <div class="wpgr-m_card wpgr-m_card--single wpgr-m_card--buyed is-collapsed">
                    <div class="wpgr-m_card__price-wrapper">
                        <p class="wpgr-m_card__price">OUT</p>
                        <p class="wpgr-m_card__price-text">bought</p>
                    </div>
                    <div class="wpgr-m_card__main">
                        <div class="wpgr-m_card__figure-wrapper">
                            <div class="wpgr-m_card__figure"></div>
                        </div>
                        <div class="wpgr-m_card__content">
                            <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                            <div class="wpgr-m_card__content-details is-hidden">
                                <p class="wpgr-m_card__desc">
                                    Professionally enable revolutionary ideas vis-a-vis premium human capital.
                                    Progressively strategize client-focused processes via resource-leveling growth strategies.
                                </p>
                                <div class="wpgr-m_card__btn-wrapper">
                                    <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                                    <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpgr-m_card__toggle">
                        <i class="wpgr-m_card__toggle-icon"></i>
                    </div>
                </div>
            </section>

            <div class="wpgr-o_popup wpgr-o_popup--single">
				<form class="wpgr-o_popup__form">

                    <div id="wpgr_popup_name" class="wpgr-o_popup__step is-active">
                        <header class="wpgr-o_popup__header">
                            <p class="wpgr-o_popup__question">Wie ist Dein Name?</p>
                            <p class="wpgr-o_popup__desc">Lasse den Beschenkten wissen, dass das Geschenk von Dir ist.</p>
                        </header>
                        <input type="text" name="name" value="" placeholder="Dein Name">
                        <button class="wpgr-m_btn wpgr-m_btn--next">Next</button>
                        <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                            <i class="wpgr-m_btn-icon">x</i>
                        </button>
                        <ul class="wpgr-o_popup__process">
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
                        </ul>
                    </div>

                    <div id="wpgr_popup_parts" class="wpgr-o_popup__step">
                        <header class="wpgr-o_popup__header">
                            <p class="wpgr-o_popup__question">Wieviele Teile möchtest Du schenken?</p>
                            <p class="wpgr-o_popup__desc">Ein Teil entspricht 50€.</p>
                        </header>
                        <input type="number" name="" value="">
                        <button class="wpgr-m_btn wpgr-m_btn--next">Next</button>
                        <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                            <i class="wpgr-m_btn-icon">x</i>
                        </button>
                        <ul class="wpgr-o_popup__process">
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
                        </ul>
                    </div>

                    <div id="wpgr_popup_buyer" class="wpgr-o_popup__step">
                        <header class="wpgr-o_popup__header">
                            <p class="wpgr-o_popup__question">Willst du den Kauf übernehmen?</p>
                            <p class="wpgr-o_popup__desc">Übernheme die Organisation und hinterlege Kontaktdaten (z.B. Tel/Email), damit die Schenker sich mit Dir absprechen können.</p>
                        </header>
                        <textarea name="" value="" placeholder="Kontaktdaten z.B. E-mail oder Telefon"></textarea>
                        <div class="wpgr-m_btn-wrapper">
                            <button class="wpgr-m_btn wpgr-m_btn--next">Nein</button>
                            <button class="wpgr-m_btn wpgr-m_btn--next">Ja</button>
                        </div>
                        <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                            <i class="wpgr-m_btn-icon">x</i>
                        </button>
                        <ul class="wpgr-o_popup__process">
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
                        </ul>
                    </div>

                    <div id="wpgr_popup_email" class="wpgr-o_popup__step">
                        <header class="wpgr-o_popup__header">
                            <p class="wpgr-o_popup__question">Wie ist Deine Email?</p>
                            <p class="wpgr-o_popup__desc">Wenn du Deine Email hinterlegst, kannst Du deine Reservierung via zugeschicktem Link rückgängig machen.</p>
                        </header>
                        <input type="text" name="" value="">
                        <input class="wpgr-m_btn wpgr-m_btn--next" type="submit" name="save">
                        <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                            <i class="wpgr-m_btn-icon">x</i>
                        </button>
                        <ul class="wpgr-o_popup__process">
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                            <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
                        </ul>
                    </div>
                </form>
			</div>


			<?php

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

		if ( $_POST['version'] == 'new' ) {
			// update new custom post type wishlists

			$wishlist_id 		= $_POST['wishlist_id'];
			$gift_id 			= $_POST['gift_id'];
			$gift_availability 	= $_POST['gift_availability'];
			$gift_reserver		= $_POST['gift_reserver'];

			$wishlist = get_post_meta($wishlist_id, 'wpgr_wishlist', true);
			$to_be_updated = array_search($gift_id, array_column($wishlist, 'gift_id'));

			$wishlist[$to_be_updated]['gift_availability'] = $gift_availability;
			$wishlist[$to_be_updated]['gift_reserver'] = $gift_reserver;

			update_post_meta($wishlist_id, 'wpgr_wishlist', $wishlist);

		} else {
			// update old wishlist type (managed through options page)

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
		}

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
