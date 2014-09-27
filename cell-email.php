<?php
	/*
	Plugin Name: Cell Email
	Plugin URI: http://google.com
	Description: Cell Email function plugin, made to work with any cell theme
	Version: 1.0
	Author: Saklik
	Author URI: http://saklik.com
	License: 
	*/


//set constant values
define( 'CELL_EMAIL_FILE', __FILE__ );
define( 'CELL_EMAIL', dirname( __FILE__ ) );
define( 'CELL_EMAIL_PATH', plugin_dir_path(__FILE__) );
define( 'CELL_EMAIL_TEXT_DOMAIN', 'cell-email' );

// options
include_once ('config.php');

// set for internationalization

function cell_email_init() {
	load_plugin_textdomain('cell-email', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'cell_email_init');


/* basic config 
---------------------------------------------------------------
*/

//set content type to html
add_filter ('wp_mail_content_type', 'html_mail_content_type');
function html_mail_content_type() {
	return "text/html";
}

// set email sent from address
add_filter ('wp_mail_from', 'cell_mail_from');
function cell_mail_from() {
	$email_options = get_option('cell_email_base_options');
	if (isset($email_options) && isset($email_options['from_name'])) {
		return $email_options['from_email'];
	} else {
		return get_bloginfo('admin_email');
	}	
}

// set email sent from name
add_filter ('wp_mail_from_name', 'mail_from_name');
function 	mail_from_name() {
	$email_options = get_option('cell_email_base_options');
	if (isset($email_options) && isset($email_options['from_name'])) {
		return $email_options['from_name'];
	} else {
		return get_bloginfo('name');
	}
}


/* welcome new user 
---------------------------------------------------------------
this one is a pluggable function, so it doesnt need a hook
*/


if( ! function_exists('wp_new_user_notification') ) {
	function wp_new_user_notification($user_id, $plaintext_pass) {
		$user = new WP_User($user_id);
		$display_name = $user->display_name;
		if (isset($user->first_name) && $user->first_name != '') {
			$display_name = $user->first_name;
		}
		$user_email = stripslashes($user->user_email);
		$email_subject = sprintf(__('Welcome to %1$s %2$s!', 'cell-email'), get_bloginfo('name'), $display_name);
		
		ob_start();
		include('template/email-header.php');

		printf(__('<p>A very special welcome to you, %1$s. Thank you for joining %2$s!</p>', 'cell-email'), $display_name, get_bloginfo('name'));

		printf(__('<p> Your password is <strong style="color:orange">%s</strong> <br> Please keep it secret and keep it safe! </p>', 'cell-email'), $plaintext_pass);

		printf(__('<p>We hope you enjoy your stay at %s. If you have any problems, questions, opinions, praise, comments, suggestions, please feel free to contact us at any time</p>', 'cell-email'), get_bloginfo('name'));

		include('template/email-footer.php');
		$message = ob_get_contents();
		ob_end_clean();

		wp_mail($user_email, $email_subject, $message);

		wp_mail(get_bloginfo('admin_email'), $email_subject, $message);

	}

}


/* Retrieve Password  Title
---------------------------------------------------------------
*/

add_filter ('retrieve_password_title', 'cell_retrieve_password_title');

function cell_retrieve_password_title() {
	return sprintf(__('Password Reset for %s', 'cell-email'), get_bloginfo('name'));
}

	
/* Retrieve Password Message
---------------------------------------------------------------
*/
add_filter ('retrieve_password_message', 'cell_retrieve_password_message',10,2);
function cell_retrieve_password_message($content, $key) {
	global $wpdb;
	$user_login = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_activation_key = '$key'");
	$reset_link = wp_login_url('url').'?action=rp&key='. $key .'&login='. $user_login;

	$email_subject = cell_retrieve_password_title();

	ob_start();
	include('template/email-header.php');

	printf(__('<p>It likes like you (hopefully) want to reset your password for your %s account.</p>', 'cell-email'), get_bloginfo('name'));

	printf(__('<p> To reset your password, visit the following address, otherwise just ignore this email and nothing will happen. <br> %s <p>', 'cell-email'), $reset_link);
	
	include('template/email-footer.php');
	$message = ob_get_contents();
	ob_end_clean();
  
	return $message;
}

/* wpmail() html wrapper
---------------------------------------------------------------
*/
function cell_email($to,$subject,$message_content) {
    $email_subject = $subject;
	
	ob_start();
	include('template/email-header.php');
	echo $message_content;
	include('template/email-footer.php');
	$message = ob_get_contents();
	ob_end_clean();
	
	$result = wp_mail($to, $subject, $message);
	return $result;
} 

?>