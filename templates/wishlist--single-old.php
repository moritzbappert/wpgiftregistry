<?php

/**
 * Template for output of a single wishlist (old design, prior to version 1.3.0)
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

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
				<?php echo (!empty($gift['gift_url']) ? '<a href="' . static::transform_to_affiliate_link( $gift['gift_url'] ) . '" class="buy-button' . $availability_class . '" target="_blank">' . __('VIEW/BUY', 'wpgiftregistry') . '</a>' : '<a href="javascript:void(0)" class="buy-button' . $availability_class . '">' . __('VIEW/BUY', 'wpgiftregistry') . '</a>'); ?>
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
				<?php echo sprintf( __('Do you want to mark %s as %sbought%s so that nobody else gifts it?', 'wpgiftregistry'), '<span id="item-name"></span>', '<em>', '</em>' ); ?>
			</p>
			<button id="yes"><?php echo __('Yes', 'wpgiftregistry'); ?></button><button id="no"><?php echo __('No, Cancel', 'wpgiftregistry'); ?></button>
		</div>
	</div>

</section>