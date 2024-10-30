<?php
/**
 *
 * return: zoom api functions
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class KiVideoChatInit {

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

        //add_action( 'init', array( &$this, 'add_page_video_chat' ) );
		add_action( 'init', array( &$this, 'add_role' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css_js' ) );


		
		
	
		

	}

	public function enqueue_css_js() {


		wp_enqueue_style( 'ki-live-video-conferences-zoom', KI_VC_URL . 'assets/css/style.css', null, KI_VC_BASE_VERSION );

		wp_enqueue_script( 'ki-live-video-conferences-script', KI_VC_URL . 'assets/js/script.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );

		wp_localize_script( 'ki-live-video-conferences-block', 'ki_live_video_conferences_ajax', admin_url( 'admin-ajax.php' ) );

	}

	public function admin_enqueue_css_js() {
		$post      = '';
		$post_type = '';
		if ( ! empty( KiFunctions::v_get( 'post_type' ) ) ) {
			$post_type = KiFunctions::v_get( 'post_type' );
		}
		if ( ! empty( KiFunctions::v_get( 'post' ) ) ) {
			$post = KiFunctions::v_get( 'post' );
		}
		
		$path = KI_VC_URL . 'assets/';
		
		wp_enqueue_script( 'ki-live-video-conferences-admin', $path . 'js/admin.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );


		if ( ! empty( $post_type ) or ! empty( $post ) ) {
			if ( $post_type == 'rainbow_video' or get_post_type( $post ) == 'rainbow_video' ) {

				

				$path_rainbow = KI_VC_URL . 'assets/rainbow/';

				wp_enqueue_style( 'ki-live-video-conferences-rainbow-admin', $path . 'css/style_admin.css', null, KI_VC_BASE_VERSION );

				wp_enqueue_script( 'ki-live-video-conferences-rainbow-shim', $path_rainbow . 'es5-shim.min.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );
				wp_enqueue_script( 'ki-live-video-conferences-rainbow-promise', $path_rainbow . 'es6-promise.min.js', array( 'ki-live-video-conferences-rainbow-shim' ), KI_VC_BASE_VERSION, true );

				wp_enqueue_script( 'ki-live-video-conferences-rainbow-moment-locales', $path_rainbow . 'moment-with-locales.min.js', array( 'ki-live-video-conferences-rainbow-shim' ), KI_VC_BASE_VERSION, true );
				wp_enqueue_script( 'ki-live-video-conferences-rainbow-angular', $path_rainbow . 'angular.min.js', array( 'ki-live-video-conferences-rainbow-moment-locales' ), KI_VC_BASE_VERSION, true );


				wp_enqueue_script( 'ki-live-video-conferences-rainbow-vendors-sdk', $path . 'js/vendors-sdk.min.js', array( 'ki-live-video-conferences-rainbow-angular' ), KI_VC_BASE_VERSION, true );
				wp_enqueue_script( 'ki-live-video-conferences-rainbow-rainbow-sdk', $path . 'js/rainbow-sdk.min.js', array( 'ki-live-video-conferences-rainbow-vendors-sdk' ), KI_VC_BASE_VERSION, true );
				wp_enqueue_script( 'ki-live-video-conferences-rainbow-index', $path . 'js/rainbow_index_admin.js', array( 'ki-live-video-conferences-rainbow-rainbow-sdk' ), KI_VC_BASE_VERSION, true );
				wp_enqueue_script( 'ki-live-video-conferences-rainbow-rainbow_admin', $path . 'js/rainbow_adm.js', array( 'ki-live-video-conferences-rainbow-index' ), KI_VC_BASE_VERSION, true );
			}
		}


	}
	
	public function add_role(){
		$capabilities =Array ( 'read' => true, 'level_0' => true );
        $ListRole = array(
            'assistant' => array('name' => 'Assistant', 'capabilities' => $capabilities),
            'doctor' => array('name' => 'Doctor', 'capabilities' => $capabilities),
            'patient' => array('name' => 'Patient', 'capabilities' => $capabilities),
            'teacher' => array('name' => 'Teacher', 'capabilities' => $capabilities),
            'student' => array('name' => 'Student', 'capabilities' => $capabilities),
        );
		$wp_roles=wp_roles()->roles;
		$UserID = get_current_user_id();
		foreach($ListRole as $role=>$val){
			//*******************************************
			//Fix Admin bar
			if(user_can( $UserID, $role )){
				add_filter('show_admin_bar', '__return_false');
			} 
			//*******************************************
			//Add new role
			if(empty($wp_roles[$role])){
				wp_roles()->add_role( $role, $val['name'], $val['capabilities']);
			}
			
		} 
		

		
	}

    public static function add_page_video_chat(){

        if(get_option( Settings::get_slug().'_add_page_ki_vc')!='yes'){

            $post_data = array(
                'post_title'    => wp_strip_all_tags( 'Video Chat'),
                'post_content'  => wp_strip_all_tags( '[ki_view_video_chat]'),
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => 1,
                'post_category' => array( 8,39 )
            );
            wp_insert_post( $post_data );
            update_option(Settings::get_slug().'_add_page_ki_vc','yes');
        }

    }
	
	
	
	
	


}

KiVideoChatInit::getInstance();










