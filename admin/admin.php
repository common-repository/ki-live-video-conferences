<?php
/**
 *
 * return: zoom api functions
 */

namespace KiLiveVideoConferences;

define( 'KI_VC_ADMIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KI_VC_ADMIN_DIR', plugin_dir_path( __FILE__ ) );

include_once( KI_VC_ADMIN_DIR . 'ajax.php' );
include_once( KI_VC_ADMIN_DIR . 'menu.php' );
include_once( KI_VC_ADMIN_DIR . 'settings.php' );


class Ki_Admin {

	private static $_instance = null;




	/**
	 * Instance
	 *
	 * @since 1.0.0
	 */
	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 */
	private function __construct() {


		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_css_js' ) );

		//add_action( 'init', array( &$this, 'save_settings' ) );



		add_action( 'admin_init', array( &$this, 'quick_meeting' ) );



	}




	public function admin_enqueue_css_js() {


		wp_enqueue_style( 'ki-live-video-conferences-admin-styles', KI_VC_ADMIN_URL . 'assets/css/styles.css' );
		//wp_enqueue_style('ki-live-video-conferences-admin-datetimepicker', KI_VC_ADMIN_URL . 'assets/css/jquery.datetimepicker.css');

		if ( KiFunctions::v_get( 'page' ) == 'ki_overview_page' ) {

			wp_enqueue_script( 'jquery-ui-datepicker' );


			wp_enqueue_script( 'ki-live-video-conferences-tp', KI_VC_ADMIN_URL . 'assets/timepicker/jquery-ui-timepicker-addon.js', array( 'jquery-ui-datepicker' ), KI_VC_BASE_VERSION, true );
			wp_enqueue_style( 'ki-live-video-conferences-ui', KI_VC_ADMIN_URL . 'assets/timepicker/jquery-ui.css', null, KI_VC_BASE_VERSION );
			wp_enqueue_style( 'ki-live-video-conferences-tp', KI_VC_ADMIN_URL . 'assets/timepicker/jquery-ui-timepicker-addon.css', null, KI_VC_BASE_VERSION );

			wp_enqueue_style( 'ki-live-video-conferences-rainbow-calendar', KI_VC_ADMIN_URL . 'assets/calendar/css/eventCalendar.css', null, KI_VC_BASE_VERSION );
			wp_enqueue_style( 'ki-live-video-conferences-rainbow-calendar-theme', KI_VC_ADMIN_URL . 'assets/calendar/css/eventCalendar_theme_responsive.css', null, KI_VC_BASE_VERSION );
			wp_enqueue_script( 'ki-live-video-conferences-calendar-moment', KI_VC_ADMIN_URL . 'assets/calendar/js/moment.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );
			wp_enqueue_script( 'ki-live-video-conferences-calendar-eventcalendar', KI_VC_ADMIN_URL . 'assets/calendar/js/jquery.eventCalendar.js', array( 'ki-live-video-conferences-calendar-moment' ), KI_VC_BASE_VERSION, true );

			wp_enqueue_script( 'ki-live-video-conferences-chart', KI_VC_ADMIN_URL . 'assets/js/Chart.min.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );
			wp_enqueue_script( 'ki-live-video-conferences-admin-script', KI_VC_ADMIN_URL . 'assets/js/script.js', array(
				'jquery',
				'postbox'
			), KI_VC_BASE_VERSION, true );

		}

		wp_enqueue_script( 'ki-live-video-conferences-admin-new-user-role', KI_VC_ADMIN_URL . 'assets/js/new_user_role.js', array(
			'jquery'
		), KI_VC_BASE_VERSION, true );


	}


	public function quick_meeting() {
		if ( KiFunctions::v_post( 'ki-overview-action' ) == 'add_room' ) {

			if ( KiFunctions::v_post( 'doctor_id' ) != '' ) {
				$host_wp_id = KiFunctions::v_post( 'doctor_id' );
			}
			if ( KiFunctions::v_post( 'teacher_id' ) != '' ) {
				$host_wp_id = KiFunctions::v_post( 'teacher_id' );
			}
			$Zoom_Api = ki_publish_api_zoom();
			$UserID   = get_user_meta( $host_wp_id, 'zoom_host_id', true );
			if ( $UserID == '' ) {
				$UserID = $Zoom_Api->DefaultHost();
			}
			$_POST['userId'] = $UserID;


			$post = array(
				'post_type'    => 'zoom_video',
				'post_title'   => KiFunctions::v_post( 'post_title' ),
				'post_status'  => 'publish',
				'post_content' => KiFunctions::v_post( 'content' ),
				'post_author'  => get_current_user_id(),
			);

			$room_id = wp_insert_post( $post );

			if ( KiFunctions::v_post( 'doctor_id' ) != '' ) {

				$email_user_id = KiFunctions::v_post( 'patients' );
				$Doctors       = array( KiFunctions::v_post( 'doctor_id' ) );
				$Patients      = array( KiFunctions::v_post( 'patients' ) );

				$sDoctors  = serialize( $Doctors );
				$sPatients = serialize( $Patients );

				update_post_meta( $room_id, 'doctors', $sDoctors );
				update_post_meta( $room_id, 'patients', $sPatients );
			}

			if ( KiFunctions::v_post( 'teacher_id' ) != '' ) {
				$email_user_id = KiFunctions::v_post( 'students' );
				$Doctors       = array( KiFunctions::v_post( 'teacher_id' ) );
				$Patients      = array( KiFunctions::v_post( 'students' ) );

				$sDoctors  = serialize( $Doctors );
				$sPatients = serialize( $Patients );

				update_post_meta( $room_id, 'teachers', $sDoctors );
				update_post_meta( $room_id, 'students', $sPatients );
			}


			update_post_meta( $room_id, 'start_time', KiFunctions::v_post( 'start_time' ) );
			update_post_meta( $room_id, 'timezone', KiFunctions::v_post( 'timezone' ) );
			update_post_meta( $room_id, 'duration', KiFunctions::v_post( 'duration' ) );
			update_post_meta( $room_id, 'password', KiFunctions::v_post( 'password' ) );
		}
	}


}


Ki_Admin::getInstance();









