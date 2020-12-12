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
		add_action( 'woocommerce_product_options_pricing', array( __CLASS__, 'discount_pricing_options' ) );

		// Save discount data.
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'save_meta' ) );

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

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$script_asset_path = self::plugin_url() . '/assets/build/admin.asset.php';

			$script_info       = file_exists( $script_asset_path )
            ? include $script_asset_path
            : [ 'dependencies' => [], 'version' => self::$version ];

			wp_enqueue_script(
				'wc-mnm-bulk-discount-metabox',
				self::plugin_url() . '/assets/build/admin.js',
				$script_info['dependencies'],
				$script_info['version'],
				true
			);

			wp_enqueue_style(
				'wc-mnm-bulk-discount-admin-styles',
				self::plugin_url() . '/assets/build/admin.css',
				false, $script_info['version']
			);

		}	

	}


	/**
	 * Add quantity discount rules.
	 */
	public static function discount_pricing_options() {

		global $product_object;

		$discount_data_array  = $product_object->get_meta( '_wc_mnm_bulk_discount_data', true );

		if ( ! is_array( $discount_data_array ) ) {
			$discount_data_array = array(
				array(
					'min'    => '',
					'max'    => '',
					'amount' => '',
				)
			);
		}

		$json = json_encode( $discount_data_array );

		?>

		<fieldset class="form-field _mnm_per_product_discount_field hide_if_static_pricing show_if_bulk_discount_mode" >

			<label><?php _e( 'Bulk Discounts', 'react-metabox' ); ?></label>

			<div id="wc_mnm_bulk_discount_data" data-discountdata="<?php echo esc_attr( $json );?> "></div>

		</fieldset>

		<?php

	}

	/**
	 * Save meta.
	 *
	 * @param  WC_Product  $product
	 * @return void
	 */
	public static function save_meta( $product ) {

		if ( ! empty( $_POST[ '_wc_mnm_bulk_discount_data' ] ) ) {
			$input_data           = wc_clean( wp_unslash( $_POST[ '_wc_mnm_bulk_discount_data' ] ) );
			$product->add_meta_data( '_wc_mnm_bulk_discount_data', $parsed_discount_data, true );
		} else {
			$product->delete_meta_data( '_wc_mnm_bulk_discount_data' );
		}
	}

}
React_Metabox::init();
