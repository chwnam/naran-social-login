<?php
/**
 * NSL: Script register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Script' ) ) {
	class NSL_Register_Script extends NSL_Register_Base_Script {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Script();
		}
	}
}
