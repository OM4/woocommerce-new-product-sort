<?php
/*
Plugin Name: WooCommerce - New Product Sort Order
Plugin URI: https://github.com/OM4/woocommerce-new-product-sort
Description: When adding a new WooCommerce product, automatically move it to the last item in the drag & drop sort order.
Version: 0.1
Author: OM4
Author URI: http://om4.com.au/
Text Domain: woocommerce-new-product-sort
Git URI: https://github.com/OM4/woocommerce-new-product-sort
Git Branch: release
License: GPLv2
*/

/*
Copyright 2014 OM4 (email: info@om4.com.au    web: http://om4.com.au/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'WC_New_Product_Sort' ) ) {

	/**
	 * This class is a singleton.
	 *
	 * Class WC_New_Product_Sort
	 */
	class WC_New_Product_Sort {

		/**
		 * Refers to a single instance of this class
		 */
		private static $instance = null;

		/**
		 * Creates or returns an instance of this class
		 * @return WC_New_Product_Sort A single instance of this class
		 */
		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Constructor
		 */
		private function __construct() {

			add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data', 10, 2 ) );
		}

		/**
		 * When adding a WooCommerce product, automatically move it to the last item in the drag & drop sort order.
		 *
		 * auto-draft or draft products are automatically handled here.
		 *
		 * @param $data
		 * @param $postarr
		 *
		 * @return mixed
		 */
		public function wp_insert_post_data( $data, $postarr ) {
			if ( 'product' != $data['post_type'] ) {
				return $data;
			}

			if ( 'draft' == $data['post_status'] || 'auto-draft' == $data['post_status'] ) {
				global $wpdb;
				$data['menu_order'] = $wpdb->get_var( "SELECT MAX(menu_order)+1 AS menu_order FROM {$wpdb->posts} WHERE post_type='product'" );
			}
			return $data;

		}

	}

	WC_New_Product_Sort::instance();

}