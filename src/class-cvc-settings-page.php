<?php

/**
 * Admin settings page class.
 *
 * @since 0.1
 */

class Content_Views_CiviCRM_Remote_Settings_Page {

	/**
	 * Plugin base name.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $base_name;

	/**
	 * Admin parent page.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $parent_page = PT_CV_DOMAIN;

	/**
	 * Admin page suffix.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $page_suffix = '-civicrm-remote';

	/**
	 * Page title.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $page_title;

	/**
	 * Menu title.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $menu_title;

	/**
	 * Option group suffix.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $option_suffix = '_group';

	/**
	 * Options settings suffix.
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $section_suffix = '_section';

	/**
	 * Romote server config.
	 *
	 * @since 0.1
	 * @var array
	 */
	private $remote_config = [];

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct( $base_name ) {

		$this->base_name = $base_name;

		$this->page_title = __( 'CiviCRM Remote', 'content-views-civicrm' );
		$this->menu_title = __( 'CiviCRM Remote', 'content-views-civicrm' );

		$this->register_hooks();

	}

	/**
	 * Page slug.
	 *
	 * @since 0.1
	 * @return string $page_slug
	 */
	public function page_slug() {

		return $this->parent_page . $this->page_suffix;

	}

	/**
	 * Section slug.
	 *
	 * @since 0.1
	 * @return string $section_slug
	 */
	public function section_slug() {

		return $this->page_slug() . $this->section_suffix;

	}

	/**
	 * Option group slug.
	 *
	 * @since 0.1
	 * @return string $option_group_slug
	 */
	public function option_group() {

		return $this->page_slug() . $this->option_suffix;

	}

	/**
	 * Setting fields to register.
	 *
	 * @since 0.1
	 * @return array $fileds
	 */
	public function get_fields() {

		/**
		 * Filter page/api config fields/options.
		 *
		 * @since 0.1
		 * @var array
		 */
		return apply_filters( 'content_views_civicrm_remote_settings_options', [
			'server' => __( 'Server url', 'content-views-civicrm' ),
			'path' => __( 'Path (REST url)', 'content-views-civicrm' ),
			'key' => __( 'Site key', 'content-views-civicrm' ),
			'api_key' => __( 'API key', 'content-views-civicrm' )
		] );

	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		add_action( 'admin_menu', [ $this, 'register_page' ] );

		add_action( 'admin_init', [ $this, 'register_settings' ] );

		add_filter( 'plugin_action_links', [ $this, 'add_plugin_action_links' ], 10, 2 );

	}

	/**
	 * Registers menu page.
	 *
	 * @since 1.0
	 */
	public function register_page() {

		/**
		 * Filter settings page capability.
		 *
		 * @since 0.1
		 * @var string
		 */
		$capability = apply_filters( 'content_views_civicrm_remote_settings_cap', 'manage_options' );

		if ( ! current_user_can( $capability ) ) return;

		add_submenu_page(
			$this->parent_page,
			$this->page_title,
			$this->menu_title,
			$capability,
			$this->page_slug(),
			[ $this, 'render_page' ]
		);

	}

	/**
	 * Add Settings link to plugin listing page.
	 *
	 * @since 0.1
	 * @param array $links
	 * @param strinf $file
	 */
	public function add_plugin_action_links( $links, $file ) {


		if ( $file != $this->base_name ) return $links;

		$links[] = sprintf(
			'<a href="%1$s">%2$s</a>',
			admin_url( 'admin.php?page=' . $this->page_slug() ),
			__( 'Settings' )
		);

		return $links;

	}

	/**
	 * Register settings.
	 *
	 * @since 0.1
	 */
	public function register_settings() {

		// register section
		add_settings_section(
			$this->section_slug(),
			__( 'Remote Settings', 'content-views-civicrm' ),
			[ $this, 'render_section' ],
			$this->page_slug()
		);

		// register setting fields
		foreach ( $this->get_fields() as $name => $title ) {

			register_setting( $this->option_group(), $this->prefixed_option_name( $name ) );
			$this->register_field( $this->prefixed_option_name( $name ), $title );

		}

	}

	/**
	 * Get prefixed field/option name.
	 *
	 * @since 0.1
	 * @param string $name Field/option name
	 * @return string $name Prefixed field/option name
	 */
	public function prefixed_option_name( $name ) {

		return $this->page_slug() . '_' . $name;

	}

	/**
	 * Registers a field.
	 *
	 * @since 0.1
	 * @param string $name THe field name
	 * @param string $title The field title
	 */
	public function register_field( $name, $title ) {

		// add setting field
		add_settings_field(
			$name,
			$title,
			[ $this, 'render_field' ],
			$this->page_slug(),
			$this->section_slug(),
			[
				'name' => $name,
				'title' => $title
			]
		);

	}

	/**
	 * Renders the settings page.
	 *
	 * @since 0.1
	 */
	public function render_page() {

		include __DIR__ . '/templates/settings-page.php';

	}

	/**
	 * Renders the section.
	 *
	 * @since 0.1
	 */
	public function render_section() {

		echo '<h1>' . __( 'CiviCRM Remote Settings', 'content-views-civicrm' ) . '</h1>';

	}

	/**
	 * Renders field.
	 *
	 * @since 0.1
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function render_field( $args ) {

		include __DIR__ . '/templates/input.php';

	}

	/**
	 * Retrieve settings for remote connection.
	 *
	 * @since 0.1
	 * @return array|bool The config array or false
	 */
	public function get_remote_config() {

		if ( ! empty( $this->remote_config ) ) return $this->remote_config;

		foreach ( $this->get_fields() as $name => $value ) {

			$option = get_option( $this->prefixed_option_name( $name ), false );

			if ( ! $option ) return false;

			$this->remote_config[$name] = $option;

		}

		return $this->remote_config;

	}

}
