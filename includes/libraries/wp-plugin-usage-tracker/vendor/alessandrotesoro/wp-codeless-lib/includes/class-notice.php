<?php
/**
 * A class to handle admin notices.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @copyright  (c) 2016 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
*/

namespace TDP;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Notice class.
 */
class Notice {

  /**
   * Ajax action that is responsible for dismissing sticky notices.
   */
  const AJAX_ACTION = 'tdp_dismiss_notice';

  /**
   * The name of the option that contains dismissed notices.
   */
  const STICKY_OPTION = 'wp_codeless_dismissed_notices';

  /**
   * Optional ID for this message.
   * @var string
   */
  protected $id = '';

  /**
   * Notice type.
   * @var string
   */
  protected $type;

  /**
   * The content of the notice.
   * @var string
   */
  protected $content;

  /**
   * Dismissable or not.
   * @var bool
   */
  protected $dismissable = true;

  /**
   * Sticky notice or not.
   * @var bool
   */
  protected $sticky;

  /**
   * Hello world.
   *
   * @param string $type     notice type.
   * @param string $content  content of the notice.
   * @param string $id       optional ID needed for sticky notices.
   */
  public function __construct( $type, $content, $id = '' ) {

    // Set ID for this notice.
    $this->set_id( $id );
    // Set type for the notice.
    $this->set_type( $type );
    // Set content for the notice.
    $this->set_content( $content );

    // Set as sticky notice if an ID is given.
    if( ! empty( $this->get_id() ) ) {
      $this->set_sticky();
    }

    // Prepare the notice.
    $content = $this->get_content();
    $type    = $this->get_type();

    if( $this->is_sticky() && ! $this->maybe_display_notice() )
      return;

    add_action( 'admin_notices', function() use ( $content, $type ) {
			include 'views/notice.php';
		}, 1 );

    add_action( 'wp_ajax_'.self::AJAX_ACTION , array( $this, 'handle_dismission' ) );

  }

  /**
   * Get the ID of the notice.
   *
   * @return string
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Set the ID.
   *
   * @param string $id id to set.
   */
  public function set_id( $id ) {
    $this->id = (string) $id;
    return $this;
  }

  /**
   * Get the type of notice.
   *
   * @return string.
   */
  public function get_type() {
    return $this->type;
  }

  /**
   * Set the notice type.
   *
   * @param string $type the type of notice.
   */
  public function set_type( $type ) {
    $this->type = (string) $type;
    return $this;
  }

  /**
   * Retrieve the content of the notice.
   *
   * @return string
   */
  public function get_content() {
    return $this->content;
  }

  /**
   * Set the content of the notice.
   *
   * @param string $content
   */
  public function set_content( $content ) {
    $this->content = (string) $content;
    return $this;
  }

  /**
   * Is this notice sticky ?
   * @return boolean
   */
  public function is_sticky() {
    return (bool) $this->sticky;
  }

  /**
   * Set this notice as sticky or not.
   */
  public function set_sticky() {
    $this->sticky = true;
    return $this;
  }

  /**
   * Get all notices that have been dismissed.
   * @return array.
   */
  public function get_dismissed_notices() {
    return get_option( self::STICKY_OPTION, array() );
  }

  /**
   * Dismiss a notice.
   *
   * @return void
   */
  private function dismiss_notice( $id ) {

    $notices   = $this->get_dismissed_notices();
    $notices[] = $id;

    update_option( self::STICKY_OPTION, $notices );

  }

  /**
   * Check whether the notice should display.
   * Used for sticky notices.
   *
   * @return bool
   */
  private function maybe_display_notice() {

    if( in_array( $this->get_id() , $this->get_dismissed_notices() ) )
      return false;

    return true;

  }

  /**
   * Handle ajax dismission of the notice.
   *
   * @return void
   */
  public function handle_dismission() {

    // Check our nonce and make sure it's correct.
    check_ajax_referer( self::AJAX_ACTION, 'notice_nonce' );

    if( ! current_user_can( 'manage_options' ) )
			return;

    $this->dismiss_notice( sanitize_title( $_POST['notice_id'] ) );

    wp_send_json_success();

  }

}
