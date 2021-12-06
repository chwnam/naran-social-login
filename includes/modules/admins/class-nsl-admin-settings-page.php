<?php
/**
 * NSL: admin settings page
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NSL_Admin_Settings_Page' ) ) {
	class NSL_Admin_Settings_Page implements NSL_Admin_Module {
		use NSL_Hook_Impl;
		use NSL_Template_Impl;

		const PAGE_SLUG = 'nsl';

		public function __construct() {
		}

		public function render_page() {
			$this
				->prepare_setings()
				->render(
					'admins/settings',
					[
						'option_group' => 'nsl_settings',
						'page'         => self::PAGE_SLUG,
					]
				)
			;
		}

		protected function prepare_setings(): self {
			// Services ////////////////////////////////////////////////////////////////////////////////////////////////
			$section_services = 'nsl-services';

			add_settings_section(
				$section_services,
				__( 'Services', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG
			);

			add_settings_field(
				'nsl-services-active',
				__( 'Enable/Disable', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-enabled',
				__( 'Available Services', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-icon-sets',
				__( 'Icon Sets', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG,
				$section_services
			);

			// Credentials /////////////////////////////////////////////////////////////////////////////////////////////
			$section_credential = 'nsl-credential';

			add_settings_section(
				$section_credential,
				__( 'Credentials', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG
			);

			add_settings_field(
				'nsl-credential-',
				__( '', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG,
				$section_credential
			);

			// Registration ////////////////////////////////////////////////////////////////////////////////////////////
			$section_registration = 'nsl-registration';

			add_settings_section(
				$section_registration,
				__( 'Registration', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG,
				$section_registration
			);

			return $this;
		}
	}
}
