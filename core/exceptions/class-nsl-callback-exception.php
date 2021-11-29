<?php
/**
 * NSL: Callback exception
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Callback_Exception' ) ) {
	class NSL_Callback_Exception extends Exception{
	}
}
