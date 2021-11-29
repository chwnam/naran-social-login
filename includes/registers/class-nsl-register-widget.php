<?php
/**
 * NSL: Widget register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Widget' ) ) {
	class NSL_Register_Widget extends NSL_Register_Base_Widget {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Custom_Table();
		}
	}
}
