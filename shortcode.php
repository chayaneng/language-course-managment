<?php 

class langShortCode {


	public function __construct() {

		add_shortcode( 'lang-table', array( $this, 'lang_table_show') );
		add_shortcode( 'lang_registration', array( $this, 'language_course_registration_form') );

	}

	public function lang_table_show( $args, $content ) {
		global $tag_instance;
		$courses 	= $tag_instance->course_data();
		ob_start(); ?>
		<div class="lang-table-wrapper">
			<div class="lang-table">
				<h1>List of the language courses</h1>
				<header class="table-header">
					<nav class="lang-heading">
						<ul>
							<li>Language</li>
							<li>Course Type</li>
							<li>Starting Date</li>
							<li>Starting Time</li>
							<li>Price</li>
							<li>Time</li>
						</ul>
					</nav>
				</header>
				<section class="table-middle">
					<nav class="lang-content">
						<?php if( $courses  && !is_wp_error($courses ) ) :
							foreach( $courses as $course ) :
						?>
						<ul>
							<li><?php echo $course->language ?></li>
							<li><?php echo $course->coursetype ?></li>
							<li><?php echo $course->startingdate ?></li>
							<li><?php echo $course->coursetime ?></li>
							<li><?php echo $course->price ?></li>
							<li><?php echo $course->endingtime ?></li>
							<li><a class="registartion" href="http://localhost/wp/comet/lang-registration-form/?form=lang-registration&id=<?php echo $course->id; ?>">Registration</a></li>
						</ul>
						<?php endforeach; endif; ?>
					</nav>
				</section>
					
			</div>
		</div>
		<?php $output = ob_get_clean();
		return $output;
	}


	public function language_course_registration_form() {
		if( isset( $_POST['lang-submit'] ) ) {
			send_mail_after_registration();
			notify_admin_on_registration();
		}

		ob_start(); ?>
		<div class="lang-table-wrapper">
			<div class="lang-table">
				<?php if( isset( $_POST['lang-submit'] ) ) : ?>
					<h1>Thanks for your registration!</h1>
					<h3>We will be in touch very soon!</h3>
				<?php else: ?>
				<h2>Give some information</h2>
				<form action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="post" class="lang-form-table">
					<input type="hidden" name="lang-id" value="<?php echo $_GET['id']; ?>">
					<input type="hidden" name="form" value="<?php echo $_GET['form']; ?>">
					<p>
						<label for="firstName">First Name</label>
						<input type="text" name="firstname" value="" class="lang-input" id="firstName">
					</p>
					<p>
						<label for="lastName">Last Name</label>
						<input type="text" name="lastname" value="" class="lang-input" id="lastName">
					</p>
					<p>
						<label for="subject">Subject</label>
						<input type="text" name="subject" value="" class="lang-input" id="subject">
					</p>
					<p>
						<label for="email">Email</label>
						<input type="text" name="email" value="" class="lang-input" id="email" required>
					</p>
					<p>
						<label for="message">Your message</label>
						<textarea name="message" id="message" cols="30" rows="8"></textarea>
					</p>
					<p>
						<input type="submit" name="lang-submit" value="Send">
					</p>

				</form>
				<?php endif; ?>
			</div>
		</div>
			
		<?php $output = ob_get_clean();
		return $output;
	}
}

new langShortCode;
