<?php
/**
 * NSL: Custom post type reg.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Reg_Post_Type' ) ) {
	class NSL_Reg_Post_Type implements NSL_Reg {
		public string $post_type;

		public array $args;

		public function __construct( string $post_type, array $args ) {
			$this->post_type = $post_type;
			$this->args      = $args;
		}

		public function register( $dispatch = null ) {
			if ( ! post_type_exists( $this->post_type ) ) {
				$return = register_post_type( $this->post_type, $this->args );
				if ( is_wp_error( $return ) ) {
					wp_die( $return );
				}
			}
		}
	}
}
