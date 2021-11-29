<?php
/**
 * NSL: Custom table register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Custom_Table' ) ) {
	abstract class NSL_Register_Base_Custom_Table implements NSL_Register {
		public function register() {
			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Custom_Table ) {
					$item->register();
				}
			}
		}

		public function unregister() {
			foreach( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Custom_Table ) {
					$item->unregister();
				}
			}
		}
	}
}
