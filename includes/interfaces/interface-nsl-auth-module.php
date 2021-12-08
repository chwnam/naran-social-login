<?php
/**
 * NSL: Auth module interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NSL_Auth_Module' ) ) {
	interface NSL_Auth_Module extends NSL_Module {
		public function authorize();

		public function revoke_token();

		public static function get_identifier(): string;
	}
}