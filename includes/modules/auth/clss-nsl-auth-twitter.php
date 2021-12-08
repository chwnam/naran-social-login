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
		const BASE_URL = 'https://api.twitter.com';

		private array $credential;

		private string $redirect_uri;

		private string $oauth_token;

		private string $oauth_token_secret;

		private string $oauth_nonce;

		private string $oauth_timestamp;

		public function __construct() {
			$this->credential         = nsl_settings()->get_credential( self::get_identifier() );
			$this->redirect_uri       = nsl_get_redirect_uri( self::get_identifier() );
			$this->oauth_token        = '';
			$this->oauth_token_secret = '';
			$this->oauth_nonce        = '';
			$this->oauth_timestamp    = '';
		}

		public function authorize() {
			if ( ! isset( $_GET['oauth_token'] ) ) {
				$url = add_query_arg(
					'oauth_callback',
					rawurlencode( $this->get_redirect_uri() ),
					self::BASE_URL . '/oauth/request_token'
				);

				$response = $this->send_request( $url, 'POST' );
				if ( is_string( $response ) ) {
					parse_str( $response, $obj );
					if (
						'true' === ( $obj['oauth_callback_confirmed'] ?? 'false' ) &&
						isset( $obj['oauth_token'], $obj['oauth_token_secret'] )
					) {
						$this->set_oauth_token( $obj['oauth_token'], $obj['oauth_token_secret'] );
					}
				}

				$redirect_url = add_query_arg(
					'oauth_token',
					rawurlencode( $this->get_oauth_token() ),
					self::BASE_URL . '/oauth/authorize'
				);

				wp_redirect( $redirect_url );
			} else {
				$response = $this->send_request(
					self::BASE_URL . '/oauth/access_token',
					'POST',
					[
						'oauth_consumer_key' => $this->get_api_key(),
						'oauth_token'        => $_GET['oauth_token'],
						'oauth_verifier'     => $_GET['oauth_verifier']
					]
				);

				if ( is_string( $response ) ) {
					parse_str( $response, $obj );
					if ( isset( $obj['oauth_token'], $obj['oauth_token_secret'] ) ) {
						$this->set_oauth_token( $obj['oauth_token'], $obj['oauth_token_secret'] );
						$user_id     = $obj['user_id'];
						$screen_name = $obj['screen_name'];

						$response = $this->send_request(
							self::BASE_URL . '/1.1/users/show.json',
							'GET',
							[
								'user_id'     => $user_id,
								'screen_name' => $screen_name,
							]
						);

						var_dump( $response );
					}
				}
			}
		}

		public function set_credential( string $api_key, string $api_secret ) {
			$this->credential = [
				'key'    => $api_key,
				'secret' => $api_secret,
			];
		}

		public function get_api_key(): string {
			return $this->credential['key'] ?? '';
		}

		public function get_api_secret() {
			return $this->credential['secret'] ?? '';
		}

		public function get_redirect_uri(): string {
			return $this->redirect_uri;
		}

		public function set_redirect_url( string $uri ) {
			$this->redirect_uri = $uri;
		}

		public function set_oauth_token( string $token, string $token_secret ) {
			$this->oauth_token        = $token;
			$this->oauth_token_secret = $token_secret;
		}

		public function get_oauth_token(): string {
			return $this->oauth_token;
		}

		public function get_oauth_token_secret(): string {
			return $this->oauth_token_secret;
		}

		public function get_oauth_nonce(): string {
			if ( ! $this->oauth_nonce ) {
				$this->set_oauth_nonce( wp_generate_password( 32, true ) );
			}

			return $this->oauth_nonce;
		}

		public function set_oauth_nonce( string $oauth_nonce ) {
			$this->oauth_nonce = $oauth_nonce;
		}

		public function get_oauth_timestamp(): string {
			if ( ! $this->oauth_timestamp ) {
				$this->set_oauth_timestamp( strval( time() ) );
			}

			return $this->oauth_timestamp;
		}

		public function set_oauth_timestamp( string $oauth_timestamp ) {
			$this->oauth_timestamp = $oauth_timestamp;
		}

		public function send_request( string $url, string $method = 'GET', array $data = [] ) {
			$r = null;

			$method = strtoupper( $method );

			if ( 'GET' === $method ) {
				if ( ! empty( $data ) ) {
					$url = add_query_arg( rawurlencode_deep( $data ), $url );
				}
				$r = wp_remote_get(
					$url,
					[
						'headers' => [
							'Authorization' => $this->build_authorization_header( $url, $method, $data ),
						]
					]

				);
			} elseif ( 'POST' === $method ) {
				$r = wp_remote_post(
					$url,
					[
						'headers' => [
							'Authorization' => $this->build_authorization_header( $url, $method, $data ),
							'Content-Type'  => 'application/x-www-form-urlencoded',
						],
						'body'    => $data,
					]
				);
			} else {
				wp_die( 'Wrong method' );
			}

			if ( is_wp_error( $r ) ) {
				wp_die( $r );
			}

			$this->set_oauth_nonce( '' );
			$this->set_oauth_timestamp( '' );

			$status = wp_remote_retrieve_response_code( $r );
			if ( 200 == $status ) {
				return wp_remote_retrieve_body( $r );
			}

			wp_die( wp_remote_retrieve_body( $r ) );
		}

		public function build_authorization_header( string $url, string $method, array $data = [] ): string {
			$signature = $this->build_oauth_signature( $url, $method, $data );

			$buffer = [
				'OAuth ',
			];

			$params = [
				'oauth_callback'         => $this->get_redirect_uri(), // TODO: redirect uri 매번 나오지 않음.
				'oauth_consumer_key'     => $this->get_api_key(),
				'oauth_nonce'            => $this->get_oauth_nonce(),
				'oauth_signature'        => $signature,
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp'        => $this->get_oauth_timestamp(),
				'oauth_version'          => '1.0',
			];

			foreach ( $params as $key => $value ) {
				$key      = rawurlencode( $key );
				$value    = rawurlencode( $value );
				$buffer[] = "$key=\"$value\"";
			}

			return implode( ', ', $buffer );
		}

		public function build_oauth_signature( string $url, string $method, array $data = [] ): string {
			$params = [];

			$p = strpos( $url, '?' );
			if ( false !== $p ) {
				$endpoint = substr( $url, 0, $p );
				$query    = substr( $url, $p + 1 );
			} else {
				$endpoint = $url;
				$query    = '';
			}

			if ( $query ) {
				parse_str( $query, $params );
			}

			$params = array_merge(
				$params,
				$data,
				[
					'oauth_consumer_key'     => $this->get_api_key(),
					'oauth_nonce'            => $this->get_oauth_nonce(),
					'oauth_timestamp'        => $this->get_oauth_timestamp(),
					'oauth_signature_method' => 'HMAC-SHA1',
					'oauth_version'          => '1.0',
				]
			);

			if ( $this->oauth_token ) {
				$params['oauth_token'] = $this->oauth_token;
			}

			ksort( $params );

			$base_string = implode(
				'&',
				rawurlencode_deep(
					[
						strtoupper( $method ),
						$endpoint,
						build_query( rawurlencode_deep( $params ) )
					]
				)
			);

			$sign_key = rawurlencode( $this->get_api_secret() ) . '&' . rawurlencode( $this->get_oauth_token_secret() );

			return base64_encode( hash_hmac( 'sha1', $base_string, $sign_key, true ) );
		}

		public static function get_identifier(): string {
			return 'twitter';
		}
	}
}
