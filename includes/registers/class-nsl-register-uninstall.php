<?php
/**
 * NSL: Uninstall register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Uninstall' ) ) {
	class NSL_Register_Uninstall extends NSL_Register_Base_Uninstall {
		public function get_items(): Generator {
			yield new NSL_Reg_Uninstall( 'registers.custom_table@unregister' );
		}
	}
}
