<?php
/**
 * NSL: Registers module
 *
 * Manage all registers
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Registers' ) ) {
	/**
	 * You can remove unused registers.
	 *
	 * @property-read NSL_Register_Activation    $activation
	 * @property-read NSL_Register_Ajax          $ajax
	 * @property-read NSL_Register_Capability    $cap
	 * @property-read NSL_Register_Comment_Meta  $comment_meta
	 * @property-read NSL_Register_Cron          $cron
	 * @property-read NSL_Register_Cron_Schedule $cron_schedule
	 * @property-read NSL_Register_Custom_Table  $custom_table
	 * @property-read NSL_Register_Deactivation  $deactivation
	 * @property-read NSL_Register_Option        $option
	 * @property-read NSL_Register_Post_Meta     $post_meta
	 * @property-read NSL_Register_Post_Type     $post_type
	 * @property-read NSL_Register_Role          $role
	 * @property-read NSL_Register_Script        $script
	 * @property-read NSL_Register_Shortcode     $shortcode
	 * @property-read NSL_Register_Sidebar       $sidebar
	 * @property-read NSL_Register_Style         $style
	 * @property-read NSL_Register_Submit        $submit
	 * @property-read NSL_Register_Taxonomy      $taxonomy
	 * @property-read NSL_Register_Term_Meta     $term_meta
	 * @property-read NSL_Register_Uninstall     $uninstall
	 * @property-read NSL_Register_User_Meta     $user_meta
	 * @property-read NSL_Register_Widget        $widget
	 * @property-read NSL_Register_WP_CLI        $wp_cli
	 */
	class NSL_Registers implements NSL_Module {
		use NSL_Submodule_Impl;

		public function __construct() {
			/**
			 * You can remove unused registers.
			 */
			$this->assign_modules(
				[
					'activation'    => NSL_Register_Activation::class,
					'ajax'          => NSL_Register_Ajax::class,
					'cap'           => function () { return new NSL_Register_Capability(); },
					'comment_meta'  => NSL_Register_Comment_Meta::class,
					'cron'          => NSL_Register_Cron::class,
					'cron_schedule' => NSL_Register_Cron_Schedule::class,
					'custom_table'  => function () { return new NSL_Register_Custom_Table(); },
					'deactivation'  => NSL_Register_Deactivation::class,
					'option'        => NSL_Register_Option::class,
					'post_meta'     => NSL_Register_Post_Meta::class,
					'post_type'     => NSL_Register_Post_Type::class,
					'role'          => function () { return new NSL_Register_Role(); },
					'script'        => NSL_Register_Script::class,
					'shortcode'     => NSL_Register_Shortcode::class,
					'sidebar'       => NSL_Register_Sidebar::class,
					'style'         => NSL_Register_Style::class,
					'submit'        => NSL_Register_Submit::class,
					'taxonomy'      => NSL_Register_Taxonomy::class,
					'term_meta'     => NSL_Register_Term_Meta::class,
					'uninstall'     => function () { return new NSL_Register_Uninstall(); },
					'user_meta'     => NSL_Register_User_Meta::class,
					'widget'        => NSL_Register_Widget::class,
					'wp_cli'        => NSL_Register_WP_CLI::class,
				]
			);
		}
	}
}
