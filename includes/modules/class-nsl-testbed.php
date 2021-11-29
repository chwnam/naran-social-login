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
			$this->add_filter( 'the_content', 'testbed_content' );
		}

		public function testbed_content( string $content ): string {
			if ( is_page( 'nsl-testbed' ) ) {
				$client_id     = '324082830092-u9u8hi0pa1ra344kl6r5uu1n36a6tr9f.apps.googleusercontent.com';
				$client_secret = 'Hz3DAm-PMxa473gkIDd7tfag';
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
	}
}
