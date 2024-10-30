<?php
/**
 * Ajax
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class KiVideoChatAjsx {

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

		add_action( 'wp_ajax_ki_live_video_conferences_zoom_config', array( &$this, 'ajax_zoom_config' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_zoom_config', array( &$this, 'ajax_zoom_config' ) );

		add_action( 'wp_ajax_ki_live_video_conferences_rainbow_save', array( &$this, 'rainbow_save' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_rainbow_save', array( &$this, 'rainbow_save' ) );

		add_action( 'wp_ajax_ki_live_video_conferences_rainbow_config', array( &$this, 'rainbow_config' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_rainbow_config', array( &$this, 'rainbow_config' ) );

		add_action( 'wp_ajax_ki_live_video_conferences_rainbow_logout', array( &$this, 'rainbow_logout' ) );
		add_action( 'wp_ajax_nopriv_ki_live_video_conferences_rainbow_logout', array( &$this, 'rainbow_logout' ) );


	}

	public function ajax_zoom_config() {

		$post_id = KiFunctions::v_post( 'post_id' );

		if ( empty( $post_id ) || ! is_numeric( $post_id ) ) {
			die();
		}

		$UserName = esc_html__( 'User-', 'ki-live-video-conferences' ) . time();

		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$UserName     = esc_html( KiFunctions::get_user_name($current_user) );
		}

		$Config = array();

		$Zoom_Api             = ki_publish_api_zoom();
		$Config['api_key']    = $Zoom_Api->api_key;
		$Config['meeting_id'] = get_post_meta( $post_id, Settings::get_slug( 'zoom' ) . '_meeting_id', true );
		$Config['password']   = get_post_meta( $post_id, 'password', true );
		$Config['signature']  = esc_html( $Zoom_Api->Signature( $Config['meeting_id'], 1) );
		$Config['user_name']  = esc_html( $UserName );

		echo json_encode( $Config );
		die();
	}


	public function rainbow_save() {

		if ( empty( KiFunctions::v_post( 'post_id' ) ) or empty( KiFunctions::v_post( 'meeting_id' ) ) or empty( KiFunctions::v_post( 'host_name' ) ) or empty( KiFunctions::v_post( 'host_email' ) ) or empty( KiFunctions::v_post( 'host_user_id' ) ) ) {
			exit;
		}

		$post_id = KiFunctions::v_post( 'post_id' );

		update_post_meta( $post_id, Settings::get_slug( 'rainbow' ) . '_meeting_id', KiFunctions::v_post( 'meeting_id' ) );
		update_post_meta( $post_id, Settings::get_slug( 'rainbow' ) . '_host_name', esc_attr( KiFunctions::v_post( 'host_name' ) ) );
		update_post_meta( $post_id, Settings::get_slug( 'rainbow' ) . '_host_email', esc_attr( KiFunctions::v_post( 'host_email' ) ) );
		update_post_meta( $post_id, Settings::get_slug( 'rainbow' ) . '_host_user_id', esc_attr( KiFunctions::v_post( 'host_user_id' ) ) );

		wp_die();
	}

	public function rainbow_config() {


		echo KiVideoChatRainbow::config( KiFunctions::v_post( 'post_id' ), true );

		wp_die();
	}

	public function rainbow_logout() {

		SetCookie( "rainbow_token", '', time() + 60, "/" );
		exit;
	}


}


KiVideoChatAjsx::getInstance();









