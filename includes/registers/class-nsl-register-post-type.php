<?php
/**
 * NSL: Custom post type register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Post_Type' ) ) {
	class NSL_Register_Post_Type extends NSL_Register_Base_Post_Type {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Post_Type();
		}
	}
}
