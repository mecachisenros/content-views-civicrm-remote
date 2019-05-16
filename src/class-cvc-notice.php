<?php

/**
 * Remote settings notices class.
 *
 * @since 0.1
 */

class Content_Views_CiviCRM_Remote_Notices {

	/**
	 * Plugin url.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $plugin_url;

	/**
	 * Notice handle.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $handle = 'dismiss_content_views_civicrm_remote_settings';

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct( $plugin_url ) {

		$this->plugin_url = $plugin_url;

		$this->register_hooks();

	}

	/**
	 * Register hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		add_action( 'admin_notices', [ $this, 'show_notice' ] );

		add_action( 'wp_ajax_' . $this->handle, [ $this, 'dismiss_notice' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_script' ] );

	}

	/**
	 * Shows missing settings notice.
	 *
	 * @since 0.1
	 */
	public function show_notice() {

		if ( ! get_option( $this->handle, false ) ) {

			printf(
				__( '<div class="notice notice-warning is-dismissible" data-action="%s"><p>Please set your CiviCRM remote settings by navigating to the <a href="%s" title="Content Views CiviCRM Remote Settings">settings page</a>.</p></div>', 'content-views-civicrm' ),
				$this->handle,
				admin_url( 'admin.php?page=content-views-civicrm-remote' )
			);

		}

	}

	/**
	 * Dismiss notice ajax handler.
	 *
	 * @since 0.1
	 */
	public function dismiss_notice() {

		if ( ! isset( $_POST['action'] ) || $_POST['action'] != $this->handle ) return;

		update_option( $this->handle, true );

	}

	/**
	 * Enqueue dismiss script.
	 *
	 * @since 0.1
	 */
	public function enqueue_script() {

		wp_enqueue_script(
			$this->handle,
			$this->plugin_url . 'src/assets/dismissNotice.js',
			[],
			false,
			true
		);

	}

}
