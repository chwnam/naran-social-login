<?php
/**
 * NSL: Style method chain helper
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Style_Helper' ) ) {
	class NSL_Style_Helper {
		/** @var NSL_Module|object */
		private $parent;

		private string $handle;

		public function __construct( $parent, string $handle ) {
			$this->parent = $parent;
			$this->handle = $handle;
		}

		/**
		 * Return another script helper.
		 *
		 * @param string $handle Handle string.
		 *
		 * @return NSL_Script_Helper
		 */
		public function script( string $handle ): NSL_Script_Helper {
			return new NSL_Script_Helper( $this->parent, $handle );
		}

		/**
		 * Return another style helper.
		 *
		 * @param string $handle Handle string.
		 *
		 * @return NSL_Style_Helper
		 */
		public function style( string $handle ): NSL_Style_Helper {
			return new NSL_Style_Helper( $this->parent, $handle );
		}

		/**
		 * Enqueue the style.
		 *
		 * @return self
		 */
		public function enqueue(): self {
			wp_enqueue_style( $this->handle );
			return $this;
		}

		public function then() {
			return $this->parent;
		}
	}
}
