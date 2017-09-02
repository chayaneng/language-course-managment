<?php 

class course_Tags {
	
	private static $instance;

	public $last_id;


	public function __construct() {
		self::$instance = $this;
	}

	public static function instance() {
		if( self::$instance === NULL ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function form_process() {
		global $wpdb;
		$wpdb->show_errors();
		$table_name = $this->get_table_name();

		if( isset( $_POST['update_change'] ) ) {
			$post_data = $_POST;
			$data_count = count( $post_data );

			if( $data_count > 8 ) :

				$data_chunks =  array_splice( $post_data, 1 );
				$data_chunks = array_chunk( $data_chunks , 7 );
				$i = 1;
				$chunk_count = count($data_chunks) - 1;
				
				foreach ( $data_chunks as $key => $value) {
					if( $i <= $chunk_count ) {
						$result = $wpdb->update(
							$table_name,
							array(
								'lang_list'		=> $post_data['lang-list'],
								'language' 		=> $value[0],
								'coursetype' 	=> $value[2],
								'startingdate' 	=> $value[3],
								'coursetime' 	=> $value[4],
								'price' 		=> $value[5],
								'endingtime' 	=> $value[6]
							),
							array(
								'id'			=> $value[1]
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							),
							array(
								'%d'
							)
						);
					}
					$i++;
				}
				return $data_chunks;
			endif;
		} 
	}



	public function sorted_courses( $column ) {
		global $wpdb;
		$table_name = $this->get_table_name();
		$sql = "SELECT * FROM {$table_name} WHERE lang_list LIKE %s ORDER BY id DESC";
		$results = $wpdb->get_results( $wpdb->prepare( $sql, '%' . $column['lang-list'].'%' ), OBJECT );
		if( $results ) {
			return $results;
		}
	}

	public function language_list() {
		global $wpdb;
		$table_name = $this->get_table_name();
		$sql = "SELECT 
				(SELECT group_concat(DISTINCT language) FROM {$table_name}) as lang,
				(SELECT group_concat(DISTINCT coursetype) FROM {$table_name}) as type";

		$results = $wpdb->get_results( $sql, OBJECT );
		if( $results ) {
			return $results;
		}
	}


	public function delete_row_course( $id ) {
		global $wpdb;
		$table_name = $this->get_table_name();
		$delete = $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d') );
		return $delete;
	}

	public function get_table_name() {
		global $wpdb;
		$wpdb->show_errors();
		$table_name = $wpdb->prefix.'langcourse';
		return $table_name;
	}

	public function course_data() {
		global $wpdb;
		$table_name = $this->get_table_name();
		$sql = "SELECT * FROM {$table_name}";

		$results = $wpdb->get_results( $sql );
		return $results;
	}

	public function last_id() {
		global $wpdb;
		$table_name = $this->get_table_name();
		$sql = "SELECT MAX(id) as max FROM {$table_name}";
		$max = $wpdb->get_results( $sql );
		return $max[0]->max;
	}

	public function get_row_value( $id ) {
		global $wpdb;
		$table_name = $this->get_table_name();
		$sql = "SELECT * FROM {$table_name} WHERE id=%d";
		$results = $wpdb->get_row( $wpdb->prepare( $sql, $id), OBJECT );
		if( $results ) {
			return $results;
		}
	}

	public function course_add_form() {

		ob_start(); ?>
			<tr class="last-chid-add">
				<td>
					<input type="text" name="language_name" value="" class="language_name">
					<span class="errors"></span>
				</td>
				<td>
					<input type="text" name="course_type" value="" class="course_type">
					<span class="errors"></span>
				</td>
				<td>
					<input type="text" name="starting_date" value="" class="starting_date">
					<span class="errors"></span>
				</td>
				<td>
					<input type="text" name="starting_time" value="" class="starting_time">
					<span class="errors"></span>
				</td>
				<td>
					<input type="text" name="price" value="" class="courseprice">
					<span class="errors"></span>
				</td>
				<td>
					<input type="text" name="endingtime" value="" class="courseendingtime">
					<span class="errors"></span>
				</td>

				<td>
					<button type="button" class="button add_course">Add</button>
				</td>
			</tr>

		<?php $output = ob_get_clean();
		return $output;
	}

}
global $tag_instance;
$tag_instance = course_Tags::instance();