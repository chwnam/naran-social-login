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
			$this->add_action( 'admin_enqueue_scripts', 'admin_enqueue_scripts' );
		}

		public function admin_enqueue_scripts( string $hook ) {
			if ( 'settings_page_nsl' === $hook ) {
				// enqueue header styles here.
				$this->enqueue_style( 'nsl-jquery-ui-theme' );
			}
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
				[ $this, 'render_field_enable_disable' ],
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-available',
				__( 'Available Services', 'nsl' ),
				[ $this, 'render_field_available_services' ],
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-icon-sets',
				__( 'Icon Sets', 'nsl' ),
				[ $this, 'render_field_icon_sets' ],
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-credentials',
				__( 'Credentials', 'nsl' ),
				[ $this, 'render_field_credentials' ],
				self::PAGE_SLUG,
				$section_services
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

		/**
		 * Render 'Enable/Disable' field.
		 */
		public function render_field_enable_disable() {
			NSL_HTML::input(
				[
					'id'      => '',
					'name'    => '',
					'value'   => 'yes',
					'type'    => 'checkbox',
					'checked' => false,
				]
			);
			NSL_HTML::tag_open( 'label', [ 'for' => '' ] );
			esc_html_e( 'Use naran social login.', 'nsl' );
			NSL_HTML::tag_close( 'label' );
		}

		/**
		 * Render 'Available Services' field.
		 *
		 */
		public function render_field_available_services() {
		}

		/**
		 * Render 'Icon Sets' field.
		 *
		 */
		public function render_field_icon_sets() {
		}

		/**
		 * Render 'Credentials' field.
		 */
		public function render_field_credentials() {
			$this
				->enqueue_script( 'nsl-settings-field-credentials' )
				->render(
					'admins/settings-field-credentials',
					[]
				)
			;
		}
	}
}
