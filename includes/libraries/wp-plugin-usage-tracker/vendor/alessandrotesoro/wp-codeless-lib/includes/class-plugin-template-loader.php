<?php
/**
 * A class to handle template files loading for plugins.
 * Inspired by WP Job Manager template's functions and made some modifications.
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
 * Plugin_Template_Loader class.
 */
class Plugin_Template_Loader {

	/**
	 * Prefix used for filters. Should match to your plugin's one.
	 *
	 * @var string
	 */
	protected $filter_prefix = '';

	/**
	 * Reference to the root directory path of the plugin that makes use of this class.
	 * Usually it's defined through a constant.
	 *
	 * @var string
	 */
	protected $plugin_directory = '';

	/**
	 * Directory name where templates are found in this plugin.
	 *
	 * @var string
	 */
	protected $plugin_template_directory = 'templates';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @var string
	 */
	protected $theme_template_directory = '';

	/**
	 * Get and include template files.
	 *
	 * @param  string $template_name name of the template file to include.
	 * @param  array  $args          arguments to pass to the template file.
	 * @return void
	 */
	public function get_template( $template_name, $args = array(), $default_path = '' ) {

		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		include( $this->locate_template( $template_name, $default_path ) );

	}

	/**
	 * Locate a template and return the path for inclusion.
	 * The function also looks into the theme and child theme.
	 *
	 * @param  string $template_name name of the template file to include.
	 * @param  string $default_path  alternative default path, defaults to empty.
	 * @return string
	 */
	public function locate_template( $template_name, $default_path = '' ) {

		$template = locate_template(
			array(
				trailingslashit( $this->theme_template_directory ) . $template_name,
				$template_name
			)
		);

		if ( ! $template && $default_path !== false ) {
			$default_path = $default_path ? $default_path : $this->plugin_directory . '/'. $this->plugin_template_directory .'/';
			if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
				$template = trailingslashit( $default_path ) . $template_name;
			}
		}

		return apply_filters( $this->filter_prefix.'_locate_template', $template, $template_name, $this->theme_template_directory );

	}

	/**
	 * Retrieve metadata from a file. Based on WP Core's get_file_data function.
	 *
	 * @param  string $file path to the file.
	 * @return string
	 */
	protected function get_file_version( $file ) {

		if ( ! file_exists( $file ) ) {
			return '';
		}

		$fp = fopen( $file, 'r' );

		$file_data = fread( $fp, 8192 );

		fclose( $fp );

		$file_data = str_replace( "\r", "\n", $file_data );

		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] )
			$version = _cleanup_header_comment( $match[1] );

		return $version;

	}

	/**
	 * Scan template files directory.
	 *
	 * @param  string $template_path path to scan.
	 * @return array
	 */
	protected function scan_template_files( $template_path = '' ) {

		if( $template_path == '' ) {
			$template_path = $this->plugin_directory . '/' . $this->plugin_template_directory . '/';
		}

		$files  = scandir( $template_path );
		$result = array();

		if ( $files ) {

			foreach ( $files as $key => $value ) {

				if ( ! in_array( $value, array( ".",".." ) ) ) {

					if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {

						$sub_files = $this->scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );

						foreach ( $sub_files as $sub_file ) {

							$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;

						}

					} else {

						$result[] = $value;

					}

				}

			}

		}

		return $result;

	}

	/**
	 * Retrieves a list of overwritten template files.
	 *
	 * @return array
	 */
	public function get_overwritten_template_files() {

		$scanned_files      = $this->scan_template_files();
		$found_files        = array();
		$outdated_templates = false;

		if( ! empty( $scanned_files ) && is_array( $scanned_files ) ) {

			foreach ( $scanned_files as $file ) {

				if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
					$theme_file = get_stylesheet_directory() . '/' . $file;
				} elseif ( file_exists( get_stylesheet_directory() . '/'. $this->theme_template_directory .'/' . $file ) ) {
					$theme_file = get_stylesheet_directory() . '/'. $this->theme_template_directory .'/' . $file;
				} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
					$theme_file = get_template_directory() . '/' . $file;
				} elseif( file_exists( get_template_directory() . '/'. $this->theme_template_directory .'/' . $file ) ) {
					$theme_file = get_template_directory() . '/'. $this->theme_template_directory .'/' . $file;
				} else {
					$theme_file = false;
				}

				// Check that file exist and verify template version.
				// Add all found files to an array.
				if( ! empty( $theme_file ) ) {

					$core_version  = $this->get_file_version( $this->plugin_directory . '/' . $this->plugin_template_directory . '/' . $file );
					$theme_version = $this->get_file_version( $theme_file );

					if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {

						if ( ! $outdated_templates ) {
							$outdated_templates = true;
						}

						$found_files[] = array(
							'file'          => str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ),
							'core_version'  => $core_version,
							'theme_version' => $theme_version,
							'outdated'      => $outdated_templates
						);

					} else {

						$found_files[] = array(
							'file' => str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ),
						);

					}

				}

			}

		}

		return $found_files;

	}

}
