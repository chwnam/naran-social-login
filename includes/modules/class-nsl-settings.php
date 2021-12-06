<?php
/**
 * NSL: settings
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Settings' ) ) {
	class NSL_Settings implements NSL_Module {
		public static function get_defaults(): array {
			return [];
		}

		public static function sanitize( $value ): array {
			$defaults = static::get_defaults();
			$value    = [];

			return $defaults;
		}
	}
}
