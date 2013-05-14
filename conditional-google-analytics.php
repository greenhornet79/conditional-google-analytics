<?php 

/*
Plugin Name: Conditional Google Analytics
Plugin URI: http://www.endocreative.com
Description: Choose whether or not to add GA code if a user is logged in
Version: 1.0
Author: Endo Creative
Author URI: http://www.endocreative.com
*/


add_action( 'admin_menu', 'cga_create_options_page');

function cga_create_options_page() {
	add_options_page( 'Conditional Google Analytics Settings', 'Conditional GA', 'manage_options', __FILE__, 'cga_settings_page');
}

function cga_settings_page() {
?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Conditional Google Analytics Settings</h2>
	
		<form method="post" action="options.php">

			<?php settings_fields('endo_cga_options'); ?>
			
				<?php do_settings_sections('endo_cga'); ?>
				
						<?php submit_button(); ?>
					
		</form>
	</div>
	<?php 
}

add_action( 'admin_init', 'cga_admin_init' );

function cga_admin_init() {
	register_setting( 'endo_cga_options', 'endo_cga_options', 'endo_cga_validate_options' );
	add_settings_section( 'endo_cga_main', 'Main Settings', 'endo_cga_section_text', 'endo_cga' );
	
	add_settings_field( 'endo_cga_analytics', 'Enter GA javascript here', 'endo_cga_ga_setting_input', 'endo_cga', 'endo_cga_main');

	add_settings_field( 'endo_cga_in_admin', 'Remove GA code from header when logged in?', 'endo_cga_remove_ga_setting_input', 'endo_cga', 'endo_cga_main');
}

// draw section header
function endo_cga_section_text() {
	// echo '<p>Enter your settings here.</p>';
}

// display form field
function endo_cga_ga_setting_input() {
	// get option 'text_string' from database
	$options = get_option( 'endo_cga_options' );
	$ga_code = $options['ga_code'];
	// echo the field
	echo "<textarea cols=60 rows=10 id='ga_code' name='endo_cga_options[ga_code]'>$ga_code</textarea>";
}

// display form field
function endo_cga_remove_ga_setting_input() {
	// get option 'text_string' from database
	$options = get_option( 'endo_cga_options' );
	if ( isset($options['remove_ga'] ) ) {
		$hide_ga = $options['remove_ga'];
	} else {
		$hide_ga = '';
	}
	
	// echo the field

	echo "<input type='checkbox' value='1' id='remove_ga' name='endo_cga_options[remove_ga]'" . checked($hide_ga, 1, 0) . " />";

}

// validate user input
function endo_cga_validate_options( $input ) {
	return $input;
	
}


add_action('wp_head', 'endo_cga_print_analytics');

function endo_cga_print_analytics() {

	$options = get_option( 'endo_cga_options' );
	if ( isset($options['remove_ga'] ) && current_user_can('delete_users') ) {
		return;
	} else {
		echo $options['ga_code'];
	}
}