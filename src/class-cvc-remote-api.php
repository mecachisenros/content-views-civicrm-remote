<?php

/**
 * Class Content_Views_CiviCRM_Remote_Api
 *
 * @since 0.1
 */

class Content_Views_CiviCRM_Remote_Api {

	/**
	 * Remote server config.
	 *
	 * @since 0.1
	 * @var array
	 */
	private $config = [];

	/**
	 * Api object.
	 *
	 * @since 0.1
	 * @var remote_civicrm_api3
	 */
	protected $api;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct( $config ) {

		$this->config = $config;

		$this->api = new cvc_remote_civicrm_api3( $this->config );

	}

	/**
	 * Call CiviCRM API.
	 *
	 * @since  0.1
	 * @param string $entity
	 * @param string $action
	 * @param array $params
	 * @return array $result
	 */
	public function call( $entity, $action, $params ) {

		try {

			$this->api->$entity->$action( $params );

			return $this->to_array( $this->api->result() );

		} catch ( CiviCRM_API3_Exception $e ) {

			return WP_Error( 'Remote CiviCRM Api error', $e->getMessage(), $params );

		}

	}

	/**
	 * Get CiviCRM API values.
	 *
	 * @since  0.1
	 * @param string $entity
	 * @param string $action
	 * @param array $params
	 * @return array $result
	 */
	public function call_values( $entity, $action, $params ) {

		return $this->call( $entity, $action, $params )['values'];

	}

	/**
	 * Convert api result to array.
	 *
	 * @since 0.1
	 * @param object $result
	 * @return array $result
	 */
	public function to_array( $result ) {

		return json_decode( json_encode( $result ), true );

	}

}
