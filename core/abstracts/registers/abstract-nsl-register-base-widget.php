<?php
/**
 * NSL: Widget register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Widget' ) ) {
	abstract class NSL_Register_Base_Widget implements NSL_Register {
		use NSL_Hook_Impl;

		public function __construct() {
			$this->add_action( 'widgets_init', 'register' );
		}

		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Widget ) {
					$item->register();
				}
			}
		}
	}
}
