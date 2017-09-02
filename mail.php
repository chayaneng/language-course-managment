<?php 
global $mail_options;
$mail_options = get_option('lc-admin-mail');

// Set the transport with SMTP

function setup_smtp_transport() {
	global $mail_options;
	$smtp = isset( $mail_options['smtp'] ) ? $mail_options['smtp'] : '';
	$smtp_port = isset( $mail_options['smtp-port'] ) ? $mail_options['smtp-port'] : '';
	$host_user = isset( $mail_options['host-user'] ) ? $mail_options['host-user'] : '';
	$host_user_password = isset( $mail_options['host-user-password'] ) ? $mail_options['host-user-password'] : '';
	$transport = ( new Swift_SmtpTransport( $smtp, $smtp_port, 'ssl') )
			->setUsername( $host_user )
			->setPassword( $host_user_password );

	$mailer = new Swift_Mailer( $transport );
	return $mailer;
}

function set_swift_message( $subject, $to, $fullname, $body ) {
	global $mail_options;
	$host_user = isset( $mail_options['host-user'] ) ? $mail_options['host-user'] : '';
	$host_user_nicknam = isset( $mail_options['host-user-nickname'] ) ? $mail_options['host-user-nickname'] : '';

	$message = (new Swift_Message( $subject ) )
			->setFrom( array( $host_user => $host_user_nicknam ) )
			->setTo( array( $to => $fullname ) )
			->setBody( $body , 'text/html');

	return $message;
}

function set_body_on_registration() {
	global $tag_instance;
	$course_id 	= isset( $_POST['lang-id'] ) ? esc_html( intval( $_POST['lang-id'] ) ) : '';
	$row_data 	= $tag_instance->get_row_value( $course_id );
	$firstname 	= !empty( $_POST['firstname'] ) ? $_POST['firstname'] : '';
	$lastname 	= !empty( $_POST['lastname'] ) ? $_POST['lastname'] : '';
	$fullname 	= $firstname.' ' .$lastname;

	$output = '';
	$output .= '<h2>Hello, '. $fullname .'</h2>';
	$output .= '<p>Thanks for your registration. We will get in touch very soon.</p>';
	$output .= '<p>Here is the details of your registration : </p>';
	$output .= '<ul>';
		$output .= '<li>Language : <strong>'. $row_data->language .'</strong></li>';
		$output .= '<li>Course Type : <strong>'. $row_data->coursetype .'</strong></li>';
		$output .= '<li>Starting Date : <strong>'. $row_data->startingdate .'</strong></li>';
		$output .= '<li>Price : <strong>'. $row_data->endingtime .'</strong></li>';
		$output .= '<li>Ending Time : <strong>'. $row_data->endingtime .'</strong></li>';
	$output .= '</ul>';
	$output .= '<p>Check other <a href="http://kalbukaraliai.lt">courses</a></p>';
	return $output;
}


function set_body_for_admin_notification() {
	global $tag_instance;
	$course_id 	= isset( $_POST['lang-id'] ) ? esc_html( intval( $_POST['lang-id'] ) ) : '';
	$row_data 	= $tag_instance->get_row_value( $course_id );
	$firstname 	= !empty( $_POST['firstname'] ) ? $_POST['firstname'] : '';
	$lastname 	= !empty( $_POST['lastname'] ) ? $_POST['lastname'] : '';
	$fullname 	= $firstname.' ' .$lastname;
	$email 		= isset( $_POST['email'] ) && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) !== false ? $_POST['email'] : '';
	$message_body = isset( $_POST['message'] ) ? $_POST['message'] : '';

	$output = '';
	$output .= '<h2>New Registration from, '. $fullname .'</h2>';
	$output .= '<p>Email address: '. $email .'</p>';
	$output .= '<p>Here is the details of '. $fullname .'\'s registration : </p>';
	$output .= '<ul>';
		$output .= '<li>Language : <strong>'. $row_data->language .'</strong></li>';
		$output .= '<li>Course Type : <strong>'. $row_data->coursetype .'</strong></li>';
		$output .= '<li>Starting Date : <strong>'. $row_data->startingdate .'</strong></li>';
		$output .= '<li>Price : <strong>'. $row_data->endingtime .'</strong></li>';
		$output .= '<li>Ending Time : <strong>'. $row_data->endingtime .'</strong></li>';
	$output .= '</ul>';
	$output .= '<p>'.$message_body.'</p>';
	return $output;	
}



function send_mail_after_registration() {
	$mailer 	= setup_smtp_transport();
	$to 		= isset( $_POST['email'] ) && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) !== false ? $_POST['email'] : '';
	$subject 	= isset( $_POST['subject'] ) && $_POST['subject'] != '' ? wp_strip_all_tags( $_POST['subject'] ) : '';
	$firstname 	= isset( $_POST['firstname'] ) && $_POST['firstname'] != '' ? wp_strip_all_tags( $_POST['firstname'] ) : '';

	$lastname 	= isset( $_POST['lastname'] ) && $_POST['lastname'] != '' ? wp_strip_all_tags( $_POST['lastname'] ) : '';
	$fullname 	= $firstname . ' ' . $lastname;
	$body 		= set_body_on_registration();

	$message = set_swift_message( $subject, $to, $fullname, $body );

	$result = $mailer->send( $message );
	return $result;
}

function notify_admin_on_registration() {
	global $mail_options;
	$mail_subject = isset( $mail_options['admin-subject'] ) ? $mail_options['admin-subject'] : '';
	$admin_mail = isset( $mail_options['admin-mail'] ) ? $mail_options['admin-mail'] : '';
	$admin_name = isset( $mail_options['admin-fullname'] ) ? $mail_options['admin-fullname'] : '';

	$mailer 	= setup_smtp_transport();
	$subject  	= $mail_subject;
	$to 		= $admin_mail;
	$fullname 	= $admin_name;
	$body 		= set_body_for_admin_notification();
	$message = set_swift_message( $subject, $to, $fullname, $body );
	
	$result = $mailer->send( $message );
	return $result;
}