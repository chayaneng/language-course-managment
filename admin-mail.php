<?php

class admin_Mail {

	public $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'lang_setup_admin_mail_page') );
		add_action( 'admin_init', array( $this, 'register_admin_mail_value') );
		$this->options = get_option('lc-admin-mail');
	}

	public function register_admin_mail_value() {
		register_setting('lang-admin-mail', 'lc-admin-mail' );

		$args = array(
			'smtp' 		=> 'SMTP',
			'mail-info' => 'mail'
		);
		add_settings_section(
			'lc-smptp-section',
			'Mail transport setup',
			array( $this, 'lc_mail_desc_print' ),
			'lang-admin-mail',
			$args
		);
		add_settings_field(
			'lc-smtp-setup',
			'SMTP host name',
			array( $this, 'lc_smtp_field_print' ),
			'lang-admin-mail',
			'lc-smptp-section'
		);
		add_settings_field(
			'lc-smtp-port-setup',
			'SMTP port',
			array( $this, 'lc_smtp_port_print' ),
			'lang-admin-mail',
			'lc-smptp-section'
		);
		add_settings_field(
			'lc-username-setup',
			'Host username',
			array( $this, 'lc_host_username_setup' ),
			'lang-admin-mail',
			'lc-smptp-section'
		);
		add_settings_field(
			'lc-username',
			'Host username Nickname',
			array( $this, 'lc_host_username_nickname' ),
			'lang-admin-mail',
			'lc-smptp-section'
		);
		add_settings_field(
			'lc-password-setup',
			'Host username password',
			array( $this, 'lc_host_user_pass_setup' ),
			'lang-admin-mail',
			'lc-smptp-section'
		);


		add_settings_section(
			'lc-mail-info',
			'Mail Sending Info',
			array( $this, 'lc_mail_desc_print' ),
			'lang-admin-mail'
		);
		add_settings_field(
			'lc-admin-mail-account',
			'Admin Mail',
			array( $this, 'lc_admin_mail_setup' ),
			'lang-admin-mail',
			'lc-mail-info'
		);
		add_settings_field(
			'lc-admin-fullname',
			'Admin Fullname',
			array( $this, 'lc_admin_fullname_setup' ),
			'lang-admin-mail',
			'lc-mail-info'
		);
		add_settings_field(
			'lc-admin-mail-subject',
			'Mail to admin subject',
			array( $this, 'subject_to_admin_mail' ),
			'lang-admin-mail',
			'lc-mail-info'
		);

	}

	public function lc_mail_desc_print( $args ) {
		switch ( $args['id'] ) {
			case 'lc-smptp-section':
				echo 'From your hosting provider get SMTP informations or go your webmail and check your one of account.';
				break;
			case 'lc-mail-info':
				echo 'Set the admin user mail account and the subject when a notification to admin.';
				break;
		}
	}

	public function lc_smtp_field_print() {
		$smpt = $this->options;
		$smtp_value = isset( $smpt['smtp'] ) ? $smpt['smtp'] : '';
		?>
			<input type="text" name="lc-admin-mail[smtp]" value="<?php echo $smtp_value; ?>" class="regular-text">
			<p class="description">SMTP for example smtp.gmail.com</p>
		<?php
	}

	public function lc_smtp_port_print() {
		$smpt = $this->options;
		$smtp_port = isset( $smpt['smtp-port'] ) ? $smpt['smtp-port'] : '';
		?>
			<input type="text" name="lc-admin-mail[smtp-port]" value="<?php echo $smtp_port; ?>" class="regular-text">
			<p class="description">SMTP port such 465.</p>
		<?php
	}

	public function lc_host_username_setup() {
		$smpt = $this->options;
		$host_user = isset( $smpt['host-user'] ) ? $smpt['host-user'] : '';
		?>
			<input type="email" name="lc-admin-mail[host-user]" value="<?php echo $host_user; ?>" class="regular-text">
			<p class="description">What is the host username. i.e test@kalbukaraliai.lt</p>
		<?php 
	}

	public function lc_host_username_nickname() {
		$smpt = $this->options;
		$host_user_nick = isset( $smpt['host-user-nickname'] ) ? $smpt['host-user-nickname'] : '';
		?>
			<input type="text" name="lc-admin-mail[host-user-nickname]" value="<?php echo $host_user_nick; ?>" class="regular-text">
			<p class="description">What is the host username nick Nanme. i.e < 'test@kalbukaraliai.lt', 'John Doe' >?</p>
		<?php 
	}

	public function lc_host_user_pass_setup() {
		$smpt = $this->options;
		$host_user_password = isset( $smpt['host-user-password'] ) ? $smpt['host-user-password'] : '';
		?>
			<input type="password" name="lc-admin-mail[host-user-password]" value="<?php echo $host_user_password; ?>" class="regular-text">
			<p class="description">Password for username i.e test@kalbukaraliai.lt password</p>
		<?php 
	}

	public function lc_admin_mail_setup() {
		$smpt = $this->options;
		$admin_mail = !empty( $smpt['admin-mail'] ) ? $smpt['admin-mail'] : get_option('admin_email');
		?>
			<input type="email" name="lc-admin-mail[admin-mail]" value="<?php echo $admin_mail; ?>" class="regular-text">
			<p class="description">What would be amdin mail to get notifications?</p>
		<?php 
	}

	public function subject_to_admin_mail() {
		$smpt = $this->options;
		$admin_subject = !empty( $smpt['admin-subject'] ) ? $smpt['admin-subject'] : '';
		?>
			<input type="text" name="lc-admin-mail[admin-subject]" value="<?php echo $admin_subject; ?>" class="regular-text">
			<p class="description">Pretty subject. Such as A new notification!</p>
		<?php 
	}

	public function lc_admin_fullname_setup() {
		$smpt = $this->options;
		$admin_default_fullname = '';
		if( is_user_logged_in() && current_user_can('manage_options') ) {
			$id = get_current_user_id();
			$user_info = get_userdata($id);
	      	$username = $user_info->user_login;
	      	$first_name = $user_info->first_name;
	      	$last_name = $user_info->last_name;
	      	$admin_default_fullname = $first_name .' ' . $last_name;
		}
		
		$admin_fullname = !empty( $smpt['admin-fullname'] ) ? $smpt['admin-fullname'] : $admin_default_fullname;
		?>
			<input type="text" name="lc-admin-mail[admin-fullname]" value="<?php echo $admin_fullname; ?>" class="regular-text">
			<p class="description">What is admin full name!</p>
		<?php 
	}


	public function lang_setup_admin_mail_page() {
		add_submenu_page(
			'lang-course',
			'Mail setup',
			'Mail setup',
			'manage_options',
			'lang-admin-mail',
			array( $this, 'lang_amdin_mail_page_setup' )
		);
	}


	public function lang_amdin_mail_page_setup() {

		?>

			<div class="wrap">
				<h1>Mail setup settings</h1>
				<form action="options.php" method="post">
					<?php 
						settings_fields('lang-admin-mail');
						do_settings_sections('lang-admin-mail');
						submit_button();
					 ?>
				</form>
			</div>

		<?php
	}

}

new admin_Mail;