<?php
/**
 * NSL: Cron register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Cron' ) ) {
	class NSL_Register_Cron extends NSL_Register_Base_Cron {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Cron();
		}
	}
}
