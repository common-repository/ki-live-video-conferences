<?php
namespace KiLiveVideoConferencesEarth;



class KiEarth {

	private static $_instance = null;
	private  $base_url = '';

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


	private function __construct(){


		$this->base_url = plugin_dir_url(__FILE__);


		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_css_js' ) );
		add_shortcode('ki_vc_earth', array( $this, 'shortcodeEarth' ));


	}

	public function enqueue_css_js(){

		wp_enqueue_style( 'ki-live-video-conferences-earth', $this->base_url .'assets/css/earth.css', null, KI_VC_BASE_VERSION );

		wp_enqueue_script('ki-live-video-conferences-miniature-earth', $this->base_url .'assets/js/miniature.earth.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script('ki-live-video-conferences-earth', $this->base_url .'assets/js/earth.js', array( 'ki-live-video-conferences-miniature-earth' ), KI_VC_BASE_VERSION, true );;
	}
	public function shortcodeEarth()
    {
		ob_start();
        include('shortcode/earth.php');
		return ob_get_clean();


    }
}

KiEarth::getInstance();
