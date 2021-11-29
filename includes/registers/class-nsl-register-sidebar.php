<?php
/**
 * NSL: Sidebar register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Sidebar' ) ) {
	class NSL_Register_Sidebar extends NSL_Register_Base_Sidebar {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Sidebar();
		}
	}
}
