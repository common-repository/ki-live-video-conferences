<?php
/**
 *
 * return: introduction Ultimate Member  Plugin
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Ki_Include_UM{

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


        add_action( 'init', array( &$this, 'plugin_um_install' ) );
        add_filter('um_email_notifications', array( &$this, 'um_email_add_template'));



		//add_action( 'init', array( &$this, 'um_save_form_fields' ) );
		//add_action( 'init', array( &$this, 'zoom_dev_fix' ) );
		
		
	
		

	}

	

    
	
	public function plugin_um_install(){
		
		//********************************************
		//Install
		//********************************************
		//Add Form UM 
		
		
		//update_option(Settings::get_slug().'_um_forms','');
	
		if(get_option( Settings::get_slug().'_um_forms')==''){
			
	
		
		//$form_fields='a:6:{s:10:"user_login";a:15:{s:5:"title";s:8:"Username";s:7:"metakey";s:10:"user_login";s:4:"type";s:4:"text";s:5:"label";s:8:"Username";s:8:"required";i:1;s:6:"public";i:1;s:8:"editable";i:0;s:8:"validate";s:15:"unique_username";s:9:"min_chars";i:3;s:9:"max_chars";i:24;s:8:"position";s:1:"1";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:10:"user_email";a:13:{s:5:"title";s:14:"E-mail Address";s:7:"metakey";s:10:"user_email";s:4:"type";s:4:"text";s:5:"label";s:14:"E-mail Address";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"validate";s:12:"unique_email";s:8:"position";s:1:"4";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:13:"user_password";a:16:{s:5:"title";s:8:"Password";s:7:"metakey";s:13:"user_password";s:4:"type";s:8:"password";s:5:"label";s:8:"Password";s:8:"required";i:1;s:6:"public";i:1;s:8:"editable";i:1;s:9:"min_chars";i:8;s:9:"max_chars";i:30;s:15:"force_good_pass";i:1;s:18:"force_confirm_pass";i:1;s:8:"position";s:1:"5";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:10:"first_name";a:12:{s:5:"title";s:10:"First Name";s:7:"metakey";s:10:"first_name";s:4:"type";s:4:"text";s:5:"label";s:10:"First Name";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"position";s:1:"2";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:9:"last_name";a:12:{s:5:"title";s:9:"Last Name";s:7:"metakey";s:9:"last_name";s:4:"type";s:4:"text";s:5:"label";s:9:"Last Name";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"position";s:1:"3";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:9:"_um_row_1";a:4:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_1";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";}}';
		
		$array_form_fields = $this->um_get_setup_form_field('mew_patient');	
		
			$form = array(
				'post_type' 	  	=> 'um_form',
				'post_title'		=> 'New Patient',
				'post_status'		=> 'publish',
				'post_author'   	=> get_current_user_id(),
			);

			$form_id_patient = wp_insert_post( $form );
			
			update_post_meta( $form_id_patient, '_um_custom_fields', $array_form_fields );
			update_post_meta( $form_id_patient, '_um_mode', 'patient');
			update_post_meta( $form_id_patient, '_um_core', 'patient');
			update_post_meta( $form_id_patient, '_um_patient_use_custom_settings', 0 );
			

			//*************************************************
			
			$form = array(
				'post_type' 	  	=> 'um_form',
				'post_title'		=> 'New Doctor',
				'post_status'		=> 'publish',
				'post_author'   	=> get_current_user_id(),
			);

			$form_id_doctor = wp_insert_post( $form );
			
			update_post_meta( $form_id_doctor, '_um_custom_fields', $array_form_fields );
			update_post_meta( $form_id_doctor, '_um_mode', 'doctor');
			update_post_meta( $form_id_doctor, '_um_core', 'doctor');
			update_post_meta( $form_id_doctor, '_um_doctor_use_custom_settings', 0 );
			
			//*************************************************
			//$form_fields='a:5:{s:7:"comment";a:13:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:8:"textarea";s:5:"title";s:7:"Comment";s:7:"metakey";s:7:"comment";s:6:"height";s:5:"100px";s:10:"visibility";s:3:"all";s:5:"label";s:7:"Comment";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"position";s:1:"4";s:8:"in_group";s:0:"";}s:12:"phone_number";a:14:{s:5:"title";s:12:"Phone Number";s:7:"metakey";s:12:"phone_number";s:4:"type";s:4:"text";s:5:"label";s:12:"Phone Number";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"validate";s:12:"phone_number";s:4:"icon";s:15:"um-faicon-phone";s:8:"position";s:1:"3";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:12:"date_meeting";a:17:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"date";s:5:"title";s:4:"Date";s:7:"metakey";s:12:"date_meeting";s:5:"range";s:5:"years";s:5:"years";s:2:"50";s:7:"years_x";s:5:"equal";s:10:"visibility";s:3:"all";s:5:"label";s:4:"Date";s:6:"public";s:1:"1";s:6:"format";s:5:"j M Y";s:13:"pretty_format";s:1:"0";s:8:"editable";s:1:"1";s:8:"position";s:1:"1";s:8:"in_group";s:0:"";}s:12:"time_meeting";a:14:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"time";s:5:"title";s:4:"Time";s:7:"metakey";s:12:"time_meeting";s:6:"format";s:5:"g:i a";s:10:"visibility";s:3:"all";s:5:"label";s:4:"Time";s:6:"public";s:1:"1";s:9:"intervals";s:2:"60";s:8:"editable";s:1:"1";s:8:"position";s:1:"2";s:8:"in_group";s:0:"";}s:9:"_um_row_1";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_1";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";s:6:"origin";s:9:"_um_row_1";}}';
			//$form_fields='a:19:{s:12:"date_meeting";a:17:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"date";s:7:"metakey";s:12:"date_meeting";s:8:"position";s:1:"1";s:5:"title";s:12:"Date Meeting";s:5:"range";s:5:"years";s:5:"years";s:2:"50";s:7:"years_x";s:5:"equal";s:10:"visibility";s:3:"all";s:5:"label";s:12:"Date Meeting";s:6:"public";s:1:"1";s:6:"format";s:5:"j M Y";s:13:"pretty_format";s:1:"0";s:8:"editable";s:1:"1";s:8:"in_group";s:0:"";}s:12:"time_meeting";a:14:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"2";s:4:"type";s:4:"time";s:7:"metakey";s:12:"time_meeting";s:8:"position";s:1:"2";s:5:"title";s:12:"Time Meeting";s:6:"format";s:5:"g:i a";s:10:"visibility";s:3:"all";s:5:"label";s:12:"Time Meeting";s:6:"public";s:1:"1";s:9:"intervals";s:2:"60";s:8:"editable";s:1:"1";s:8:"in_group";s:0:"";}s:10:"first_name";a:12:{s:5:"title";s:10:"First Name";s:7:"metakey";s:10:"first_name";s:4:"type";s:4:"text";s:5:"label";s:10:"First Name";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"position";s:1:"6";s:6:"in_row";s:9:"_um_row_3";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:9:"last_name";a:12:{s:5:"title";s:9:"Last Name";s:7:"metakey";s:9:"last_name";s:4:"type";s:4:"text";s:5:"label";s:9:"Last Name";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"position";s:1:"7";s:6:"in_row";s:9:"_um_row_3";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:10:"user_email";a:13:{s:5:"title";s:14:"E-mail Address";s:7:"metakey";s:10:"user_email";s:4:"type";s:4:"text";s:5:"label";s:14:"E-mail Address";s:8:"required";i:0;s:6:"public";i:1;s:8:"validate";s:12:"unique_email";s:12:"autocomplete";s:3:"off";s:8:"position";s:1:"8";s:6:"in_row";s:9:"_um_row_3";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:13:"mobile_number";a:15:{s:6:"in_row";s:9:"_um_row_4";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"text";s:7:"metakey";s:13:"mobile_number";s:8:"position";s:1:"9";s:5:"title";s:13:"Mobile Number";s:4:"help";s:73:"Important! You will get a text message when your visit is about to start.";s:10:"visibility";s:3:"all";s:5:"label";s:13:"Mobile Number";s:6:"public";s:1:"1";s:8:"validate";s:12:"phone_number";s:8:"editable";s:1:"1";s:4:"icon";s:16:"um-faicon-mobile";s:8:"in_group";s:0:"";}s:6:"gender";a:13:{s:5:"title";s:6:"Gender";s:7:"metakey";s:6:"gender";s:4:"type";s:5:"radio";s:5:"label";s:6:"Gender";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:7:"options";a:2:{i:0;s:4:"Male";i:1;s:6:"Female";}s:8:"position";s:2:"11";s:6:"in_row";s:9:"_um_row_5";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:11:"postal_code";a:12:{s:6:"in_row";s:9:"_um_row_5";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"text";s:5:"title";s:11:"Postal code";s:7:"metakey";s:11:"postal_code";s:10:"visibility";s:3:"all";s:5:"label";s:11:"Postal code";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"position";s:2:"12";s:8:"in_group";s:0:"";}s:12:"reason_visit";a:14:{s:6:"in_row";s:9:"_um_row_5";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:8:"textarea";s:7:"metakey";s:12:"reason_visit";s:8:"position";s:2:"13";s:5:"title";s:16:"Reason for visit";s:4:"help";s:20:"1000 characters left";s:6:"height";s:5:"100px";s:10:"visibility";s:3:"all";s:5:"label";s:16:"Reason for visit";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"in_group";s:0:"";}s:11:"need_french";a:13:{s:6:"in_row";s:9:"_um_row_5";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:8:"checkbox";s:7:"metakey";s:11:"need_french";s:8:"position";s:2:"14";s:5:"title";s:43:"J&rsquo;ai besoin que ma visite soit en français";s:7:"options";a:1:{i:0;s:43:"J&rsquo;ai besoin que ma visite soit en français";}s:10:"visibility";s:3:"all";s:5:"label";s:43:"J&rsquo;ai besoin que ma visite soit en français";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"in_group";s:0:"";}s:11:"card_number";a:12:{s:4:"type";s:4:"text";s:5:"title";s:19:"Health card number ";s:7:"metakey";s:11:"card_number";s:10:"visibility";s:3:"all";s:5:"label";s:19:"Health card number ";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"position";s:1:"3";s:6:"in_row";s:9:"_um_row_2";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:12:"version_code";a:12:{s:4:"type";s:4:"text";s:5:"title";s:12:"Version code";s:7:"metakey";s:12:"version_code";s:10:"visibility";s:3:"all";s:5:"label";s:12:"Version code";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"position";s:1:"4";s:6:"in_row";s:9:"_um_row_2";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"2";s:8:"in_group";s:0:"";}s:8:"user_url";a:15:{s:6:"in_row";s:9:"_um_row_2";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"3";s:4:"type";s:3:"url";s:7:"metakey";s:8:"user_url";s:8:"position";s:1:"5";s:5:"title";s:11:"Website URL";s:10:"visibility";s:3:"all";s:5:"label";s:11:"Website URL";s:10:"url_target";s:6:"_blank";s:7:"url_rel";s:6:"follow";s:6:"public";s:1:"1";s:8:"validate";s:3:"url";s:8:"editable";s:1:"0";s:8:"in_group";s:0:"";}s:10:"birth_date";a:16:{s:5:"title";s:10:"Birth Date";s:7:"metakey";s:10:"birth_date";s:4:"type";s:4:"date";s:5:"label";s:10:"Birth Date";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:13:"pretty_format";i:1;s:5:"years";i:115;s:7:"years_x";s:4:"past";s:4:"icon";s:18:"um-faicon-calendar";s:8:"position";s:2:"10";s:6:"in_row";s:9:"_um_row_5";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:9:"_um_row_1";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_1";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"2";s:6:"origin";s:9:"_um_row_1";}s:9:"_um_row_2";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_2";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"3";s:6:"origin";s:9:"_um_row_2";}s:9:"_um_row_3";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_3";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";s:6:"origin";s:9:"_um_row_3";}s:9:"_um_row_4";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_4";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";s:6:"origin";s:9:"_um_row_4";}s:9:"_um_row_5";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_5";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";s:6:"origin";s:9:"_um_row_5";}}';
			
			
			$array_form_fields = $this->um_get_setup_form_field('create_meeting_request');	
			$form = array(
				'post_type' 	  	=> 'um_form',
				'post_title'		=> 'Create a meeting request',
				'post_status'		=> 'publish',
				'post_author'   	=> get_current_user_id(),
			);

			$form_id_appointment = wp_insert_post( $form );
			
			update_post_meta( $form_id_appointment, '_um_custom_fields', $array_form_fields );
			update_post_meta( $form_id_appointment, '_um_mode', 'create_meeting_request');
			update_post_meta( $form_id_appointment, '_um_core', 'create_meeting_request');
			update_post_meta( $form_id_appointment, '_um_create_meeting_request_use_custom_settings', 0 );
			
			
			//*******************************************************************
			//Save ID Forms
			
			$UM_Forms=Array(
				'shortcod_patient'=>'[ultimatemember form_id="'.$form_id_patient.'"]',
				'shortcod_doctor'=>'[ultimatemember form_id="'.$form_id_doctor.'"]',
				'shortcod_create_meeting_reques'=>'[ultimatemember form_id="'.$form_id_appointment.'"]',
				'post_id_doctor'=>$form_id_doctor,
				'post_id_patient'=>$form_id_patient,
				'post_id_create_meeting_request'=>$form_id_appointment
			);
			
			$sUM_Forms=serialize($UM_Forms);
			
            update_option(Settings::get_slug().'_um_forms',$sUM_Forms);

        }
		
		
		//*******************************************************************
		//Add Template UM Email
		$template_um = locate_template( array(
					trailingslashit( 'ultimate-member/email' ) .'invite_meeting.php'
		) );

		if($template_um==''){
			//Update um options
			$um_options=get_option( 'um_options');
			
			$um_options['invite_meeting_on']=true;
			$um_options['invite_meeting_sub']='Patient Invite Meeting';
			$um_options['invite_meeting']='<p>Url meeting: {url_meeting}</p>';
			update_option( 'um_options', $um_options );
			
			//Create folder
			if(is_dir(get_template_directory().'/ultimate-member/')==false){
				mkdir(get_template_directory().'/ultimate-member/');
				mkdir(get_template_directory().'/ultimate-member/email/');
			}
			//Add template file

			$source=KI_VC_DIR.'tmpl/um/email/invite_meeting.php';
			$um_path=get_template_directory().'/ultimate-member/email/invite_meeting.php';

			copy($source,$um_path);
		}
		

		
	}
	
	public function um_get_setup_form_field($name){
		$form_fields=file_get_contents(KI_VC_DIR.'tmpl/um/setup_form_fields/'.$name.'.dat');
			
			
		$array_form_fields = unserialize( $form_fields );
		return $array_form_fields;		
	}
	
	public function um_email_add_template($notifications){
		$notifications['invite_meeting']=array(
			'key'           => 'invite_meeting',
			'title'         => __( 'Patient Invite Meeting','ultimate-member' ),
			'subject'       => 'Your account at {site_name} was updated',
			'body'          => 'Hi {display_name},<br /><br />' .
							   'You recently updated your {site_name} account.<br /><br />' .
							   'If you did not make this change and believe your {site_name} account has been compromised, please contact us at the following email address: {admin_email}<br /><br />' .
							   'Thanks,<br />' .
							   '{site_name}',
			'description'   => __('Whether to send the user an email when he updated their account','ultimate-member'),
			'recipient'     => 'user',
			'default_active'=> true
		);
		

		return $notifications;
	}
	
	public  function um_save_form_fields(){
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );
		$um_forms = unserialize( $UM_FORMS );
		
		$Forms=Array(
			'post_id_create_meeting_request'=>'create_meeting_request',
			'post_id_doctor'=>'new_doctor',
			'post_id_patient'=>'mew_patient'
		);
		
		foreach($Forms as $key=>$type){
			$id=$um_forms[$key];
			$field_source=get_post_meta( $id, '_um_custom_fields');
			$sField_source= serialize($field_source[0]);
			$file=KI_VC_DIR.'tmpl/um/setup_form_fields/'.$type.'.dat';
			$fp = fopen($file, 'w');
			fwrite($fp, $sField_source );
			fclose($fp);
		}
		
		
	}
	public  function zoom_dev_fix(){
		$UM_FORMS = get_option( Settings::get_slug() . '_um_forms' );

		$um_forms = unserialize( $UM_FORMS );
		$id=$um_forms['post_id_appointment'];

		$field_source=get_post_meta( '256', '_um_custom_fields');
		//$field=get_post_meta( $id, '_um_custom_fields');
	
//$form_fields='a:5:{s:7:"comment";a:13:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:8:"textarea";s:5:"title";s:7:"Comment";s:7:"metakey";s:7:"comment";s:6:"height";s:5:"100px";s:10:"visibility";s:3:"all";s:5:"label";s:7:"Comment";s:6:"public";s:1:"1";s:8:"editable";s:1:"1";s:8:"position";s:1:"4";s:8:"in_group";s:0:"";}s:12:"phone_number";a:14:{s:5:"title";s:12:"Phone Number";s:7:"metakey";s:12:"phone_number";s:4:"type";s:4:"text";s:5:"label";s:12:"Phone Number";s:8:"required";i:0;s:6:"public";i:1;s:8:"editable";i:1;s:8:"validate";s:12:"phone_number";s:4:"icon";s:15:"um-faicon-phone";s:8:"position";s:1:"3";s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:8:"in_group";s:0:"";}s:12:"date_meeting";a:17:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"date";s:5:"title";s:4:"Date";s:7:"metakey";s:12:"date_meeting";s:5:"range";s:5:"years";s:5:"years";s:2:"50";s:7:"years_x";s:5:"equal";s:10:"visibility";s:3:"all";s:5:"label";s:4:"Date";s:6:"public";s:1:"1";s:6:"format";s:5:"j M Y";s:13:"pretty_format";s:1:"0";s:8:"editable";s:1:"1";s:8:"position";s:1:"1";s:8:"in_group";s:0:"";}s:12:"time_meeting";a:14:{s:6:"in_row";s:9:"_um_row_1";s:10:"in_sub_row";s:1:"0";s:9:"in_column";s:1:"1";s:4:"type";s:4:"time";s:5:"title";s:4:"Time";s:7:"metakey";s:12:"time_meeting";s:6:"format";s:5:"g:i a";s:10:"visibility";s:3:"all";s:5:"label";s:4:"Time";s:6:"public";s:1:"1";s:9:"intervals";s:2:"60";s:8:"editable";s:1:"1";s:8:"position";s:1:"2";s:8:"in_group";s:0:"";}s:9:"_um_row_1";a:5:{s:4:"type";s:3:"row";s:2:"id";s:9:"_um_row_1";s:8:"sub_rows";s:1:"1";s:4:"cols";s:1:"1";s:6:"origin";s:9:"_um_row_1";}}';
					
			//$array_form_fields = unserialize( $form_fields );	
	/*$s= serialize($field_source[0]);
	
	$fp = fopen(KI_VC_DIR.'file.txt', 'a');
fwrite($fp, $s );
fclose($fp); */
$s=file_get_contents(KI_VC_DIR.'tmpl/um/form_fields/make_appointment.dat');
	update_post_meta( $id, '_um_custom_fields', unserialize($s));		
		//echo serialize($field);
		//KiFunctions::e_array($form_fields);
		/*$Zoom_Api = ki_publish_api_zoom();
		echo '<pre>';
		//$l=$Zoom_Api->DeleteUser('yYqc0mWYR_KQBfNG2Q2QOQ');
		$l=$Zoom_Api->ListUsers();
		print_r($l);
		echo '</pre>';*/
		
		
		exit;
	}
	
	
	


}

Ki_Include_UM::getInstance();










