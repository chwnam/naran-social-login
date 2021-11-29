<?php
/**
 * NSL: Style register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Style' ) ) {
	class NSL_Register_Style extends NSL_Register_Base_Style {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Style();
		}
	}
}
