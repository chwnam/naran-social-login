<?php
/**
 * NSL: Option register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Option' ) ) {
	/**
	 * NOTE: Add 'property-read' phpdoc to make your editor inspect option items properly.
	 */
	class NSL_Register_Option extends NSL_Register_Base_Option {
		/**
		 * Define items here.
		 *
		 * To use alias, do not forget to return generator as 'key => value' form!
		 *
		 * @return Generator
		 */
		public function get_items(): Generator {
			yield; // yield 'alias' => new NSL_Reg_Option();
		}
	}
}
