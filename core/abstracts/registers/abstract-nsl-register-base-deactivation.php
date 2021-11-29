<?php
/**
 * NSL: Deactivation register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Deactivation' ) ) {
	abstract class NSL_Register_Base_Deactivation implements NSL_Register {
		public function __construct() {
			register_deactivation_hook( nsl()->get_main_file(), [ $this, 'register' ] );
		}

		/**
		 * Method name can mislead, but it does deactivation callback jobs.
		 */
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Deactivation ) {
					$item->register();
				}
			}
		}
	}
}
