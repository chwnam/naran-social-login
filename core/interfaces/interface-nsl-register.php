<?php
/**
 * NSL: Register interface
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NSL_Register' ) ) {
	interface NSL_Register {
		public function get_items(): Generator;

		public function register();
	}
}
