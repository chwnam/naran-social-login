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


if ( ! function_exists( 'nsl_auth_url' ) ) {
	/**
	 * Return authorize url by identifier.
	 *
	 * @param string $id Identifier, e.g. facebook, google.
	 *                   Keys of nsl_get_available_services() return array.
	 *
	 * @return string
	 */
	function nsl_get_authorize_url( string $id ): string {
		$id  = sanitize_key( $id );
		$url = trailingslashit( home_url( "nsl/$id" ) );

		return apply_filters( 'nsl_get_authorize_url', $url );
	}
}
