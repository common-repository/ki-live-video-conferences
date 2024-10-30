<?php
/**
 *
 * return: zoom api functions
 */

namespace KiLiveVideoConferences;

define( 'KI_VC_SETTINGS_URL', plugin_dir_url( __FILE__ ) );
define( 'KI_VC_SETTINGS_DIR', plugin_dir_path( __FILE__ ) );




class Ki_Initialization {

	private static $_instance = null;
	private static $options = Array();


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

		add_action( 'admin_init', array( &$this, 'save_settings' ) );

		add_action( 'admin_menu', array( &$this, 'addPluginOptionsPage' ), 8 );




	}


	public function admin_enqueue_css_js() {

		wp_enqueue_style( 'ki-live-video-conferences-rainbow-options', KI_VC_SETTINGS_URL . 'assets/css/options.css' );


	}

	public function addPluginOptionsPage() {


		add_menu_page(
			esc_html__( 'KI Live Video Conferences', 'ki-live-video-conferences' ),
			esc_html__( 'KI Conferences', 'ki-live-video-conferences' ),
			'manage_options',
			'ki_options_page',
			array(
				&$this,
				'tmpPage'
			),
			plugins_url( 'ki-live-video-conferences/images/icon.png' ),
			6
		);

		add_submenu_page(
			'ki_options_page',
			esc_html__( 'Settings', 'ki-live-video-conferences' ),
			esc_html__( 'Settings', 'ki-live-video-conferences' ),
			'manage_options',
			'ki_options_page',
			array(
				&$this,
				'tmpPage'
			) );

		self::$options = Settings::get_options();

	}


	public function tmpPage() {
		include_once( 'templates/settings.php' );

	}


	public function save_settings() {
		if ( KiFunctions::v_get( 'page' ) == 'ki_options_page' AND KiFunctions::v_post( 'action' ) == 'update' ) {
			$options_save = array(
				//Video Chat
				'video_chat_select_service' => KiFunctions::v_post( 'video_chat_select_service', '' ),
				//Zoom
				'zoomApiKey'                => KiFunctions::v_post( 'zoomApiKey', '' ),
				'zoomApiSecret'             => KiFunctions::v_post( 'zoomApiSecret', '' ),
				'zoomPublicMeeting'         => KiFunctions::v_post( 'zoomPublicMeeting', '0' ),
				//Rainbow
				'rainbowApiKey'             => KiFunctions::v_post( 'rainbowApiKey', '' ),
				'rainbowApiSecret'          => KiFunctions::v_post( 'rainbowApiSecret', '' ),
			);


			update_option( 'wc_options', serialize( $options_save ) );

			wp_redirect( $_SERVER['REQUEST_URI'] );


		}
	}


}


Ki_Initialization::getInstance();









