<?php
/**
 * NSL: Widget reg.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Reg_Widget' ) ) {
	class NSL_Reg_Widget implements NSL_Reg {
		/**
		 * @var object|string
		 */
		public $widget;

		/**
		 * @param string|object $widget
		 */
		public function __construct( $widget ) {
			$this->widget = $widget;
		}

		public function register( $dispatch = null ) {
			register_widget( $this->widget );
		}
	}
}
