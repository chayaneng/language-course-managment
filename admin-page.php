<?php 
class admin_Page_Course {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'lang_course_menu') );
		add_action( 'admin_init', array( $this, 'lang_course_setting_value' ) );
	}

	public function lang_course_setting_value() {
		register_setting('lang-course-group', 'course-value', array( $this, 'sanitize_lang_value' ) );

		/**
		* Here is to add language section
		*
		* function language_add_info will print text
		*
		* @see add_settings_section() https://codex.wordpress.org/Function_Reference/add_settings_section
		*
		*/

		add_settings_section(
			'lang-section',
			'Add Language',
			array( $this, 'language_add_info' ),
			'lang-course'
		);

		/**
		* Here is to add language field
		*
		* function language_add_info will print text
		*
		* @see add_settings_field() https://codex.wordpress.org/Function_Reference/add_settings_field
		*
		*/

		add_settings_field(
			'language-add',
			'<label for="add-lang">Language</label>',
			array( $this, 'language_add_field' ),
			'lang-course',
			'lang-section'
		);
	}

	public function sanitize_lang_value( $old_value ) {
		$old_option = get_option( 'course-value' );
		if( count( $old_option ) > 0 && is_array( $old_option ) ) {
			$last_id = array_pop( explode( '-', key( $old_value ) ) );
			$old_option[ 'lang-' . $last_id ] = $old_value[ key($old_value) ];
			return $old_option;
		}
		return $old_value;
	}

	public function language_add_info() {}

	public function language_add_field() {
		$course_value = get_option('course-value');

		if( count( $course_value ) > 0 && is_array( $course_value ) ) {
			end( $course_value ); // Move the internal pointer to the last
			$last_id = array_pop( explode('-', key( $course_value ) ) );
			$id = count( $course_value ) == 0 ? '1' : $last_id + 1;
			reset( $course_value );	
		} else {
			$id = 1;
		}
		echo '<input type="text" name="course-value[lang-' . $id . ']" value="" id="add-lang">';
	}

	public function lang_course_menu() {
		add_menu_page(
			'Manage course',
			'Manage course',
			'manage_options',
			'lang-course',
			array( $this, 'lang_course_content' ),
			'dashicons-clipboard'
		);
	}

	public function lang_course_content() {
		$tag_instance = course_Tags::instance();
		if( isset( $_GET['delete'] ) ) {
			$tag_instance->delete_row_course( $_GET['delete'] );
		}
		$courses 	= $tag_instance->course_data();
		$list 	= $tag_instance->language_list();
		?>
			<div class="wrap lang_course-wrapper">
				<h1>Course Managment</h1>
				<?php settings_errors(); ?>
					<form action="options.php" method="POST">
						
						<?php 
							settings_fields('lang-course-group');
							do_settings_sections( 'lang-course');
							submit_button('Add', 'primary', '', false );
						?>
					</form>
					<hr>
					<form action="<?php echo admin_url(); ?>admin.php?page=lang-course" method="GET">
						<h2>Manage the courses of the Languages</h2>
						<input type="hidden" name="page" value="lang-course">
						<p><strong>Add/Edit/Delete</strong> the courses of this language choosen : 
							<select name="lang-list" id="" onchange="if( this.value != 0) this.form.submit();">
								<option value="select-lang">Select Language</option>
								<?php 
									$lang_list = array_reverse( get_option( 'course-value' ) );
									if( isset( $lang_list ) ) :
									foreach( $lang_list as $key => $value ) :
								 ?>
									<option value="<?php echo $key; ?>"<?php echo $_GET['lang-list'] == $key ? ' selected' : ''; ?>><?php echo $value; ?></option>
								<?php endforeach; endif; ?>
							</select><a class="all_courses" href="<?php echo admin_url(); ?>admin.php?page=lang-course">All courses</a></p>
					</form>
					
					<?php if( !isset( $_GET['lang-list'] ) || $_GET['lang-list'] == 'select-lang' ): ?>
					<form class="lang-course" action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="POST">
						
						<table>
							<tbody>
								<tr>
									<th>Name</th>
									<th>Course Type</th>
									<th>Starting Date</th>
									<th>Starting Time</th>
									<th>Price</th>
									<th>Time</th>
									<th>Action</th>
								</tr>
								<?php if( $courses ) : ?>
									<?php foreach( $courses as $course ) : ?>
										<tr class="course-<?php echo $course->id; ?>">
											<td class="language"><?php echo $course->language; ?></td>
											<td class="coursetype"><?php echo $course->coursetype; ?></td>
											<td class="startingdate"><?php echo $course->startingdate; ?></td>
											<td class="coursetime"><?php echo $course->coursetime; ?></td>
											<td><?php echo $course->price; ?></td>
											<td><?php echo $course->endingtime; ?></td>
											<td><a class="not-here" id="edit-<?php echo $course->id; ?>" href="?page=lang-course&lang-list=<?php echo $course->lang_list; ?>&edit=<?php echo $course->id; ?>">Edit</a><a onClick="return confirm('are you sure you want to delete??');" href="?page=lang-course&delete=<?php echo $course->id; ?>"> / Delete</a></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</form>
					<?php else: ?>
					<?php $courses = $tag_instance->sorted_courses( $_GET ); ?>
					<form class="lang-course" action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="POST">
						<input type="hidden" name="lang-list" value="<?php echo $_GET['lang-list']; ?>">
						<table>
							<tbody>
								<tr>
									<th>Name</th>
									<th>Course Type</th>
									<th>Starting Date</th>
									<th>Starting Time</th>
									<th>Price</th>
									<th>Time</th>
									<th>Action</th>
								</tr>
								<?php if( $courses ) : ?>
									<?php foreach( $courses as $course ) : ?>
										<tr class="course-<?php echo $course->id; ?>">
											<td class="language"><?php echo $course->language; ?></td>
											<td class="coursetype"><?php echo $course->coursetype; ?></td>
											<td class="startingdate"><?php echo $course->startingdate; ?></td>
											<td class="coursetime"><?php echo $course->coursetime; ?></td>
											<td class="price"><?php echo $course->price; ?></td>
											<td class="endingtime"><?php echo $course->endingtime; ?></td>
											<td class="edit_delete"><a class="course-edit" id="edit-<?php echo $course->id; ?>" href="?page=lang-course&edit=<?php echo $course->id; ?>">Edit</a><a onClick="return confirm('are you sure you want to delete??');" href="?page=lang-course&lang-list=<?php echo $_GET['lang-list'] ?>&delete=<?php echo $course->id; ?>"> / Delete</a><a href="?cancel" id="cancel-<?php echo $course->id; ?>">Cancel</a></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php echo $tag_instance->course_add_form() ?>
							</tbody>
						</table>
						<?php submit_button( 'Save Changes', 'primary lang-save', 'update_change', false ); ?>
						<a class="all_courses lang-save" href="<?php echo admin_url(); ?>admin.php?page=lang-course" >All Courses</a>
					</form>
				<?php endif; ?>
			</div>

		<?php

	}

}

new admin_Page_Course();