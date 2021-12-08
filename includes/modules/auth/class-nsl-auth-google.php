<?php
/**
 * NSL: Google auth
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth_Google' ) ) {
	class NSL_Auth_Google implements NSL_Auth_Module {
		protected string $api_key;

		protected string $api_secret;

		protected string $redirect_uri;

		protected string $scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';

		protected string $access_token;

		public function __construct() {
			$credential = nsl_settings()->get_credential( static::get_identifier() );

			$this->api_key      = $credential['key'] ?? '';
			$this->api_secret   = $credential['secret'] ?? '';
			$this->redirect_uri = nsl_get_redirect_uri( static::get_identifier() );
			$this->access_token = '';
		}

		/**
		 * @throws Exception
		 */
		public function authorize() {
			if ( ! isset( $_GET['code'] ) ) {
				wp_redirect( $this->flow_1_authorize() );
				exit;
			} else {
				return $this->flow_2_get_token()->get_profile();
			}
		}

		/**
		 * @throws Exception
		 */
		public function revoke_token() {
			$this->request(
				'https://oauth2.googleapis.com/revoke',
				'POST',
				[ 'token' => $this->access_token ]
			);
		}

		protected function flow_1_authorize(): string {
			return add_query_arg(
				rawurlencode_deep(
					[
						'client_id'     => $this->api_key,
						'redirect_uri'  => $this->redirect_uri,
						'response_type' => 'code',
						'scope'         => $this->scope,
						'access_type'   => 'online',
						'state'         => 'nsl=test&time=' . time(), // TODO: state
					]
				),
				'https://accounts.google.com/o/oauth2/v2/auth'
			);
		}

		/**
		 * @throws Exception
		 */
		protected function flow_2_get_token(): self {
			$response = $this->request(
				'https://oauth2.googleapis.com/token',
				'POST',
				[
					'client_id'     => $this->api_key,
					'client_secret' => $this->api_secret,
					'code'          => $_GET['code'],
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $this->redirect_uri,
				]
			);

			if ( ! $this->verify( $response, 'access_token' ) ) {
				throw new Exception( 'Invalid token request.' );
			}

			$this->access_token = $response['access_token'];

			return $this;
		}

		/**
		 * @throws Exception
		 */
		protected function get_profile(): array {
			$response = $this->request(
				'https://www.googleapis.com/oauth2/v2/userinfo',
				'GET',
				[],
				[ 'headers' => [ 'Authorization' => 'Bearer ' . $this->access_token ] ]
			);

			if ( ! $this->verify( $response, 'id&email' ) ) {
				throw new Exception( 'Invalid /oauth2/v2/userinfo response.' );
			}

			return $response;
		}

		/**
		 * @throws Exception
		 */
		protected function request( string $url, string $method = 'GET', array $data = [], array $args = [] ): array {
			return nsl_remote_request( $url, $method, $data, $args );
		}

		protected function verify( array $response, string $condition ): bool {
			return nsl_verify_response( $response, $condition );
		}

		public static function get_identifier(): string {
			return 'google';
		}
	}
}
