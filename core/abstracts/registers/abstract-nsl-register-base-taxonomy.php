<?php
/**
 * NSL: Custom taxonomy register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Taxonomy' ) ) {
	abstract class NSL_Register_Base_Taxonomy implements NSL_Register {
		use NSL_Hook_Impl;

		public function __construct() {
			$this->add_filter( 'init', 'register' );
		}

		/**
		 * @callback
		 * @actin       init
		 */
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Taxonomy ) {
					$item->register();
				}
			}
		}
	}
}
