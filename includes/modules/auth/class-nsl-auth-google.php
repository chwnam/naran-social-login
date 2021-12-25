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
		protected NSL_Remote_Request $r;

		protected string $api_key;

		protected string $api_secret;

		protected string $redirect_uri;

		protected string $scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';

		protected string $access_token;

		public function __construct() {
			$credential = nsl_settings()->get_credential( static::get_identifier() );

			$this->r            = new NSL_Remote_Request();
			$this->api_key      = $credential['key'] ?? '';
			$this->api_secret   = $credential['secret'] ?? '';
			$this->redirect_uri = nsl_get_redirect_uri( static::get_identifier() );
			$this->access_token = '';
		}

		/**
		 * @throws Exception
		 */
		public function authorize(): NSL_Profile {
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
			$state = wp_generate_password( 32 );

			nsl_session()->set( 'nsl:google:state', $state );

			return add_query_arg(
				rawurlencode_deep(
					[
						'client_id'     => $this->api_key,
						'redirect_uri'  => $this->redirect_uri,
						'response_type' => 'code',
						'scope'         => $this->scope,
						'access_type'   => 'online',
						'state'         => $state
					]
				),
				'https://accounts.google.com/o/oauth2/v2/auth'
			);
		}

		/**
		 * @throws Exception
		 */
		protected function flow_2_get_token(): self {
			$expected_state = nsl_session()->get( 'nsl:google:state' );
			$actual_state   = $_GET['state'] ?? '';

			if ( $expected_state !== $actual_state ) {
				throw new Exception( 'Invalid state.' );
			}

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
		protected function get_profile(): NSL_Profile {
			$response = $this->request(
				'https://www.googleapis.com/oauth2/v2/userinfo',
				'GET',
				[],
				[ 'headers' => [ 'Authorization' => 'Bearer ' . $this->access_token ] ]
			);

			if ( ! $this->verify( $response, 'id&email' ) ) {
				throw new Exception( 'Invalid /oauth2/v2/userinfo response.' );
			}

			/**
			 * @var array $response
			 *
			 * @sample
			 * Array
			 * (
			 *   [id]             => 1234567890
			 *   [email]          => john@gmail.com
			 *   [verified_email] => 1
			 *   [name]           => John Doe
			 *   [given_name]     => John
			 *   [family_name]    => Doe
			 *   [picture]        => https://lh3.googleusercontent.com/a-/path/to/picture
			 *   [locale]         => ko
			 * )
			 */

			$profile = new NSL_Profile();

			$profile->service    = self::get_identifier();
			$profile->id         = $response['id'] ?? '';
			$profile->email      = $response['email'] ?? '';
			$profile->name       = $response['name'] ?? '';
			$profile->first_name = $response['given_name'] ?? '';
			$profile->last_name  = $response['family_name'] ?? '';
			$profile->picture    = $response['picture'] ?? '';
			$profile->locale     = $response['locale'] ?? '';

			return apply_filters( 'nsl_get_profile_google', $profile, $response );
		}

		/**
		 * @throws Exception
		 */
		protected function request( string $url, string $method = 'GET', array $data = [], array $args = [] ): array {
			return $this->r->request( $url, $method, $data, $args );
		}

		protected function verify( array $response, string $condition ): bool {
			return $this->r->verify_response( $response, $condition );
		}

		protected function generate_state( array $input, $timestamp = null ): string {
			if ( ! $timestamp ) {
				$timestamp = time();
			}

			$input['timestamp'] = $timestamp;

			asort( $input );

			$base_string = build_query( rawurlencode_deep( $input ) );
			$sign_key    = $this->api_secret . '&' . AUTH_KEY;
			$hash        = hash_hmac( 'sha1', $base_string, $sign_key );

			return build_query(
				rawurlencode_deep(
					[
						'timestamp' => $timestamp,
						'signature' => $hash
					]
				)
			);
		}

		public static function get_identifier(): string {
			return 'google';
		}
	}
}
