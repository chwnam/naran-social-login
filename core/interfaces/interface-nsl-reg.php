<?php
/**
 * NSL: Registerable interface
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NSL_Reg' ) ) {
	interface NSL_Reg {
		public function register( $dispatch = null );
	}
}
