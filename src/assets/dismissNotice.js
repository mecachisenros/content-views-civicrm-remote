( function() {
	var handle = 'dismiss_content_views_civicrm_remote_settings';
	var dismiss = document.querySelector( '[data-action="' + handle + '"]' );
	dismiss.addEventListener( 'click', function( e ) {
		var request = new XMLHttpRequest();
		request.open( 'POST', ajaxurl );
		request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		request.send( 'action=' + handle );
	} );
} )()
