<?php
/**
 * NSL: User handler module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_User_Handler' ) ) {
	class NSL_User_Handler implements NSL_Module {
		public function get_handler_url(): string {
			$settings = nsl_settings();

			return '';
		}
	}
}
