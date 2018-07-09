<?php
/**
 * Codeless Library.
 * An helper library with functionalities that are common among my WP projects.
 *
 * Copyright (c) 2016 Alessandro Tesoro
 *
 * Codeless Library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Codeless Library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @copyright  (c) 2016 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    wp-codeless-lib
*/

namespace TDP;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Codeless Class
 * @since 1.0.0
 */
class Codeless {

	/**
	 * Includes path.
	 *
	 * @var string
	 */
	private $includes_path = '';

	/**
	 * Get thing started.
	 */
	public function __construct() {

		// Set includes path.
		$this->includes_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/includes/';

		spl_autoload_register( array( $this, 'autoload' ) );

	}

	/**
	 * Whether or not this site is in debug mode.
	 * @return boolean
	 */
	public static function is_development() {
		return ( defined( 'WP_DEBUG' ) && WP_DEBUG );
	}

	/**
	 * Whether or not this site is in script and debug mode.
	 * @return boolean
	 */
	public static function is_script_debug() {
		return self::is_development() && ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
	}

	/**
	 * Shows an admin notice.
	 *
	 * @param  string $type   the type of message accepted types are success, error, warning, info.
	 * @param  string $text   the content of the message.
	 * @param  string $id     optional ID, if passed, notice will be sticky.
	 */
	public static function show_admin_notice( $content, $type, $id = '' ) {
		new \TDP\Notice( $type, $content, $id );
	}

	/**
	 * Adds an action link for the specified plugin into the plugin's page.
	 *
	 * @param string $plugin_slug the slug of the plugin.
	 * @param string $label       the label of the link.
	 * @param string $link        the link.
	 */
	public static function add_plugin_action_link( $plugin_slug, $label, $link ) {

		$link  = esc_html( $link );
		$label = esc_html( $label );

		add_filter( 'plugin_action_links_'.$plugin_slug, function( $links ) use ( $link, $label ) {

			if( ! empty ( $link ) && ! empty( $label ) ) {
				$custom_link = '<a href="'. $link .'">'. $label .'</a>';
				array_push( $links, $custom_link );
			}

			return $links;

		}, 10 );

	}

	/**
	 * Add a new link to the plugin's meta row.
	 *
	 * @param string $plugin_file the folder/file of the plugin.
	 * @param string $label       the label of the link.
	 * @param string $link        the link.
	 */
	public static function add_plugin_meta_link( $plugin_file, $label, $link ) {

		$link  = esc_html( $link );
		$label = esc_html( $label );

		add_filter( 'plugin_row_meta', function( $input, $file ) use ( $plugin_file, $link, $label ) {

			if ( $file != $plugin_file )
				return $input;

			$links = array( '<a href="'. $link .'">'. $label .'</a>' );

			$input = array_merge( $input, $links );

			return $input;

		}, 10, 2 );

	}

	/**
	 * Add a message within the "after_plugin_row" area of the plugin's page.
	 *
	 * @param string $plugin_slug the slug of the plugin.
	 * @param string $message     the message to display.
	 * @param string $type        the class that is applied to the message, defaults to update-message.
	 */
	public static function add_plugin_message( $plugin_slug, $message, $type = 'update-message' ) {

		add_filter( 'after_plugin_row_'.$plugin_slug, function( $plugin_name ) use ( $message, $type ) {

			echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="'.$type.'">'.$message.'</div></td>';

		}, 10 );

	}

	/**
	 * Adds a bubble counter to a menu in wp-admin.
	 *
	 * @param int $menu_key the array key number of a menu.
	 * @param int $counter  the number to display.
	 */
	public static function add_count_bubble( $menu_key, $counter ) {

		add_filter( 'admin_menu', function() use ( $menu_key, $counter ) {

			global $menu;

			$menu[ $menu_key ][0] .= " <span class='update-plugins count-1'><span class='update-count'>". $counter ."</span></span>";

		}, 10 );

	}

	/**
	 * Add items to the admin menu bar.
	 *
	 * @param array $args https://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_menu
	 */
	public static function add_menu_bar_item( $args ) {

		add_filter( 'wp_before_admin_bar_render', function() use ( $args ) {

			global $wp_admin_bar;

			$wp_admin_bar->add_menu( $args );

		}, 999 );

	}

	/**
	 * Adds a column to the user's table.
	 *
	 * @param string  $label    column label.
	 * @param string  $callback function that holds the content of the column.
	 * @param integer $priority priority for the action.
	 */
	public static function add_user_column( $label, $callback, $priority = 10 ) {

		add_filter( 'manage_users_columns', function( $columns ) use ( $label ) {

			$key = sanitize_title_with_dashes( $label );

			return array_merge( $columns, array( $key => $label ) );

		}, $priority );

		add_filter( 'manage_users_custom_column', function( $output, $column_name, $user_id ) use ( $label, $callback ) {

			$key = sanitize_title_with_dashes( $label );

			if ( $column_name === $key ) {

				ob_start();

				call_user_func( $callback, $user_id );

				return ob_get_clean();

			}

		}, $priority, 3 );

	}

