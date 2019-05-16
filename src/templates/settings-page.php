<div class="wrap">
	<h2><?php _e( 'Content Views CiviCRM Remote Settings', 'content-views-civicrm' ); ?></h2>
		<form method="post" action="options.php">
		<?php
			settings_fields( $this->option_group() );
			do_settings_sections( $this->section_slug() );
			do_settings_fields( $this->page_slug(), $this->section_slug() );
			submit_button();
		?>
	</form>
</div>
