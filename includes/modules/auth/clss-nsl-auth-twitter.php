<?php
/**
 * NSL: twitter auth
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth_Twitter' ) ) {
	class NSL_Auth_Twitter implements NSL_Module {
		public function authorize() {
		}

		public function get_client_id(){
			return NSL_TWITTER_CLIENT_ID;
		}

		public function get_client_secret() {
			return NSL_TWITTER_CLIENT_SECRET;
		}
	}
}
