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
		use NSL_Hook_Impl;

		private array $settings;

		public function __construct() {
			if ( did_action( 'init' ) ) {
				$this->load_option();
			} else {
				wp_die( __CLASS__ . ' is initialized incorrectly.' );
			}
		}

		public function load_option() {
			$this->settings = nsl_option()->settings->get_value();
		}

		public function is_enabled(): bool {
			return (bool) $this->get_value( 'enabled' );
		}

		public function get_credentials(): array {
			return (array) $this->get_value( 'credentials' );
		}

		public function get_credential( string $identifier ): array {
			$credentials = $this->get_credentials();
			$default     = [ 'key' => '', 'secret' => '' ];

			if ( isset( $credentials[ $identifier ] ) ) {
				return wp_parse_args( $credentials[ $identifier ], $default );
			} else {
				return $default;
			}
		}

		protected function get_value( string $key ) {
			return $this->settings[ $key ] ?? ( static::get_defaults()[ $key ] ?? null );
		}

		public static function get_defaults(): array {
			return [
				'enabled'     => false,
				'credentials' => [],
			];
		}

		public static function sanitize( $value ): array {
			$defaults  = static::get_defaults();
			$sanitized = static::get_defaults();

			// Sanitize 'enabled'.
			$sanitized['enabled'] = filter_var( $value['enabled'] ?? $defaults['enabled'], FILTER_VALIDATE_BOOLEAN );

			// Sanitize 'credentials'.
			if ( isset( $value['credentials'] ) && is_array( $value['credentials'] ) ) {
				foreach ( $value['credentials'] as $id => $item ) {
					$sanitized['credentials'][ $id ] = [
						'key'    => sanitize_text_field( $item['key'] ?? '' ),
						'secret' => sanitize_text_field( $item['secret'] ?? '' ),
					];
				}
			}

			return $sanitized;
		}
	}
}
