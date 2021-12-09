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
				self::PAGE_SLUG
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
					'name'    => nsl_option()->settings->get_option_name() . '[enabled]',
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
					'option_name' => nsl_option()->settings->get_option_name(),
					'values'      => nsl_settings()->get_active_services(),
				]
			);
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
					'option_name'  => nsl_option()->settings->get_option_name(),
					'option_value' => nsl_settings()->get_credentials(),
					'items'        => $items,
				]
			);
		}
	}
}
