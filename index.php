<?php
/**
 * Plugin Name:       Naran Social Login
 * Plugin URI:        https://github.com/chwnam/naran-social-login
 * Description:       Naran social login, just another social login plugin for WordPress.
 * Version:           0.1.0
 * Requires at least: 5.1.0
 * Requires PHP:      7.4
 * Author:            changwoo
 * Author URI:        https://blog.changwoo.pe.kr/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:
 * Text Domain:       nsl
 * Domain Path:       /languages
 * CPBN version:      1.2.2
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

const NSL_MAIN_FILE = __FILE__;
const NSL_VERSION   = '0.1.0';
const NSL_PRIORITY = 190;

nsl();
