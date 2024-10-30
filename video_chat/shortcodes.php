<?php
/**
 *
 * @package CONFERENCE_VIDEO_ROOM/Shortcodes
 */


namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Shortcodes of WP
 *
 * @since  1.0.0
 */
class Shortcodes {

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
	 * Constructor
	 *
	 * @since  1.0.0
	 */
	private function __construct() {

		if ( $this->is_shortcode( 'zoom' ) ) {
			add_action( 'wp_enqueue_scripts', array( &$this, 'zoom_enqueue_css_js' ) );
			add_action( 'wp_print_scripts', array( &$this, 'zoom_remove_all_scripts' ), 100 );
			add_action( 'wp_print_styles', array( &$this, 'zoom_remove_all_styles' ), 100 );
		}

		if ( $this->is_shortcode( 'rainbow' ) ) {
			add_action( 'wp_enqueue_scripts', array( &$this, 'rainbow_enqueue_css_js' ) );
			add_action( 'wp_print_scripts', array( &$this, 'rainbow_remove_all_scripts' ), 100 );
			add_action( 'wp_print_styles', array( &$this, 'rainbow_remove_all_styles' ), 100 );
		}

		add_shortcode( 'ki_video_chat', array( $this, 'video_chat_inline' ) );
		//add_shortcode( 'ki_view_video_chat', array( $this, 'ki_view_video_chat' ) );

		add_action( 'init', array( &$this, 'include_video_chat' ) );


		add_filter( 'the_content', array( &$this, 'content_shortcode' ) );




	}


