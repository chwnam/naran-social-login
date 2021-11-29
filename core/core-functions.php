<?php
/**
 * NSL: functions.php
 */

/* Skip ABSPATH check for unit testing. */

if ( ! function_exists( 'nsl' ) ) {
	/**
	 * NSL_Main alias.
	 *
	 * @return NSL_Main
	 */
	function nsl(): NSL_Main {
		return NSL_Main::get_instance();
	}
}


if ( ! function_exists( 'nsl_parse_module' ) ) {
	/**
	 * Retrieve submodule by given string notaion.
	 *
	 * @param string $module_notation
	 *
	 * @return object|false;
	 */
	function nsl_parse_module( string $module_notation ) {
		return nsl()->get_module_by_notation( $module_notation );
	}
}


if ( ! function_exists( 'nsl_parse_callback' ) ) {
	/**
	 * Return submodule's callback method by given string notation.
	 *
	 * @param Closure|array|string $maybe_callback
	 *
	 * @return callable|array|string
	 * @throws NSL_Callback_Exception
	 * @example foo.bar@baz ---> array( nsl()->foo->bar, 'baz )
	 */
	function nsl_parse_callback( $maybe_callback ) {
		return nsl()->parse_callback( $maybe_callback );
	}
}


if ( ! function_exists( 'nsl_option' ) ) {
	/**
	 * Alias function for option.
	 *
	 * @return NSL_Register_Option|null
	 */
	function nsl_option(): ?NSL_Register_Option {
		return nsl()->registers->option;
	}
}


if ( ! function_exists( 'nsl_comment_meta' ) ) {
	/**
	 * Alias function for comment meta.
	 *
	 * @return NSL_Register_Comment_Meta|null
	 */
	function nsl_comment_meta(): ?NSL_Register_Comment_Meta {
		return nsl()->registers->comment_meta;
	}
}


if ( ! function_exists( 'nsl_post_meta' ) ) {
	/**
	 * Alias function for post meta.
	 *
	 * @return NSL_Register_Post_Meta|null
	 */
	function nsl_post_meta(): ?NSL_Register_Post_Meta {
		return nsl()->registers->post_meta;
	}
}


if ( ! function_exists( 'nsl_term_meta' ) ) {
	/**
	 * Alias function for term meta.
	 *
	 * @return NSL_Register_Term_Meta|null
	 */
	function nsl_term_meta(): ?NSL_Register_Term_Meta {
		return nsl()->registers->term_meta;
	}
}


if ( ! function_exists( 'nsl_user_meta' ) ) {
	/**
	 * Alias function for user meta.
	 *
	 * @return NSL_Register_User_Meta|null
	 */
	function nsl_user_meta(): ?NSL_Register_User_Meta {
		return nsl()->registers->user_meta;
	}
}


if ( ! function_exists( 'nsl_script_debug' ) ) {
	/**
	 * Return SCRIPT_DEBUG.
	 *
	 * @return bool
	 */
	function nsl_script_debug(): bool {
		return apply_filters( 'nsl_script_debug', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
	}
}


if ( ! function_exists( 'nsl_format_callback' ) ) {
	/**
	 * Format callback method or function.
	 *
	 * This method does not care about $callable is actually callable.
	 *
	 * @param Closure|array|string $callback
	 *
	 * @return string
	 */
	function nsl_format_callback( $callback ): string {
		if ( is_string( $callback ) ) {
			return $callback;
		} elseif (
			( is_array( $callback ) && 2 === count( $callback ) ) &&
			( is_object( $callback[0] ) || is_string( $callback[0] ) ) &&
			is_string( $callback[1] )
		) {
			if ( method_exists( $callback[0], $callback[1] ) ) {
				try {
					$ref = new ReflectionClass( $callback[0] );
					if ( $ref->isAnonymous() ) {
						return "{AnonymousClass}::{$callback[1]}";
					}
				} catch ( ReflectionException $e ) {
				}
			}

			if ( is_string( $callback[0] ) ) {
				return "{$callback[0]}::{$callback[1]}";
			} elseif ( is_object( $callback[0] ) ) {
				return get_class( $callback[0] ) . '::' . $callback[1];
			}
		} elseif ( $callback instanceof Closure ) {
			return '{Closure}';
		}

		return '{Unknown}';
	}
}
