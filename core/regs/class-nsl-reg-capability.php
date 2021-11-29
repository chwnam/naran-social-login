<?php
/**
 * NSL: Capability reg.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Reg_Capability' ) ) {
	class NSL_Reg_Capability implements NSL_Reg {
		public string $role;

		public array $capabilities;

		public function __construct( string $role, array $capabilities ) {
			$this->role         = $role;
			$this->capabilities = $capabilities;
		}

		public function register( $dispatch = null ) {
			$role = get_role( $this->role );

			if ( $role ) {
				foreach ( $this->capabilities as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

		public function unregister() {
			$role = get_role( $this->role );

			if ( $role ) {
				foreach ( $this->capabilities as $capability ) {
					$role->remove_cap( $capability );
				}
			}
		}
	}
}
