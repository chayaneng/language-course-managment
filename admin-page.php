<?php 
class admin_Page_Course {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'lang_course_menu') );
	}

	public function lang_course_menu() {
		add_menu_page(
			'Course Managment',
			'Course Managment',
			'manage_options',
			'lang-course',
			array( $this, 'lang_course_content' )
		);
	}

	public function lang_course_content() {
		$tag_instance = course_Tags::instance();
		if( isset( $_GET['delete'] ) ) {
			$tag_instance->delete_row_course( $_GET['delete'] );
		}

		$tag_instance->form_process();
		$courses 	= $tag_instance->course_data();
		$list 	= $tag_instance->language_list();
		
		?>
			<div class="wrap lang_course">
				<h1>Course Managment</h1>
				<?php settings_errors(); ?>
					<form action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="POST">
						<p>
							<label for="language-list">Language List</label>
							<select name="language-list" id="language-list"'>
								<?php if( $list ) : ?>
									<?php
										foreach( $list as $language ) : 
											$lang_list = explode( ',', $language->lang );
											foreach( $lang_list as $lang ) :
									?>
										<option value="<?php echo $lang; ?>" <?php echo $_POST['language-list'] == $lang ? ' selected' : ''; ?>><?php echo ucfirst( $lang ); ?></option>
									<?php endforeach; endforeach; ?>
								<?php endif; ?>
							</select>
							&nbsp;

							


							<label for="coursetype-list">Language List</label>
							<select name="coursetype-list" id="coursetype-list" onchange='if(this.value != 0) { this.form.submit(); }'>

								<?php if( $list ) : ?>
									<?php
										foreach( $list as $course_list ) : 
											$course_list = explode( ',', $language->type );
											foreach( $course_list as $type ) :
									?>
										<option value="<?php echo $type; ?>" <?php echo $_POST['coursetype-list'] == $type ? ' selected' : ''; ?>><?php echo ucfirst( $type ); ?></option>
									<?php endforeach; endforeach; ?>
								<?php endif; ?>
							</select>
						</p>
						
					</form>
					
					<?php if( !isset( $_POST['language-list'] ) ): ?>
					<form action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="POST">
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
											<td class="edit_delete"><a class="course-edit" id="edit-<?php echo $course->id; ?>" href="?page=lang-course&edit=<?php echo $course->id; ?>">Edit</a><a href="?page=lang-course&delete=<?php echo $course->id; ?>"> / Delete</a><a href="?cancel" id="cancel-<?php echo $course->id; ?>">Cancel</a></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php echo $tag_instance->course_add_form() ?>
							</tbody>
						</table>
						<?php submit_button(); ?>
					</form>
					<?php else: ?>
						<?php $courses = $tag_instance->sorted_courses( $_POST ); ?>
					<form action="<?php echo $_REQUEST['PHP_SELF']; ?>" method="POST">
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
											<td class="edit_delete"><a class="course-edit" id="edit-<?php echo $course->id; ?>" href="?page=lang-course&edit=<?php echo $course->id; ?>">Edit</a><a href="?page=lang-course&delete=<?php echo $course->id; ?>"> / Delete</a><a href="?cancel" id="cancel-<?php echo $course->id; ?>">Cancel</a></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php echo $tag_instance->course_add_form() ?>
							</tbody>
						</table>
						<?php submit_button(); ?>
					</form>
				<?php endif; ?>

					<script>
						(function( $ ) {
							$(document).ready( function() {
								$('.starting_date').datetimepicker({
									lang:'en',
									timepicker:false,
									format:'Y/m/d',
									formatDate:'Y/m/d',
									minDate:'-1970/01/02', // yesterday is minimum date
									maxDate:'+2050/01/02' // and tommorow is maximum date calendar
								});

								$('.starting_time').datetimepicker({
									datepicker:false,
									format:'g:i a',
									step : 5
								});

								$('.courseendingtime').datetimepicker({
									datepicker:false,
									format:'g:i a',
									step : 5
								});
							});
						})(jQuery);
					</script>
			</div>

		<?php

	}

}

new admin_Page_Course();