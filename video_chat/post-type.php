<?php

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class PostType {

	private static $_instance = null;

	private $slug = 'ki_publish_zoom';


	public $post_type = 'zoom_video';
	public $service = 'zoom';

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


		$this->post_type = Settings::get_service() . '_video';
		$this->service   = Settings::get_service();

		add_action( 'init', array( &$this, 'add_post_type' ) );


		add_action( 'save_post_zoom_video', array( &$this, 'create_meeting' ), 10 );

		add_action( 'save_post_rainbow_video', array( &$this, 'create_rainbow_meeting' ), 10 );


		add_action( 'before_delete_post', array( &$this, 'delete_meeting' ) );


		add_filter( 'use_block_editor_for_post_type', array( &$this, 'disable_gutenberg' ), 10, 2 );


	}

	public function add_post_type() {



		if(Settings::show_menu_overview() === false)return false;

		if ( ! empty( $_POST['video_chat_select_service'] ) ) {
			flush_rewrite_rules();
		}
		$menu_position=100;
		foreach ( Settings::list_service() as $type ) {

			$post_type = $type . '_video';

			$active = false;

			if ( Settings::get_service() == $type ) {
				$active = true;
			}

			$labels = array(
				'name'               => _x( ucfirst( $type ) . ' Meetings', 'Meetings', 'ki-live-video-conferences' ),
				'singular_name'      => _x( 'Meeting', 'Meeting', 'ki-live-video-conferences' ),
				'menu_name'          => _x( 'Meetings', 'Meeting', 'ki-live-video-conferences' ),
				'name_admin_bar'     => _x( 'Meeting', 'Meeting', 'ki-live-video-conferences' ),
				'add_new'            => __( 'Add New', 'ki-live-video-conferences' ),
				'add_new_item'       => __( 'Add New meeting', 'ki-live-video-conferences' ),
				'new_item'           => __( 'New meeting', 'ki-live-video-conferences' ),
				'edit_item'          => __( 'Edit meeting', 'ki-live-video-conferences' ),
				'view_item'          => __( 'View meetings', 'ki-live-video-conferences' ),
				'all_items'          => __( 'Meetings', 'ki-live-video-conferences' ),
				'search_items'       => __( 'Search meetings', 'ki-live-video-conferences' ),
				'parent_item_colon'  => __( 'Parent meetings:', 'ki-live-video-conferences' ),
				'not_found'          => __( 'No meetings found.', 'ki-live-video-conferences' ),
				'not_found_in_trash' => __( 'No meetings found in Trash.', 'ki-live-video-conferences' ),
			);

			
			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => $active,
				'query_var'          => true,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => $menu_position,
				'rewrite'            => array( 'slug' => $post_type ),
				'show_in_rest'       => true,
				'show_in_menu'       => Ki_AdminMenu::get_menu_main_slug()
			);
			$menu_position++;

			register_post_type( $post_type, $args );

		}

	}


	/**
	 * @param $post_id
	 *
	 * @since  1.0.0
	 */
	public function create_meeting( $post_id ) {
		


		if ( wp_is_post_revision( $post_id ) || get_post( $post_id )->post_status != 'publish' ) {
			return;
		}
		if ( empty( $_POST['post_title'] ) ) {
			return;
		}


		if ( empty( $_POST['start_time'] ) ) {
			$start_time = date( 'Y-m-d H:i:s' );
		} else {
			$start_time = gmdate( "Y-m-d\TH:i:s", strtotime( $this->check_post( 'start_time' ) ) );
		}

		$agenda = $this->check_post( 'content', '' );
		$agenda = trim( strip_tags( $agenda ) );

		$Zoom_Api  = ki_publish_api_zoom();
		$zoom_data = array(
			'userId'                         => $this->check_post( 'userId' ),
			'topic'                          => $this->check_post( 'post_title' ),
			'type'                           => $this->check_post( 'type', 2 ),
			'start_time'                     => $start_time,
			'duration'                       => $this->check_post( 'duration' ),
			'schedule_for'                   => $this->check_post( 'schedule_for', null ),
			'password'                       => $this->check_post( 'password', null ),
			'timezone'                       => $this->check_post( 'timezone' ),
			'agenda'                         => $agenda,
			'settings'                       => array(
				'host_video'               => $this->check_post( 'host_video', false ),
				'participant_video'        => $this->check_post( 'participant_video', false ),
				'mute_upon_entry'          => $this->check_post( 'mute_upon_entry', false ),
				'watermark'                => $this->check_post( 'watermark', false ),
				'cn_meeting'               => $this->check_post( 'cn_meeting', false ),
				'in_meeting'               => $this->check_post( 'in_meeting', false ),
				'join_before_host'         => $this->check_post( 'join_before_host', false ),
				'use_pmi'                  => $this->check_post( 'use_pmi', false ),
				'approval_type'            => $this->check_post( 'approval_type', 0 ),
				'registration_type'        => $this->check_post( 'registration_type', 1 ),
				'audio'                    => $this->check_post( 'audio', null ),
				'auto_recording'           => $this->check_post( 'auto_recording', 'none' ),
				'enforce_login'            => $this->check_post( 'enforce_login', false ),
				'enforce_login_domains'    => $this->check_post( 'enforce_login_domains', null ),
				'alternative_hosts'        => $this->check_post( 'alternative_hosts', null ),
				'global_dial_in_countries' => $this->check_post( 'global_dial_in_countries', null )
			),
			'registrants_email_notification' => $this->check_post( 'registrants_email_notification', false )

		);
		
		

		//Create New Meeting
		if ( empty( get_post_meta( $post_id, Settings::get_slug() . '_uuid', true ) ) ) {

			$meeting = $Zoom_Api->CreateMeeting( $zoom_data );
			if ( $meeting !== false ) {
				update_post_meta( $post_id, Settings::get_slug() . '_meeting_id', $meeting->id );
				update_post_meta( $post_id, Settings::get_slug() . '_uuid', $meeting->uuid );

				$zoom_data['id'] = $meeting->id;

				$meeting = (array) $meeting;
				update_post_meta( $post_id, Settings::get_slug() . '_meeting_details', json_encode( $meeting ) );
			}
			
			$arrRole=array('doctor','teacher','student','patient');
			foreach($arrRole as $selRole){
				if($this->check_post( 'select_'.$selRole, false )!==false){
					$arrTypeRole=Array($this->check_post( 'select_'.$selRole, false ));
					$sTR=serialize($arrTypeRole);
					update_post_meta( $post_id, $selRole.'s', $sTR );
				}
			}
			
			


		} else {//Update Meeting
			unset( $zoom_data['userId'] );
			$meeting_id = get_post_meta( $post_id, Settings::get_slug() . '_meeting_id', true );
			$meeting    = $Zoom_Api->UpdateMeeting( $meeting_id, $zoom_data );

		}
		
		update_post_meta( $post_id, Settings::get_slug() . '_twitter_hashtag', $this->check_post('twitter_hashtag','') );
		

		if ( $meeting !== false ) {
			$shortcode = '[ki_video_chat meeting_id="' . esc_attr( $post_id ) . '"]';
			update_post_meta( $post_id, Settings::get_slug() . '_shortcode', $shortcode );
			update_post_meta( $post_id, Settings::get_slug() . '_userId', $zoom_data['userId'] );

			$meeting = (array) $meeting;
			update_post_meta( $post_id, Settings::get_slug() . '_meeting_details', json_encode( $meeting ) );
		}


	}

	public function delete_meeting( $post_id ) {
		$type = get_post_type( $post_id );

		if ( $type == 'zoom_video' ) {

			$api        = ki_publish_api_zoom();
			$meeting_id = get_post_meta( $post_id, Settings::get_slug() . '_meeting_id', true );
			$api->DeleteMeeting( $meeting_id );
		}
	}


	/**
	 * @param $post_id
	 *
	 * @since  1.0.0
	 */
	public function create_rainbow_meeting( $post_id ) {
		if ( empty( get_post_meta( $post_id, Settings::get_slug() . '_shortcode', true ) ) ) {

			update_post_meta(
				$post_id,
				Settings::get_slug() . '_shortcode',
				'[ki_video_chat meeting_id="' . esc_attr( $post_id ) . '"]'
			);
		}
	}


	private function check_post( $key, $default = '' ) {

		$key = trim( $key, '_' );

		if ( empty( $_POST[ $key ] ) ) {

			return $default;

		}

		if ( $default === false and $_POST[ $key ] === '1' ) {
			return true;
		}

		return sanitize_text_field( $_POST[ $key ] );
	}

	public function disable_gutenberg( $is_enabled, $post_type ) {

		if ( $post_type === 'rainbow_video' ) {
			return false;
		}
		if ( $post_type === 'zoom_video' ) {
			return false;
		}

		return $is_enabled;

	}

}

PostType::getInstance();
