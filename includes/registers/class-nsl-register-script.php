<?php
/**
 * NSL: Script register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Script' ) ) {
	class NSL_Register_Script extends NSL_Register_Base_Script {
		public function get_items(): Generator {
			yield new NSL_Reg_Script(
				'nsl-settings-field-credentials',
				$this->src_helper( 'admins/settings-field-credentials.js' ),
				[ 'jquery', 'jquery-ui-tabs' ]
			);
		}
	}
}
