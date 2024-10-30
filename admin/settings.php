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


		add_action( 'admin_init', array( $this, 'settings_init' ) );


	}

	public function settings_init() {
		register_setting( KI_VC_SLUG, KI_VC_SLUG );


    }



}


Ki_Initialization::getInstance();









