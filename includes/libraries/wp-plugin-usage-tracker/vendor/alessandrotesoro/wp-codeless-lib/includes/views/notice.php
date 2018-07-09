<?php
/**
 * Template file that handles the display of the admin notices.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @copyright  (c) 2016 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
*/
?>

<div class="notice notice-<?php echo $type; ?> is-dismissible" <?php if( $this->is_sticky() ) : ?>id="tdp-codeless-notice-<?php echo $this->get_id();?>"<?php endif; ?>>
  <p><?php echo $content; ?></p>
</div>
<?php if( $this->is_sticky() ) : ?>
<script type="text/javascript">
jQuery( document ).ready( function ( $ ) {

  var data = {
	  'action': '<?php echo self::AJAX_ACTION; ?>',
    'notice_id': '<?php echo $this->get_id();?>',
    'notice_nonce': '<?php echo wp_create_nonce(self::AJAX_ACTION); ?>'
	};

  jQuery( '#tdp-codeless-notice-<?php echo $this->get_id();?>' ).on('click', '.notice-dismiss', function ( event ) {
    jQuery.ajax({
      url: "<?php echo admin_url('admin-ajax.php'); ?>",
      type: 'POST',
      data: data,
    });
  });

});
</script>
<?php endif; ?>
