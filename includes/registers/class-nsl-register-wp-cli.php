<?php
/**
 * NSL: WP-CLI register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_WP_CLI' ) ) {
	class NSL_Register_WP_CLI extends NSL_Register_Base_WP_CLI {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_WP_CLI();
		}
	}
}
