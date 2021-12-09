<?php
/**
 * NSL: Transient session module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Transient_Session' ) ) {
	class NSL_Transient_Session implements NSL_Module, NSL_Session {
		use NSL_Hook_Impl;

		const COOKIE_NAME = 'nsl_session';

		private string $session_id;

		private int $expiration;

		private array $storage;

		private bool $dirty;

		public function __construct() {
			if ( static::verify_cookie() ) {
				$this->session_id = static::get_session_id();
				$this->expiration = static::get_expiration();
				$this->storage    = get_transient( $this->session_id );
				$this->dirty      = false;

				if ( false === $this->storage ) {
					$this->storage = [];
				}
			} else {
				$this->session_id = static::generate_session_id();
				$this->expiration = time() + HOUR_IN_SECONDS;
				$this->storage    = [];
				$this->dirty      = true;

				$this->send_cookie();
			}

			$this->add_action( 'shutdown', 'save' );
		}

		public function has( string $key ): bool {
			return isset( $this->storage[ $key ] );
		}

		public function get( string $key ) {
			return $this->storage[ $key ] ?? null;
		}

		public function set( string $key, $value ) {
			if ( is_null( $value ) ) {
				unset( $this->storage[ $key ] );
			} else {
				$this->storage[ $key ] = $value;
			}
			$this->dirty = true;
		}

		public function remove( string $key ) {
			unset( $this->storage[ $key ] );
			$this->dirty = true;
		}

		public function reset() {
			$this->storage = [];
			$this->dirty   = true;
		}

		public function destroy() {
			$this->reset();

			delete_transient( $this->session_id );

			if ( has_action( 'shutdown', [ $this, 'save' ] ) ) {
				$this->remove_action( 'shutdown', 'save' );
			}

			if ( ! headers_sent() ) {
				setcookie(
					static::COOKIE_NAME,
					' ',
					time() - ( 10 * YEAR_IN_SECONDS ),
					COOKIEPATH,
					COOKIE_DOMAIN,
					is_ssl(),
					true
				);
			}
		}

		public function send_cookie() {
			if ( ! headers_sent() ) {
				setcookie(
					static::COOKIE_NAME,
					$this->generate_cookie_value(),
					$this->expiration,
					COOKIEPATH,
					COOKIE_DOMAIN,
					is_ssl(),
					true
				);
			}
		}

		public function save() {
			if ( $this->dirty ) {
				set_transient( $this->session_id, $this->storage, max( 10, $this->expiration - time() ) );
			}
		}

		protected function generate_cookie_value(): string {
			$session_id = $this->session_id;
			$timestamp  = time();
			$hash       = base64_encode( hash_hmac( self::algo(), "$session_id|$timestamp", AUTH_KEY, true ) );

			return "$session_id|$timestamp|$hash";
		}

		protected static function get_session_id(): string {
			$value = static::parse_cookie();

			return $value ? $value[0] : '';
		}

		protected static function get_expiration(): int {
			$value = static::parse_cookie();

			return $value ? intval( $value[1] ) : 0;
		}

		protected static function verify_cookie(): bool {
			$value = static::parse_cookie();

			if ( $value ) {
				[ $session_id, $timestamp, $expected_hash ] = $value;

				$actual_hash = base64_encode( hash_hmac( self::algo(), "$session_id|$timestamp", AUTH_KEY, true ) );

				return hash_equals( $expected_hash, $actual_hash );
			}

			return false;
		}

		protected static function parse_cookie(): ?array {
			if ( isset( $_COOKIE[ static::COOKIE_NAME ] ) ) {
				$cookie_value = explode( '|', urldecode( $_COOKIE[ static::COOKIE_NAME ] ) );

				return 3 === count( $cookie_value ) ? $cookie_value : null;
			}

			return null;
		}

		private static function generate_session_id(): string {
			return 'nsl_' . wp_generate_password( 32, false );
		}

		protected static function algo(): string {
			return function_exists( 'hash' ) ? 'sha256' : 'sha1';
		}
	}
}