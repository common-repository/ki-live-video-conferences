<?php
/**
 *
 * return: Rainbow api functions
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class KiVideoChatRainbow {

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


		if ( ! empty( KiFunctions::v_get( 'ki_rainbow' ) ) ) {
			$this->action_hook();
		}

		add_action( 'init', array( &$this, 'action_rainbow' ) );


	}


	public static function auth_url() {

		$rainbow_hot = 'https://openrainbow.com';
		$options     = Settings::get_options();

		$urlAuth = $rainbow_hot . '/api/rainbow/authentication/v1.0/oauth/authorize?response_type=token&client_id=' . $options['rainbowApiKey'] . '&redirect_uri=' . self::get_hook() . '&state=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&scope=all';

		return $urlAuth;

	}

	public static function config( $post_id = '', $json = false ) {
		$options = Settings::get_options();

		$token      = '';
		$meeting_id = '';
		if ( ! empty( $post_id ) ) {
			$meeting_id = get_post_meta( $post_id, Settings::get_slug( 'rainbow' ) . '_meeting_id', true );
		}


		if ( ! empty( $_COOKIE["rainbow_token"] ) ) {
			$token = $_COOKIE["rainbow_token"];
		}

		$config = array(
			'token'      => sanitize_text_field( $token ),
			'api_key'    => sanitize_text_field( $options['rainbowApiKey'] ),
			'api_secret' => sanitize_text_field( $options['rainbowApiSecret'] ),
			'hook'       => sanitize_text_field( self::get_hook() ),
			'meeting_id' => sanitize_text_field( $meeting_id ),
		);

		if ( $json === true ) {
			$config = json_encode( $config );
		}

		return $config;
	}

	public static function get_hook() {

		return home_url( '/?ki_rainbow=auth' );
	}

	public function action_rainbow() {

	}

	private function action_hook() {

		$token = KiFunctions::v_get( 'token' );
		$state = KiFunctions::v_get( 'state' );


		if ( strlen( $token ) > 10 and strlen( $state ) > 5 ) {

			SetCookie( "rainbow_token", esc_attr( $token ), time() + 60 * 60 * 1, "/" );

			header( "Location: " . urldecode(  $state ) );

			exit;
		}


		?>
        <!DOCTYPE HTML>
        <html lang="en">
        <head>
            <title><?php esc_html_e( 'Rainbow JS Token', 'ki-live-video-conferences' ) ?></title>
        </head>
        <body>

        <script>
					let url = location.pathname + location.search + location.hash;
					url = url.replace('#access_token=', '&token=');
					url = url.replace('#error=', '&error=');
					if (location.hash) window.location.replace(url);
        </script>
		<?php
		if ( ! empty( KiFunctions::v_get( 'error' ) ) ) {
			$error             = KiFunctions::v_get( 'error' );
			$error_description = KiFunctions::v_get( 'error_description' );
			echo '<div class="error">';
			echo '<p>' . esc_html__( 'Error: ', 'ki-live-video-conferences' ) . esc_html( $error ) . '</p>';
			echo '<p>' . esc_html__( 'Error description: ', 'ki-live-video-conferences' ) . esc_html( $error_description ) . '</p>';
			echo '<p><a href="' . esc_url( $state ) . '">' . esc_html__( 'Back', 'ki-live-video-conferences' ) . '</a></p>';
			echo '</div>';
		}
		?>
        </body>
        </html>
		<?php
		exit;
	}


	public static function get_token() {
		$token = '';

		if ( ! empty( $_COOKIE["rainbow_token"] ) ) {
			$token = $_COOKIE["rainbow_token"];
		}

		return $token;
	}

}

KiVideoChatRainbow::getInstance();











