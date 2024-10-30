<?php

namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class KiView {

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
	 * Leads constructor.
	 * @since  1.0.0
	 * @access public
	 */
	private function __construct() {
		
		add_action( 'init', array( &$this, 'room_action' ) );

		add_action( 'um_pre_account_shortcode', array( &$this, 'um_account' ), 10, 1 );
		//add_action( 'um_members_directory_head', array( &$this, 'um_members' ), 10, 1 );


		add_action( 'um_before_patient_form_is_loaded', array( &$this, 'um_view_form_patient' ), 10, 1 );
		add_action( 'um_before_doctor_form_is_loaded', array( &$this, 'um_view_form_doctor' ), 10, 1 );
		add_action( 'um_before_create_meeting_request_form_is_loaded', array( &$this, 'um_view_form_appointment' ), 10, 1 );

		add_action( 'um_main_patient_fields',  array( &$this, 'um_add_patient_fields' ), 100 );
		add_action( 'um_main_doctor_fields',  array( &$this, 'um_add_doctor_fields' ), 100 );
		add_action( 'um_main_create_meeting_request_fields',  array( &$this, 'um_add_appointment_fields' ), 100 );
		add_action( 'um_submit_form_patient',  array( &$this, 'um_submit_patient_form' ), 100 );
		add_action( 'um_submit_form_doctor',  array( &$this, 'um_submit_doctor_form' ), 100 );
		add_action( 'um_submit_form_create_meeting_request',  array( &$this, 'um_submit_create_meeting_request' ), 100 );
		
		add_action( 'user_register', array( &$this, 'register_new_user' ) );
		
		
		add_filter('um_account_page_default_tabs_hook', array( &$this, 'add_um_account_page_default_tabs'));
		add_action('um_account_tab__meetings', array( &$this,  'um_account_tab__meetings'));
		add_filter('um_account_content_hook_meetings', array( &$this,  'um_account_content_hook_meetings'));
		

		add_action('um_account_tab__meetings_request', array( &$this,  'um_account_tab__meetings_request'));
		add_filter('um_account_content_hook_meetings_request', array( &$this,  'um_account_content_hook_meetings_request'));
		add_filter('um_account_content_hook_my_meetings_request', array( &$this,  'um_account_content_hook_my_meetings_request'));
		add_filter('um_account_content_hook_patients', array( &$this,  'um_account_content_hook_patients'));
		add_filter('um_account_content_hook_students', array( &$this,  'um_account_content_hook_students'));


        add_action( 'admin_notices', array( &$this,'zoom_reg_message') );
		
	}


	public function um_account( $attr ) {
		
		if(!empty(KiFunctions::v_get('ki_vc_room'))){
			if(Settings::get_option( 'video_chat_select_purpose', 'medicine' )=='medicine'){
				include_once( 'tmpl/views/forms/room.php' );
			}else{
				include_once( 'tmpl/views/forms/room_education.php' );
			}
			
			
		}
		if(KiFunctions::v_get('ki_vc_view')=='new_user'){
			include_once( 'tmpl/views/new_user.php' );
		}
		if(KiFunctions::v_get('ki_vc_view')=='meeting_request'){
			include_once( 'tmpl/views/meeting_request.php' );
		}
		
	}

	public function um_members( $attr ) {

		//include_once( 'tmpl/views/members.php' );
	}



	public function get_um_forms() {
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );

		$arrUM_FORMS = unserialize( $UM_FORMS );

		return $arrUM_FORMS;
	}

	public function um_view_form_patient( $args ) {

		$mode = 'patient';
		require 'tmpl/views/forms/default.php';
	}
	public function um_view_form_doctor ($args ) {

		$mode = 'doctor';
		require 'tmpl/views/forms/default.php';
	}
	
	public function um_view_form_appointment ($args ) {

		$mode = 'create_meeting_request';
		if(Settings::get_option( 'video_chat_select_purpose', 'medicine' )=='medicine'){
			require 'tmpl/views/forms/meeting_request.php';
		}else{
			require 'tmpl/views/forms/meeting_request_student.php';
		}
		
	}


	public function um_add_patient_fields( $args ) {
		echo UM()->fields()->display( 'patient', $args );
	}
	
	public function um_add_doctor_fields( $args ) {
		echo UM()->fields()->display( 'doctor', $args );
	}
	
	public function um_add_appointment_fields( $args ) {
		echo UM()->fields()->display( 'appointment', $args );
	}
	
	public function um_submit_patient_form($post){

		$submitted=$post['submitted'];
		
		if($submitted['user_password']!=$submitted['confirm_user_password'])return false;
		
		$userdata = array(
			'user_pass'    => $submitted['user_password'],
			'user_login'   => $submitted['user_login'],
			'user_email'   => $submitted['user_email'],
			'first_name'   => $submitted['first_name'],
			'last_name'    => $submitted['last_name'],
			'rich_editing' => 'true',
			'role'         => 'patient'
		);
		
		if(Settings::get_option( 'video_chat_select_purpose', 'medicine' )=='education'){
			$userdata['role']='student';
		}
		
		wp_insert_user( $userdata );
		header( "Location: " . $_SERVER['REQUEST_URI'] );
	}
	
	public function um_submit_doctor_form($post){

		$submitted=$post['submitted'];
		
		if($submitted['user_password']!=$submitted['confirm_user_password'])return false;
		$userdata = array(
			'user_pass'    => $submitted['user_password'],
			'user_login'   => $submitted['user_login'],
			'user_email'   => $submitted['user_email'],
			'first_name'   => $submitted['first_name'],
			'last_name'    => $submitted['last_name'],
			'rich_editing' => 'true',
			'role'         => 'doctor'
		);

		$user_id=wp_insert_user( $userdata );
		if(intval($user_id)>0){
			update_user_meta($user_id, Settings::get_slug().'_assistant_id',get_current_user_id());
		}
		
		header( "Location: " . $_SERVER['REQUEST_URI'] );
	}
	
	
	public function register_new_user($user_id){
		if(user_can( $user_id, 'doctor' ) OR user_can( $user_id, 'teacher' )){
			$user_info = get_userdata($user_id);
			$Zoom_Api  = ki_publish_api_zoom();
			$zoom_user_id=$Zoom_Api->CreateUser($user_info->user_email,$user_info->first_name,$user_info->last_name);


			if(!empty($zoom_user_id->id)){
				update_user_meta($user_id, 'zoom_host_id', $zoom_user_id->id);
            }else{
				update_user_meta($user_id, 'zoom_host_id', '');
			}
			
            if(!empty($zoom_user_id->message)){
				update_user_meta($user_id, 'zoom_message', $zoom_user_id->message);
				update_option(Settings::get_slug().'_zoom_reg_message',$zoom_user_id->message);



            }

		}
		
		if(KiFunctions::v_post('_wp_http_referer')=='/register/'){
			
			if(Settings::get_option( 'video_chat_select_purpose', 'medicine' )=='medicine'){
				wp_update_user(Array('ID'=>$user_id,'role'=>'patient'));
			}else{
				wp_update_user(Array('ID'=>$user_id,'role'=>'student'));
			}
			
			
			wp_redirect('/account/');
		}


	}

    public function zoom_reg_message(){
		$message=get_option( Settings::get_slug().'_zoom_reg_message');
	    if(!empty($message)){
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Zoom message: <?php echo $message; ?></p>
        </div>
        <?php
		update_option(Settings::get_slug().'_zoom_reg_message','');
        }
    }
	
	public function room_action(){
		if(!empty(KiFunctions::v_get('ki_vc_room')) AND !empty(KiFunctions::v_post('action_room'))){
			if(KiFunctions::v_post('action_room')=='new'){
				
				$post = array(
					'post_type' 	  	=> 'zoom_video',
					'post_title'		=> KiFunctions::v_post('post_title'),
					'post_status'		=> 'publish',
					'post_content'		=> KiFunctions::v_post('content'),
					'post_author'   	=> get_current_user_id(),
				);

				$room_id = wp_insert_post( $post );
				
				if(KiFunctions::v_post('doctor_id')!=''){
					$email_user_id=KiFunctions::v_post('patients');
					$Doctors=Array(KiFunctions::v_post('doctor_id'));
					$Patients=Array(KiFunctions::v_post('patients'));
					
					$sDoctors=serialize($Doctors);
					$sPatients=serialize($Patients);
					
					update_post_meta( $room_id, 'doctors', $sDoctors );
					update_post_meta( $room_id, 'patients', $sPatients );
				}
				
				if(KiFunctions::v_post('teacher_id')!=''){
					$email_user_id=KiFunctions::v_post('students');
					$Doctors=Array(KiFunctions::v_post('teacher_id'));
					$Patients=Array(KiFunctions::v_post('students'));
					
					$sDoctors=serialize($Doctors);
					$sPatients=serialize($Patients);
					
					update_post_meta( $room_id, 'teachers', $sDoctors );
					update_post_meta( $room_id, 'students', $sPatients );
				}
				
				
				
				update_post_meta( $room_id, 'start_time', KiFunctions::v_post('start_time') );
				update_post_meta( $room_id, 'timezone', KiFunctions::v_post('timezone') );
				update_post_meta( $room_id, 'duration', KiFunctions::v_post('duration') );
				update_post_meta( $room_id, 'password', KiFunctions::v_post('password') );
				
				
				$appointment_id=KiFunctions::v_post('appointment_id') ;
				if(!empty($appointment_id)){
					update_post_meta( $room_id, 'appointment', $appointment_id );
				
					update_post_meta( $appointment_id, 'room', $room_id );
				}
				
				
				
				$user_info = get_userdata($email_user_id);
				$user_email = $user_info->user_email;
				
				$url_room=get_the_permalink($room_id);
				
				$um_options=get_option( 'um_options');
				$subject=esc_attr($um_options['invite_meeting_sub']);
				
				$template_um_email = locate_template( array(
					trailingslashit( 'ultimate-member/email' ) .'invite_meeting.php'
				) );
				if($template_um_email==''){
					$template_um_email=KI_VC_DIR.'tmpl/um/email/invite_meeting.php';
				}
				$message=file_get_contents($template_um_email);

				$html_link='<a href="'.$url_room.'" target="_blank">'.$url_room.'</a>';
				
				$message=str_replace('{url_meeting}',$html_link,$message);
				

				wp_mail($user_email,$subject,$message);
				
			
		
				
				wp_redirect('/account/');
				exit;
			}else{
				$room_id = KiFunctions::v_get('ki_vc_room');
				$post = array(
					'ID'                => $room_id,
					'post_title'		=> KiFunctions::v_post('post_title'),
					'post_content'		=> KiFunctions::v_post('content'),
				);

				wp_update_post( $post );
				
				
				if(KiFunctions::v_post('doctor_id')!=''){

					$Doctors=Array(KiFunctions::v_post('doctor_id'));
					$Patients=Array(KiFunctions::v_post('patients'));
					
					$sDoctors=serialize($Doctors);
					$sPatients=serialize($Patients);
					
					update_post_meta( $room_id, 'doctors', $sDoctors );
					update_post_meta( $room_id, 'patients', $sPatients );
				}
				
				if(KiFunctions::v_post('teacher_id')!=''){

					$Doctors=Array(KiFunctions::v_post('teacher_id'));
					$Patients=Array(KiFunctions::v_post('students'));
					
					$sDoctors=serialize($Doctors);
					$sPatients=serialize($Patients);
					
					update_post_meta( $room_id, 'teachers', $sDoctors );
					update_post_meta( $room_id, 'students', $sPatients );
				}
				
				
				update_post_meta( $room_id, 'start_time', KiFunctions::v_post('start_time') );
				update_post_meta( $room_id, 'timezone', KiFunctions::v_post('timezone') );
				update_post_meta( $room_id, 'duration', KiFunctions::v_post('duration') );
				update_post_meta( $room_id, 'password', KiFunctions::v_post('password') );

				

				
			}
		}
	}
	
	
	public function um_submit_create_meeting_request($post){


		$submitted=$post['submitted'];
			
		
		$start_time=date('Y-m-d H:i',strtotime($submitted['date_meeting'].' '.$submitted['time_meeting']));
		
		$patient_info=get_userdata(get_current_user_id());
		
		$title=$patient_info->display_name.' '.$start_time;
		
		$post = array(
			'post_type' 	  	=> 'ki_appointment',
			'post_title'		=> wp_strip_all_tags($title),
			'post_status'		=> 'publish',
			'post_content'		=> KiFunctions::v_post('comment'),
			'post_author'   	=> get_current_user_id(),
		);

		$room_id = wp_insert_post( $post );
		
		if(KiFunctions::v_post('doctor')!=''){
			$Doctors=Array(KiFunctions::v_post('doctor'));
			$sDoctors=serialize($Doctors);
			update_post_meta( $room_id, 'doctors', $sDoctors );
			update_post_meta( $room_id, 'patient', get_current_user_id() );
		}
		if(KiFunctions::v_post('teacher')!=''){
			$Teachers=Array(KiFunctions::v_post('teacher'));
			$sTeachers=serialize($Teachers);
			update_post_meta( $room_id, 'teachers', $sTeachers );
			update_post_meta( $room_id, 'student', get_current_user_id() );
		}
		
		update_post_meta( $room_id, 'start_time', $start_time );
		
		
		update_post_meta( $room_id, 'first_name', $submitted['first_name']);
		update_post_meta( $room_id, 'last_name', $submitted['last_name']);
		update_post_meta( $room_id, 'user_email', $submitted['user_email']);
		update_post_meta( $room_id, 'mobile_number', $submitted['mobile_number']);
		update_post_meta( $room_id, 'birth_date', $submitted['birth_date']);
		update_post_meta( $room_id, 'postal_code', $submitted['postal_code']);
		update_post_meta( $room_id, 'card_number', $submitted['card_number']);
		update_post_meta( $room_id, 'version_code', $submitted['version_code']);
		update_post_meta( $room_id, 'reason_visit', $submitted['reason_visit']);
		
		$need_french='';
		if(!empty($submitted['need_french']))$need_french='need_french';
		update_post_meta( $room_id, 'need_french', $need_french);
		
		update_post_meta( $room_id, 'gender',  $submitted['gender'][0]);
		
		header( "Location: " . $_SERVER['REQUEST_URI'] );
	}
	
	public function add_um_account_page_default_tabs($tabs){
		//um-faicon-pencil
		//um-faicon-medkit
		//um-faicon-bookmark-o
		
		
		$UserID = get_current_user_id();
		
		//******************************************************
		//Medicine Tabs
		//******************************************************
		
		if ( user_can( $UserID, 'assistant' ) OR user_can( $UserID, 'doctor' )  ) {
			
			$tabs[343]['meetings'] = array(
				'icon'          => 'um-icon-videocamera',
				'title'         => 'All meetings',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
			
			
		}
		if ( user_can( $UserID, 'patient' )) {
			
			$tabs[343]['meetings'] = array(
				'icon'          => 'um-icon-videocamera',
				'title'         => 'Your meetings',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
			
			$tabs[344]['my_meetings_request'] = array(
				'icon'          => 'um-faicon-pencil',
				'title'         => 'Meeting request',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
			
			
		}
		
		if ( user_can( $UserID, 'assistant' )){
			$tabs[342]['meetings_request'] = array(
				'icon'          => 'um-faicon-pencil',
				'title'         => 'Meeting request',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
		}
		
		
		
		if ( user_can( $UserID, 'assistant' ) OR user_can( $UserID, 'doctor' ) ){
			$tabs[341]['patients'] = array(
				'icon'          => 'um-faicon-users',
				'title'         => 'Patients',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
		}
		
		//******************************************************
		//Education Tabs
		//******************************************************
		if ( user_can( $UserID, 'teacher' )){
			$tabs[343]['meetings'] = array(
				'icon'          => 'um-icon-videocamera',
				'title'         => 'All meetings',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
			$tabs[342]['meetings_request'] = array(
				'icon'          => 'um-faicon-pencil',
				'title'         => 'Meeting request',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
			$tabs[341]['students'] = array(
				'icon'          => 'um-faicon-users',
				'title'         => 'Students',
				//'submit_title'  => 'Update Meetings',
				'custom'  => true
			);
		}
		if ( user_can( $UserID, 'student' )) {
			
			$tabs[343]['meetings'] = array(
				'icon'          => 'um-icon-videocamera',
				'title'         => 'Your meetings',
				'custom'  => true
			);
			
			$tabs[344]['my_meetings_request'] = array(
				'icon'          => 'um-faicon-pencil',
				'title'         => 'Meeting request',
				'custom'  => true
			);
			
			
		}


		


		return $tabs;

	}
	
	public function um_account_tab__meetings( $info ) {

		global $ultimatemember;
		extract( $info );

		$output = $ultimatemember->account->get_tab_output('meetings');
		if ( $output ) { echo $output; }
	}
	public function um_account_content_hook_meetings( $output ){
		ob_start();
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );

				$arrUM_FORMS = unserialize( $UM_FORMS );
				
				$UserID = get_current_user_id();
				if ( user_can( $UserID, 'assistant' ) ) {
					include_once( 'tmpl/views/account_assistant.php' );
				}
				if ( user_can( $UserID, 'doctor' ) ) {
					include_once( 'tmpl/views/account_doctor.php' );
				}
				if ( user_can( $UserID, 'patient' ) ) {
					include_once( 'tmpl/views/account_patient.php' );
				}
				if ( user_can( $UserID, 'teacher' ) ) {
					include_once( 'tmpl/views/account_teacher.php' );
				}
				if ( user_can( $UserID, 'student' ) ) {
					include_once( 'tmpl/views/account_student.php' );
				}
			
		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}	
	
	public function um_account_tab__meetings_request( $info ) {
		
		global $ultimatemember;
		extract( $info );

		$output = $ultimatemember->account->get_tab_output('meetings_request');
	
		if ( $output ) { echo $output; }
	}
	
	public function um_account_content_hook_meetings_request( $output ){
		ob_start();
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );

				$arrUM_FORMS = unserialize( $UM_FORMS );
				
				$UserID = get_current_user_id();
				if ( user_can( $UserID, 'assistant' ) ){
					include_once( 'tmpl/views/account_meetings_request.php' );
				}
				if ( user_can( $UserID, 'teacher' ) ){
					include_once( 'tmpl/views/account_teacher_meetings_request.php' );
				}
				
			
		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	
	public function um_account_content_hook_my_meetings_request( $output ){
		ob_start();
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );

				$arrUM_FORMS = unserialize( $UM_FORMS );
				
				$UserID = get_current_user_id();
				if ( user_can( $UserID, 'patient' ) ){
					include_once( 'tmpl/views/account_patient_meetings_request.php' );
				}
				if ( user_can( $UserID, 'student' ) ){
					include_once( 'tmpl/views/account_student_meetings_request.php' );
				}

			
		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function um_account_content_hook_patients( $output ){
		ob_start();
		
		include( 'tmpl/views/account_list_patients.php' );

		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function um_account_content_hook_students( $output ){
		ob_start();
		
		include( 'tmpl/views/account_list_students.php' );

		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}

}


KiView::getInstance();






