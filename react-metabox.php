<?php
/**
* Plugin Name: React Metabox
* Plugin URI: https://github.com/helgatheviking/react-metabox
* Description: Add react to a metabox
* Version: 1.0.0-beta-1
* Author: Kathy Darling
* Author URI: https://kathyisawesome.com/
*
* Text Domain: react-metabox
* Domain Path: /languages/
*
* Requires at least: 5.0
* Tested up to: 5.6
* Requires PHP: 7.0

* WC requires at least: 4.7
* WC tested up to: 4.7
*
* Copyright: © 2020 Backcourt Development.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class React_Metabox {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public static $version = '1.0.0-beta-1';

	/**
	 * Plugin URL.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename(__FILE__) );
	}

	/**
	 * Plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Fire in the hole!
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_plugin' ) );
	}

	/**
	 * Hooks.
	 */
	public static function load_plugin() {

		/*
		 * Admin.
		 */
		// Admin styles and scripts.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 20 );
		
		// Display.
		add_action( 'woocommerce_product_options_pricing', array( __CLASS__, 'pricing_options' ) );

		// Localization.
		add_action( 'init', array( __CLASS__, 'localize_plugin' ) );
	}

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public static function localize_plugin() {
		load_plugin_textdomain( 'react-metabox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/*
	|--------------------------------------------------------------------------
	| Admin and Metaboxes.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Front-end script.
	 *
	 * @param  array  $dependencies
	 */
	public static function admin_scripts() {

		// Get admin screen id.
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		/*
		 * Enqueue styles and scripts.
		 */
		if ( 'product' === $screen_id ) {
			$script_path       = '/assets/build/metabox.js';
			$script_asset_path = dirname( __FILE__ ) . '/assets/build/metabox.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require( $script_asset_path )
				: array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
			$script_url = plugins_url( $script_path, __FILE__ );

			wp_register_script(
				'react-metabox-example',
				$script_url,
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);

			wp_register_style(
				'react-metabox-example',
				plugins_url( '/assets/build/style-metabox.css', __FILE__ ),
				// Add any dependencies styles may have, such as wp-components.
				array(),
				filemtime( dirname( __FILE__ ) . '/assets/build/style-metabox.css' )
			);

			wp_enqueue_script( 'react-metabox-example' );
			wp_enqueue_style( 'react-metabox-example' );
		}	

	}


	/**
	 * Add quantity discount rules.
	 */
	public static function pricing_options() {
		?>

		<fieldset class="form-field _react_metabox_example_field" >

			<label><?php _e( 'React Metabox', 'react-metabox' ); ?></label>

			<div id="react-metabox-example-root"></div>

		</fieldset>

		<?php

	}

}
React_Metabox::init();
