<?php
/**
 *
 * return: zoom api functions
 */

namespace KiLiveVideoConferences;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


function ki_publish_api_zoom() {
	try {
		return Api::getInstance();
	} catch ( \Exception $e ) {
		echo $e->getMessage();
	}
}


ki_publish_api_zoom();










