<?php
/**
 * NSL: Admin modules group
 *
 * Manage all admin modules
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Admins' ) ) {
	class NSL_Admins implements NSL_Module {
		use NSL_Submodule_Impl;

		public function __construct() {
			$this->assign_modules(
				[
				]
			);
		}
	}
}
