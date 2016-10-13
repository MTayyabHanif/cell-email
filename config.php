<?php

/* Options Page 
---------------------------------------------------------------
*/
add_action( 'admin_menu', 'cell_email_example_theme_menu' );
function cell_email_example_theme_menu() {

	add_options_page(
		__('Email', 'cell-email'), 			// The title to be displayed in the browser window for this page.
		__('Email', 'cell-email'),			// The text to be displayed for this menu item
		'administrator',					// Which type of users can see this menu item
		'cell_email_options',				// The unique ID - that is, the slug - for this menu item
		'cell_email_theme_display'			// The name of the function to call when rendering this menu's page
	);
}


/* Add script for uploads 
---------------------------------------------------------------
*/

function cell_email_options_enqueue_scripts() {  

	wp_register_script( 'email-upload', plugins_url() . '/cell-email/js/email-upload.js', array('jquery','media-upload','thickbox'),1000 );  
	if ( 'settings_page_cell_email_options' == get_current_screen() -> id ) {  
		wp_enqueue_script('jquery');  
		wp_enqueue_script('thickbox');  
		wp_enqueue_style('thickbox');  
		wp_enqueue_script('media-upload');  
		wp_enqueue_script('email-upload');  
	}  
}  
add_action('admin_enqueue_scripts', 'cell_email_options_enqueue_scripts');  


function cell_email_options_setup() {  
	global $pagenow;  
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {  
		// Now we'll replace the 'Insert into Post Button' inside Thickbox  
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 ); 
	} 
} 
add_action( 'admin_init', 'cell_email_options_setup' ); 
function replace_thickbox_text($translated_text, $text, $domain) { 
	if ('Insert into Post' == $text) { 
		$referer = strpos( wp_get_referer(), 'cell-email-settings' ); 
		if ( $referer != '' ) { 
			return __('Use as Email Header', 'cell-email' );  
		}  
	}  
	return $translated_text;  
}


/* Options Page, with tabs deactivated for now 
---------------------------------------------------------------
*/
function cell_email_theme_display( $active_tab = '' ) {
?>
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e('Email Options', 'cell-email') ?></h2>
		<?php settings_errors(); ?>
		
		<?php
			if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
			} else if( $active_tab == 'social_options' ) {
				$active_tab = 'social_options';
			} else if( $active_tab == 'input_examples' ) {
				$active_tab = 'input_examples';
			} else {
				$active_tab = 'base_options';
			}
		?>
		
		<!-- <h2 class="nav-tab-wrapper">
			<a href="?page=cell_email_options&tab=base_options" class="nav-tab <?php //echo $active_tab == 'base_options' ? 'nav-tab-active' : ''; ?>">Base Options</a>
			<a href="?page=cell_email_options&tab=social_options" class="nav-tab <?php //echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>">Social Options</a>
			<a href="?page=cell_email_options&tab=input_examples" class="nav-tab <?php //echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>">Input Examples</a> 
		</h2> -->
		
		<form method="post" action="options.php">
			<?php
				if( $active_tab == 'base_options' ) {
					settings_fields( 'cell_email_base_options' );
					do_settings_sections( 'cell_email_base_options' );
				} elseif( $active_tab == 'social_options' ) {
					// settings_fields( 'cell_email_theme_social_options' );
					// do_settings_sections( 'cell_email_theme_social_options' );
				} else {
					// settings_fields( 'cell_email_theme_input_examples' );
					// do_settings_sections( 'cell_email_theme_input_examples' );
				}
				
				submit_button();
			?>
		</form>
	</div>
<?php
}


