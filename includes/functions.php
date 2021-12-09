<?php
/**
 * NSL: Functions
 */

/* Skip ABSPATH check for unit testing. */

if ( ! function_exists( 'nsl_settings' ) ) {
	/**
	 * Return setitngs module.
	 *
	 * Alias of nsl()->settings.
	 *
	 * @return NSL_Settings
	 */
	function nsl_settings(): NSL_Settings {
		return nsl()->settings;
	}
}


if ( ! function_exists( 'nsl_session' ) ) {
	/**
	 * Return setitngs module.
	 *
	 * Alias of nsl()->session.
	 *
	 * @return NSL_Transient_Session
	 */
	function nsl_session(): NSL_Transient_Session {
		return nsl()->session;
	}
}


if ( ! function_exists( 'nsl_get_available_services' ) ) {
	/**
	 * Get list of social login services.
	 *
	 * @return array
	 */
	function nsl_get_available_services(): array {
		$services = [
			'facebook' => __( 'Facebook', 'nsl' ),
			'google'   => __( 'Google', 'nsl' ),
			'kakao'    => __( 'Kakao', 'nsl' ),
			'naver'    => __( 'Naver', 'nsl' ),
			'payco'    => __( 'Payco', 'nsl' ),
			'twitter'  => __( 'Twitter', 'nsl' ),
		];

		return apply_filters( 'nsl_get_available_services', $services );
	}
}


if ( ! function_exists( 'nsl_get_devlopers_page_urls' ) ) {
	/**
	 * Get list of social services' developers page urls.
	 *
	 * @return array
	 */
	function nsl_get_devlopers_page_urls(): array {
		$services = [
			'facebook' => 'https://developers.facebook.com',
			'google'   => 'https://console.cloud.google.com',
			'kakao'    => 'https://developers.kakao.com',
			'naver'    => 'https://developers.naver.com',
			'payco'    => 'https://developers.payco.com',
			'twitter'  => 'https://developer.twitter.com',
		];

		return apply_filters( 'nsl_get_devlopers_page_urls', $services );
	}
}


if ( ! function_exists( 'nsl_get_developers_page_titles' ) ) {
	/**
	 * Get list of social services' developers page urls.
	 *
	 * @return array
	 */
	function nsl_get_developers_page_titles(): array {
		$dev_titles = [
			'facebook' => __( 'Mata for developers', 'nsl' ),
			'google'   => __( 'Google cloud platform', 'nsl' ),
			'kakao'    => __( 'Kakao develeopers', 'nsl' ),
			'naver'    => __( 'Naver developers', 'nsl' ),
			'payco'    => __( 'Payco developers', 'nsl' ),
			'twitter'  => __( 'Twitter developer platform', 'nsl' ),
		];

		return apply_filters( 'nsl_get_dev_titles', $dev_titles );
	}
}


if ( ! function_exists( 'nsl_get_redirect_uris' ) ) {
	/**
	 * Get list of redirect URIs.
	 *
	 * @return array
	 */
	function nsl_get_redirect_uris(): array {
		$uri = trailingslashit( home_url( 'nsl' ) );

		$uris = [
			'facebook' => $uri . 'facebook/',
			'google'   => $uri . 'google/',
			'kakao'    => $uri . 'kakao/',
			'naver'    => $uri . 'naver/',
			'payco'    => $uri . 'payco/',
			'twitter'  => $uri . 'twitter/',
		];

		return apply_filters( 'nsl_get_redirect_uris', $uris );
	}
}


if ( ! function_exists( 'nsl_get_redirect_uri' ) ) {
	/**
	 * Get redirect URI by identifier.
	 *
	 * @param string $id Identifier.
	 *
	 * @return string
	 */
	function nsl_get_redirect_uri( string $id ): string {
		$id  = sanitize_key( $id );
		$uri = trailingslashit( home_url( "nsl/$id" ) );

		return apply_filters( 'nsl_get_redirect_uri', $uri );
	}
}


if ( ! function_exists( 'nsl_get_user_agent' ) ) {
	function nsl_get_user_agent(): string {
		$wp_version  = get_bloginfo( 'version' );
		$nsl_version = nsl()->get_version();
		$url         = get_bloginfo( 'url' );

		return apply_filters( 'nsl_get_user_agent', "WordPress/$wp_version, Naran Social Login/$nsl_version; $url" );
	}
}

if ( ! function_exists( 'nsl_remote_request' ) ) {
	/**
	 * @throws Exception
	 */
	function nsl_remote_request( string $url, string $method = 'GET', array $data = [], array $args = [] ): array {
		$method = strtoupper( $method );

		$args = wp_parse_args(
			$args,
			[
				'method'     => $method,
				'user-agent' => nsl_get_user_agent(),
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

		$r = wp_safe_remote_request( $url, $args );

		if ( is_wp_error( $r ) ) {
			throw new Exception( $r->get_error_message() );
		}

		return nsl_remote_parse_body( $r );
	}
}

if ( ! function_exists( 'nsl_remote_parse_body' ) ) {
	/**
	 * @param array $response
	 *
	 * @return array
	 * @throws Exception
	 */
	function nsl_remote_parse_body( array $response ): array {
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
}


if ( ! function_exists( 'nsl_verify_response' ) ) {
	function nsl_verify_response( array $response, string $condition ): bool {
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
}
