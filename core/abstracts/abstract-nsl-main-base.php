<?php
/**
 * NSL: Main base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Main_Base' ) ) {
	/**
	 * Class NSL_Main_Base
	 */
	abstract class NSL_Main_Base implements NSL_Module {
		use NSL_Hook_Impl;
		use NSL_Submodule_Impl;

		/**
		 * @var NSL_Main_Base|null
		 */
		private static ?NSL_Main_Base $instance = null;

		/**
		 * Free storage for the plugin.
		 *
		 * @var array
		 */
		private array $storage = [];

		/**
		 * Parsed module cache.
		 * Key:   input string notation.
		 * Value: found module, or false.
		 *
		 * @var array
		 */
		private array $parsed_cache = [];

		/**
		 * Get instance method.
		 *
		 * @return NSL_Main_Base
		 */
		public static function get_instance(): NSL_Main_Base {
			if ( is_null( self::$instance ) ) {
				self::$instance = new static();
				self::$instance->initialize();
			}
			return self::$instance;
		}

		/**
		 * NSL_Main_Base constructor.
		 */
		protected function __construct() {
		}

		/**
		 * Return plugin main file.
		 *
		 * @return string
		 */
		public function get_main_file(): string {
			return NSL_MAIN_FILE;
		}

		/**
		 * Get default priority
		 *
		 * @return int
		 */
		public function get_priority(): int {
			return NSL_PRIORITY;
		}

		/**
		 * Retrieve submodule by given string notaion.
		 *
		 * @param string $module_notation
		 *
		 * @return object|false
		 */
		public function get_module_by_notation( string $module_notation ) {
			if ( class_exists( $module_notation ) ) {
				return new $module_notation();
			} elseif ( $module_notation ) {
				if ( ! isset( $this->parsed_cache[ $module_notation ] ) ) {
					$module = $this;
					foreach ( explode( '.', $module_notation ) as $crumb ) {
						if ( isset( $module->{$crumb} ) ) {
							$module = $module->{$crumb};
						} else {
							$module = false;
							break;
						}
					}
					$this->parsed_cache[ $module_notation ] = $module;
				}

				return $this->parsed_cache[ $module_notation ];
			}

			return false;
		}

		/**
		 * Return submodule's callback method by given string notation.
		 *
		 * @param Closure|array|string $item
		 *
		 * @return Closure|array|string
		 * @throws NSL_Callback_Exception
		 * @example foo.bar@baz ---> array( nsl()->foo->bar, 'baz )
		 */
		public function parse_callback( $item ) {
			if ( is_callable( $item ) ) {
				return $item;
			} elseif ( is_string( $item ) && false !== strpos( $item, '@' ) ) {
				[ $module_part, $method ] = explode( '@', $item, 2 );

				$module = $this->get_module_by_notation( $module_part );

				if ( $module && is_callable( [ $module, $method ] ) ) {
					return [ $module, $method ];
				}
			}

			throw new NSL_Callback_Exception(
				sprintf(
				/* translators: formatted module name. */
					__( '%s is invalid for callback.', 'nsl' ),
					nsl_format_callback( $item )
				),
				100
			);
		}

		/**
		 * Get the theme version
		 *
		 * @return string
		 */
		public function get_version(): string {
			return NSL_VERSION;
		}

		/**
		 * Get something from storage.
		 */
		public function get( string $key, $default = '' ) {
			return $this->storage[ $key ] ?? $default;
		}

		/**
		 * Set something to storage.
		 */
		public function set( string $key, $value ) {
			$this->storage[ $key ] = $value;
		}

		/**
		 * Load textdomain
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'nsl', false, wp_basename( dirname( $this->get_main_file() ) ) . '/languages' );
		}

		/**
		 * Initialize conditional modules.
		 *
		 * @return void
		 */
		public function init_conditional_modules() {
		}

		protected function initialize() {
			$this->assign_modules( $this->get_modules() );

			$this
				->add_action( 'plugins_loaded', 'load_textdomain' )
				->add_action( 'wp', 'init_conditional_modules' )
			;

			$this->extra_initialize();

			do_action( 'nsl_initialized' );
		}

		/**
		 * Return root modules
		 *
		 * @return array
		 */
		abstract protected function get_modules(): array;

		/**
		 * Do NSL_Main specific initialization.
		 */
		abstract protected function extra_initialize(): void;
	}
}
