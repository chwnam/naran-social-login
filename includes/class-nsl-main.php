<?php
/**
 * NSL: Main class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Main' ) ) {
	/**
	 * Class NSL_Main
	 *
	 * @property-read NSL_Admins    $admins
	 * @property-read NSL_Registers $registers
	 */
	final class NSL_Main extends NSL_Main_Base {
		/**
		 * Return root modules
		 *
		 * @return array
		 *
		 * @used-by NSL_Main_Base::initialize()
		 */
		protected function get_modules(): array {
			return [
				'admins'    => NSL_Admins::class,
				'registers' => NSL_Registers::class,
			];
		}

		protected function extra_initialize(): void {
			// Do some plugin-specific initialization tasks.
			// $plugin = plugin_basename( $this->get_main_file() );
			// $this->add_filter( "plugin_action_links_$plugin", 'add_plugin_action_links' );
		}

		public function add_plugin_action_links( array $actions ): array {
			return array_merge(
				[
					'settings' => sprintf(
					/* translators: %1$s: link to settings , %2$s: aria-label  , %3$s: text */
						'<a href="%1$s" id="nss-settings" aria-label="%2$s">%3$s</a>',
						admin_url( 'options-general.php?page=nsl' ), // NOTE: You need to implement the page.
						esc_attr__( 'NSL settings', 'nsl' ),
						esc_html__( 'Settings', 'nsl' )
					)
				],
				$actions
			);
		}
	}
}
