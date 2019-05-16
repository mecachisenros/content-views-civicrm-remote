<?php
/**
 *
 */
?>
<input
	style="display: block;"
	size="100"
	required="true"
	name="<?php esc_attr_e( $args['name'] ); ?>"
	id="<?php esc_attr_e( $args['name'] ); ?>"
	type='<?php in_array( $args['name'], ['content-views-civicrm-remote_api_key', 'content-views-civicrm-remote_key'] ) ? printf('password') : printf('text'); ?>'
	value="<?php echo get_option( $args['name'], '' ); ?>"/>
