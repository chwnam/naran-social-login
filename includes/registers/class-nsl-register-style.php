<?php
/**
 * NSL: Style register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Register_Style' ) ) {
	class NSL_Register_Style extends NSL_Register_Base_Style {
		public function get_items(): Generator {
			yield new NSL_Reg_Style(
				'nsl-jquery-ui',
				$this->src_helper( 'jquery-ui.min.css' ),
				[],
				'1.13.0',
			);
			yield new NSL_Reg_Style(
				'nsl-jquery-ui-structure',
				$this->src_helper( 'jquery-ui.structure.min.css' ),
				[],
				'1.13.0',
			);
			yield new NSL_Reg_Style(
				'nsl-jquery-ui-theme',
				$this->src_helper( 'jquery-ui.theme.min.css' ),
				[ 'nsl-jquery-ui', 'nsl-jquery-ui-structure' ],
				'1.13.0',
			);
		}
	}
}
