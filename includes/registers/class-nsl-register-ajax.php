<?php
/**
 * NSL: AJAX (admin-ajax.php, or wc-ajax) register.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Ajax' ) ) {
	class NSL_Register_Ajax extends NSL_Register_Base_Ajax {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Ajax();
		}
	}
}
