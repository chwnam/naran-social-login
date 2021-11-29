<?php
/**
 * NSL: Submit (admin-post.php) register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Submit' ) ) {
	class NSL_Register_Submit extends NSL_Register_Base_Submit {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Submit();
		}
	}
}
