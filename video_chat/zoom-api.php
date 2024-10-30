<?php
/**
 *
 * @package KiSocialVideoChat/Api
 */

namespace KiLiveVideoConferences;

use \Firebase\JWT\JWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Api {

	public $api_key;
	public $api_secret;
	private $api_url = 'https://api.zoom.us/v2/';

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
		
		$zoomApiKey = Settings::get_option('zoomApiKey');
		$zoomApiSecret = Settings::get_option('zoomApiSecret');


		if ( ! empty( $zoomApiKey ) AND ! empty( $zoomApiSecret ) ) {

			$this->api_key    = $zoomApiKey;
			$this->api_secret = $zoomApiSecret;
		}



	}

	//function to generate JWT
	private function generateJWTKey() {
		$key    = $this->api_key;
		$secret = $this->api_secret;
		$token  = array(
			"iss" => $key,
			"exp" => time() + 3600 //60 seconds as suggested
		);

		return JWT::encode( $token, $secret );
	}

	public function Signature( $meeting_number, $role = '0' ) {

		$time = time() * 1000 - 30000;//time in milliseconds (or close enough)
		$data = base64_encode( $this->api_key . $meeting_number . $time . $role );
		$hash = hash_hmac( 'sha256', $data, $this->api_secret, true );
		$_sig = $this->api_key . "." . esc_attr( $meeting_number ) . "." . esc_attr( $time ) . "." . esc_attr( $role ) . "." . base64_encode( $hash );

		//return signature, url safe base64 encoded
		return rtrim( strtr( base64_encode( $_sig ), '+/', '-_' ), '=' );
	}


	public function CreateMeeting( $data = array() ) {

		return $this->Query( 'users/' . $data['userId'] . '/meetings', $data, true, 'POST' );
	}

	public function UpdateMeeting( $meeting_id, $data = array() ) {

		return $this->Query( 'meetings/' . $meeting_id, $data, true, 'PATCH' );
	}

	public function EndMeeting( $id ) {
		$params = Array( "action" => "end" );

		return $this->Query( 'meetings/' . $id . '/status', $params, true, 'PUT' );
	}

	public function DeleteMeeting( $id, $params = array() ) {

		return $this->Query( 'meetings/' . $id, $params, true, 'DELETE' );
	}

	public function ListMeetings( $host_id, $type = 'scheduled', $params = array() ) {

		$params['page_size'] = 300;
		$params['type']      = $type;

		return $this->Query( 'users/' . esc_attr( $host_id ) . '/meetings', $params );
	}

	public function getMeetingInfo( $id, $host_id, $params = array() ) {

		$params['meetingId'] = $id;
		$params['userId']    = $host_id;

		return $this->Query( 'meetings/' . $id, $params );
	}

	public function ListUsers( $params = array() ) {

		if ( empty( $params['page_size'] ) ) {
			$params['page_size'] = 300;
		}

		return $this->Query( 'users/', $params );
	}

	public function DefaultHost() {
		$users = $this->ListUsers();
		if ( $users === false ) {
			return false;
		}
		$host_id = $users->users[0]->id;

		return $host_id;
	}

	public function CreateUser( $email, $first_name, $last_name, $type = 1 ) {
		$User = array(
			'action'    => 'create',
			'user_info' => Array(
				'email'      => $email,
				'type'       => $type,
				'first_name' => $first_name,
				'last_name'  => $last_name
			)
		);

		return $this->Query( 'users/', $User, true, 'POST' );
	}

	public function UpdateUser( $user_id, $data ) {
		$User = array(
			'first_name'    => $data['first_name'] ? $data['first_name'] : null,
			'last_name'     => $data['last_name'] ? $data['last_name'] : null,
			'type'          => $data['type'] ? $data['type'] : null,
			'pmi'           => $data['pmi'] ? $data['pmi'] : null,
			'timezone'      => $data['timezone'] ? $data['timezone'] : null,
			'dept'          => $data['dept'] ? $data['dept'] : null,
			'vanity_name'   => $data['vanity_name'] ? $data['vanity_name'] : null,
			'host_key'      => $data['timezone'] ? $data['timezone'] : null,
			'cms_user_id'   => $data['cms_user_id'] ? $data['cms_user_id'] : null,
			'job_title'     => $data['job_title'] ? $data['job_title'] : null,
			'company'       => $data['company'] ? $data['company'] : null,
			'location'      => $data['location'] ? $data['location'] : null,
			'phone_number'  => $data['phone_number'] ? $data['phone_number'] : null,
			'phone_country' => $data['phone_country'] ? $data['phone_country'] : null
		);

		return $this->Query( 'users/' . $user_id, $User, true, 'PATCH' );
	}

	public function DeleteUser( $user_id, $data = Array() ) {
		$User = array(
			'action'             => $data['action'] ? $data['action'] : 'disassociate',
			'transfer_email'     => $data['transfer_email'] ? $data['transfer_email'] : null,
			'transfer_meeting'   => $data['transfer_meeting'] ? $data['transfer_meeting'] : null,
			'transfer_webinar'   => $data['transfer_webinar'] ? $data['transfer_webinar'] : null,
			'transfer_recording' => $data['transfer_recording'] ? $data['transfer_recording'] : null,
		);

		return $this->Query( 'users/' . $user_id, $User, true, 'DELETE' );
	}


	public function GetUser( $user_id ) {

		return $this->Query( 'users/' . $user_id );
	}


	public function ListAccounts( $params = array() ) {

		if ( empty( $params['page_number'] ) ) {
			$params['page_number'] = 1;
		}
		if ( empty( $params['page_size'] ) ) {
			$params['page_size'] = 30;
		}

		return $this->Query( 'accounts/', $params );
	}


	private function Query( $cmd, $data = Array(), $TypeArray = true, $method = 'GET' ) {

		if ( empty( $this->api_key ) OR empty( $this->api_secret ) ) {
			return false;
		}

		$request_url = $this->api_url . $cmd;

		$postFields = json_encode( $data );
		$headers    = array(
			'Authorization' => 'Bearer ' . $this->generateJWTKey(),
			'Content-Type'  => 'application/json'
		);

		if ( $method === 'GET' ) {
			$request_url .= '?' . http_build_query( $data );
			$postFields  = null;
		}


		$args = array(
			'method'  => $method,
			'headers' => $headers,
			'body'    => $postFields
		);


		$response = wp_remote_request( $request_url, $args );


		$response = $response['body'];


		if ( ! $response ) {
			return false;
		}

		if ( $TypeArray ) {
			$response = json_decode( $response );
		}

		return $response;
	}
}

