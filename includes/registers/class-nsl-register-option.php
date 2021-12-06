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
	 * @property-read NSL_Reg_Option $settings
	 */
	class NSL_Register_Option extends NSL_Register_Base_Option {
		/**
		 * @return Generator
		 */
		public function get_items(): Generator {
			yield 'settings' => new NSL_Reg_Option(
				'nsl_settings',
				'nsl_settings',
				[
					'type'              => 'array',
					'description'       => 'Naran social login settings value.',
					'sanitize_callback' => [ NSL_Settings::class, 'sanitize' ],
					'show_in_rest'      => false,
					'default'           => NSL_Settings::get_defaults(),
					'autoload'          => false,
				]
			);
		}
	}
}
