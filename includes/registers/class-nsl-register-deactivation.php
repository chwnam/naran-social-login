<?php
/**
 * NSL: Deactivation register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Deactivation' ) ) {
	class NSL_Register_Deactivation extends NSL_Register_Base_Deactivation {
		public function get_items(): Generator {
			// Remove defined roles
			yield new NSL_Reg_Activation( 'registers.role@unregister' );

			// Remove defined caps
			yield new NSL_Reg_Activation( 'registers.cap@unregister' );
		}
	}
}
