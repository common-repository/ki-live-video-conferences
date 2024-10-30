<?php
/*
 * Plugin Name: KI Live Video Conferences
 * Description: Video conferencing plugin. It supports Zoom and OpenRainbow services.
 * Version: 1.2.6
 * Author: Wael Hassan
 * Author URI: http://waelhassan.com
 * Text Domain: ki-live-video-conferences
 *
 */

namespace KiLiveVideoConferences;

define( 'KI_VC_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'KI_VC_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'KI_VC_SLUG', 'ki_live_video_options' );
define( 'KI_VC_BASE_VERSION', '1.2.6' );

include_once 'vendor/autoload.php';



include KI_VC_BASE_DIR . 'admin/admin.php';
include KI_VC_BASE_DIR . 'earth/earth.php';
include KI_VC_BASE_DIR . 'video_chat/init.php';

