<?php
/**
 *
 * return: zoom api functions
 */

namespace KiLiveVideoConferences;


class Ki_AdminMenu {

	private static $_instance = null;

    public static $menu_main_slug = 'ki_settings_page';
    public static $main_page = 'PageSettings';
    public static $show_menu_overview = false;
    public static $show_page_get_started = true;
    public static $ki_global_menu = false;
    public static $main_menu_plugon = false;

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

	



        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'init', array( &$this, 'init_menu' ) );


		$menu_priority = 1;
		if ( get_option( 'pl_ki_twitter_analytics_active', false ) === 'active' OR get_option( 'pl_ki_publish_active', false ) === 'active' ) {
			$menu_priority = 100;
			self::$ki_global_menu=true;
		}

		add_action( 'admin_menu', array( &$this, 'adm_menu' ), $menu_priority);





	}





    public static function admin_init() {




        if ( !empty($_GET['kivcdef']) ) {
            update_option(KI_VC_SLUG, '');
            update_option( KI_VC_SLUG.'_is_key', false);
            update_option( 'pl_ki_twitter_analytics_active', false);
            wp_redirect( 'admin.php?page=ki_get_started' );
            exit;
        }

        $ki_twitter_analytics = is_plugin_active( 'ki-twitter-analytics/ki-twitter-analytics.php' );
        

        if ( $ki_twitter_analytics==true AND  get_option( 'pl_ki_twitter_analytics_active', false )!=='active' ) {
            update_option( 'pl_ki_twitter_analytics_active', 'active');
            wp_redirect( $_SERVER['REQUEST_URI'] );

        }
        if($ki_twitter_analytics==false AND get_option( 'pl_ki_twitter_analytics_active', false )==='active' ){
            update_option( 'pl_ki_twitter_analytics_active', '');
            wp_redirect( $_SERVER['REQUEST_URI'] );

        }
		
		$ki_publish = is_plugin_active( 'ki-publish/sm-publish.php' );
		
		if ( $ki_publish==true AND  get_option( 'pl_ki_publish_active', false )!=='active' ) {
            update_option( 'pl_ki_publish_active', 'active');
            wp_redirect( $_SERVER['REQUEST_URI'] );

        }
        if($ki_publish==false AND get_option( 'pl_ki_publish_active', false )==='active' ){
            update_option( 'pl_ki_publish_active', '');
            wp_redirect( $_SERVER['REQUEST_URI'] );

        }




        self:: $show_menu_overview = Settings::show_menu_overview();

        if(get_option( KI_VC_SLUG.'_is_key',false)==false AND self:: $show_menu_overview==true){
            update_option( KI_VC_SLUG.'_is_key', self:: $show_menu_overview);
            wp_redirect( 'admin.php?page=ki_overview_page' );
            exit;
        }
        update_option( KI_VC_SLUG.'_is_key', self:: $show_menu_overview);








    }

    public static function init_menu() {




        $options = Settings::get_options();



        $show_menu_overview = Settings::show_menu_overview();

        if (empty( $options ) && KiFunctions::v_get('page')!='ki_settings_page') {

            $MainSlug = 'ki_get_started';
            $MainPage = 'PageGetStarted';
        } else {
            self::$show_page_get_started = false;

            $MainSlug = 'ki_settings_page';
            $MainPage = 'PageSettings';
            if ($show_menu_overview) {
                $MainSlug = 'ki_overview_page';
                $MainPage = 'PageOverview';
            }
        }

        //Inlude menu plugin ki_inbox


       if(self::$ki_global_menu){
			

			if(get_option( 'pl_ki_publish_active', false ) === 'active'){
				$MainSlug = 'ki_options_page'; 
			}elseif(get_option( 'pl_ki_twitter_analytics_active', false ) === 'active'){
				$MainSlug = 'ki_twitter_analytics'; 
				
			}
            
        }


        self::$menu_main_slug = $MainSlug;
        self::$main_page = $MainPage;
        self::$show_menu_overview = $show_menu_overview;




    }



    public static function get_menu_main_slug() {
        return self::$menu_main_slug;
    }





    public function adm_menu() {




		$options = Settings::get_options();
        $MainSlug=self::$menu_main_slug;
        $MainPage=self::$main_page;
        $show_menu_overview=self::$show_menu_overview;
		$position=1;
		if(self::$ki_global_menu === false) {
			//Show Main Menu
			add_menu_page(
				esc_html__('KI Live Video Conferences', 'ki-live-video-conferences'),
				esc_html__('KI', 'ki-live-video-conferences'),
				'manage_options',
				$MainSlug,
				array(
					&$this,
					$MainPage
				),
				plugins_url('ki-live-video-conferences/images/icon.png'),
				6
			);
		}else{
			$position=30;
			$top_pos=1;
			if(get_option( 'pl_ki_publish_active', false )==='active' AND get_option( 'pl_ki_twitter_analytics_active', false ) === 'active'){
				$top_pos=2;
			}
			add_submenu_page(
                $MainSlug,
                esc_html__('KI Live Video Conferences', 'ki-live-video-conferences'),
				esc_html__('KI Conferences', 'ki-live-video-conferences'),
                'manage_options',
                'ki_get_started',
                array(
                    &$this,
                    $MainPage
                ),
                $top_pos
            );

		}

        



        //Show page get started
        if(self::$show_page_get_started ) {
            add_submenu_page(
                $MainSlug,
                esc_html__('Get Started', 'ki-live-video-conferences'),
                esc_html__('Get Started', 'ki-live-video-conferences'),
                'manage_options',
                'ki_get_started',
                array(
                    &$this,
                    'PageGetStarted'
                ),
                $position
            );
			$position++;

        }

        //Show page get overview
        if($show_menu_overview) {
            add_submenu_page(
                $MainSlug,
                esc_html__('Overview', 'ki-live-video-conferences'),
                esc_html__('Overview', 'ki-live-video-conferences'),
                'manage_options',
                'ki_overview_page',
                array(&$this, 'PageOverview'),
                $position
            );
            $position++;
        }



        //Show page get settings
       if($MainSlug == 'ki_settings_page' OR $show_menu_overview  OR self::$show_page_get_started==false){

           add_submenu_page(
                $MainSlug,
                esc_html__('Settings', 'ki-live-video-conferences'),
                esc_html__('Settings', 'ki-live-video-conferences'),
                'manage_options',
                'ki_settings_page',
                array(
                    &$this,
                    'PageSettings'
                ),
                $position
            );
            $position++;
      }




	}


	public function PageGetStarted() {
		include_once( 'templates/get_started.php' );
	}

	public function PageOverview() {
		include_once( 'templates/overview.php' );
	}

	public function PageSettings() {
		include_once( 'templates/settings.php' );
	}





}


Ki_AdminMenu::getInstance();








