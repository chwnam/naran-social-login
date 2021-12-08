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

		public function get_active_services(): array {
			return (array) $this->get_value( 'active_services' );
		}

		public function get_icon_set(): string {
			return (string) $this->get_value( 'icon_set' );
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
				'enabled'         => false,
				'active_services' => [],
				'icon_set'        => '',
				'credentials'     => [],
			];
		}

		public static function sanitize( $value ): array {
			$defaults  = static::get_defaults();
			$sanitized = static::get_defaults();

			// Sanitize 'enabled'.
			$sanitized['enabled'] = filter_var( $value['enabled'] ?? $defaults['enabled'], FILTER_VALIDATE_BOOLEAN );

			// Sanitize 'active_services'.
			$services = nsl_get_available_services();
			if ( isset( $value['active_services'] ) && is_array( $value['active_services'] ) ) {
				foreach ( $value['active_services'] as $service ) {
					if ( isset( $services[ $service ] ) ) {
						$sanitized['active_services'][] = $service;
					}
				}
			}

			// TODO: Sanitize 'icon_sets'.
			$sanitized['icon_set'] = sanitize_key( $value['icon_set'] ?? '' );

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
