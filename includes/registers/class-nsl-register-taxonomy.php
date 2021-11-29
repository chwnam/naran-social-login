<?php
/**
 * NSL: Custom taxonomy register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Taxonomy' ) ) {
	class NSL_Register_Taxonomy extends NSL_Register_Base_Taxonomy {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Taxonomy();
		}
	}
}
