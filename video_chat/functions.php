<?php
/**
 *
 * return: Ki Live Video Conferences functions
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class KiFunctions {

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

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	}

	public static function adm_get_post_id() {

		$post_id = self::v_get( 'post', 0 );

		return $post_id;
	}

	public static function v_post( $key, $value = '' ) {

		if ( ! empty( $_POST[ $key ] ) ) {
			$value = sanitize_text_field( $_POST[ $key ] );
		}

		return $value;
	}

	public static function v_get( $key, $value = '' ) {

		if ( ! empty( $_GET[ $key ] ) ) {
			$value = sanitize_text_field( $_GET[ $key ] );
		}

		return $value;
	}

	public static function e_array( $array = array(), $exit = true ) {

		echo '<pre>';
		print_r( $array );
		echo '</pre>';
		if ( $exit ) {
			exit;
		}
	}

	public static function get_user_name( $user_info = array() ) {
		$user_name = 'no name';
		if ( ! empty( $user_info->display_name ) ) {
			$user_name = $user_info->display_name;
		} elseif ( ! empty( $user_info->user_nicename ) ) {
			$user_name = $user_info->user_nicename;
		} elseif ( ! empty( $user_info->user_login ) ) {
			$user_name = $user_info->user_login;
		} else {
			$user_name = $user_info->user_email;
		}

		return $user_name;
	}

	public static function presenter() {
		$presenter = array();
		$purpose   = Settings::get_option( 'video_chat_select_purpose', 'medicine' );
		if ( $purpose == 'education' ) {
			$presenter['name']   = 'Teacher';
			$presenter['role']   = 'teacher';
			$presenter['select'] = 'teachers';
		} else {
			$presenter['name']   = 'Doctor';
			$presenter['role']   = 'doctor';
			$presenter['select'] = 'doctors';
		}

		return $presenter;
	}

	public static function member() {
		$presenter = array();
		$purpose   = Settings::get_option( 'video_chat_select_purpose', 'medicine' );
		if ( $purpose == 'education' ) {
			$presenter['name']   = 'Student';
			$presenter['role']   = 'student';
			$presenter['select'] = 'students';
		} else {
			$presenter['name']   = 'Patient';
			$presenter['role']   = 'patient';
			$presenter['select'] = 'patients';
		}

		return $presenter;
	}


	public function load_plugin_textdomain() {
		$domain = 'ki-live-video-conferences';
		apply_filters( 'plugin_locale', get_locale(), $domain );
		load_plugin_textdomain( $domain, false, KI_VC_BASE_DIR );
	}


}


KiFunctions::getInstance();













