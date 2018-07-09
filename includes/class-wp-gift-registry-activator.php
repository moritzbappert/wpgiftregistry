<?php

/**
 * Fired during plugin activation
 *
 * @link       http://dreiqbik.de
 * @since      1.0.0
 *
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Gift_Registry
 * @subpackage WP_Gift_Registry/includes
 * @author     Moritz Bappert <mb@dreiqbik.de>
 */

class WP_Gift_Registry_Activator {


	public static function activate() {

		// init wpgr_settings in db
		if ( !get_option('wpgr_settings') ) {

			//	figure out the local currency symbol
			//  as seen at https://stackoverflow.com/a/30026774
			setlocale( LC_ALL, get_locale() );
			$local_settings = localeconv();
			$locale = get_locale(); // browser or user locale
			$currency = $local_settings['int_curr_symbol'];
			if ( class_exists('NumberFormatter') ) {
				try {
				    $fmt = new NumberFormatter( $locale."@currency=$currency", NumberFormatter::CURRENCY );
				    $symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
				} catch (IntlException $e) {
				    $symbol = '€';
				}
			} else {
				$symbol = '€';
			}

			$wpgr_settings = array(
				'currency_symbol' => $symbol,
				'currency_symbol_placement' => 'after',
				'activation_date' => date('j F Y'),
			);
		    update_option('wpgr_settings', $wpgr_settings);
		}
	}

}
