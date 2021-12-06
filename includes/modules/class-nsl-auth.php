<?php
/**
 * NSL: auth module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth' ) ) {
	class NSL_Auth implements NSL_Module {
		use NSL_Hook_Impl;

		public function __construct() {
		}

		public function handle_redirect() {
			$auth = $this->get_auth_backend( get_query_var( 'nsl' ) );
			if ( ! $auth ) {
				wp_die( 'Unsupported service ID: ' . get_query_var( 'nsl' ) );
			}

			$auth->authorize();
		}

		protected function get_auth_backend( string $service_id ) {
			return new NSL_Auth_Google();
		}
	}
}
