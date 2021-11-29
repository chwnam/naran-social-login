<?php
/**
 * NSL: Shortcode register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Shortcode' ) ) {
	class NSL_Register_Shortcode extends NSL_Register_Base_Shortcode {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Shortcode();
		}
	}
}
