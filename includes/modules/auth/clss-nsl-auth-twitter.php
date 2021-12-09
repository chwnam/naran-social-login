<?php
/**
 * NSL: twitter auth
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Auth_Twitter' ) ) {
	class NSL_Auth_Twitter implements NSL_Auth_Module {
		const BASE_URL = 'https://api.twitter.com';

		protected string $api_key;

		protected string $api_secret;

		protected string $redirect_uri;

		protected string $oauth_token;

		protected string $oauth_token_secret;

		protected string $oauth_nonce;

		protected string $oauth_timestamp;

		public function __construct() {
			$credential = nsl_settings()->get_credential( self::get_identifier() );

			$this->api_key            = $credential['key'] ?? '';
			$this->api_secret         = $credential['secret'] ?? '';
			$this->redirect_uri       = nsl_get_redirect_uri( self::get_identifier() );
			$this->oauth_token        = '';
			$this->oauth_token_secret = '';
			$this->oauth_nonce        = '';
			$this->oauth_timestamp    = '';
		}

		/**
		 * @return NSL_Profile
		 *
		 * @throws Exception
		 */
		public function authorize(): NSL_Profile {
			if ( ! isset( $_GET['oauth_token'] ) ) {
				wp_redirect( $this->flow_1_authorize() );
				exit;
			} else {
				return $this->flow_2_get_token()->get_profile();
			}
		}

		/**
		 * @return void
		 * @throws Exception
		 */
		public function revoke_token() {
			$r = $this->request( $this->get_url_invalidate_token(), 'POST' );
			if ( ! $this->verify( $r, 'access_token' ) ) {
				throw new Exception( 'Invalid invalidate_token response.' );
			}
		}

		/**
		 * @throws Exception
		 */
		protected function flow_1_authorize(): string {
			$r = $this->request( $this->get_url_request_token(), 'POST' );

			if ( $this->verify( $r, 'oauth_callback_confirmed=true&oauth_token&oauth_token_secret' ) ) {
				$this->oauth_token        = $r['oauth_token'];
				$this->oauth_token_secret = $r['oauth_token_secret'];

				return $this->get_url_authorize();
			} else {
				throw new Exception( 'Invalid request_token response.' );
			}
		}

		/**
		 * @throws Exception
		 */
		protected function flow_2_get_token(): self {
			$r = $this->request(
				$this->get_url_access_token(),
				'POST',
				[
					'oauth_token'    => $_GET['oauth_token'] ?? '',
					'oauth_verifier' => $_GET['oauth_verifier'] ?? '',
				]
			);

			if ( $this->verify( $r, 'oauth_token&oauth_token_secret' ) ) {
				$this->oauth_token        = $r['oauth_token'];
				$this->oauth_token_secret = $r['oauth_token_secret'];

				return $this;
			} else {
				throw new Exception( 'Invalid access_token response.' );
			}
		}

		/**
		 * @return NSL_Profile
		 * @throws Exception
		 */
		protected function get_profile(): NSL_Profile {
			$response = $this->request( $this->get_url_verify_credentials() );

			if ( ! $this->verify( $response, 'id&email' ) ) {
				throw new Exception( 'Invalid verify_credentials response.' );
			}

			/**
			 * @var array $response
			 *
			 * @sample
			 * Array
			 * (
			 *   [id] => 1234567890
			 *   [id_str] => 1234567890
			 *   [name] => John Doe
			 *   [screen_name] => ep6tri
			 *   [location] => Seoul, Korea
			 *   [description] =>
			 *   [url] => https://john.co.kr
			 *   [entities] => Array
			 *   (
			 *     [url] => Array
			 *     (
			 *       [urls] => Array
			 *       (
			 *         [0] => Array
			 *         (
			 *           [url] => https://t.co/abcdefg
			 *           [expanded_url] => https://john.co.kr
			 *           [display_url] => john.co.kr
			 *           [indices] => Array
			 *           (
			 *             [0] => 0
			 *             [1] => 23
			 *           )
			 *         )
			 *       )
			 *     )
			 *     [description] => Array
			 *     (
			 *       [urls] => Array
			 *       (
			 *       )
			 *     )
			 *   )
			 *   [protected] =>
			 *   [followers_count] => 7
			 *   [friends_count] => 29
			 *   [listed_count] => 0
			 *   [created_at] => Fri May 10 02:55:31 +0000 2013
			 *   [favourites_count] => 10
			 *   [utc_offset] =>
			 *   [time_zone] =>
			 *   [geo_enabled] =>
			 *   [verified] =>
			 *   [statuses_count] => 100
			 *   [lang] =>
			 *   [contributors_enabled] =>
			 *   [is_translator] =>
			 *   [is_translation_enabled] => 1
			 *   [profile_background_color] => 000000
			 *   [profile_background_image_url] => http://abs.twimg.com/images/themes/theme1/bg.png
			 *   [profile_background_image_url_https] => https://abs.twimg.com/images/themes/theme1/bg.png
			 *   [profile_background_tile] =>
			 *   [profile_image_url] => http://pbs.twimg.com/profile_images/image-url
			 *   [profile_image_url_https] => https://pbs.twimg.com/profile_images/image-url
			 *   [profile_link_color] => FA743E
			 *   [profile_sidebar_border_color] => 000000
			 *   [profile_sidebar_fill_color] => 000000
			 *   [profile_text_color] => 000000
			 *   [profile_use_background_image] =>
			 *   [has_extended_profile] => 1
			 *   [default_profile] =>
			 *   [default_profile_image] =>
			 *   [following] =>
			 *   [follow_request_sent] =>
			 *   [notifications] =>
			 *   [translator_type] => none
			 *   [withheld_in_countries] => Array
			 *   (
			 *   )
			 *   [suspended] =>
			 *   [needs_phone_verification] =>
			 *   [email] => john@email.com
			 * )
			 */
			$profile = new NSL_Profile();

			$profile->service = self::get_identifier();
			$profile->id      = $response['id'] ?? '';
			$profile->email   = $response['email'] ?? '';
			$profile->name    = $response['name'] ?? '';
			$profile->picture = $response['profile_image_url_https'] ?? '';

			return apply_filters( 'nsl_get_profile_twitter', $profile, $response );
		}

		protected function get_oauth_nonce(): string {
			if ( ! $this->oauth_nonce ) {
				$this->oauth_nonce = wp_generate_password( 32, false );
			}

			return $this->oauth_nonce;
		}

		protected function get_oauth_timestamp(): string {
			if ( ! $this->oauth_timestamp ) {
				$this->oauth_timestamp = strval( time() );
			}

			return $this->oauth_timestamp;
		}

		/**
		 * @param string $url
		 * @param string $method
		 * @param array  $data
		 *
		 * @return array
		 * @throws Exception
		 */
		protected function request( string $url, string $method = 'GET', array $data = [] ): array {
			$method = strtoupper( $method );

			$args = [
				'method'     => $method,
				'user-agent' => nsl_get_user_agent(),
			];

			if ( 'GET' === $method ) {
				if ( ! empty( $data ) ) {
					$url = add_query_arg( rawurlencode_deep( $data ), $url );
				}

				$args['headers'] = [
					'Authorization' => $this->build_authorization_header( $url, $method, $data )
				];
			} elseif ( 'POST' === $method ) {
				$args['headers'] = [
					'Authorization' => $this->build_authorization_header( $url, $method, $data ),
					'Content-Type'  => 'application/x-www-form-urlencoded',
				];

				$args['body'] = $data;
			} else {
				throw new Exception(
				/* translators: HTTP method */
					sprintf( __( 'Unsupported HTTP method: %s', 'nsl' ), $method )
				);
			}

			$r = wp_safe_remote_request( $url, $args );

			if ( is_wp_error( $r ) ) {
				throw new Exception( $r->get_error_message() );
			}

			$this->oauth_nonce     = '';
			$this->oauth_timestamp = '';

			return nsl_remote_parse_body( $r );
		}

		protected function build_authorization_header( string $url, string $method, array $data = [] ): string {
			$signature = $this->build_oauth_signature( $url, $method, $data );

			$buffer = [
				'OAuth ',
			];

			$params = [
				'oauth_consumer_key'     => $this->api_key,
				'oauth_nonce'            => $this->get_oauth_nonce(),
				'oauth_signature'        => $signature,
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp'        => $this->get_oauth_timestamp(),
				'oauth_token'            => $this->oauth_token,
				'oauth_version'          => '1.0',
			];

			foreach ( array_filter( $params ) as $key => $value ) {
				$key      = rawurlencode( $key );
				$value    = rawurlencode( $value );
				$buffer[] = "$key=\"$value\"";
			}

			return implode( ', ', $buffer );
		}

		protected function build_oauth_signature( string $url, string $method, array $data = [] ): string {
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

			$params = array_filter(
				array_merge(
					$params,
					$data,
					[
						'oauth_consumer_key'     => $this->api_key,
						'oauth_nonce'            => $this->get_oauth_nonce(),
						'oauth_timestamp'        => $this->get_oauth_timestamp(),
						'oauth_token'            => $this->oauth_token,
						'oauth_signature_method' => 'HMAC-SHA1',
						'oauth_version'          => '1.0',
					]
				)
			);

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

			$sign_key = rawurlencode( $this->api_secret ) . '&' . rawurlencode( $this->oauth_token_secret );

			return base64_encode( hash_hmac( 'sha1', $base_string, $sign_key, true ) );
		}

		protected function verify( array $response, string $condition ): bool {
			return nsl_verify_response( $response, $condition );
		}

		protected function get_url_request_token(): string {
			return add_query_arg(
				'oauth_callback',
				rawurlencode( $this->redirect_uri ),
				static::BASE_URL . '/oauth/request_token'
			);
		}

		protected function get_url_authorize(): string {
			return add_query_arg(
				'oauth_token',
				rawurlencode( $this->oauth_token ),
				self::BASE_URL . '/oauth/authorize'
			);
		}

		protected function get_url_access_token(): string {
			return static::BASE_URL . '/oauth/access_token';
		}

		protected function get_url_verify_credentials(): string {
			return add_query_arg(
				rawurlencode_deep(
					[
						'include_entities' => 'false',
						'skip_status'      => 'true',
						'include_email'    => 'true',
					]
				),
				self::BASE_URL . '/1.1/account/verify_credentials.json'
			);
		}

		protected function get_url_invalidate_token(): string {
			return self::BASE_URL . '/1.1/oauth/invalidate_token';
		}

		public static function get_identifier(): string {
			return 'twitter';
		}
	}
}
