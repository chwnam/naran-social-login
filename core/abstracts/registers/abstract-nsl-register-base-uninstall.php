<?php
/**
 * NSL: Uninstall register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Uninstall' ) ) {
	abstract class NSL_Register_Base_Uninstall implements NSL_Register {
		/**
		 * Method name can mislead, but it does uninstall callback jobs.
		 */
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Uninstall ) {
					$item->register();
				}
			}
		}
	}
}
