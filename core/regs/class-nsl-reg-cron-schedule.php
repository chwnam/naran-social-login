<?php
/**
 * NSL: Cron schedule reg.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Reg_Cron_Schedule' ) ) {
	class NSL_Reg_Cron_Schedule implements NSL_Reg {
		public string $name;

		public int $interval;

		public string $display;

		public function __construct(
			string $name,
			int $interval,
			string $display
		) {
			$this->name     = $name;
			$this->interval = $interval;
			$this->display  = $display;
		}

		public function register( $dispatch = null ) {
			// Do nothing.
		}
	}
}
