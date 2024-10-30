<?php
/**
 * Ajax
 */

namespace KiLiveVideoConferences;


class KiVC_AdminAjax {

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

	private function __construct() {


		add_action( 'wp_ajax_ki_live_video_conferences_admin_member_autocomplete', array( &$this, 'member_autocomplete' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_admin_member_autocomplete', array( &$this, 'member_autocomplete' ) );

		add_action( 'wp_ajax_ki_live_video_conferences_admin_member_search', array( &$this, 'member_asearch' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_admin_member_search', array( &$this, 'member_asearch' ) );
		
		add_action( 'wp_ajax_ki_live_video_conferences_quick_meeting', array( &$this, 'quick_meeting' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_quick_meeting', array( &$this, 'quick_meeting' ) );


	}

	public function member_autocomplete() {
		$autocomplet=Array();
		$search=KiFunctions::v_post( 'search' );
		if($search!=''){
			$infoMember=KiFunctions::member();
			$members=get_users(Array('role'=>$infoMember['role']));
			foreach ( $members as $id => $member ) {
				if(strpos(KiFunctions::get_user_name($member),$search)===0){
					$autocomplet[]=KiFunctions::get_user_name($member);
				}
			}
		}
		echo json_encode( $autocomplet );
		exit;
	}
	
	public function member_asearch() {
		$autocomplet=Array();
		$search=KiFunctions::v_post( 'search' );
		if($search!=''){
			$infoMember=KiFunctions::member();
			$members=get_users(Array('role'=>$infoMember['role']));
			foreach ( $members as $id => $member ) {
				if(strpos(KiFunctions::get_user_name($member),$search)===0){
					$autocomplet[]=array(
						'id'=>$member->ID,
						'href'=>home_url( 'wp-admin/user-edit.php?user_id=' . $member->ID),
						'name'=>KiFunctions::get_user_name($member)
					);;
				}
			}
		}
		echo json_encode( $autocomplet );
		exit;
	}
	
	
	public function quick_meeting()
    {
		




		if (KiFunctions::v_post('doctor_id') != '') {
			$host_wp_id = KiFunctions::v_post('doctor_id');
		}
		if (KiFunctions::v_post('teacher_id') != '') {
			$host_wp_id = KiFunctions::v_post('teacher_id');
		}
		$Zoom_Api = ki_publish_api_zoom();
		$UserID = get_user_meta($host_wp_id, 'zoom_host_id', true);
		if ($UserID == '') {
			$UserID = $Zoom_Api->DefaultHost();
		}
		$_POST['userId'] = $UserID;


		$post = array(
			'post_type' => 'zoom_video',
			'post_title' => KiFunctions::v_post('post_title'),
			'post_status' => 'publish',
			'post_content' => KiFunctions::v_post('content'),
			'post_author' => get_current_user_id(),
		);

		$room_id = wp_insert_post($post);

		if (KiFunctions::v_post('doctor_id') != '') {

			$email_user_id = KiFunctions::v_post('patients');
			$Doctors = array(KiFunctions::v_post('doctor_id'));
			$Patients = array(KiFunctions::v_post('patients'));

			$sDoctors = serialize($Doctors);
			$sPatients = serialize($Patients);

			update_post_meta($room_id, 'doctors', $sDoctors);
			update_post_meta($room_id, 'patients', $sPatients);
		}

		if (KiFunctions::v_post('teacher_id') != '') {
			$email_user_id = KiFunctions::v_post('students');
			$Doctors = array(KiFunctions::v_post('teacher_id'));
			$Patients = array(KiFunctions::v_post('students'));

			$sDoctors = serialize($Doctors);
			$sPatients = serialize($Patients);

			update_post_meta($room_id, 'teachers', $sDoctors);
			update_post_meta($room_id, 'students', $sPatients);
		}


		update_post_meta($room_id, 'start_time', KiFunctions::v_post('start_time'));
		update_post_meta($room_id, 'timezone', KiFunctions::v_post('timezone'));
		update_post_meta($room_id, 'duration', KiFunctions::v_post('duration'));
		update_post_meta($room_id, 'password', KiFunctions::v_post('password'));
		
		$result=Array(
			'post_id'=>$room_id,
			'post_title'=>KiFunctions::v_post('post_title'),
			'href'=>home_url( 'wp-admin/post.php?post=' . esc_attr( $room_id ) . '&action=edit' ),
			'page'=>get_permalink( $room_id ),
		);
		echo json_encode( $result );
		exit;

    }
	

}


KiVC_AdminAjax::getInstance();









