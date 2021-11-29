<?php

if ( ! ( defined( 'WP_UNINSTALL_PLUGIN' ) && WP_UNINSTALL_PLUGIN ) ) {
	exit;
}

require_once __DIR__ . '/index.php';
require_once __DIR__ . '/core/uninstall-functions.php';

$uninstall = nsl()->registers->uninstall;
if ( $uninstall ) {
	$uninstall->register();
}

// You may use these functions to purge data.
// nsl_cleanup_option();
// nsl_cleanup_meta();
// nsl_cleanup_terms();
// nsl_cleanup_posts();
