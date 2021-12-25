<?php
/**
 * NSL: Auth API call
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Remote_Request' ) ) {
	class NSL_Remote_Request implements NSL_Module {
		/**
		 * @throws Exception
		 */
		public function request( string $url, string $method = 'GET', array $data = [], array $args = [] ): array {
			$method = strtoupper( $method );

			$args = wp_parse_args(
				$args,
				[
					'method'     => $method,
					'user-agent' => static::get_user_agent(),
				]
			);

			if ( 'GET' === $method ) {
				if ( ! empty( $data ) ) {
					$url = add_query_arg( rawurlencode_deep( $data ), $url );
				}
			} elseif ( 'POST' === $method ) {
				$args['body'] = $data;
			} else {
				throw new Exception(
				/* translators: HTTP method */
					sprintf( __( 'Unsupported HTTP method: %s', 'nsl' ), $method )
				);
			}

			return wp_safe_remote_request( $url, $args );
		}

		/**
		 * @throws Exception
		 */
		public function parse_response( array $response ): array {
			$status = wp_remote_retrieve_response_code( $response );

			if ( 200 != $status ) {
				throw new Exception(
					wp_remote_retrieve_response_message( $response ),
					$status
				);
			}

			$content_type = wp_remote_retrieve_header( $response, 'Content-Type' );
			$content      = wp_remote_retrieve_body( $response );

			if ( 0 === strpos( $content_type, 'application/json' ) ) {
				$content = json_decode( $content, true );
			} else {
				parse_str( $content, $content );
			}

			if ( ! $content ) {
				$content = [];
			}

			return $content;
		}

		public function verify_response( array $response, string $condition ): bool {
			$condition = wp_parse_args( $condition );
			$verified  = true;

			foreach ( $condition as $k => $v ) {
				if (
					( $k && $v && ( ! isset( $response[ $k ] ) || $v != $response[ $k ] ) ) ||
					( $k && ! $v && ! isset( $response[ $k ] ) )
				) {
					$verified = false;
					break;
				}
			}

			return $verified;
		}

		protected static function get_user_agent(): string {
			$wp_version  = get_bloginfo( 'version' );
			$nsl_version = nsl()->get_version();
			$url         = get_bloginfo( 'url' );

			return apply_filters( 'nsl_get_user_agent', "WordPress/$wp_version, Naran Social Login/$nsl_version; $url" );
		}
	}
}