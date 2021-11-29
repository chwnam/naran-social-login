<?php
/**
 * NSL: Capability register base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Capability' ) ) {
	abstract class NSL_Register_Base_Capability implements NSL_Register {
		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Capability ) {
					$item->register();
				}
			}
		}

		public function unregister() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Capability ) {
					$item->unregister();
				}
			}
		}
	}
}
