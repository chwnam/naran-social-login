<?php
/**
 * NSL: rewrite rule register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Rewrite_Rule' ) ) {
	class NSL_Register_Rewrite_Rule implements NSL_Register {
		use NSL_Hook_Impl;

		private array $bindings = [];

		private array $query_vars = [];

		public function __construct() {
			$this->add_action( 'init', 'register' );
		}

		public function register() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NSL_Reg_Rewrite ) {
					$item->register();
					if ( $item->binding ) {
						$this->bindings[ $item->regex ] = $item->binding;
					}
					if ( $item->query_vars ) {
						$this->query_vars[] = $item->query_vars;
					}
				}
			}

			if ( $this->bindings ) {
				$this->add_action( 'template_redirect', 'handle_binding' );
			}

			$this->query_vars = array_unique( array_filter( array_merge( ...$this->query_vars ) ) );
			if ( $this->query_vars ) {
				$this->add_filter( 'query_vars', 'add_query_vars' );
			}
		}

		public function get_items(): Generator {
			yield new NSL_Reg_Rewrite(
				'^nsl/([^/]+)/?$',
				'index.php?nsl=$matches[1]',
				'top',
				'auth@handle_redirect',
				'nsl'
			);
		}

		public function handle_binding() {
			global $wp;

			$binding = $this->bindings[ $wp->matched_rule ] ?? null;

			if ( $binding ) {
				try {
					$callback = nsl_parse_callback( $binding );
				} catch ( NSL_Callback_Exception $e ) {
					$error = new WP_Error();
					$error->add(
						'nsl_rewrite_rule_error',
						sprintf(
							'Rewrite rule binding `%s` is invalid. Please check your rewrite rule register items.',
							nsl_format_callback( $binding )
						)
					);
					wp_die( $error );
				}
				$callback();
				exit;
			}
		}

		public function add_query_vars( array $query_vars ): array {
			return array_merge( $query_vars, $this->query_vars );
		}
	}
}
