<?php
/**
 * NSL: Role register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Role' ) ) {
	abstract class NSL_Register_Base_Role implements NSL_Register {
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Role ) {
					$item->register();
				}
			}
		}

		public function unregister() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Role ) {
					$item->unregister();
				}
			}
		}
	}
}
