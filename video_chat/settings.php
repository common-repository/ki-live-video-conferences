<?php
/**
 * User: localseomap
 * Date: 25.10.2019
 * @package LocalSeoMap/Options
 */


namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Settings {

	private static $_instance = null;

	private static $service = '';
	private static $slug = '';
	private static $slug_prefix = 'ki_live_video_conferences_';
	private static $options = array();

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

		//Get options
		$options = Settings::get_options();

		if ( ! empty( $options ) ) {

			$service = '';

			if ( ! empty( $options['video_chat_select_service'] ) ) {
				$service = $options['video_chat_select_service'];
			}

			if ( ! empty( KiFunctions::v_post( 'video_chat_select_service' ) ) ) {
				$service = KiFunctions::v_post( 'video_chat_select_service' );
			}


			self::$service = $service;
			self::$slug    = self::$slug_prefix . $service;
			self::$options = $options;
		}

	}

	public static function get_slug( $service = '' ) {
		$slug = self::$slug;
		if ( ! empty( $service ) ) {
			$slug = self::$slug_prefix . $service;
		}

		return $slug;
	}

	public static function get_service() {
		return self::$service;
	}

	public static function get_options() {
		return get_option( KI_VC_SLUG );
	}

	public static function get_option( $key, $value = '' ) {
		$options = self::get_options();

		if ( ! empty( $options[ $key ] ) ) {
			$value = $options[ $key ];
		}

		return $value;
	}

	public static function list_service() {
		$list = array( 'zoom', 'rainbow' );

		return $list;
	}

	public static function show_menu_overview() {

		$result = false;
		if ( ( ! empty( self::get_option( 'zoomApiKey' ) ) AND ! empty( self::get_option( 'zoomApiSecret' ) ) ) OR ( ! empty( self::get_option( 'rainbowApiKey' ) ) AND ! empty( self::get_option( 'rainbowApiSecret' ) ) ) ) {
			$result = true;
		}

		return $result;
	}

}

Settings::getInstance();

