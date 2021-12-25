<?php
/**
 * NSL: User profile object
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Profile' ) ) {
	class NSL_Profile {
		/**
		 * Service idendifier, e.g. google, twitter, facebook, ...
		 *
		 * @var string
		 */
		public string $service = '';

		/**
		 * Unique user identifier in the service.
		 *
		 * @var string
		 */
		public string $id = '';

		/**
		 * Email address
		 *
		 * @var string
		 */
		public string $email = '';

		/**
		 * User name
		 *
		 * @var string
		 */
		public string $name = '';

		/**
		 * First name
		 *
		 * @var string
		 */
		public string $first_name = '';

		/**
		 * Last name
		 *
		 * @var string
		 */
		public string $last_name = '';

		/**
		 * User profile image URL.
		 *
		 * @var string
		 */
		public string $picture = '';


		/**
		 * User locale
		 *
		 * @var string
		 */
		public string $locale = '';

		/**
		 * Extra attributes
		 *
		 * @var array
		 */
		public array $extra_attrs = [];

		public function to_array(): array {
			return get_object_vars( $this );
		}

		public static function from_array( array $array ): NSL_Profile {
			$instance = new static();
			$attrs    = array_keys( get_object_vars( $instance ) );

			foreach ( $attrs as $attr ) {
				if ( isset( $array[ $attr ] ) ) {
					$instance->$attr = $array[ $attr ];
				}
			}

			return $instance;
		}
	}
}
