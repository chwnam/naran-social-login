<?php
/**
 * NSL: menu register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Menu' ) ) {
	class NSL_Register_Menu implements NSL_Register {
		use NSL_Hook_Impl;

		private array $callbacks = [];

		public function __construct() {
			$this->add_action( 'admin_menu', 'register' );
		}

		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Menu || $item instanceof NSL_Reg_Submenu ) {
					$page_hook = $item->register( [ $this, 'dispatch' ] );

					$this->callbacks[ $page_hook ] = $item->callback;
				}
			}
		}

		public function get_items(): Generator {
			yield new NSL_Reg_Submenu(
				'options-general.php',
				'Naran Social Login',
				'Naran Social Login',
				'manage_options',
				'nsl',
				'admins.settings_page@render_page'
			);
		}

		public function dispatch() {
			global $page_hook;

			try {
				if ( $page_hook && isset( $this->callbacks [ $page_hook ] ) ) {
					$callback = nsl_parse_callback( $this->callbacks [ $page_hook ] );
					if ( is_callable( $callback ) ) {
						call_user_func( $callback );
					}
				}
			} catch ( NSL_Callback_Exception $e ) {
				wp_die( $e->getMessage() );
			}
		}
	}
}
