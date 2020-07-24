=== Simple Wishlists for Weddings, Birthdays etc. ===
Contributors: 3qbik
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=86EEHAN7TGUMY
Tags: gift registry, wishlist, gift, wishes, wedding gift registry, wedding, birthday, product list, plugin
Requires at least: 4.8
Tested up to: 5.4.2
Requires PHP: 5.6
Stable tag: 1.4.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily create wishlists for your wedding, birthday or other occasion.

== Description ==

Are you looking for a clean and simple solution to display your wedding gift registry on your website or just want to create a wishlist for your birthday or other occasions?
WPGiftRegistry easily lets you add gifts to your wishlist and embed it anywhere with a shortcode. Each gift is displayed with an image, a title, a description, the price and a product link. More importantly, people can indicate if they already bought the gift (so nobody else does).

WPGiftRegistry comes with a nice design and is built responsively so it displays nicely on all devices.

= Languages =
* English
* German
* Dutch

*Disclaimer*: As this is a free plugin, some of your product links may be transformed into affiliate links.

== Installation ==

1. Upload the `wpgiftregistry` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create wishlists through the new backend menu titled "Wishlists"
4. Place your shortcode (e.g. `[wishlist id='49']`) anywhere in your content, to display the wishlist

If you want to display a list with links to each of your wishlists, use the shortcode `[wishlist id='all']`.

== Frequently Asked Questions ==

= What features are we planning for future releases? =

* A color picker for customizing the wishlist colors
* Email notifications
* Filtering
* Potentially PayPal integration

== Screenshots ==

1. The embedded wishlist
2. Gift reservation overlay
3. Setting up the wishlist in the backend
4. An expanded wishlist item in the backend


== Changelog ==

= 1.4.13 =
2020-07-24

* Fixed broken backend fields (bugs were introduced with 1.4.12, sorry!)

= 1.4.12 =
2020-07-24

* Fixed some broken links

= 1.4.11 =
2020-05-29

* Fixed issues with CMB2 upgrade, metaboxes where not showing when editing whishlists

= 1.4.10 =
2020-05-26

* Assured compatibility with WordPress 5.4.1
* Fixed styling issues with the Twenty Twenty theme
* Fixed various broken Amazon links
* Fixed several other small bugs
* Added global option to hide the total price of splitted gifts
* Added the option to enable gifts to be given unlimitedly
* Updated CMB2 library to v2.7.0

= 1.4.9 =
* Bugfixes/Updates to CMB2 and CMB2-Conditionals to fix problems with WPBakery Visual Composer (thanks to @jtsternberg)

= 1.4.8 =
* Fixed XSS vulnerabilities

= 1.4.7 =
* Compatibility with WordPress 5.0
* Removed aggressive CSS rules that caused problems previously
* Added an option to hide/show gift price decimals to the settings page

= 1.4.6 =
* Bugfix for password protecting single wishlists

= 1.4.5 =
* Important bugfix for reserving single gifts

= 1.4.4 =
* New shortcode `[wishlist id='all']` displays a list with links to each of your wishlists

= 1.4.3 =
* Added missing strings to translations
* Fixed some display bugs regarding successive reservation of gift parts 

= 1.4.2 =
* Changed price display to price per part
* Minor CSS bugfixes

= 1.4.1 =
* Important bugfix: added missing JS and CSS files

= 1.4.0 =
* **NEW FEATURE:** Split gifts into parts to enable group gifting
* Display unavailable gifts at the end of the wishlist
* New 'Copy Shortcode' button

= 1.3.5 =
* New settings to enable/disable email and personal message fields in the gift reservation dialogue
* Optional tracking of anonymous user data to improve the plugin

= 1.3.4 =
* A few minor bugfixes

= 1.3.3 =
* Bugfix for successive gift reservations
* Bugfix for multiple wishlists on one page

= 1.3.2 =
* Bugfix for empty settings page
* Fallback for NumberFormatter (caused a fatal error on activation if absent)

= 1.3.1 =
* Bugfix in the uninstall script

= 1.3.0 =
* Major design overhaul
* People can now leave their name when reserving gifts
* Many small bugfixes and improvements

= 1.2.5 =
* Important bugfix for a display error in the reservation overlay

= 1.2.4 =
* Bugfix for IE and Edge

= 1.2.3 =
* Bugfix for logged out users to enable marking gifts as bought

= 1.2.2 =
* Language File Bugfix

= 1.2.1 =
* Dutch translation

= 1.2.0 =
* New feature: the wishlist is now sortable via drag and drop

= 1.1.1 =
* Bugfixes

= 1.1.0 =
* New feature enabling wishlist items to be used without a product URL
* New settings page to customize the currency symbol used for displaying of the wishlist
* German translation

= 1.0.2 =
* Fix for a bug causing the wishlist to be always embedded at the top

= 1.0.1 =
* Fix of a minor JavaScript bug

= 1.0.0 =
* First version of WPGiftRegistry with the basic functionality.
