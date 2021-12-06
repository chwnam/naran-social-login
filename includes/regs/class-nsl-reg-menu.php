<?php
/**
 * NSL: menu reg
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Reg_Menu' ) ) {
	class NSL_Reg_Menu implements NSL_Reg {
		public string $page_title;

		public string $menu_title;

		public string $capability;

		public string $menu_slug;

		public $callback;

		public string $icon_url;

		public ?int $position;

		/**
		 * @param string          $page_title
		 * @param string          $menu_title
		 * @param string          $capability
		 * @param string          $menu_slug
		 * @param callable|string $callback
		 * @param string          $icon_url
		 * @param int|null        $position
		 */
		public function __construct(
			string $page_title,
			string $menu_title,
			string $capability,
			string $menu_slug,
			$callback,
			string $icon_url = '',
			?int $position = null
		) {
			$this->page_title = $page_title;
			$this->menu_title = $menu_title;
			$this->capability = $capability;
			$this->menu_slug  = $menu_slug;
			$this->callback   = $callback;
			$this->icon_url   = $icon_url;
			$this->position   = $position;
		}

		/**
		 * @param callable|null $dispatch
		 *
		 * @return string
		 */
		public function register( $dispatch = null ): string {
			return add_menu_page(
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->menu_slug,
				$dispatch,
				$this->icon_url,
				$this->position
			);
		}
	}
}