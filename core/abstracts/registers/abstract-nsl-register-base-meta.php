<?php
/**
 * NSL: Register meta base
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Base_Meta' ) ) {
	abstract class NSL_Register_Base_Meta implements NSL_Register {
		use NSL_Hook_Impl;

		/**
		 * @var array Key: alias
		 *            Value: array of size 3.
		 *            - 0: object type
		 *            - 1: object subtype
		 *            - 2: key.
		 */
		private array $fields = [];

		public function __construct() {
			$this->add_action( 'init', 'register' );
		}

		public function __get( string $alias ): ?NSL_Reg_Meta {
			if ( isset( $this->fields[ $alias ] ) ) {
				return NSL_Reg_Meta::factory( ...$this->fields[ $alias ] );
			} else {
				return null;
			}
		}

		public function register() {
			foreach ( $this->get_items() as $idx => $item ) {
				if ( $item instanceof NSL_Reg_Meta ) {
					$item->register();

					$alias = is_int( $idx ) ? $item->get_key() : $idx;

					$this->fields[ $alias ] = [ $item->get_object_type(), $item->object_subtype, $item->get_key() ];
				}
			}
		}
	}
}
