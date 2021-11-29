<?php
/**
 * NSL: Admin module interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NSL_Admin_Module' ) ) {
	interface NSL_Admin_Module extends NSL_Module {
	}
}
