<?php
/**
 * NSL: Functions
 */

/* Skip ABSPATH check for unit testing. */

// Define your functions here.
if ( ! function_exists( 'nsl_get_services' ) ) {
	/**
	 * Get list of social login services.
	 *
	 * @return array
	 */
	function nsl_get_services(): array {
		$services = [
			'facebook' => __( 'Facebook', 'nsl' ),
			'google'   => __( 'Google', 'nsl' ),
			'twitter'  => __( 'Twitter', 'nsl' ),
		];

		return apply_filters( 'nsl_get_services', $services );
	}
}
