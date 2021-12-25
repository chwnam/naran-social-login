<?php
/**
 * NSL: auth handler module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth_Handler' ) ) {
	class NSL_Auth_Handler implements NSL_Module {
		use NSL_Hook_Impl;

		public function handle_redirect() {
			try {
				$auth    = $this->get_auth_module( get_query_var( 'nsl' ) );
				$profile = $auth->authorize();

				nsl_session()->set( 'nsl:profile', $profile->to_array() );
				$auth->revoke_token();

				wp_safe_redirect( nsl_user_handler()->get_handler_url() );
			} catch ( Exception $e ) {
				$code = $e->getCode();
				if ( $code ) {
					wp_die( "$code: {$e->getMessage()}" );
				} else {
					wp_die( $e->getMessage() );
				}
			}
		}

		/**
		 * @param string $identifier
		 *
		 * @return NSL_Auth_Module
		 * @throws Exception
		 */
		protected function get_auth_module( string $identifier ): NSL_Auth_Module {
			$class_name = 'NSL_Auth_' . ucfirst( $identifier );

			if ( ! class_exists( $class_name ) ) {
				throw new Exception( "Auth module for '$identifier' does not exist." );
			}

			return new $class_name();
		}
	}
}
