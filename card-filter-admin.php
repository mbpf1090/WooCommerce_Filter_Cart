<?php settings_errors(); ?>

<form method="post" action="options.php" class="sunset-general-form">
	<?php settings_fields( 'category-settings' ); ?>
	<?php do_settings_sections( 'filter-cart' ); ?>
	<?php submit_button(); ?>
</form>
