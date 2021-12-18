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
				$this->enqueue_style( 'nsl-settings-page' );
				if ( isset( $_GET['doc'] ) ) {
					$this->enqueue_style( 'nsl-github-markdown' );
				}
			}
		}

		public function render_page() {
			if ( isset( $_GET['doc'] ) ) {
				$this->render_document( $_GET['doc'] );
			} else {
				$this
					->prepare_setings()
					->enqueue_script( 'nsl-settings-page' )
					->render(
						'admins/settings-page',
						[
							'option_group' => 'nsl_settings',
							'page'         => self::PAGE_SLUG,
						]
					)
				;
			}
		}

		protected function render_document( string $doc ) {
			$locale = get_user_locale();
			if ( 'ko_KR' !== $locale ) {
				echo '<p>Sorry, currently Korean documents are available.</p>';
				return;
			}

			$doc      = sanitize_key( $doc );
			$doc_path = dirname( nsl()->get_main_file() ) . "/docs/$doc.md";

			if ( file_exists( $doc_path ) && is_readable( $doc_path ) ) {
				$pd = new Parsedown();

				// Search and replace relative image urls.
				$url  = plugin_dir_url( nsl()->get_main_file() ) . 'docs/img/';
				$text = file_get_contents( $doc_path );
				$text = str_replace( '![](./img/', '![](' . $url, $text );

				$rendered = $pd->text( $text );

				if ( $rendered ) {
					echo '<div class="wrap"><article class="markdown-body">';
					echo $rendered;
					echo '</article></div>';
				}
			}
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
				'nsl-services-enable-disable',
				__( 'Enable/Disable', 'nsl' ),
				[ $this, 'render_field_enable_disable' ],
				self::PAGE_SLUG,
				$section_services
			);

			add_settings_field(
				'nsl-services-active',
				__( 'Active Services', 'nsl' ),
				[ $this, 'render_field_active_services' ],
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

			// Login Buttons ///////////////////////////////////////////////////////////////////////////////////////////
			$section_login_buttons = 'nsl-login-buttons';

			add_settings_section(
				$section_login_buttons,
				__( 'Login Buttons', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG
			);

			add_settings_field(
				'nsl-login-buttons-icon-sets',
				__( 'Icon Sets', 'nsl' ),
				[ $this, 'render_field_icon_sets' ],
				self::PAGE_SLUG,
				$section_login_buttons
			);

			add_settings_field(
				'nsl-login-buttons-default-login-support',
				__( 'Default Login Support', 'nsl' ),
				[ $this, 'render_field_default_login_support' ],
				self::PAGE_SLUG,
				$section_login_buttons
			);

			// Registration ////////////////////////////////////////////////////////////////////////////////////////////
			$section_registration = 'nsl-registration';

			add_settings_section(
				$section_registration,
				__( 'Registration', 'nsl' ),
				'__return_empty_string',
				self::PAGE_SLUG
			);

			add_settings_field(
				'nsl-email-required',
				__( 'Email Required', 'nsl' ),
				[ $this, 'render_field_email_required' ],
				self::PAGE_SLUG,
				$section_registration,
				[ 'label_for' => 'nsl-field-email-required' ]
			);

			add_settings_field(
				'nsl-extra-form',
				__( 'Extra Form', 'nsl' ),
				[ $this, 'render_field_extra_form' ],
				self::PAGE_SLUG,
				$section_registration,
				[ 'label_for' => 'nsl-field-extra-form' ]
			);

			add_settings_field(
				'nsl-registration-complete',
				__( 'Registration Complete', 'nsl' ),
				[ $this, 'render_field_registration_complete' ],
				self::PAGE_SLUG,
				$section_registration,
				[ 'label_for' => 'nsl-field-registration-complete' ]
			);

			add_settings_field(
				'nsl-login-complete',
				__( 'Login Complete', 'nsl' ),
				[ $this, 'render_field_login_complete' ],
				self::PAGE_SLUG,
				$section_registration,
				[ 'label_for' => 'nsl-field-login-complete' ]
			);

			return $this;
		}

		/**
		 * Render 'Enable/Disable' field.
		 */
		public function render_field_enable_disable() {
			NSL_HTML::input(
				[
					'id'      => 'nsl-field-enabled',
					'name'    => nsl_settings()->get_name() . '[enabled]',
					'value'   => 'yes',
					'type'    => 'checkbox',
					'checked' => nsl_settings()->is_enabled(),
				]
			);
			NSL_HTML::tag_open( 'label', [ 'for' => 'nsl-field-enabled' ] );
			esc_html_e( 'Use naran social login.', 'nsl' );
			NSL_HTML::tag_close( 'label' );
		}

		/**
		 * Render 'Active Services' field.
		 *
		 */
		public function render_field_active_services() {
			$services = nsl_get_available_services();

			asort( $services );

			$this->render(
				'admins/settings-field-active-service',
				[
					'services'    => $services,
					'option_name' => nsl_settings()->get_name(),
					'values'      => nsl_settings()->get_active_services(),
				]
			);
		}

		/**
		 * Render 'Credentials' field.
		 */
		public function render_field_credentials() {
			$services      = nsl_get_available_services();
			$urls          = nsl_get_devlopers_page_urls();
			$titles        = nsl_get_developers_page_titles();
			$redirect_uris = nsl_get_redirect_uris();
			$items         = [];

			asort( $services );

			foreach ( $services as $id => $service ) {
				if ( isset( $urls[ $id ], $titles[ $id ], $redirect_uris[ $id ] ) ) {
					$items[ $id ] = [
						'name'         => $service,
						'url'          => $urls[ $id ],
						'dev_title'    => $titles[ $id ],
						'redirect_uri' => $redirect_uris[ $id ],
					];
				}
			}

			$this->render(
				'admins/settings-field-credentials',
				[
					'option_name'  => nsl_settings()->get_name(),
					'option_value' => nsl_settings()->get_credentials(),
					'items'        => $items,
				]
			);
		}

		/**
		 * Render 'Login Buttons' > 'Icon Sets' field.
		 *
		 */
		public function render_field_icon_sets() {
			$this->render(
				'admins/settings-field-icon-set',
				[
					'all_avail' => nsl_get_available_services(),
					'icon_sets' => nsl_get_icon_sets(),
					'id'        => 'nsl-field-icon-set',
					'name'      => nsl_settings()->get_name() . '[icon_set]',
					'value'     => nsl_settings()->get_icon_set(),
				]
			);
		}

		/**
		 * Render 'Login Buttons' > 'Default Login Support' field.
		 */
		public function render_field_default_login_support() {
			NSL_HTML::input(
				[
					'id'      => 'nsl-field-default-login-support',
					'name'    => nsl_settings()->get_name() . '[default_login_support]',
					'type'    => 'checkbox',
					'value'   => 'yes',
					'checked' => nsl_settings()->is_default_login_support(),
				]
			);

			NSL_HTML::tag_open( 'label', [ 'for' => 'nsl-field-default-login-support' ] );
			esc_html_e( 'Show login buttons in wp-login.php login form.', 'nsl' );
			NSL_HTML::tag_close( 'label' );
		}

		/**
		 * Render 'Email Required' field.
		 */
		public function render_field_email_required() {
			NSL_HTML::input(
				[
					'id'      => 'nsl-field-email-required',
					'name'    => nsl_settings()->get_name() . '[email_required]',
					'type'    => 'checkbox',
					'value'   => 'yes',
					'checked' => nsl_settings()->is_email_required(),
				]
			);

			NSL_HTML::tag_open( 'label', [ 'for' => 'nsl-field-email-required' ] );
			esc_html_e( 'Do not allow a blank email address.', 'nsl' );
			NSL_HTML::tag_close( 'label' );

			$desc = [
				__( 'A sogial login service may not give you a user\'s email address.', 'nsl' ),
				__( 'Or a user may intentionally exclude his/her email address.', 'nsl' ),
				__( 'In those cases, do not allow registration and login.', 'nsl' ),
			];

			echo '<p class="description">' . esc_html( implode( ' ', $desc ) ) . '</p>';
		}

		/**
		 * Render 'Extra Form' field.
		 */
		public function render_field_extra_form() {
			wp_dropdown_pages(
				[
					'id'               => 'nsl-field-extra-form',
					'name'             => nsl_settings()->get_name() . '[extra_form]',
					'value'            => '',
					'show_option_none' => '-- ' . __( 'Default', 'nsl' ) . ' --',
				]
			);

			$desc = [
				__( 'Specify a page that output a form to obtain additional information when a user first logs in.', 'nsl' ),
				__( 'Defaults to do not use extra form.', 'nsl' ),
			];

			echo '<p class="description">' . esc_html( implode( ' ', $desc ) ) . '</p>';

			echo '<p class="description">' .
			     esc_html__( 'NOTE: To get an email address from the form explicitly, be sure to uncheck \'Email required\'.', 'nsl' )
			     . '</p>';
		}

		/**
		 * Render 'Registration Complete' field.
		 */
		public function render_field_registration_complete() {
			wp_dropdown_pages(
				[
					'id'               => 'nsl-field-registration-complete',
					'name'             => nsl_settings()->get_name() . '[registration_complete]',
					'value'            => '',
					'show_option_none' => '-- ' . __( 'Default', 'nsl' ) . ' --',
				]
			);

			$desc = [
				__( 'Specify a page that shows welcome message after successful registration.', 'nsl' ),
				__( 'Defaults to home URL.', 'nsl' ),
			];

			echo '<p class="description">' . esc_html( implode( ' ', $desc ) ) . '</p>';
		}

		/**
		 * Render 'Login Complete' field.
		 */
		public function render_field_login_complete() {
			wp_dropdown_pages(
				[
					'id'               => 'nsl-field-login-complete',
					'name'             => nsl_settings()->get_name() . '[login_complete]',
					'value'            => '',
					'show_option_none' => '-- ' . __( 'Default', 'nsl' ) . ' --',
				]
			);

			$desc = [
				__( 'Specify a page to redirect users to after successful login.', 'nsl' ),
				__( 'Defaults to home URL.', 'nsl' ),
			];

			echo '<p class="description">' . esc_html( implode( ' ', $desc ) ) . '</p>';
		}
	}
}
