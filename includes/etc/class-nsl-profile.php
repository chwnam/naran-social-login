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
	}
}
