<?php
/**
 * NSL: Default login support module.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Default_Login_Support' ) ) {
	class NSL_Default_Login_Support implements NSL_Module {
		use NSL_Hook_Impl;
		use NSL_Template_Impl;

		public function __construct() {
			$this->add_action( 'login_form', 'login_form' );
		}

		public function login_form() {
			if ( nsl_settings()->is_default_login_support() ) {
				$this->render(
					'default-login-support',
					[]
				);
			}
		}
	}
}