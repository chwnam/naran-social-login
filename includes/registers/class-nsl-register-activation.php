<?php
/**
 * NSL: Activation register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Activation' ) ) {
	class NSL_Register_Activation extends NSL_Register_Base_Activation {
		public function get_items(): Generator {
			// Add defined roles
			yield new NSL_Reg_Activation( 'registers.role@register' );

			// Add defined caps
			yield new NSL_Reg_Activation( 'registers.cap@register' );

			// Add custom tables
			yield new NSL_Reg_Activation( 'registers.custom_table@register' );
		}
	}
}