	/**
	 * Output the meeting video inside the page.
	 *
	 * @param $atts
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function video_chat_inline( $atts ) {

		$atts = shortcode_atts( array(
			'meeting_id' => '',
			'link_only'  => '',
		), $atts );


		ob_start();
		if ( get_post_type( $atts['meeting_id'] ) == 'zoom_video' ) {
			include( 'tmpl/shortcode/zoom.php' );
		}
		if ( get_post_type( $atts['meeting_id'] ) == 'rainbow_video' ) {
			include( 'tmpl/shortcode/rainbow.php' );
		}

		return ob_get_clean();
	}



    public function ki_view_video_chat( $atts ) {

        $atts = shortcode_atts( array(
            'meeting_id' => '',
            'link_only'  => '',
        ), $atts );


        ob_start();

        include( 'view/view.php' );

        return ob_get_clean();
    }

	//*************************************************************************
	//Zoom include js & css
	public function zoom_enqueue_css_js() {

		$path      = KI_VC_URL . 'assets/';
		$path_zoom = KI_VC_URL . 'assets/zoom/';

        wp_enqueue_style( 'ki-live-video-conferences-zoom-bootstrap', $path_zoom . 'bootstrap.css', null, KI_VC_BASE_VERSION );
		wp_enqueue_script( 'ki-live-video-conferences-zoom-redux', $path_zoom . 'redux.min.js', array( 'react-dom','jquery' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-zoom-redux-thunk', $path_zoom . 'redux-thunk.min.js', array( 'ki-live-video-conferences-zoom-redux' ), KI_VC_BASE_VERSION, true );

		wp_enqueue_script( 'ki-live-video-conferences-zoom-lodash', $path_zoom . 'lodash.min.js', array( 'ki-live-video-conferences-zoom-redux-thunk' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-zoom-meeting', $path_zoom . 'zoom-meeting-1.7.9.min.js', array( 'ki-live-video-conferences-zoom-lodash' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-zoom-tool', $path . 'js/tool.js', array( 'ki-live-video-conferences-zoom-meeting' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-zoom-index', $path . 'js/index.js', array( 'ki-live-video-conferences-zoom-tool' ), KI_VC_BASE_VERSION, true );

	}


	public function zoom_remove_all_scripts() {
		$allowed_js = array(
			'ki-live-video-conferences-block',
			'react',
			'jquery',
			'react-dom',
			'ki-live-video-conferences-zoom-lodash',
			'ki-live-video-conferences-zoom-redux-thunk',
			'ki-live-video-conferences-zoom-lodash',
			'ki-live-video-conferences-zoom-meeting',
			'zki-live-video-conferences-zoom-tool',
			'ki-live-video-conferences-zoom-index',
		);
		global $wp_scripts;

		//$wp_scripts->queue = array();
		foreach ( $wp_scripts->queue as $key => $js ) {
			if ( array_search( $js, $allowed_js ) === false ) {
				unset( $wp_scripts->queue[ $key ] );
			}
		}
		foreach ( $wp_scripts->registered as $key => $js ) {
			if ( array_search( $key, $allowed_js ) === false ) {
				unset( $wp_scripts->queue[ $key ] );
			}
		}


	}

	public function zoom_remove_all_styles() {
		$allowed_css = array(
			'ki-live-video-conferences-zoom-bootstrap',
			'react-select'
		);
		global $wp_styles;
		//$wp_styles->queue = array();
		foreach ( $wp_styles->queue as $key => $css ) {
			if ( array_search( $css, $allowed_css ) === false ) {
				unset( $wp_styles->queue[ $key ] );
			}
		}
	}

	//*************************************************************************
	//Rainbow include js & css
	public function rainbow_enqueue_css_js() {

		$path = KI_VC_URL . 'assets/';

		$path_rainbow = KI_VC_URL . 'assets/rainbow/';


		wp_enqueue_style( 'font-awesome', $path . 'font-awesome/css/font-awesome.css', null, KI_VC_BASE_VERSION );
		wp_enqueue_style( 'ki-live-video-conferences-rainbow', $path . 'css/rainbow_style.css', null, KI_VC_BASE_VERSION );

		wp_enqueue_script( 'ki-live-video-conferences-rainbow-shim', $path_rainbow . 'es5-shim.min.js', array( 'jquery' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-rainbow-promise', $path_rainbow . 'es6-promise.min.js', array( 'ki-live-video-conferences-rainbow-shim' ), KI_VC_BASE_VERSION, true );

		wp_enqueue_script( 'ki-live-video-conferences-rainbow-moment-with-locales', $path_rainbow . 'moment-with-locales.min.js', array( 'ki-live-video-conferences-rainbow-promise' ), KI_VC_BASE_VERSION, true );

		wp_enqueue_script( 'ki-live-video-conferences-rainbow-angular', $path_rainbow . 'angular.min.js', array( 'ki-live-video-conferences-rainbow-moment-with-locales' ), KI_VC_BASE_VERSION, true );

		wp_enqueue_script( 'ki-live-video-conferences-rainbow-vendors-sdk', $path . 'js/vendors-sdk.min.js', array( 'ki-live-video-conferences-rainbow-angular' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-rainbow-rainbow-sdk', $path . 'js/rainbow-sdk.min.js', array( 'ki-live-video-conferences-rainbow-vendors-sdk' ), KI_VC_BASE_VERSION, true );
		wp_enqueue_script( 'ki-live-video-conferences-rainbow-rainbow_index', $path . 'js/rainbow_index.js', array( 'ki-live-video-conferences-rainbow-rainbow-sdk' ), KI_VC_BASE_VERSION, true );


	}

	public function rainbow_remove_all_scripts() {
		$allowed_js = array(
			'ki-live-video-conferences-block',
			'jquery',
			'ki-live-video-conferences-rainbow-shim',
			'kki-live-video-conferences-rainbow-promise',
			'ki-live-video-conferences-rainbow-moment-with-locales',
			'ki-live-video-conferences-rainbow-angular',
			'ki-live-video-conferences-rainbow-vendors-sdk',
			'ki-live-video-conferences-rainbow-rainbow-sdk',
			'ki-live-video-conferences-rainbow-rainbow_index'
		);
		global $wp_scripts;

		//$wp_scripts->queue = array();
		foreach ( $wp_scripts->queue as $key => $js ) {
			if ( array_search( $js, $allowed_js ) === false ) {
				unset( $wp_scripts->queue[ $key ] );
			}
		}
		foreach ( $wp_scripts->registered as $key => $js ) {
			if ( array_search( $key, $allowed_js ) === false ) {
				unset( $wp_scripts->queue[ $key ] );
			}
		}


	}

	public function rainbow_remove_all_styles() {
		$allowed_css = array(
			'font-awesome',
			'ki-live-video-conferences-rainbow'
		);
		global $wp_styles;

		//$wp_styles->queue = array();
		foreach ( $wp_styles->queue as $key => $css ) {
			if ( array_search( $css, $allowed_css ) === false ) {
				unset( $wp_styles->queue[ $key ] );
			}
		}
	}

	//*************************************************************************
	//*************************************************************************

	public static function include_video_chat() {
		if ( self::is_shortcode( 'zoom' ) ) {
			include_once( 'tmpl/zoom/zoom.php' );
			exit;
		}
		if ( self::is_shortcode( 'rainbow' ) ) {
			include_once( 'tmpl/rainbow/rainbow.php' );
			exit;
		}
	}

	public function content_shortcode( $content ) {
		if ( get_post_type( get_the_ID() ) == 'zoom_video' OR get_post_type( get_the_ID() ) == 'rainbow_video' ) {
			$content = '[ki_video_chat meeting_id="' . get_the_ID() . '"]';
		}


		return $content;
	}

	public static function is_shortcode( $name = '' ) {

		$result_shortcode = false;

		$list_hortcode = Array(
			'zoom'    => 'conference-video-room',
			'rainbow' => 'conference-video-room-rainbow'
		);

		if ( $name == '' ) {
			foreach ( $list_hortcode as $key => $get_key ) {
				if ( ! empty( KiFunctions::v_get( $get_key ) ) ) {
					$result_shortcode = $key;
					break;
				}
			}
		} else {
			if ( empty( $list_hortcode[ $name ] ) ) {
				return false;
			}
			$get_key = $list_hortcode[ $name ];
			if ( ! empty( KiFunctions::v_get( $get_key ) ) ) {
				$result_shortcode = $name;
			}
		}

		return $result_shortcode;


	}


}


Shortcodes::getInstance();
