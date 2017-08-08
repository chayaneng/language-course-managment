<?php 
/*
Plugin Name: Langaue Course Manage
Description: Create Language course
Author: Chayan Biswas
Tags: course, language, list, course list, language list
Version: 1.0
License: GPL
*/

if( !defined('PLUGINS_PATH') ) define( 'PLUGINS_PATH', plugin_dir_path( __FILE__ ) );
if( !defined('PLUGINS_URL_ASSEST') ) define( 'PLUGINS_URL_ASSEST', plugins_url('asset/', __FILE__ ) );
if( !defined('PLUGINS_URL_URL') ) define( 'PLUGINS_URL', plugins_url('/', __FILE__ ) );

register_activation_hook( __FILE__, 'nc_create_location_table' );
function nc_create_location_table() {
    // makes the location table
    global $wpdb;
    $wpdb->show_errors();

    $table_name = $wpdb->prefix . "langcourse";

    $sql = "CREATE TABLE " . $table_name . " (
            id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (id),
            language VARCHAR (100),
            coursetype VARCHAR (55),
            startingdate VARCHAR (55),
            coursetime VARCHAR (55),
            price VARCHAR (75),
            endingtime VARCHAR (55) 
        );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
} 

add_action( 'plugins_loaded', 'langcourse_load' );
function langcourse_load() {
    new langCourse();
}

class langCourse {
    public function __construct() {
        add_action( 'wp_ajax_add_course', array( $this, 'add_course_ajax' ) );
        add_action( 'wp_ajax_nopriv_add_course', array( $this, 'add_course_ajax' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'js_css_files') );
        add_action( 'admin_notices', array( $this, 'custom_notice_on_action' ) );
        $this->admin_page_course();
    }

    public function js_css_files() {
        wp_enqueue_style( 'admin-css', PLUGINS_URL_ASSEST .'css/admin.css' );
        wp_enqueue_style( 'datetimepicker-css', PLUGINS_URL_ASSEST .'css/jquery.datetimepicker.min.css' );

        wp_enqueue_script('admin-custom', PLUGINS_URL_ASSEST.'js/admin.js', array( 'jquery'), '', true );
        wp_enqueue_script('datetimepicker-js', PLUGINS_URL_ASSEST.'js/jquery.datetimepicker.full.min.js', array( 'jquery'), '', true );
        wp_localize_script( 'admin-custom', 'course', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
    }

    public function add_course_ajax() {
        global $wpdb;
        $wpdb->show_errors();
        $table_name = $wpdb->prefix . "langcourse";

        if( $_POST ) {
            $languageName = isset( $_POST['languageName'] ) ? $_POST['languageName'] : '';
            $courseType = isset( $_POST['courseType'] ) ? $_POST['courseType'] : '';
            $startingDate = isset( $_POST['startingDate'] ) ? $_POST['startingDate'] : '';
            $startingTime = isset( $_POST['startingTime'] ) ? $_POST['startingTime'] : '';
            $price = isset( $_POST['price'] ) ? $_POST['price'] : '';
            $endingTime = isset( $_POST['endingTime'] ) ? $_POST['endingTime'] : '';

            $wpdb->insert(
                $table_name,
                array(
                    'language' => $languageName,
                    'coursetype' => $courseType,
                    'startingdate' => $startingDate,
                    'coursetime' => $startingTime,
                    'price' => $price,
                    'endingtime' => $endingTime
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $tag_instance = course_Tags::instance();
            $last_id = $tag_instance->last_id();
            $updated_id = intval( $last_id );
            $html = '<tr class="course-'.$updated_id.'">';
                $html .= '<td class="language">'.$languageName.'</td>';
                $html .= '<td class="coursetype">'.$courseType.'</td>';
                $html .= '<td class="startingdate">'.$startingDate.'</td>';
                $html .= '<td class="coursetime">'.$startingTime.'</td>';
                $html .= '<td class="price">'.$price.'</td>';
                $html .= '<td class="endingtime">'.$endingTime.'</td>';
                $html .= '<td class="edit_delete"><a id="edit-'.$updated_id.'" href="?page=lang-course&edit='.$updated_id.'">Edit</a><a href="?page=lang-course&delete='.$updated_id.'"> / Delete</a><a href="?cancel" id="cancel-'.$updated_id.'">Cancel</a></td>';
           $html .= '</tr>';
            echo $html;
        }
        die();
    }

    public function custom_notice_on_action() {
        $screen = get_current_screen();
        if( $screen->id == 'toplevel_page_lang-course' ) {
            $tag_instance = course_Tags::instance();
            $message = '';
            if( $tag_instance->delete_row_course( $_GET['delete'] ) ) {
                $message = 'Course has been deleted successfully!';
                echo '<div class="notice notice-success is-dismissible">
                        <p>'.$message .'</p>
                    </div>';
            }
            if( $tag_instance->form_process() ) {
                $message = 'Course has been updated successfully!';
                echo '<div class="notice notice-success is-dismissible">
                        <p>'.$message .'</p>
                    </div>';
            }
            
        }
    }

    public function admin_page_course() {
        require_once( PLUGINS_PATH .'/admin-page.php' );
        require_once( PLUGINS_PATH .'/template-tags.php' );
    }
}





