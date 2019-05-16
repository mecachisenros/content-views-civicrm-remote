<?php
/**
 * Plugin Name: Content Views CiviCRM Remote Api
 * Description: Adds the ability to connect Content Views CiviCRM to a remote CiviCRM server.
 * Version: 0.1
 * Author: Andrei Mondoc
 * Author URI: https://github.com/mecachisenros
 * Plugin URI: https://github.com/mecachisenros/content-views-civicrm-remote
 * GitHub Plugin URI: mecachisenros/content-views-civicrm-remote
 * Text Domain: content-views-civicrm
 * Domain Path: /languages
 */

add_action( 'content_views_civicrm_files_included', function() {

	$plugin_dir = plugin_dir_path( __FILE__ );

	include $plugin_dir . 'src/class.api.php';
	include $plugin_dir . 'src/class-cvc-remote-api.php';
	include $plugin_dir . 'src/class-cvc-settings-page.php';
	include $plugin_dir . 'src/class-cvc-notice.php';

	$admin_page = new Content_Views_CiviCRM_Remote_Settings_Page( plugin_basename( __FILE__ ) );

	$remote_config = $admin_page->get_remote_config();

	if ( ! is_array( $remote_config ) || empty( $remote_config ) ) {

		new Content_Views_CiviCRM_Remote_Notices( plugin_dir_url( __FILE__ ) );

	} else {

		add_filter( 'content_views_civicrm_api_object', function( $object ) use ( $remote_config ) {

			/**
			 * Filter remote api class.
			 *
			 * @since 0.1
			 * @var object
			 */
			return apply_filters( 'content_views_civicrm_remote_api_object',
				( new Content_Views_CiviCRM_Remote_Api( $remote_config ) )
			);

		} );

	}

} );
