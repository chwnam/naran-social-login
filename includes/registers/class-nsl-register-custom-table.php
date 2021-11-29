<?php
/**
 * NSL: Custom table register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Custom_Table' ) ) {
	class NSL_Register_Custom_Table extends NSL_Register_Base_Custom_Table {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Custom_Table();
		}
	}
}
