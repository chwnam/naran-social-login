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


if ( ! function_exists( 'nsl_auth_handler' ) ) {
	/**
	 * Return auth handler module.
	 *
	 * Alias of nsl()->auth_handler.
	 *
	 * @return NSL_Auth_Handler
	 */
	function nsl_auth_handler(): NSL_Auth_Handler {
		return nsl()->auth_handler;
	}
}


if ( ! function_exists( 'nsl_user_handler' ) ) {
	/**
	 * Return auth handler module.
	 *
	 * Alias of nsl()->auth_handler.
	 *
	 * @return NSL_User_Handler
	 */
	function nsl_user_handler(): NSL_User_Handler {
		return nsl()->user_handler;
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


if ( ! function_exists( 'nsl_get_icon_sets' ) ) {
	/**
	 * Get list of icon sets.
	 *
	 * @return array
	 */
	function nsl_get_icon_sets(): array {
		$url = plugin_dir_url( nsl()->get_main_file() ) . 'assets/img/';

		$icon_sets = [
			'default' => [
				'facebook' => $url . 'facebook.png',
				'google'   => $url . 'google.png',
				'kakao'    => $url . 'kakao.png',
				'naver'    => $url . 'naver.png',
				'payco'    => $url . 'payco.png',
				'twitter'  => $url . 'twitter.png',
			],
		];

		return apply_filters( 'nsl_get_icon_sets', $icon_sets );
	}
}


if ( ! function_exists( 'nsl_get_doc_url' ) ) {
	/**
	 * Get redirect URI by identifier.
	 *
	 * @param string  $id Identifier.
	 * @param ?string $locale
	 *
	 * @return string
	 */
	function nsl_get_doc_url( string $id, ?string $locale = null ): string {
		if ( is_null( $locale ) ) {
			$locale = get_user_locale();
		}

		if ( 'ko_KR' === $locale ) {
			return admin_url( "options-general.php?page=nsl&doc=" . sanitize_key( $id ) );
		}

		return '';
	}
}

