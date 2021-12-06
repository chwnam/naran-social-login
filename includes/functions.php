<?php
/**
 * NSL: Functions
 */

/* Skip ABSPATH check for unit testing. */

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
			'facebook' => '',
			'google'   => '',
			'twitter'  => '',
		];

		return apply_filters( 'nsl_get_devlopers_page_urls', $services );
	}
}