function cell_email_initialize_theme_options() {

	if( false == get_option( 'cell_email_base_options' ) ) {	
		add_option( 'cell_email_base_options' );
	}

	add_settings_section(
		'email_identity_section',
		__('Email Identity', 'cell-email'),
		'cell_email_identity_callback',
		'cell_email_base_options'
	);

	add_settings_field(	
		'from_name',
		__('From Name', 'cell-email'),
		'cell_email_from_name_callback',
		'cell_email_base_options',
		'email_identity_section',
		array(
			__('This will be used as the email sender name', 'cell-email')
		)
	);

	add_settings_field(	
		'from_email',
		__('From Email Address', 'cell-email'),
		'cell_email_from_email_callback',
		'cell_email_base_options',
		'email_identity_section',
		array(
			__('This will be used as the sender email', 'cell-email')
		)
	);

	add_settings_section(
		'email_design_section',
		__('Email Design','cell-email'),
		'cell_email_design_callback',
		'cell_email_base_options'
	);

	add_settings_field(
		'email_header',
		__( 'Email Header', 'cell-email' ),
		'email_header_callback',
		'cell_email_base_options',
		'email_design_section'
	);

	add_settings_field(
		'email_header_preview',
		__( 'Email Header Preview', 'cell-email' ),
		'email_header_preview_callback',
		'cell_email_base_options',
		'email_design_section'
	);

	add_settings_field(
		'email_notification_test',
		__( 'Email Preview', 'cell-email' ),
		'email_notification_test_callback',
		'cell_email_base_options',
		'email_design_section'
	);

	register_setting(
		'cell_email_base_options',
		'cell_email_base_options',
		'input_array_validation'

	);
	
}
add_action( 'admin_init', 'cell_email_initialize_theme_options' );


/* Section Callbacks 
---------------------------------------------------------------
*/

function cell_email_identity_callback() {
	echo __('<p>Basic email corespondance identity.</p>', 'cell-email');
}

function cell_email_design_callback() {
	echo __('<p>Basic email design options.</p>', 'cell-email');
}

function cell_email_preview_callback() {
	echo __('<p>Send a test email. </p>', 'cell-email');
}

/* Field Callbacks 
---------------------------------------------------------------
*/

function cell_email_from_name_callback($args) {
	$options = get_option( 'cell_email_base_options' );
	$name = (isset($options['from_name']) ? $options['from_name'] : '');
	$html =  '<input type="text" id="from_name" name="cell_email_base_options[from_name]" value="' . $name . '" />';
	$html .= '<label for="cell_email_base_options[from_name]">&nbsp;'  . $args[0] . '</label>'; 
	echo $html;	
}

function cell_email_from_email_callback($args) {
	$options = get_option( 'cell_email_base_options' );
	$email = (isset($options['from_email']) ? $options['from_email'] : '');
	$html =  '<input type="text" id="from_email" name="cell_email_base_options[from_email]" value="' . $email . '" />';
	$html .= '<label for="cell_email_base_options[from_email]">&nbsp;'  . $args[0] . '</label>'; 
	echo $html;	
}

function email_header_callback() {  
	$options = get_option( 'cell_email_base_options' );  
	$header = (isset($options['email_header']) ? $options['email_header'] : '');
	?>  
		<input type="text" id="logo_url" name="cell_email_base_options[email_header]" value="<?php echo esc_url( $header); ?>" />  
		<input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Banner', 'cell-email' ); ?>" />  
		<span class="description"><?php _e('Upload an image for the banner.', 'cell-email' ); ?></span>  
	<?php  
} 

function email_header_preview_callback() {  
	$options = get_option( 'cell_email_base_options' );  ?>
	<?php if (isset($options['email_header'])): ?>
		<div id="upload_logo_preview" style="min-height: 100px;">
			<img style="max-width:100%;" src="<?php echo esc_url( $options['email_header'] ); ?>" />  
		</div>
	<?php endif ?>
	<?php  
}

function email_notification_test_callback() {  
	?>  
	<div id="email-test" class="hidden">
		<a href="#" id="send-notification-test" class="button button-secondary"><?php echo __('Notification Email', 'cell-email') ?></a>
		<a href="#" id="send-reset-password-test" class="button button-secondary"><?php echo __('Reset Password Email', 'cell-email') ?></a>
	</div>  
	<?php  
} 

/* Basic Validation 
---------------------------------------------------------------
*/

function input_array_validation( $input ) {
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
		}
	}
	return apply_filters( 'input_array_validattion', $output, $input );
}


?>