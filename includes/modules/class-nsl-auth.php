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

			try {
				$profile = $auth->authorize();
				echo '<h1>' . $auth::get_identifier() . '</h1>';
				echo '<div><pre>' . print_r( $profile, 1 ) . '</pre></div>';

				$auth->revoke_token();
				echo '<div>Successfully token revoked.</div>';

				nsl_session()->destroy();
				echo '<div>Session destroyed.</div>';
			} catch ( Exception $e ) {
				$code = $e->getCode();
				if ( $code ) {
					wp_die( "$code: {$e->getMessage()}" );
				} else {
					wp_die( $e->getMessage() );
				}
			}
		}

		protected function get_auth_backend( string $service_id ) {
			switch ( $service_id ) {
				case 'google':
					return new NSL_Auth_Google();

				case 'twitter':
					return new NSL_Auth_Twitter();
			}
		}
	}
}
