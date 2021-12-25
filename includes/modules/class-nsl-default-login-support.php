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
			global $pagenow;

			if ( 'wp-login.php' === $pagenow ) {
				$this->add_action( 'init', 'initialize', nsl()->get_priority() + 1 );
			}
		}

		public function initialize() {
			if ( nsl_settings()->is_enabled() && nsl_settings()->is_default_login_support() ) {
				$this
					->add_action( 'login_init', 'prepare_scripts' )
					->add_action( 'login_form', 'login_form' )
				;
			}
		}

		public function prepare_scripts() {
			$this->enqueue_style( 'nsl-default-login-support' );
		}

		public function login_form() {
			if ( nsl_settings()->is_enabled() && nsl_settings()->is_default_login_support() ) {
				$all_avail = nsl_get_available_services();
				$all_icons = nsl_get_icon_sets();
				$uris      = nsl_get_redirect_uris();

				$services = nsl_settings()->get_active_services();
				$icon_set = nsl_settings()->get_icon_set();

				$this->render(
					'default-login-support',
					[
						'all_avail' => $all_avail,
						'services'  => $services,
						'uris'      => $uris,
						'icons'     => $all_icons[ $icon_set ] ?? [],
					]
				);
			}
		}
	}
}