	/**
	 * Add a column to a post type's table.
	 *
	 * @param mixed  $post_types post type name or array of post types.
	 * @param string  $label      the label of the column.
	 * @param string  $callback   the name of the function that will handle the output.
	 * @param integer $priority   priority of the actions and filters fired.
	 */
	public static function add_post_type_column( $post_types, $label, $callback, $priority = 10 ) {

		if( ! is_array( $post_types ) ) {
			$post_types = array( $post_types );
		}

		foreach ( $post_types as $post_type ) {

			add_filter( 'manage_'.$post_type.'_posts_columns', function( $columns ) use ( $label ) {

				$key = sanitize_title_with_dashes( $label );

				return array_merge( $columns, array( $key => $label ) );

			}, $priority );

			add_action( 'manage_'.$post_type.'_posts_custom_column', function( $column, $post_id ) use ( $label, $callback ) {

				$key = sanitize_title_with_dashes( $label );

				if ( $column === $key ) {
					call_user_func( $callback, $post_id );
				}

			}, $priority, 2 );

		}

	}

	/**
	 * Add a column to taxonomies tables.
	 *
	 * @param mixed   $taxonomies taxonomy name or an array of multiple taxonomies.
	 * @param string  $label      the label of the column.
	 * @param string  $callback   the name of the function that handles the output.
	 * @param integer $priority   priority for the hooks fired.
	 */
	public static function add_taxonomy_column( $taxonomies, $label, $callback, $priority = 10 ) {

		if( ! is_array( $taxonomies ) ) {
			$taxonomies = array( $taxonomies );
		}

		foreach ( $taxonomies as $tax ) {

			add_filter( 'manage_edit-'.$tax.'_columns', function( $columns ) use ( $label ) {

				$key = sanitize_title_with_dashes( $label );

				return array_merge( $columns, array( $key => $label ) );

			}, $priority );

			add_action( 'manage_'.$tax.'_custom_column', function( $value, $column, $tax_id ) use ( $label, $callback ) {

				$key = sanitize_title_with_dashes( $label );

				if ( $column === $key ) {
					call_user_func( $callback, $tax_id );
				}

			}, $priority, 3 );

		}

	}

	/**
	 * Add a new action to the row of a post type.
	 *
	 * @param string $post_type post type name.
	 * @param string $label     label of the link.
	 * @param string $url       url of the link.
	 */
	public static function add_post_row_action( $post_type, $label, $url ) {

		$callback = function( $actions, $post ) use ( $post_type, $label, $url ) {

			if( $post->post_type == $post_type ) {

				$key = sanitize_title_with_dashes( $label );

				$actions[ $key ] = '<a href="'. $url .'">'. $label .'</a>';

			}

			return $actions;

		};

		add_filter( 'post_row_actions', $callback, 10, 2 );
		add_filter( 'page_row_actions', $callback, 10, 2 );

	}

	/**
	 * Loads the required scripts for using the javascript helper libraries.
	 *
	 * @since 1.0.0
	 * @return void.
	 */
	public static function add_ui_helper_files() {

		$suffix  = ( self::is_script_debug() ) ? '': '.min';
		$css_dir = untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/css/';
		$js_dir  = untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/js/';

		wp_register_style( 'codeless-helper-styling', $css_dir . 'codeless-helper-style' . $suffix . '.css', '1.0.0' );
		wp_enqueue_style( 'codeless-helper-styling' );

		wp_register_script( 'codeless-helper-library', $js_dir . 'codeless-helper-library' . $suffix . '.js', 'jQuery', '1.0.0', true );
		wp_enqueue_script( 'codeless-helper-library' );

	}

	/**
	 * Removes a post type column.
	 *
	 * @param  mixed $post_types post types.
	 * @param  mixed $columns    columns to remove.
	 * @return void
	 */
	public static function remove_post_type_column( $post_types, $columns ) {

		if ( ! is_array( $post_types ) ) {
			$post_types = array( $post_types );
		}

		if ( ! is_array( $columns ) ) {
			$columns = array( $columns );
		}

		foreach ( $post_types as $post_type ) {

			add_action( 'manage_edit-'.$post_type.'_columns', function( $column_headers ) use ( $columns ) {

				foreach ( $columns as $column ) {
					unset( $column_headers[ $column ] );
					unset( $column_headers[ strtolower( $column ) ] );
				}

				return $column_headers;

			} );

		}

	}

	/**
	 * Autoload classes.
	 *
	 * @since 1.0.0
	 * @param  string $class class to load.
	 * @return void.
	 */
	public function autoload( $class_name ) {

		// Autoload classes with this namespace.
		if ( false === strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		// Remove namespace from class name.
		$class_file = str_replace( __NAMESPACE__ . '\\', '', $class_name );

		// Convert class name to filename.
		$class_file = strtolower( $class_file );
		$class_file = str_replace( '_', '-', $class_file );

		// If there's any subnamespace convert that to a path.
		$class_path = explode( '\\', $class_file );
		$class_file = array_pop( $class_path );
		$class_path = implode( '/', $class_path );

		// Finally load the file.
		$file = $this->includes_path . $class_path . '/class-' . $class_file . '.php';

		if( file_exists( $file ) ) {
			require_once $file;
		}

	}

}
