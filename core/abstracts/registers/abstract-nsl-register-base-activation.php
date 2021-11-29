<?php
/**
 * NSL: Activation register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Activation' ) ) {
	abstract class NSL_Register_Base_Activation implements NSL_Register {
		public function __construct() {
			register_activation_hook( nsl()->get_main_file(), [ $this, 'register' ] );
		}

		/**
		 * Method name can mislead, but it does activation callback jobs.
		 */
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Activation ) {
					$item->register();
				}
			}
		}
	}
}
