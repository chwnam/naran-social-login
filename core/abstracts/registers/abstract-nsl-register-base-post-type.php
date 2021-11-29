<?php
/**
 * NSL: Custom post type register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Post_Type' ) ) {
	abstract class NSL_Register_Base_Post_Type implements NSL_Register {
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
				if ( $item instanceof NSL_Reg_Post_Type ) {
					$item->register();
				}
			}
		}
	}
}
