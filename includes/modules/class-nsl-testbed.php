<?php
/**
 * NSL: Testbed module.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Testbed' ) ) {
	class NSL_Testbed implements NSL_Module {
		use NSL_Hook_Impl;
		use NSL_Template_Impl;

		public function __construct() {
			$this
//				->add_filter( 'the_content', 'testbed_content_google' )
//				->add_filter( 'the_content', 'testbed_content_facebook' )
//				->add_filter( 'the_content', 'testbed_content_naver' )
//				->add_filter( 'the_content', 'testbed_content_kakao' )
				->add_filter( 'the_content', 'testbed_content_payco' )
			;
		}

		public function testbed_content_google( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = NSL_GOOGLE_CLIENT_ID;
				$client_secret = NSL_GOOGLE_CLIENT_SECRET;
				$redirect_uri  = 'https://naran.dev.site/nsl-testbed/';

				if ( ! isset( $_GET['code'] ) ) {
					$url = add_query_arg(
						urlencode_deep(
							[
								'client_id'     => $client_id,
								'redirect_uri'  => $redirect_uri,
								'response_type' => 'code',
								'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
								'access_type'   => 'online',
								'state'         => 'nsl=test&time=' . time(),
							]
						),
						'https://accounts.google.com/o/oauth2/v2/auth'
					);

					$content = '<p><a href="' . esc_url( $url ) . '">Google OAuth 2.0</a></p>';
				} else {
					$r = wp_remote_post(
						'https://oauth2.googleapis.com/token',
						[
							'body' => [
								'client_id'     => $client_id,
								'client_secret' => $client_secret,
								'code'          => $_GET['code'],
								'grant_type'    => 'authorization_code',
								'redirect_uri'  => $redirect_uri,
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

					$content = '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
					           '<p><a href="' . $url . '">Go back</a></p>';
				}
			}

			return $content;
		}

		public function testbed_content_facebook( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = NSL_FACEBOOK_CLIENT_ID;
				$client_secret = NSL_FACEBOOK_CLIENT_SECRET;
				$redirect_uri  = 'https://naran.dev.site/nsl-testbed/';

				if ( ! isset( $_GET['code'] ) ) {
					$url = add_query_arg(
						urlencode_deep(
							[
								'client_id'     => $client_id,
								'redirect_uri'  => $redirect_uri,
								'response_type' => 'code',
								'scope'         => 'public_profile,email',
								'state'         => 'nsl=test&time=' . time(),
							]
						),
						'https://www.facebook.com/v12.0/dialog/oauth'
					);

					$content = '<p><a href="' . esc_url( $url ) . '">Facebook OAuth 2.0</a></p>';
				} else {
					$r = wp_remote_get(
						add_query_arg(
							urlencode_deep(
								[
									'client_id'     => $client_id,
									'redirect_uri'  => $redirect_uri,
									'client_secret' => $client_secret,
									'code'          => $_GET['code'],
								]
							),
							'https://graph.facebook.com/v12.0/oauth/access_token',
						)
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$access_token = $body->access_token;

					$r = wp_remote_get(
						add_query_arg(
							urlencode_deep(
								[
									'fields'       => 'id,email,picture,name,first_name,last_name',
									'access_token' => $access_token,
								]
							),
							'https://graph.facebook.com/me',
						)
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$uri = $_SERVER['REQUEST_URI'];
					$url = esc_url( substr( $uri, 0, strpos( $uri, '?' ) ) );

					$content = '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
					           '<p><a href="' . $url . '">Go back</a></p>';
				}
			}

			return $content;
		}

		public function testbed_content_naver( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = NSL_NAVER_CLIENT_ID;
				$client_secret = NSL_NAVER_CLIENT_SECRET;
				$redirect_uri  = 'https://naran.dev.site/nsl-testbed/';

				if ( ! isset( $_GET['code'] ) ) {
					$url = add_query_arg(
						urlencode_deep(
							[
								'response_type' => 'code',
								'client_id'     => $client_id,
								'redirect_uri'  => $redirect_uri,
								'state'         => 'nsl',
							]
						),
						'https://nid.naver.com/oauth2.0/authorize'
					);

					$content = '<p><a href="' . esc_url( $url ) . '">Naver OAuth 2.0</a></p>';
				} else {
					$r = wp_remote_post(
						'https://nid.naver.com/oauth2.0/token',
						[
							'body' => [
								'grant_type'    => 'authorization_code',
								'client_id'     => $client_id,
								'client_secret' => $client_secret,
								'code'          => $_GET['code'],
								'state'         => 'nsl',
							]
						]
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$access_token = $body->access_token;

					$r = wp_remote_get(
						'https://openapi.naver.com/v1/nid/me',
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

					$delete_url = add_query_arg(
						urlencode_deep(
							[
								'grant_type'       => 'delete',
								'client_id'        => $client_id,
								'client_secret'    => $client_secret,
								'access_token'     => $access_token,
								'service_provider' => 'NAVER',
							]
						),
						'https://nid.naver.com/oauth2.0/token',
					);

					$content = '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
					           '<p><a href="' . esc_url( $delete_url ) . '">Delete Token</a></p>' .
					           '<p><a href="' . $url . '">Go back</a></p>';
				}
			}

			return $content;
		}

		public function testbed_content_kakao( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = NSL_KAKAO_CLIENT_ID;
				$client_secret = NSL_KAKAO_CLIENT_SECRET;
				$redirect_uri  = 'https://naran.dev.site/nsl-testbed/';

				if ( ! isset( $_GET['code'] ) ) {
					$url = add_query_arg(
						urlencode_deep(
							[
								'client_id'     => $client_id,
								'redirect_uri'  => $redirect_uri,
								'response_type' => 'code',
								'state'         => 'nsl',
							]
						),
						'https://kauth.kakao.com/oauth/authorize'
					);

					$content = '<p><a href="' . esc_url( $url ) . '">Kakao OAuth 2.0</a></p>';
				} else {
					$r = wp_remote_post(
						'https://kauth.kakao.com/oauth/token',
						[
							'body' => [
								'grant_type'    => 'authorization_code',
								'client_id'     => $client_id,
								'redirect_uri'  => $redirect_uri,
								'code'          => $_GET['code'],
								'client_secret' => $client_secret,
							]
						]
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$access_token = $body->access_token;

					$r = wp_remote_get(
						'https://kapi.kakao.com//v2/user/me',
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

					$content = '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
					           '<p><a href="' . $url . '">Go back</a></p>';
				}
			}

			return $content;
		}

		public function testbed_content_payco( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = NSL_PAYCO_CLIENT_ID;
				$client_secret = NSL_PAYCO_CLIENT_SECRET;
				$redirect_uri  = 'https://naran.dev.site/nsl-testbed/';

				if ( ! isset( $_GET['code'] ) ) {
					$url = add_query_arg(
						urlencode_deep(
							[
								'response_type'       => 'code',
								'client_id'           => $client_id,
								'redirect_uri'        => $redirect_uri,
								'state'               => 'nsl',
								'serviceProviderCode' => 'FRIENDS',
								'viewType'            => 'mobile_app',
								'userLocale'          => 'ko_KR',
							]
						),
						'https://id.payco.com/oauth2.0/authorize'
					);

					$content = '<p><a href="' . esc_url( $url ) . '">Payco OAuth 2.0</a></p>';
				} else {
					$r = wp_remote_post(
						'https://id.payco.com/oauth2.0/token',
						[
							'body' => [
								'grant_type'    => 'authorization_code',
								'client_id'     => $client_id,
								'client_secret' => $client_secret,
								'code'          => $_GET['code'],
								'state'         => 'nsl',
							]
						]
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$access_token = $body->access_token;

					$r = wp_remote_post(
						'https://apis-paycoid.krp.toastoven.net/payco/friends/find_member_v2.json',
						[
							'headers' => [
								'client_id'    => $client_id,
								'access_token' => $access_token
							]
						]
					);

					$body = wp_remote_retrieve_body( $r );
					if ( is_string( $body ) ) {
						$body = json_decode( $body );
					}

					$uri = $_SERVER['REQUEST_URI'];
					$url = esc_url( substr( $uri, 0, strpos( $uri, '?' ) ) );

					$content = '<p><pre>' . wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . '</pre></p>' .
					           '<p><a href="' . $url . '">Go back</a></p>';
				}
			}

			return $content;
		}
	}
}
