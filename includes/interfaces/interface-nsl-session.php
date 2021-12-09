<?php
/**
 * NSL: Session interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NSL_Session' ) ) {
	interface NSL_Session {
		public function has( string $key ): bool;

		public function get( string $key );

		public function set( string $key, $value );

		public function remove( string $key );

		public function reset();

		public function destroy();
	}
}