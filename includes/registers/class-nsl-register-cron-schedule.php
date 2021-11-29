<?php
/**
 * NSL: Cron schedule register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Cron_Schedule' ) ) {
	class NSL_Register_Cron_Schedule extends NSL_Register_Base_Cron_Schedule {
		public function get_items(): Generator {
			yield; // yield new NSL_Reg_Cron_Schedule();
		}
	}
}
