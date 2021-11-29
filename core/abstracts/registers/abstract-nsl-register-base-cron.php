<?php
/**
 * NSL: Cron register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Cron' ) ) {
	abstract class NSL_Register_Base_Cron implements NSL_Register {
		use NSL_Hook_Impl;

		public function __construct() {
			register_activation_hook( nsl()->get_main_file(), [ $this, 'register' ] );
			register_deactivation_hook( nsl()->get_main_file(), [ $this, 'unregister' ] );
		}

		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Cron ) {
					$item->register();
				}
			}
		}

		public function unregister() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Cron ) {
					$item->unregister();
				}
			}
		}
	}
}
