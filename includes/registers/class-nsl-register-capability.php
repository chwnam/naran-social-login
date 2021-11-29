<?php
/**
 * NSL: Role register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class_alias( NSL_Reg_Capability::class, 'NSL_Reg_Cap' );

if ( ! class_exists( 'NSL_Register_Capability' ) ) {
	class NSL_Register_Capability extends NSL_Register_Base_Capability {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Cap();
		}
	}
}
