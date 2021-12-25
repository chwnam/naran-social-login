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
			if ( is_admin() ) {
				yield new NSL_Reg_Style(
					'nsl-jquery-ui',
					$this->src_helper( 'admins/jquery-ui.min.css' ),
					[],
					'1.13.0',
				);
				yield new NSL_Reg_Style(
					'nsl-settings-page',
					$this->src_helper( 'admins/settings-page.css', false ),
					[ 'nsl-jquery-ui' ]
				);
				yield new NSL_Reg_Style(
					'nsl-github-markdown',
					$this->src_helper( 'admins/github-markdown.css', false )
				);
			} else {
				yield new NSL_Reg_Style(
					'nsl-default-login-support',
					$this->src_helper( 'default-login-support.css', false )
				);
			}
		}
	}
}
