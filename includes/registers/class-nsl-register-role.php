<?php
/**
 * NSL: Role register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Role' ) ) {
	class NSL_Register_Role extends NSL_Register_Base_Role {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Role();
		}
	}
}
