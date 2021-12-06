<?php
/**
 * NSL: Google auth
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth_Google' ) ) {
	class NSL_Auth_Google implements NSL_Module {
		public function authorize() {
			if ( isset( $_GET['auth'] ) && '1' === $_GET['auth'] ) {
				$url = $this->get_auth_url();
				wp_redirect( $url );
				exit;
			} elseif ( isset( $_GET['code'] ) ) {
				$r = wp_remote_post(
					'https://oauth2.googleapis.com/token',
					[
						'body' => [
							'client_id'     => $this->get_client_id(),
							'client_secret' => $this->get_client_secret(),
							'code'          => $_GET['code'],
							'grant_type'    => 'authorization_code',
							'redirect_uri'  => static::get_redirect_uri(),
						]
					]
				);

				$body = wp_remote_retrieve_body( $r );
				if ( is_string( $body ) ) {
					$body = json_decode( $body );
				}

				$access_token = $body->access_token;

				$r = wp_remote_get(
					'https://www.googleapis.com/oauth2/v2/userinfo',
					[
						'headers' => [
							'Authorization' => 'Bearer ' . $access_token
						]
					]
				);

				$body = wp_remote_retrieve_body( $r );
				if ( is_string( $body ) ) {
					$body = json_decode( $body );
				}

				$uri = $_SERVER['REQUEST_URI'];
				$url = esc_url( substr( $uri, 0, strpos( $uri, '?' ) ) );

				echo '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
				     '<p><a href="' . $url . '">Go back</a></p>';
			}
		}

		public function get_auth_url(): string {
			return add_query_arg(
				urlencode_deep(
					[
						'client_id'     => $this->get_client_id(),
						'redirect_uri'  => self::get_redirect_uri(),
						'response_type' => 'code',
						'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
						'access_type'   => 'online',
						'state'         => 'nsl=test&time=' . time(),
					]
				),
				'https://accounts.google.com/o/oauth2/v2/auth'
			);
		}

		public function get_client_id(): string {
			return NSL_GOOGLE_CLIENT_ID;
		}

		public function get_client_secret(): string {
			return NSL_GOOGLE_CLIENT_SECRET;
		}

		public static function get_redirect_uri(): string {
			return home_url( '/nsl/google/' );
		}
	}
}
