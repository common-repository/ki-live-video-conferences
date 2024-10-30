<?php
/**
 *
 * @package KiSocialVideoChat
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class MeetingMetaBoxes {

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
	 * PostType constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function __construct() {
		if ( is_admin() ) {
			$service = Settings::get_service();
			if ( $service == 'zoom' ) {
				add_filter( 'rwmb_meta_boxes', array( $this, 'get_meta_boxes' ) );
				add_action( 'add_meta_boxes', array( &$this, 'init_meta_boxes' ) );
			}

			if ( $service == 'rainbow' ) {
				add_filter( 'rwmb_meta_boxes', array( $this, 'get_rainbow_meta_boxes' ) );
				add_action( 'add_meta_boxes', array( &$this, 'init_meta_boxes_rainbow' ) );
			}


			add_action( 'show_user_profile', array( &$this, 'extra_user_profile_fields' ) );
			add_action( 'edit_user_profile', array( &$this, 'extra_user_profile_fields' ) );

			add_action( 'personal_options_update', array( &$this, 'save_extra_user_profile_fields' ) );
			add_action( 'edit_user_profile_update', array( &$this, 'save_extra_user_profile_fields' ) );

		}
	}


	/**
	 * Create metaboxes for projects and media custom post type.
	 * @return array
	 */
	public function get_meta_boxes( $meta_boxes ) {


		$post_id        = KiFunctions::adm_get_post_id();
		$select_user_id = '';
		if ( $post_id > 0 ) {
			$select_user_id = get_post_meta( $post_id, 'userId', true );

		}


		$Zoom_Api           = ki_publish_api_zoom();
		$optUsers           = array();
		$optHostAlternative = array( '' => 'Default Host' );


		if ( ! empty( $Zoom_Api->api_key ) ) {
			$zmUserList = $Zoom_Api->ListUsers();
			if ( isset( $zmUserList->users ) ) {
				foreach ( $zmUserList->users as $user ) {
					$optUsers[ $user->id ]              = $user->first_name . ' ' . $user->last_name;
					$optHostAlternative[ $user->email ] = $user->first_name . ' ' . $user->last_name;
				}
			}


		}


		$default_timezone = get_option( 'timezone_string' );
		if ( empty( $default_timezone ) ) {
			$default_timezone = date_default_timezone_get();
		}

		$Timezone = timezone_identifiers_list();
		array_unshift( $Timezone, $default_timezone );
		$TimezoneLits = array();
		foreach ( $Timezone as $name ) {
			$TimezoneLits[ $name ] = $name;
		}


		if ( empty( $wp_option ) ) {
			$wp_option =  Settings::get_options(  );
		}
		$public_meeting = '';
		if ( ! empty( $wp_option['zoomPublicMeeting'] ) ) {
			$public_meeting = $wp_option['zoomPublicMeeting'];
		}

		$password = rand();

		$fields = array(

			array(
				'id'      => 'userId',
				'type'    => 'select',
				'name'    => esc_html__( 'Host', 'ki-live-video-conferences' ),
				'options' => $optUsers,
			),
			array(
				'id'   => 'start_time',
				'type' => 'datetime',
				'name' => esc_html__( 'Start time', 'ki-live-video-conferences' ),
				'std'  => date( 'Y-m-d H:i', time() )
			),
			array(
				'id'      => 'timezone',
				'type'    => 'select',
				'name'    => esc_html__( 'Timezone', 'ki-live-video-conferences' ),
				'options' => $TimezoneLits,
			),
			array(
				'id'   => 'duration',
				'type' => 'number',
				'name' => esc_html__( 'Duration', 'ki-live-video-conferences' ),
				'std'  => '60'
			),
			array(
				'id'   => 'password',
				'type' => 'text',
				'name' => esc_html__( 'Password', 'ki-live-video-conferences' ),
				'std'  => esc_attr( $password )
			),
			array(
				'id'   => 'agenda',
				'type' => 'text',
				'name' => esc_html__( 'Agenda', 'ki-live-video-conferences' ),
			),
			array(
				'id'   => 'public_meeting',
				'type' => 'checkbox',
				'name' => esc_html__( 'Public meeting', 'ki-live-video-conferences' ),
				'std'  => $public_meeting
			),
			array(
				'id'   => 'host_video',
				'type' => 'checkbox',
				'name' => esc_html__( 'Host video', 'ki-live-video-conferences' ),
			),
			array(
				'id'   => 'participant_video',
				'type' => 'checkbox',
				'name' => esc_html__( 'Participant video', 'ki-live-video-conferences' ),
			),
			array(
				'id'   => 'mute_upon_entry',
				'type' => 'checkbox',
				'name' => esc_html__( 'Mute upon entry', 'ki-live-video-conferences' ),
			),
			array(
				'id'   => 'watermark',
				'type' => 'checkbox',
				'name' => esc_html__( 'Watermark', 'ki-live-video-conferences' ),
			),
			array(
				'id'   => 'join_before_host',
				'type' => 'checkbox',
				'name' => esc_html__( 'Join before host', 'ki-live-video-conferences' ),
			),
			array(
				'id'      => 'approval_type',
				'type'    => 'select',
				'name'    => esc_html__( 'Approval type', 'ki-live-video-conferences' ),
				'options' => array(
					'0' => 'Automatically approve',
					'1' => 'Manually approve',
					'2' => 'No registration required',
				),
			),
			array(
				'id'      => 'registration_type',
				'type'    => 'select',
				'name'    => esc_html__( 'Registration type', 'ki-live-video-conferences' ),
				'options' => array(
					'1' => 'Attendees register once and can attend any of the occurrences',
					'2' => 'Attendees need to register for each occurrence to attend',
					'3' => 'Attendees register once and can choose one or more occurrences to attend',
				),
			),
			array(
				'id'      => 'audio',
				'type'    => 'select',
				'name'    => esc_html__( 'Audio', 'ki-live-video-conferences' ),
				'options' => array(
					'both'      => 'Both Telephony and VoIP',
					'telephony' => 'Telephony only',
					'voip'      => 'VoIP only',
				),
			),
			array(
				'id'      => 'auto_recording',
				'type'    => 'select',
				'name'    => esc_html__( 'Auto recording', 'ki-live-video-conferences' ),
				'options' => array(
					'none'  => 'Disabled',
					'local' => 'Record on local',
					'cloud' => 'Record on cloud',
				),
			),

			array(
				'id'   => 'enforce_login',
				'type' => 'checkbox',
				'name' => esc_html__( 'Enforce login', 'ki-live-video-conferences' ),
			),
			array(
				'id'      => 'alternative_hosts',
				'type'    => 'select',
				'name'    => esc_html__( 'Alternative hosts', 'ki-live-video-conferences' ),
				'options' => $optHostAlternative,

			)

		);
		
		
		

		if ( empty( $post_id ) ) {
			$info_presenter=KiFunctions::presenter();
			$info_member=KiFunctions::member();
			$presenters=get_users(Array('role'=>$info_presenter['role']));
			$members=get_users(Array('role'=>$info_member['role']));
			$SelectPresenters=Array();
			$SelectMembers=Array();
			
			foreach($presenters as $presenter){
				$SelectPresenters[$presenter->ID]=KiFunctions::get_user_name($presenter);
			}
			
			foreach($members as $member){
				$SelectMembers[$member->ID]=KiFunctions::get_user_name($member);
			}
			
			$fields[]=array(
				'id'      => 'select_'.$info_presenter['role'],
				'type'    => 'select',
				'name'    => esc_html__( $info_presenter['name'], 'ki-live-video-conferences' ),
				'options' => $SelectPresenters,
			);
			$fields[]=array(
				'id'      => 'select_'.$info_member['role'],
				'type'    => 'select',
				'name'    => esc_html__( $info_member['name'], 'ki-live-video-conferences' ),
				'options' => $SelectMembers,
			);
		}

	


		if ( ! empty( $post_id ) ) {


			$shortcode = get_post_meta( $post_id, Settings::get_slug() . '_shortcode', true );
			if ( ! empty( $shortcode ) ) {
				$text_field = array(
					'id'   => 'shortcode',
					'name' => 'shortcode',
					'type' => 'custom_html',
					'std'  => esc_html__( $shortcode )
				);
				array_unshift( $fields, $text_field );
			}

			$meeting_id = get_post_meta( $post_id, Settings::get_slug() . '_meeting_id', true );
			if ( ! empty( $meeting_id ) ) {
				$text_field = array(
					'id'   => 'meeting_id',
					'name' => 'meeting_id',
					'type' => 'custom_html',
					'std'  => esc_html__( $meeting_id )
				);
				array_unshift( $fields, $text_field );
			}
			
			$doctors =unserialize(get_post_meta( $post_id,'doctors', true ));
			$patients =unserialize(get_post_meta( $post_id,'patients', true ));
			$teachers =unserialize(get_post_meta( $post_id,'teachers', true ));
			$students =unserialize(get_post_meta( $post_id,'students', true ));
			
			if(is_array($doctors)){
				$doctor_id=$doctors[0];
				$name=KiFunctions::get_user_name(get_userdata($doctor_id));
				$fields[]=array(
					'id'   => 'lbl_doctor',
					'name' => esc_html__( 'Doctor', 'ki-live-video-conferences' ),
					'type' => 'custom_html',
					'std'  => esc_html__( $name )
				);
			}
			
			if(is_array($patients)){
				$patient_id=$patients[0];
				$name=KiFunctions::get_user_name(get_userdata($patient_id));
				$fields[]=array(
					'id'   => 'lbl_patient',
					'name' => esc_html__( 'Patient', 'ki-live-video-conferences' ),
					'type' => 'custom_html',
					'std'  => esc_html__( $name )
				);
			}
			if(is_array($teachers)){
				$teacher_id=$teachers[0];
				$name=KiFunctions::get_user_name(get_userdata($teacher_id));
				$fields[]=array(
					'id'   => 'lbl_teacher',
					'name' => esc_html__( 'Tacher', 'ki-live-video-conferences' ),
					'type' => 'custom_html',
					'std'  => esc_html__( $name )
				);
			}
			if(is_array($students)){
				$student_id=$students[0];
				$name=KiFunctions::get_user_name(get_userdata($student_id));
				$fields[]=array(
					'id'   => 'lbl_student',
					'name' => esc_html__( 'Student', 'ki-live-video-conferences' ),
					'type' => 'custom_html',
					'std'  => esc_html__( $name )
				);
			}
			
			


		}

		$meta_boxes[] = array(
			'id'         => 'meeting_details',
			'title'      => esc_html__( 'Meeting Details', 'ki-live-video-conferences' ),
			'post_types' => array( 'zoom_video' ),
			'context'    => 'normal',
			'priority'   => 'default',
			'autosave'   => 'false',
			'fields'     => $fields


		);


		return $meta_boxes;

	}

	/**
	 * Create metaboxes for projects and media custom post type.
	 * @return array
	 */
	public function get_rainbow_meta_boxes( $meta_boxes ) {

		$post_id = KiFunctions::adm_get_post_id();


		$shortcode  = '';
		$meeting_id = '';

		$html = '';

		$default_timezone = get_option( 'timezone_string' );
		if ( empty( $default_timezone ) ) {
			$default_timezone = date_default_timezone_get();
		}

		$Timezone = timezone_identifiers_list();
		array_unshift( $Timezone, $default_timezone );
		$TimezoneLits = array();
		foreach ( $Timezone as $name ) {
			$TimezoneLits[ $name ] = $name;
		}

		$html_contact_list = '';


		//New Post
		if ( empty( $_GET['post'] ) ) {

			$fields = array(
				array(
					'id'   => 'start_time',
					'type' => 'datetime',
					'name' => esc_html__( 'Start time', 'ki-live-video-conferences' ),
					'std'  => date( 'Y-m-d H:i', time() )
				),
				array(
					'id'      => 'timezone',
					'type'    => 'select',
					'name'    => esc_html__( 'Timezone', 'ki-live-video-conferences' ),
					'options' => $TimezoneLits,
				)

			);

		} else {

			$shortcode  = get_post_meta( $post_id, Settings::get_slug() . '_shortcode', true );
			$meeting_id = get_post_meta( $post_id, Settings::get_slug() . '_meeting_id', true );
			$html_host  = '<div class="vc_rainbow_host">' . get_post_meta( $post_id, Settings::get_slug() . '_host_name', true ) . '</div>';

			$urlAuth = KiVideoChatRainbow::auth_url();
			$post    = get_post( $post_id );
			$html    = '<div class="vc_rainbow_post_id" style="display: none" >' . esc_attr( $post_id ) . '</div>';
			$html    .= '<div class="vc_rainbow_post_title" style="display: none" >' . trim( esc_attr( $post->post_title ) ) . '</div>';
			$html    .= '<div class="vc_rainbow_post_content" style="display: none" >' . trim( strip_tags( esc_attr( $post->post_content ) ) ) . '</div>';
			$html    .= '<div class="vc_rainbow_post_meeting_id" style="display: none" >' . esc_attr( $meeting_id ) . '</div>';

			$html .= '<div class="vc_rainbow_status" ></div>';

			$html .= '<div class="wrpLogin" style="display: none"><button type="button" class="but rainbow-join" >' . esc_html__( 'Join', 'ki-live-video-conferences' ) . '</button></div>';
			$html .= '<div class="wrpLogout" style="display: none"><button type="button" class="rainbow_logout">' . esc_html__( 'Logout', 'ki-live-video-conferences' ) . '</button></div>';
			$html .= '<div class="wrpLogin" style="display: none">';
			$html .= '<div class="winRainbowOAuth" >';
			$html .= '<div class="close">x</div>';
			$html .= '<p>Log in please</p>';
			$html .= '<button type="button" class="but rainbow-join" >' . esc_html__( 'Join', 'ki-live-video-conferences' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="vc_rainbow_page_url" style="display: none" >' . esc_url( $urlAuth ) . '</div>';


			$html_contact_list .= '<div class="vc_rainbow_users_list" style="display: none">';
			$html_contact_list .= '<div class="contact-list"></div>';
			$html_contact_list .= '<button class="but-send-inviting" type="button"  >' . esc_html__( 'Inviting', 'ki-live-video-conferences' ) . '</button>';
			$html_contact_list .= '</div>';
			if ( $meeting_id == '' ) {//Create Conferense

				$html .= '<div class="vc_rainbow_init" style="display: none">' . esc_attr( 'create_conference' ) . '</div>';

			} else {//Edit Conferense

				$html .= '<div class="vc_rainbow_init" style="display: none">' . esc_attr( 'edit_conference' ) . '</div>';

			}


			$fields = array(
				array(
					'id'   => 'start_time',
					'type' => 'datetime',
					'name' => esc_html__( 'Start time', 'ki-live-video-conferences' ),
				),
				array(
					'id'      => 'timezone',
					'type'    => 'select',
					'name'    => esc_html__( 'Timezone', 'ki-live-video-conferences' ),
					'options' => $TimezoneLits,
				),
				array(
					'id'   => 'rainbow_html',
					'type' => 'custom_html',
					'name' => esc_html__( 'Rainbow', 'ki-live-video-conferences' ),
					'std'  => $html
				),
				array(
					'id'   => 'rainbow_contact_list',
					'type' => 'custom_html',
					'name' => esc_html__( 'Contacts', 'ki-live-video-conferences' ),
					'std'  => $html_contact_list
				),
				array(
					'id'   => 'rainbow_host',
					'type' => 'custom_html',
					'name' => esc_html__( 'Host', 'ki-live-video-conferences' ),
					'std'  => $html_host
				),
				array(
					'id'   => 'shortcode',
					'name' => 'shortcode',
					'type' => 'custom_html',
					'std'  => esc_html__( $shortcode )
				)

			);

		}


		$meta_boxes[] = array(
			'id'         => 'meeting_details',
			'title'      => esc_html__( 'Meeting Details', 'ki-live-video-conferences' ),
			'post_types' => array( 'rainbow_video' ),
			'context'    => 'normal',
			'priority'   => 'default',
			'autosave'   => 'false',
			'fields'     => $fields


		);

		return $meta_boxes;
	}


	public function init_meta_boxes() {

		$meeting_id = get_post_meta( get_the_ID(), Settings::get_slug() . '_meeting_id', true );

		if ( ! empty( $meeting_id ) ) {
			add_meta_box(
				'conference_video_room_side',
				esc_html__( 'Meeting Details', 'zoom_video' ),
				array( $this, 'add_meta_boxes_side' ),
				array( 'zoom_video' ),
				'side',
				'high'
			);
		}
		if ( get_option( 'pl_ki_twitter_analytics_active', false ) === 'active'){
			add_meta_box(
				'conference_video_room_side_hashtag',
				esc_html__( 'Meeting Hashtag', 'zoom_video' ),
				array( $this, 'add_meta_boxes_side_hashtag' ),
				array( 'zoom_video' ),
				'side',
				'high'
			);
			
		}
		
	}


	public function add_meta_boxes_side() {

		$post_id = get_the_ID();

		$meeting_id = get_post_meta( $post_id, Settings::get_slug() . '_meeting_id', true );

		$meeting_details = get_post_meta( $post_id, Settings::get_slug() . '_meeting_details', true );
		$meeting_details = json_decode( $meeting_details, true );

		$Zoom_Api = ki_publish_api_zoom();

		$meeting_info = (array) $Zoom_Api->getMeetingInfo( $meeting_id, $meeting_details['host_id'] );
		if ( ! empty( $meeting_info ) ) {
			$meeting_details = $meeting_info;
		}

		if ( ! empty( $meeting_id ) ):

			?>
            <div>


				<?php if ( ! empty( $meeting_details['start_url'] ) ): ?>
                    <p>
                        <a href="<?php echo esc_url( $meeting_details['start_url'] ) ?>" target="_blank" class="">
							<?php echo esc_html__( 'Start Meeting', 'ki-live-video-conferences' ); ?>
                        </a>
                    </p>
				<?php endif; ?>

				<?php if ( ! empty( $meeting_details['join_url'] ) ): ?>
                    <p>
                        <a href="<?php echo esc_url( $meeting_details['join_url'] ) ?>" target="_blank" class="">
							<?php echo esc_html__( 'Join Meeting', 'ki-live-video-conferences' ); ?>
                        </a>
                    </p>
				<?php endif; ?>

				<?php if ( ! empty( $meeting_details['join_url'] ) ): ?>
                    <a href="https://zoom.us/wc/<?php echo esc_attr( $meeting_id ); ?>/start" target="_blank" class="">
						<?php echo esc_html__( 'Start via Browser', 'ki-live-video-conferences' ); ?>
                    </a>
				<?php endif; ?>

                <ul>
                    <li>
                        <b><?php echo esc_html__( 'Meeting id', 'ki-live-video-conferences' ); ?>:</b>
						<?php echo esc_html( $meeting_id ); ?>
                    </li>
                </ul>
            </div>

		<?php endif;

	}
	
	public function add_meta_boxes_side_hashtag(){
		
		$post_id = get_the_ID();

		$twitter_hashtag = get_post_meta( $post_id, Settings::get_slug() . '_twitter_hashtag', true );
		?>
		<div class="twitter_hashtag">
			<input type="text" name="twitter_hashtag" value="<?php echo $twitter_hashtag; ?>">
		</div>
		<?php
		
	}


	public function init_meta_boxes_rainbow() {


		$meeting_id = get_post_meta( get_the_ID(), Settings::get_slug() . '_meeting_id', true );

		if ( ! empty( $meeting_id ) ) {
			add_meta_box(
				'conference_video_room_side',
				esc_html__( 'Meeting Details', 'ki-live-video-conferences' ),
				array( $this, 'add_meta_boxes_side_rainbow' ),
				array( 'rainbow_video' ),
				'side',
				'high'
			);
		}
	}


	public function add_meta_boxes_side_rainbow() {


		$post_id = get_the_ID()
		?>
        <div>
            <p>
                <a href="<?php the_permalink( $post_id ); ?>" target="_blank" class="">
					<?php echo esc_html__( 'Show the meeting on the front-end', 'ki-live-video-conferences' ); ?>
                </a>
            </p>

            <p>
                <a href="<?php echo home_url( '/?conference-video-room-rainbow=' . $post_id ); ?>" target="_blank"
                   class="">
					<?php echo esc_html__( 'Start via Browser', 'ki-live-video-conferences' ); ?>
                </a>
            </p>
        </div>

		<?php

	}


	public function extra_user_profile_fields( $user ) {
		if ( ! user_can( $user->ID, 'doctor' ) ) {
			return false;
		}

		$assistants   = get_users( Array( 'role' => 'assistant' ) );
		$assistant_id = get_user_meta( $user->ID, Settings::get_slug() . '_assistant_id', true );
		?>
        <h3><?php _e( "Assistants", "ki-live-video-conferences" ); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="assistants"><?php _e( "Assistants", "ki-live-video-conferences" ); ?></label></th>
                <td>
                    <select name="assistants">
						<?php
						foreach ( $assistants as $val ) {
							?>
                            <option value="<?php echo $val->ID; ?>" <?php selected( $val->ID, $assistant_id ); ?> ><?php echo $val->display_name ?></option>
							<?php
						}
						?>
                    </select>

                </td>
            </tr>

        </table>
		<?php /*

	<h3><?php _e("Zoom", "ki-live-video-conferences"); ?></h3>

	  <table class="form-table">
    <tr>
        <th><label for="zoom_id">Zoom ID</label></th>
        <td>
		<?php
			$zoom_id=get_user_meta($user->ID, 'zoom_host_id',true);
			if($zoom_id==''){
				$zoom_id=get_user_meta($user->ID, 'zoom_message',true);
			}
			if($zoom_id==''){
				$zoom_id=esc_html__('User not added!', 'ki-live-video-conferences' );
			}
			echo $zoom_id;
		?>

        </td>
    </tr>

    </table>

	*/ ?>

		<?php
	}


	public function save_extra_user_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! user_can( $user_id, 'doctor' ) ) {
			return false;
		}
		if ( ! empty( KiFunctions::v_post( 'assistants' ) ) ) {
			update_user_meta( $user_id, Settings::get_slug() . '_assistant_id', KiFunctions::v_post( 'assistants' ) );
		}
	}


}


MeetingMetaBoxes::getInstance();
